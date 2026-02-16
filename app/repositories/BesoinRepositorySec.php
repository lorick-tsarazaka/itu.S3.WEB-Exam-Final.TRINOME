<?php

namespace app\repositories;
use Flight\Engine;
use PDOException;

class BesoinRepositorySec {
    private Engine $app;

    public function __construct($app) {
        $this->app = $app;
    }
    /**
     * Insert multiple besoins for a given ville.
     * $besoins is an array of ['besoin' => id, 'quantite' => q] entries.
     * Returns array of inserted ids on success, or false on failure.
     */
    public function insertMultipleForVille(int $villeId, array $besoins)
    {
        $db = $this->app->db();
        $inserted = [];
        try {
            $db->beginTransaction();
            foreach ($besoins as $b) {
                $besoinId = (int)($b['besoin'] ?? 0);
                $quantite = (int)($b['quantite'] ?? 0);
                if ($besoinId <= 0) {
                    continue;
                }
                $db->runQuery(
                    'INSERT INTO bngrc_besoinVille (bv_besoin, bv_quantite, bv_ville) VALUES (?, ?, ?)',
                    [ $besoinId, $quantite, $villeId ]
                );
                $inserted[] = (int)$db->lastInsertId();
            }
            $db->commit();
            return $inserted;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }
}