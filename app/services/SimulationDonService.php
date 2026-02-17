<?php

namespace app\services;

use PDO;
use app\repositories\BesoinRepository;
use app\repositories\CollecteRepository;
use app\repositories\DistributionRepository;
use app\repositories\VilleRepository;

/**
 * Service de simulation de dispatch de dons
 * Gère la distribution automatique des collectes vers les villes selon leurs besoins
 */
class SimulationDonService {
    private PDO $pdo;
    private BesoinRepository $besoinRepo;
    private CollecteRepository $collecteRepo;
    private DistributionRepository $distributionRepo;
    private VilleRepository $villeRepo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->besoinRepo = new BesoinRepository($pdo);
        $this->collecteRepo = new CollecteRepository($pdo);
        $this->distributionRepo = new DistributionRepository($pdo);
        $this->villeRepo = new VilleRepository($pdo);
    }

    /**
     * Simuler le dispatch SANS enregistrer en base
     * Retourne un aperçu des distributions qui seraient effectuées
     */
    public function simulerSansEnregistrer(): array {

        $resultat = [
            'date' => date('Y-m-d'),
            'distributions' => [],
            'total_distribue' => 0,
            'success' => true
        ];

        $villes = $this->villeRepo->findAll();

        foreach ($villes as $ville) {

            $villeId = (int)$ville['v_id'];
            $besoins = $this->besoinRepo->findBesoinsNonSatisfaitsParVille($villeId);

            foreach ($besoins as $besoin) {

                $besoinId = (int)$besoin['besoin_id'];
                $reste = (int)$besoin['quantite_demandee']
                    - (int)$besoin['quantite_distribuee'];

                if ($reste <= 0) continue;

                $collectes = $this->collecteRepo
                    ->getCollectesDisponiblesParBesoin($besoinId);

                foreach ($collectes as $collecte) {

                    if ($reste <= 0) break;

                    $stockDisponible = (int)$collecte['stock_disponible'];

                    if ($stockDisponible <= 0) continue;

                    $aDistribuer = min($reste, $stockDisponible);

                    $resultat['distributions'][] = [
                        'ville_id' => $villeId,
                        'ville_nom' => $ville['v_nom'],
                        'besoin_id' => $besoinId,
                        'besoin_libelle' => $besoin['besoin_libelle'],
                        'quantite' => $aDistribuer,
                        'collecte_id' => $collecte['cd_id'],
                        'collecte_date' => $collecte['c_date']
                    ];

                    $reste -= $aDistribuer;
                    $resultat['total_distribue'] += $aDistribuer;
                }
            }
        }

        return $resultat;
    }


    /**
     * Grouper les résultats de simulation par ville pour l'affichage
     */
    public function grouperSimulationParVille(array $simulationResult): array {
        $parVille = [];
        foreach ($simulationResult['distributions'] as $dist) {
            $villeId = $dist['ville_id'];
            if (!isset($parVille[$villeId])) {
                $parVille[$villeId] = [
                    'ville_id' => $villeId,
                    'ville_nom' => $dist['ville_nom'],
                    'besoins' => [],
                    'total_demande' => 0,
                    'total_distribue' => 0,
                    'total_reste' => 0
                ];
            }
            $parVille[$villeId]['besoins'][] = [
                'besoin_id' => $dist['besoin_id'],
                'besoin_libelle' => $dist['besoin_libelle'],
                'unite' => $dist['unite'],
                'quantite_demandee' => $dist['quantite_demandee'],
                'quantite_distribuee' => $dist['quantite_distribuee'],
                'reste' => $dist['reste_apres']
            ];
            $parVille[$villeId]['total_demande'] += $dist['quantite_demandee'];
            $parVille[$villeId]['total_distribue'] += $dist['quantite_distribuee'];
            $parVille[$villeId]['total_reste'] += $dist['reste_apres'];
        }
        return array_values($parVille);
    }

    /**
     * Simuler le dispatch de dons et ENREGISTRER en base (appelé après validation)
     */
    public function simulerDispatch(?string $date = null): array {

        $date = $date ?? date('Y-m-d');

        $resultat = [
            'date' => $date,
            'distributions' => [],
            'total_distribue' => 0
        ];

        try {

            $this->pdo->beginTransaction();

            $distributionId = $this->distributionRepo->creerDistribution($date);

            $villes = $this->villeRepo->findAll();

            foreach ($villes as $ville) {

                $villeId = (int)$ville['v_id'];
                $besoins = $this->besoinRepo->findBesoinsNonSatisfaitsParVille($villeId);

                foreach ($besoins as $besoin) {

                    $besoinId = (int)$besoin['besoin_id'];
                    $reste = (int)$besoin['quantite_demandee']
                        - (int)$besoin['quantite_distribuee'];

                    if ($reste <= 0) continue;

                    $collectes = $this->collecteRepo
                        ->getCollectesDisponiblesParBesoin($besoinId);

                    foreach ($collectes as $collecte) {

                        if ($reste <= 0) break;

                        $stockDisponible = (int)$collecte['stock_disponible'];

                        if ($stockDisponible <= 0) continue;

                        $aDistribuer = min($reste, $stockDisponible);

                        $this->distributionRepo->insererDistributionDetail(
                            $distributionId,
                            $besoinId,
                            $aDistribuer,
                            $villeId,
                            (int)$collecte['cd_id']
                        );

                        $reste -= $aDistribuer;
                        $resultat['total_distribue'] += $aDistribuer;
                    }
                }
            }

            $this->pdo->commit();
            $resultat['success'] = true;
            $resultat['distribution_id'] = $distributionId;

        } catch (\Exception $e) {

            $this->pdo->rollBack();
            $resultat['success'] = false;
            $resultat['error'] = $e->getMessage();
        }

        return $resultat;
    }


    /**
     * Calculer le stock disponible par besoin
     * Stock = Total collecté - Total distribué
     */
    private function calculerStockDisponible(): array {
        $sql = "SELECT 
                    b.b_id AS besoin_id,
                    COALESCE(SUM(cd.cd_quantite), 0) AS total_collecte,
                    COALESCE(
                        (SELECT SUM(dd.dd_quantite) 
                         FROM bngrc_distributionDetails dd 
                         WHERE dd.dd_besoin = b.b_id), 0
                    ) AS total_distribue
                FROM bngrc_besoin b
                LEFT JOIN bngrc_collecteDetails cd ON cd.cd_besoin = b.b_id
                GROUP BY b.b_id";

        $result = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $stock = [];
        foreach ($result as $row) {
            $stock[(int)$row['besoin_id']] = max(0, (int)$row['total_collecte'] - (int)$row['total_distribue']);
        }

        return $stock;
    }

    /**
     * Récupérer le résumé des distributions par ville
     * (Ville, besoin, Demandé, Distribué, Reste)
     */
    public function getResumeDistributionsParVille(): array {
        return $this->distributionRepo->getResumeDistributionsParVille();
    }

    /**
     * Récupérer les distributions groupées par ville
     */
    public function getDistributionsGroupeesParVille(): array {
        $resume = $this->getResumeDistributionsParVille();

        $parVille = [];
        foreach ($resume as $row) {
            $villeId = $row['ville_id'];
            if (!isset($parVille[$villeId])) {
                $parVille[$villeId] = [
                    'ville_id' => $villeId,
                    'ville_nom' => $row['ville_nom'],
                    'besoins' => [],
                    'total_demande' => 0,
                    'total_distribue' => 0,
                    'total_reste' => 0
                ];
            }

            $parVille[$villeId]['besoins'][] = [
                'besoin_id' => $row['besoin_id'],
                'besoin_libelle' => $row['besoin_libelle'],
                'unite' => $row['unite'],
                'quantite_demandee' => (int)$row['quantite_demandee'],
                'quantite_distribuee' => (int)$row['quantite_distribuee'],
                'reste' => (int)$row['reste']
            ];

            $parVille[$villeId]['total_demande'] += (int)$row['quantite_demandee'];
            $parVille[$villeId]['total_distribue'] += (int)$row['quantite_distribuee'];
            $parVille[$villeId]['total_reste'] += (int)$row['reste'];
        }

        return array_values($parVille);
    }

    /**
     * Récupérer les statistiques globales
     */
    public function getStatistiquesGlobales(): array {
        $resume = $this->getResumeDistributionsParVille();

        $stats = [
            'total_besoins' => count($resume),
            'total_demande' => 0,
            'total_distribue' => 0,
            'total_reste' => 0,
            'besoins_satisfaits' => 0,
            'besoins_partiels' => 0,
            'besoins_non_traites' => 0
        ];

        foreach ($resume as $row) {
            $demande = (int)$row['quantite_demandee'];
            $distribue = (int)$row['quantite_distribuee'];
            $reste = (int)$row['reste'];

            $stats['total_demande'] += $demande;
            $stats['total_distribue'] += $distribue;
            $stats['total_reste'] += $reste;

            if ($reste == 0) {
                $stats['besoins_satisfaits']++;
            } elseif ($distribue > 0) {
                $stats['besoins_partiels']++;
            } else {
                $stats['besoins_non_traites']++;
            }
        }

        $stats['pourcentage_satisfaction'] = $stats['total_demande'] > 0 
            ? round(($stats['total_distribue'] / $stats['total_demande']) * 100, 2) 
            : 0;

        return $stats;
    }
}
