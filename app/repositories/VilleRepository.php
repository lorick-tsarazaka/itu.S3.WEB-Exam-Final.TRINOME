<?php

namespace app\repositories;

use PDO;

class VilleRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    //  function to get all ville
    public function findAll() {
        $st = $this->pdo->prepare("SELECT * FROM bngrc_ville");
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}