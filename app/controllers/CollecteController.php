<?php

namespace app\controllers;

use app\repositories\CollecteRepository;
use Flight;
use flight\Engine;

class CollecteController {
    protected Engine $app;
    protected CollecteRepository $collecteRepository;

    public function __construct(Engine $app) {
        $this->app = $app;
        $this->collecteRepository = new CollecteRepository(Flight::db());
    }

    /**
     * Affiche la liste des collectes
     */
    public function listCollectes() {
        $collectes = $this->collecteRepository->getAllCollectes();

        Flight::render('ListCollecte', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'collectes' => $collectes
        ]);
    }

    /**
     * Affiche le formulaire d'ajout de collecte
     */
    public function addCollecte() {
        $besoins = $this->collecteRepository->getAllBesoins();

        Flight::render('AddCollecte', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'besoins' => $besoins
        ]);
    }

    /**
     * Traite la soumission du formulaire de collecte
     */
    public function enregistrerCollecte() {
        $date = Flight::request()->data->date;
        $detailsBesoins = Flight::request()->data->cd_besoin;
        $detailsQuantites = Flight::request()->data->cd_quantite;

        if (empty($date) || empty($detailsBesoins)) {
            Flight::redirect('/collecte/add?error=1');
            return;
        }

        $details = [];
        for ($i = 0; $i < count($detailsBesoins); $i++) {
            if (!empty($detailsBesoins[$i]) && !empty($detailsQuantites[$i])) {
                $details[] = [
                    'cd_besoin'   => (int) $detailsBesoins[$i],
                    'cd_quantite' => (int) $detailsQuantites[$i]
                ];
            }
        }

        if (empty($details)) {
            Flight::redirect('/collecte/add?error=1');
            return;
        }

        try {
            $collecteId = $this->collecteRepository->insererCollecte($date, $details);
            Flight::redirect('/collecte?success=1');
        } catch (\Exception $e) {
            Flight::redirect('/collecte/add?error=2');
        }
    }

    /**
     * Retourne les détails d'une collecte en JSON (pour le fetch côté client)
     */
    public function getDetails(int $id) {
        $details = $this->collecteRepository->getCollecteDetails($id);
        Flight::json($details);
    }
}
