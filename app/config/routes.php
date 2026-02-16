<?php

use app\controllers\MainController;
use app\controllers\TableauBordController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

$router->group('', function(Router $router) use ($app) {
    // Page d'accueil - Tableau de bord
    $router->get('/', function() use ($app) {
        $controller = new MainController($app);
        $controller->home();
    });
}, [ SecurityHeadersMiddleware::class ]);

//  Tableau de bord Routes
$router->group('', function(Router $router) use ($app) {

    //  Register
    $router->get('/tableauBord', function() use ($app) {
        $controller = new TableauBordController($app);
        $controller->showTableauBord();
    });

}, [ SecurityHeadersMiddleware::class ]);