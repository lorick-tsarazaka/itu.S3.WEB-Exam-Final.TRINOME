<?php

use app\controllers\MainController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

$router->group('', function(Router $router) use ($app) {
    $router->get('/', function() use ($app) {
        $controller = new MainController($app);
        $controller->home();
    });

}, [ SecurityHeadersMiddleware::class ]);