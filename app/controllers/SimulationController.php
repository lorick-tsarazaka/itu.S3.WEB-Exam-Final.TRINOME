<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\services\SimulationDonService;

class SimulationController {
    protected Engine $app;

    public function __construct($app) {
        $this->app = $app;
    }

    /**
     * Afficher la page de simulation avec la liste des distributions par ville
     */
    public function index() {
        $db = Flight::db();
        $service = new SimulationDonService($db);

        $distributionsParVille = $service->getDistributionsGroupeesParVille();
        $statistiques = $service->getStatistiquesGlobales();

        Flight::render('simulation', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'distributionsParVille' => $distributionsParVille,
            'statistiques' => $statistiques
        ]);
    }

    /**
     * Exécuter la simulation de dispatch
     */
    public function executer() {
        $db = Flight::db();
        $service = new SimulationDonService($db);

        // Exécuter la simulation
        $result = $service->simulerDispatch();

        // Récupérer les données mises à jour
        $distributionsParVille = $service->getDistributionsGroupeesParVille();
        $statistiques = $service->getStatistiquesGlobales();

        Flight::render('simulation', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'distributionsParVille' => $distributionsParVille,
            'statistiques' => $statistiques,
            'simulation_result' => $result
        ]);
    }
}
