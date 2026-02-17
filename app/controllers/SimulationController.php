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
     * Accepte le type de simulation : 1=par date, 2=par ordre de quantité, 3=proportionnel
     */
    public function executer() {
        $db = Flight::db();
        $service = new SimulationDonService($db);

        $typeSimulation = (int)(Flight::request()->data->type_simulation ?? 1);

        // Simuler SANS enregistrer en base selon le type choisi
        switch ($typeSimulation) {
            case 2:
                $result = $service->simulerParOrdreQuantite();
                break;
            case 3:
                $result = $service->simulerProportionnel();
                break;
            default:
                $result = $service->simulerSansEnregistrer();
                break;
        }

        // Grouper par ville pour l'affichage
        $simulationParVille = $service->grouperSimulationParVille($result);

        $statistiques = $service->getStatistiquesGlobales();

        Flight::render('simulation', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'distributionsParVille' => $service->getDistributionsGroupeesParVille(),
            'simulationParVille' => $simulationParVille,
            'statistiques' => $statistiques,
            'simulation_result' => $result,
            'mode_apercu' => true,
            'type_simulation' => $typeSimulation
        ]);
    }

    /**
     * Valider et enregistrer la simulation en base
     * Accepte le type de simulation pour utiliser le même algorithme que l'aperçu
     */
    public function valider() {
        $db = Flight::db();
        $service = new SimulationDonService($db);

        $typeSimulation = (int)(Flight::request()->data->type_simulation ?? 1);

        // Exécuter et ENREGISTRER en base avec le même type que l'aperçu
        $result = $service->simulerDispatch(null, $typeSimulation);

        // Récupérer les données mises à jour
        $distributionsParVille = $service->getDistributionsGroupeesParVille();
        $statistiques = $service->getStatistiquesGlobales();

        Flight::render('simulation', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'distributionsParVille' => $distributionsParVille,
            'statistiques' => $statistiques,
            'validation_result' => $result,
            'type_simulation' => $typeSimulation
        ]);
    }
}
