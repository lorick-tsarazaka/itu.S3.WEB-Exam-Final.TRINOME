<?php
declare(strict_types=1);

namespace app\repositories;

use flight\Engine;
use PDOException;

class BesoinRepository
{
    protected Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

    /**
     * Insert a single besoin for a ville into `bngrc_besoinVille`.
     * Returns inserted id on success, false on failure.
     */
    public function insertBesoinVille(int $besoinId, int $villeId, int $quantite)
    {
        $db = $this->app->db();
        try {
            $stmt = $db->runQuery(
                'INSERT INTO bngrc_besoinVille (bv_besoin, bv_quantite, bv_ville) VALUES (?, ?, ?)',
                [ $besoinId, $quantite, $villeId ]
            );
            return (int)$db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
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
