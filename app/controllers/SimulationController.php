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
     * Afficher la page de simulation (état initial)
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
     * Simuler le dispatch (aperçu sans insertion en base)
     */
    public function executer() {
        $db = Flight::db();
        $service = new SimulationDonService($db);

        // Simuler SANS enregistrer en base
        $result = $service->simulerSansEnregistrer();

        // Grouper par ville pour l'affichage
        $simulationParVille = $service->grouperSimulationParVille($result);

        $statistiques = $service->getStatistiquesGlobales();

        Flight::render('simulation', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'distributionsParVille' => $service->getDistributionsGroupeesParVille(),
            'simulationParVille' => $simulationParVille,
            'statistiques' => $statistiques,
            'simulation_result' => $result,
            'mode_apercu' => true
        ]);
    }

    /**
     * Valider et enregistrer la simulation en base
     */
    public function valider() {
        $db = Flight::db();
        $service = new SimulationDonService($db);

        // Exécuter et ENREGISTRER en base
        $result = $service->simulerDispatch();

        // Récupérer les données mises à jour
        $distributionsParVille = $service->getDistributionsGroupeesParVille();
        $statistiques = $service->getStatistiquesGlobales();

        Flight::render('simulation', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'distributionsParVille' => $distributionsParVille,
            'statistiques' => $statistiques,
            'validation_result' => $result
        ]);
    }
}
