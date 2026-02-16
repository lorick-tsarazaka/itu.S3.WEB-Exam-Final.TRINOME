<?php

namespace app\repositories;

use PDO;

class BesoinRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}