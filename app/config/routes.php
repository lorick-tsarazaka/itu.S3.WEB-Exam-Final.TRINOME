<?php

use app\controllers\MainController;
use app\controllers\DistributionController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

$router->group('', function(Router $router) use ($app) {
    // Page d'accueil - Tableau de bord
    $router->get('/', function() use ($app) {
        $controller = new MainController($app);
        $controller->home();
    });

    // Saisie Distribution
    $router->get('/distribution/saisie', function() use ($app) {
        $controller = new DistributionController($app);
        $controller->saisieDistribution();
    });

    $router->post('/distribution/saisie', function() use ($app) {
        $controller = new DistributionController($app);
        $controller->enregistrerDistribution();
    });
}, [ SecurityHeadersMiddleware::class ]);