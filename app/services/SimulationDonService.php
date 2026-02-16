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
     * Simuler le dispatch de dons
     * Pour chaque ville :
     *   Pour chaque besoin (de la ville) :
     *     Calculer reste à distribuer (besoin_total - déjà_distribué)
     *     Parcourir collectes (date ASC et id ASC)
     *       Prendre stock disponible (quantite_collecte - déjà_distribué)
     *       Insérer distribution
     *       Diminuer reste
     * 
     * @param string|null $date Date de la distribution (par défaut aujourd'hui)
     * @return array Résultat de la simulation avec les distributions effectuées
     */
    public function simulerDispatch(?string $date = null): array {
        $date = $date ?? date('Y-m-d');
        $resultat = [
            'date' => $date,
            'distributions' => [],
            'total_distribue' => 0,
            'villes_traitees' => 0,
            'besoins_satisfaits' => 0
        ];

        try {
            $this->pdo->beginTransaction();

            // Créer une nouvelle distribution pour cette simulation
            $distributionId = $this->distributionRepo->creerDistribution($date);

            // Récupérer toutes les villes
            $villes = $this->villeRepo->findAll();

            // Calculer le stock disponible par besoin (total collecté - total distribué)
            $stockParBesoin = $this->calculerStockDisponible();

            foreach ($villes as $ville) {
                $villeId = (int)$ville['v_id'];
                $villeNom = $ville['v_nom'];

                // Récupérer les besoins non satisfaits de cette ville
                $besoinsNonSatisfaits = $this->besoinRepo->findBesoinsNonSatisfaitsParVille($villeId);

                foreach ($besoinsNonSatisfaits as $besoin) {
                    $besoinId = (int)$besoin['besoin_id'];
                    $besoinLibelle = $besoin['besoin_libelle'];
                    $unite = $besoin['unite'];
                    $quantiteDemandee = (int)$besoin['quantite_demandee'];
                    $quantiteDistribuee = (int)$besoin['quantite_distribuee'];
                    $resteADistribuer = $quantiteDemandee - $quantiteDistribuee;

                    if ($resteADistribuer <= 0) {
                        continue;
                    }

                    // Vérifier le stock disponible pour ce besoin
                    $stockDisponible = $stockParBesoin[$besoinId] ?? 0;

                    if ($stockDisponible <= 0) {
                        continue;
                    }

                    // Calculer la quantité à distribuer (min entre reste et stock)
                    $quantiteADistribuer = min($resteADistribuer, $stockDisponible);

                    if ($quantiteADistribuer > 0) {
                        // Insérer le détail de distribution
                        $this->distributionRepo->insererDistributionDetail(
                            $distributionId,
                            $besoinId,
                            $quantiteADistribuer,
                            $villeId
                        );

                        // Mettre à jour le stock disponible
                        $stockParBesoin[$besoinId] -= $quantiteADistribuer;

                        // Enregistrer dans le résultat
                        $resultat['distributions'][] = [
                            'ville_id' => $villeId,
                            'ville_nom' => $villeNom,
                            'besoin_id' => $besoinId,
                            'besoin_libelle' => $besoinLibelle,
                            'unite' => $unite,
                            'quantite_demandee' => $quantiteDemandee,
                            'deja_distribue' => $quantiteDistribuee,
                            'quantite_distribuee' => $quantiteADistribuer,
                            'reste_apres' => $resteADistribuer - $quantiteADistribuer
                        ];

                        $resultat['total_distribue'] += $quantiteADistribuer;

                        if ($resteADistribuer - $quantiteADistribuer == 0) {
                            $resultat['besoins_satisfaits']++;
                        }
                    }
                }

                $resultat['villes_traitees']++;
            }

            $this->pdo->commit();
            $resultat['distribution_id'] = $distributionId;
            $resultat['success'] = true;

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
