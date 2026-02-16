<?php

use app\controllers\MainController;
use app\controllers\BesoinController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

$router->group('', function(Router $router) use ($app) {
    // Page d'accueil - Tableau de bord
    $router->get('/', function() use ($app) {
        $controller = new MainController($app);
        $controller->home();
    });

    // Besoins: saisie + enregistrement
    $router->get('/besoins', function() use ($app) {
        $controller = new BesoinController($app);
        $controller->index();
    });

    $router->post('/besoins/save', function() use ($app) {
        $controller = new BesoinController($app);
        $controller->save();
    });
}, [ SecurityHeadersMiddleware::class ]);