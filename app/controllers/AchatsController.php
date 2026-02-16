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
     * Traitement du bouton "Recouvrir" : insère une collecte avec le besoin acheté
     * et enregistre une entrée négative sur le besoin 'Ariary' (b_id = 1) pour diminuer la trésorerie.
     */
    public function recouvrir() {
        $data = Flight::request()->data;
        $villeId = (int)($data->ville ?? 0);
        $besoinId = (int)($data->besoin ?? 0);
        $quantite = floatval($data->quantite ?? 0);
        $percent = $data->percent ?? null;

        if ($villeId <= 0 || $besoinId <= 0 || $quantite <= 0) {
            Flight::redirect('/achats?error=1');
            return;
        }

        // validate percent server-side: must be numeric and between 0 and 100
        if (!is_numeric($percent)) {
            Flight::redirect('/achats?error=3');
            return;
        }
        $percent = floatval($percent);
        if ($percent < 0 || $percent > 100) {
            Flight::redirect('/achats?error=4');
            return;
        }

        // récupérer le prix unitaire du besoin
        $db = Flight::db();
        $stmt = $db->prepare('SELECT b_prixUnitraire FROM bngrc_besoin WHERE b_id = ?');
        $stmt->execute([$besoinId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $prixUnitaire = $row ? floatval($row['b_prixUnitraire']) : 0.0;

        $prixSansPourcent = $quantite * $prixUnitaire;
        $prixAvecPourcent = $prixSansPourcent * (1 + ($percent / 100.0));

        // Vérifier côté serveur si fonds suffisants (catégorie "Argent" = 1)
        $totalArgent = $this->besoinRepo->getTotalCollecteParCategorie(1);
        if ($prixAvecPourcent > $totalArgent) {
            Flight::redirect('/achats?error=5');
            return;
        }

        // Préparer détails: d'abord le besoin acheté (quantité positive), puis retrait sur Ariary (b_id = 1) en négatif
        $details = [
            [ 'cd_besoin' => $besoinId, 'cd_quantite' => $quantite ],
            [ 'cd_besoin' => 1, 'cd_quantite' => -round($prixAvecPourcent, 2) ]
        ];

        try {
            $this->collecteRepo->insererCollecte(date('Y-m-d'), $details);
            Flight::redirect('/achats?success=1');
        } catch (\Exception $e) {
            Flight::redirect('/achats?error=2');
        }
    }
}
