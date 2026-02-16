<?php

namespace app\controllers;

use Flight;
use app\repositories\BesoinRepository;
use app\repositories\DistributionRepository;
use app\repositories\VilleRepository;
use app\services\TableauBordService;
// use flight\Engine;

class TableauBordController {
    
    //  get page TableauBord
    public static function showTableauBord() {
        $pdo  = Flight::db();

        $villeRepo = new VilleRepository($pdo);
        $besoinRepo = new BesoinRepository($pdo);
        $distributionRepo = new DistributionRepository($pdo);

        $svc  = new TableauBordService($villeRepo, $besoinRepo, $distributionRepo);

        $data = $svc->getTableauBordData();

        Flight::render('TableauBord', [
            'csp_nonce' => Flight::get('csp_nonce'),
            'data' => $data
        ]);
    }
}