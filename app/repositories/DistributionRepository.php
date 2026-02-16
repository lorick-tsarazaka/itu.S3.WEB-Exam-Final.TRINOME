<?php

namespace app\repositories;

use PDO;

class DistributionRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}