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
            'villes_traitees' => 0,
            'besoins_satisfaits' => 0,
            'success' => true
        ];

        $villes = $this->villeRepo->findAll();
        $stockParBesoin = $this->calculerStockDisponible();

        foreach ($villes as $ville) {
            $villeId = (int)$ville['v_id'];
            $villeNom = $ville['v_nom'];

                // Prioriser par date de demande (anciennes d'abord)
                $besoinsNonSatisfaits = $this->besoinRepo->findBesoinsNonSatisfaitsParVilleOrdreDate($villeId);

            foreach ($besoinsNonSatisfaits as $besoin) {
                $besoinId = (int)$besoin['besoin_id'];
                $quantiteDemandee = (int)$besoin['quantite_demandee'];
                $quantiteDistribuee = (int)$besoin['quantite_distribuee'];
                $resteADistribuer = $quantiteDemandee - $quantiteDistribuee;

                if ($resteADistribuer <= 0) continue;

                $stockDisponible = $stockParBesoin[$besoinId] ?? 0;
                if ($stockDisponible <= 0) continue;

                $quantiteADistribuer = min($resteADistribuer, $stockDisponible);

                if ($quantiteADistribuer > 0) {
                    $stockParBesoin[$besoinId] -= $quantiteADistribuer;

                    $resultat['distributions'][] = [
                        'ville_id' => $villeId,
                        'ville_nom' => $villeNom,
                        'besoin_id' => $besoinId,
                            'date_demande' => $besoin['date_demande'] ?? null,
                        'besoin_libelle' => $besoin['besoin_libelle'],
                        'unite' => $besoin['unite'],
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
                'date_demande' => $dist['date_demande'] ?? null,
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
     * Valider et enregistrer en base : utilise simulerSansEnregistrer() 
     * pour garantir que seules les distributions de l'aperçu sont insérées.
     */
    public function simulerDispatch(?string $date = null): array {
        $date = $date ?? date('Y-m-d');

        // Récupérer exactement le même résultat que l'aperçu
        $apercu = $this->simulerSansEnregistrer();

        if (empty($apercu['distributions'])) {
            return [
                'date' => $date,
                'distributions' => [],
                'total_distribue' => 0,
                'villes_traitees' => 0,
                'besoins_satisfaits' => 0,
                'success' => true
            ];
        }

        try {
            $this->pdo->beginTransaction();

            // Créer une nouvelle distribution
            $distributionId = $this->distributionRepo->creerDistribution($date);

            // Insérer UNIQUEMENT les distributions calculées par l'aperçu
            foreach ($apercu['distributions'] as $dist) {
                $this->distributionRepo->insererDistributionDetail(
                    $distributionId,
                    (int)$dist['besoin_id'],
                    (int)$dist['quantite_distribuee'],
                    (int)$dist['ville_id']
                );
            }

            $this->pdo->commit();

            $apercu['distribution_id'] = $distributionId;
            $apercu['date'] = $date;
            $apercu['success'] = true;

            return $apercu;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return [
                'date' => $date,
                'distributions' => [],
                'total_distribue' => 0,
                'villes_traitees' => 0,
                'besoins_satisfaits' => 0,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Simulation de distribution par ordre de quantité (du plus petit au plus grand)
     * 1. Récupérer la liste des villes
     * 2. Récupérer les besoins (group by besoin) d'une ville
     * 3. Récupérer la liste des besoins par ville et par besoin ordonnés par quantité
     * 4. Simuler la distribution sans sauvegarder en considérant les besoins déjà distribués
     */
    public function simulerParOrdreQuantite(): array {
        $resultat = [
            'date' => date('Y-m-d'),
            'distributions' => [],
            'total_distribue' => 0,
            'villes_traitees' => 0,
            'besoins_satisfaits' => 0,
            'success' => true
        ];

        // 1. Récupérer la liste des villes
        $villes = $this->villeRepo->findAll();
        $stockParBesoin = $this->calculerStockDisponible();

        foreach ($villes as $ville) {
            $villeId = (int)$ville['v_id'];
            $villeNom = $ville['v_nom'];

            // 2. Récupérer les besoins groupés par besoin pour cette ville
            $besoinsGroupes = $this->besoinRepo->findBesoinsParVilleGroupByBesoin($villeId);

            foreach ($besoinsGroupes as $besoinGroupe) {
                $besoinId = (int)$besoinGroupe['besoin_id'];
                $totalDemande = (int)$besoinGroupe['quantite_demandee'];
                $totalDistribue = (int)$besoinGroupe['quantite_distribuee'];
                $resteGlobal = $totalDemande - $totalDistribue;

                if ($resteGlobal <= 0) continue;

                $stockDisponible = $stockParBesoin[$besoinId] ?? 0;
                if ($stockDisponible <= 0) continue;

                // 3. Récupérer la liste des besoins par ville et par besoin ordonnés par quantité (plus petit d'abord)
                $besoinsIndividuels = $this->besoinRepo->findBesoinsParVilleEtBesoinOrdreQuantite($villeId, $besoinId);

                // Distribuer en tenant compte des besoins déjà distribués
                $dejaDistribueRestant = $totalDistribue;

                foreach ($besoinsIndividuels as $besoinIndiv) {
                    $quantiteEntree = (int)$besoinIndiv['quantite_demandee'];

                    // Soustraire le déjà distribué des entrées les plus petites d'abord
                    if ($dejaDistribueRestant >= $quantiteEntree) {
                        $dejaDistribueRestant -= $quantiteEntree;
                        continue;
                    }

                    $resteEntree = $quantiteEntree - $dejaDistribueRestant;
                    $dejaDistribueRestant = 0;

                    if ($resteEntree <= 0) continue;

                    $stockDisponible = $stockParBesoin[$besoinId] ?? 0;
                    if ($stockDisponible <= 0) break;

                    $quantiteADistribuer = min($resteEntree, $stockDisponible);

                    if ($quantiteADistribuer > 0) {
                        $stockParBesoin[$besoinId] -= $quantiteADistribuer;

                        $resultat['distributions'][] = [
                            'ville_id' => $villeId,
                            'ville_nom' => $villeNom,
                            'besoin_id' => $besoinId,
                            'bv_id' => $besoinIndiv['bv_id'],
                            'date_demande' => $besoinIndiv['date_demande'] ?? null,
                            'besoin_libelle' => $besoinIndiv['besoin_libelle'],
                            'unite' => $besoinIndiv['unite'],
                            'quantite_demandee' => $quantiteEntree,
                            'deja_distribue' => $quantiteEntree - $resteEntree,
                            'quantite_distribuee' => $quantiteADistribuer,
                            'reste_apres' => $resteEntree - $quantiteADistribuer
                        ];

                        $resultat['total_distribue'] += $quantiteADistribuer;
                        if ($resteEntree - $quantiteADistribuer == 0) {
                            $resultat['besoins_satisfaits']++;
                        }
                    }
                }
            }

            $resultat['villes_traitees']++;
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
     * Insérer une distribution simulée (wrapper)
     * @param string $date
     * @param array $details each item: ['dd_besoin'=>int, 'dd_quantite'=>int, 'dd_ville'=>int]
     * @return int distribution id
     */
    public function insererDistributionSimulee(string $date, array $details): int {
        return $this->distributionRepo->insererDistribution($date, $details);
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
                'date_demande' => $row['date_demande'] ?? null,
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
