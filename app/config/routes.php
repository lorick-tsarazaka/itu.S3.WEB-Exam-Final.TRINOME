<?php

use app\controllers\MainController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

$router->group('', function(Router $router) use ($app) {
    // Page d'accueil - Tableau de bord
    $router->get('/', function() use ($app) {
        $controller = new MainController($app);
        $controller->home();
    });

    // Pages des villes
    $router->get('/villes', function() use ($app) {
        $controller = new MainController($app);
        $controller->villes();
    });

    // Pages des besoins
    $router->get('/besoins', function() use ($app) {
        $controller = new MainController($app);
        $controller->besoins();
    });

    // Pages des dons
    $router->get('/dons', function() use ($app) {
        $controller = new MainController($app);
        $controller->dons();
    });

}, [ SecurityHeadersMiddleware::class ]);