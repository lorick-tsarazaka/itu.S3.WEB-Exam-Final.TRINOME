<?php

namespace app\repositories;

use PDO;

class BesoinRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // function to get besoin for one ville
    public function findByVille($id_ville) {
        $st = $this->pdo->prepare("SELECT 
                    v.v_nom AS ville,
                    b.b_libelle AS besoin,
                    cb.cb_libelle AS categorie,
                    ub.ub_libelle AS unite,
                    bv.bv_quantite AS quantite,
                    b.b_prixUnitraire AS prix_unitaire
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_categorieBesoin cb ON b.b_categorie = cb.cb_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                WHERE v.v_id = ?");
        $st->execute([(int)$id_ville]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
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