<?php

namespace app\controllers;
use Flight;
use flight\Engine;

class MainController {
    protected Engine $app;

    public function __construct($app) {
        $this->app = $app;
    }

    /**
     * Page d'accueil - Tableau de bord
     */
    public function home() {
        Flight::render('home', [
            'csp_nonce' => Flight::get('csp_nonce')
        ]);
    }

}