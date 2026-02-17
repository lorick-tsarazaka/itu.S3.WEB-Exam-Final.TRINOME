<?php

namespace app\controllers;

use app\repositories\BesoinRepository;
use app\repositories\CollecteRepository;
use Flight;
use flight\Engine;

class AchatsController {
    protected Engine $app;
    protected BesoinRepository $besoinRepo;
    protected CollecteRepository $collecteRepo;

    public function __construct(Engine $app) {
        $this->app = $app;
        $this->besoinRepo = new BesoinRepository(Flight::db());
        $this->collecteRepo = new CollecteRepository(Flight::db());
    }

    public function index() {
        $besoins = $this->besoinRepo->findAllBesoinsNonArgentAvecEtat();
        $totalArgent = $this->besoinRepo->getTotalCollecteParCategorie(1); // en MGA

        Flight::render('Achats', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'besoins' => $besoins,
            'totalArgent' => $totalArgent
        ]);
    }

    /**
     * Enregistrer tous les achats du panier en une seule fois.
     * Reçoit un JSON avec la liste des besoins à recouvrir.
     */
    public function enregistrer() {
        $data = Flight::request()->data;
        $panierJson = $data->panier ?? '[]';
        $percent = floatval($data->percent ?? 0);

        $items = json_decode($panierJson, true);
        if (!is_array($items) || empty($items)) {
            Flight::redirect('/achats?error=1');
            return;
        }

        if ($percent < 0 || $percent > 100) {
            Flight::redirect('/achats?error=4');
            return;
        }

        $db = Flight::db();
        $totalArgent = $this->besoinRepo->getTotalCollecteParCategorie(1);

        // Calculer le coût total
        $coutTotal = 0;
        $detailsCollecte = [];

        foreach ($items as $item) {
            $villeId = (int)($item['ville'] ?? 0);
            $besoinId = (int)($item['besoin'] ?? 0);
            $quantite = floatval($item['quantite'] ?? 0);

            if ($villeId <= 0 || $besoinId <= 0 || $quantite <= 0) continue;

            // Récupérer le prix unitaire
            $stmt = $db->prepare('SELECT b_prixUnitraire FROM bngrc_besoin WHERE b_id = ?');
            $stmt->execute([$besoinId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $prixUnitaire = $row ? floatval($row['b_prixUnitraire']) : 0.0;

            $prixAvec = $quantite * $prixUnitaire * (1 + ($percent / 100.0));
            $coutTotal += $prixAvec;

            // Ajouter le besoin acheté (quantité positive)
            $detailsCollecte[] = ['cd_besoin' => $besoinId, 'cd_quantite' => $quantite];
            // Retrait sur Ariary (négatif)
            $detailsCollecte[] = ['cd_besoin' => 1, 'cd_quantite' => -round($prixAvec, 2)];
        }

        if ($coutTotal > $totalArgent) {
            Flight::redirect('/achats?error=5');
            return;
        }

        if (empty($detailsCollecte)) {
            Flight::redirect('/achats?error=1');
            return;
        }

        try {
            $this->collecteRepo->insererCollecte(date('Y-m-d'), $detailsCollecte);
            Flight::redirect('/achats?success=1');
        } catch (\Exception $e) {
            Flight::redirect('/achats?error=2');
        }
    }
}
