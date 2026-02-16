<?php

namespace app\services;

use app\repositories\VilleRepository;
use app\repositories\BesoinRepository;
use app\repositories\DistributionRepository;

class TableauBordService {
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
        $villes = $this->$villeRepo->findAll();
        $besoins = [];

        for ($i = 0; $i < count($villes); $i++) {
            $besoins = $this->$besoinRepo->findByVille($villes['v_id']);
        }

        return $besoins;
    }

    //  function pour recuperer toutes les distributions d'une ville
    public function findByDistributionVille() {
        $villes = $this->$villeRepo->findAll();
        $distribution = [];

        for ($i = 0; $i < count($villes); $i++) {
            $distribution = $this->$distributionRepo->findByVille($villes['v_id']);
        }

        return $distribution;
    }

    //  function to get all assembled (besoins + distributions)
    public function getTableauBordData() {

        $villes = $this->villeRepo->findAll();
        $result = [];

        foreach ($villes as $ville) {

            $besoins = $this->besoinRepo->findByVille($ville['v_id']);
            $distributions = $this->distributionRepo->findByVille($ville['v_id']);

            $result[] = [
                'ville' => $ville,
                'besoins' => $besoins,
                'distributions' => $distributions
            ];
        }

        return $result;
    }
}