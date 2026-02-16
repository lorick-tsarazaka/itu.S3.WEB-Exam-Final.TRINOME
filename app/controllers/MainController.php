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

    /**
     * Page de gestion des villes
     */
    public function villes() {
        Flight::render('villes', [
            'csp_nonce' => Flight::get('csp_nonce')
        ]);
    }

    /**
     * Page de gestion des besoins
     */
    public function besoins() {
        Flight::render('besoins', [
            'csp_nonce' => Flight::get('csp_nonce')
        ]);
    }

    /**
     * Page de gestion des dons
     */
    public function dons() {
        Flight::render('dons', [
            'csp_nonce' => Flight::get('csp_nonce')
        ]);
    }
}