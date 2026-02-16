<?php

namespace app\controllers;

use Flight;
use app\repositories\BesoinRepository;
use app\repositories\DistributionRepository;
// use flight\Engine;

class BesoinController {
    
    //  get page TableauBord
    public static function showRegister() {
    Flight::render('TableauBord', [
      'csp_nonce' => Flight::get('csp_nonce')
    ]);
  }
}