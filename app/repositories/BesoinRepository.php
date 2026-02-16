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
     * Insert a single besoin
     */
    public function insertBesoinVille(int $besoinId, int $villeId, int $quantite)
    {
        $db = $this->pdo;
        try {
            $stmt = $db->runQuery(
                'INSERT INTO bngrc_besoinVille (bv_besoin, bv_quantite, bv_ville, bv_status) VALUES (?, ?, ?, ?)',
                [ $besoinId, $quantite, $villeId, 1 ]
            );
            return (int)$db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Insert multiple besoins 
     */
    public function insertMultipleForVille(int $villeId, array $besoins)
    {
        $db = $this->pdo;
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
                    'INSERT INTO bngrc_besoinVille (bv_besoin, bv_quantite, bv_ville, bv_status) VALUES (?, ?, ?, ?)',
                    [ $besoinId, $quantite, $villeId, 1 ]
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

    /**
     * Récupérer les besoins pas encore satisfaits dans une ville
     * (reste à distribuer > 0)
     */
    public function getBesoinsNonSatisfaits(int $villeId): array
    {
        $sql = "SELECT 
                    bv.bv_id,
                    bv.bv_besoin,
                    bv.bv_quantite,
                    bv.bv_ville,
                    b.b_libelle,
                    ub.ub_libelle AS unite,
                    v.v_nom AS ville
                FROM bngrc_besoinVille bv
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                WHERE bv.bv_ville = :villeId
                ORDER BY b.b_libelle";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':villeId' => $villeId]);
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($besoins as $besoin) {
            $reste = $this->getResteADistribuer((int) $besoin['bv_besoin'], $villeId);
            if ($reste > 0) {
                $besoin['reste'] = $reste;
                $result[] = $besoin;
            }
        }
        return $result;
    }

    /**
     * Calculer le reste (quantité) de besoin à satisfaire
     * reste = besoin_total - déjà_distribué
     */
    public function getResteADistribuer(int $besoinId, int $villeId): int
    {
        // Total besoin pour cette ville
        $sqlBesoin = "SELECT SUM(bv_quantite) 
                      FROM bngrc_besoinVille 
                      WHERE bv_besoin = :besoinId AND bv_ville = :villeId";
        $stmt = $this->pdo->prepare($sqlBesoin);
        $stmt->execute([':besoinId' => $besoinId, ':villeId' => $villeId]);
        $totalBesoin = (int) $stmt->fetchColumn();

        // Total déjà distribué pour ce besoin dans cette ville
        $sqlDistrib = "SELECT SUM(dd_quantite) 
                       FROM bngrc_distributionDetails 
                       WHERE dd_besoin = :besoinId AND dd_ville = :villeId";
        $stmt2 = $this->pdo->prepare($sqlDistrib);
        $stmt2->execute([':besoinId' => $besoinId, ':villeId' => $villeId]);
        $totalDistribue = (int) $stmt2->fetchColumn();

        $reste = $totalBesoin - $totalDistribue;
        if ($reste < 0) {
            $reste = 0;
        }
        return $reste;
    }

}