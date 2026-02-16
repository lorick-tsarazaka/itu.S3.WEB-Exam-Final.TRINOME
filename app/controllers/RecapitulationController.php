<?php

namespace app\controllers;

use app\repositories\VilleRepository;
use app\services\RecapitulationService;
use Flight;

class RecapitulationController {
    private VilleRepository $villeRepository;
    private RecapitulationService $recapitulationService;

    public function __construct() {
        $pdo = Flight::db();
        $this->villeRepository = new VilleRepository($pdo);
        $this->recapitulationService = new RecapitulationService($pdo);
    }

    public function index() {
        $villes = $this->villeRepository->findAll();
        
        $villeId = $_GET['ville_id'] ?? null;
        
        if ($villeId) {
            $recapitulations = $this->recapitulationService->getRecapitulationParVille((int)$villeId);
        } else {
            $recapitulations = $this->recapitulationService->getRecapitulation();
        }
        
        return Flight::render('recapitulation', [
            'recapitulations' => $recapitulations,
            'villes' => $villes
        ]);
    }

    public function getData() {
        $villeId = $_GET['ville_id'] ?? null;
        
        if ($villeId) {
            $recapitulations = $this->recapitulationService->getRecapitulationParVille((int)$villeId);
        } else {
            $recapitulations = $this->recapitulationService->getRecapitulation();
        }
        
        Flight::json($recapitulations);
    }
}
