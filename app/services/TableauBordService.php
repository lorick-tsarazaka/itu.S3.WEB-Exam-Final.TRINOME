<?php

namespace app\services;

use app\repositories\VilleRepository;
use app\repositories\BesoinRepository;
use app\repositories\DistributionRepository;

class EchangerService {
    private VilleRepository $villeRepo;
    private BesoinRepository $besoinRepo;
    private DistributionRepository $distributionRepo;

    public function __construct(VilleRepository $villeRepo, BesoinRepository $besoinRepo, DistributionRepository $distributionRepo) {
        $this->villeRepo = $villeRepo;
        $this->besoinRepo = $besoinRepo;
        $this->distributionRepo = $distributionRepo;
    }

    // function pour recuperer toutes les besoins d'une ville
    public function findByBesoinVille() {
        $villes = $villeRepo->findAll();
        $besoins = [];

        for ($i = 0; $i < count($villes); $i++) {
            $besoins = $besoinRepo->findByVille($villes['v_id']);
        }

        return $besoins;
    }

    //  function pour recuperer toutes les distributions d'une ville
    public function findByDistributionVille() {
        $villes = $villeRepo->findAll();
        $distribution = [];

        for ($i = 0; $i < count($villes); $i++) {
            $distribution = $distributionRepo->findByVille($villes['v_id']);
        }

        return $distribution;
    }
}