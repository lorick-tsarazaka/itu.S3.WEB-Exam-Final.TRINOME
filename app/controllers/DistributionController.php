<?php

namespace app\controllers;

use app\repositories\DistributionRepository;
use Flight;
use flight\Engine;

class DistributionController {
    protected Engine $app;
    protected DistributionRepository $distributionRepository;

    public function __construct(Engine $app) {
        $this->app = $app;
        $this->distributionRepository = new DistributionRepository(Flight::db());
    }

    /**
     * Affiche le formulaire de saisie de distribution
     */
    public function saisieDistribution() {
        $besoins = $this->distributionRepository->getAllBesoins();
        $villes = $this->distributionRepository->getAllVilles();

        Flight::render('SaisieDistribution', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'besoins' => $besoins,
            'villes' => $villes
        ]);
    }

    /**
     * Traite la soumission du formulaire de distribution
     */
    public function enregistrerDistribution() {
        $date = Flight::request()->data->date;
        $detailsBesoins = Flight::request()->data->dd_besoin;
        $detailsQuantites = Flight::request()->data->dd_quantite;
        $detailsVilles = Flight::request()->data->dd_ville;

        if (empty($date) || empty($detailsBesoins)) {
            Flight::redirect('/distribution/saisie?error=1');
            return;
        }

        $details = [];
        for ($i = 0; $i < count($detailsBesoins); $i++) {
            if (!empty($detailsBesoins[$i]) && !empty($detailsQuantites[$i]) && !empty($detailsVilles[$i])) {
                $details[] = [
                    'dd_don'      => 0, // sera mis à jour après insertion
                    'dd_besoin'   => (int) $detailsBesoins[$i],
                    'dd_quantite' => (int) $detailsQuantites[$i],
                    'dd_ville'    => (int) $detailsVilles[$i]
                ];
            }
        }

        try {
            $distributionId = $this->distributionRepository->insererDistribution($date, $details);
            Flight::redirect('/distribution/saisie?success=1');
        } catch (\Exception $e) {
            Flight::redirect('/distribution/saisie?error=2');
        }
    }
}
