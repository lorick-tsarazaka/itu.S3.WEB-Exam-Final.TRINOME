<?php

namespace app\repositories;

use PDO;

class CollecteRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer toutes les collectes avec le nombre de détails et la date
     */
    public function getAllCollectes(): array {
        $sql = "SELECT 
                    c.c_id,
                    c.c_date,
                    COUNT(cd.cd_id) AS nb_details,
                    SUM(cd.cd_quantite) AS total_quantite
                FROM bngrc_collecte c
                LEFT JOIN bngrc_collecteDetails cd ON cd.cd_collecte = c.c_id
                GROUP BY c.c_id, c.c_date
                ORDER BY c.c_date DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les détails d'une collecte
     */
    public function getCollecteDetails(int $collecteId): array {
        $sql = "SELECT 
                    cd.cd_id,
                    cd.cd_quantite,
                    b.b_id,
                    b.b_libelle,
                    ub.ub_libelle AS unite
                FROM bngrc_collecteDetails cd
                JOIN bngrc_besoin b ON cd.cd_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                WHERE cd.cd_collecte = :collecteId
                ORDER BY b.b_libelle";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collecteId' => $collecteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les collectes disponibles par besoin, ordonnées par date et id (FIFO)
     * Stock disponible = quantite_collecte - déjà_distribué
     */
    public function getCollectesDisponiblesParBesoin(int $besoinId): array {
        $sql = "SELECT 
                    cd.cd_id,
                    cd.cd_collecte,
                    cd.cd_besoin,
                    cd.cd_quantite AS quantite_collectee,
                    c.c_date,
                    b.b_libelle AS besoin_libelle,
                    COALESCE(
                        (SELECT SUM(dd.dd_quantite) 
                         FROM bngrc_distributionDetails dd 
                         WHERE dd.dd_besoin = cd.cd_besoin), 0
                    ) AS total_distribue_global,
                    cd.cd_quantite AS stock_initial
                FROM bngrc_collecteDetails cd
                JOIN bngrc_collecte c ON cd.cd_collecte = c.c_id
                JOIN bngrc_besoin b ON cd.cd_besoin = b.b_id
                WHERE cd.cd_besoin = :besoin_id
                ORDER BY c.c_date ASC, cd.cd_id ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':besoin_id' => $besoinId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer le stock total disponible par besoin (toutes collectes - distributions)
     */
    public function getStockDisponibleParBesoin(int $besoinId): int {
        $sql = "SELECT 
                    COALESCE(SUM(cd.cd_quantite), 0) - COALESCE(
                        (SELECT SUM(dd.dd_quantite) 
                         FROM bngrc_distributionDetails dd 
                         WHERE dd.dd_besoin = :besoin_id_2), 0
                    ) AS stock_disponible
                FROM bngrc_collecteDetails cd
                WHERE cd.cd_besoin = :besoin_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':besoin_id' => $besoinId, ':besoin_id_2' => $besoinId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return max(0, (int)($result['stock_disponible'] ?? 0));
    }

    /**
     * Récupérer toutes les collectes avec stock disponible par besoin (pour simulation)
     */
    public function getAllCollectesAvecStockParBesoin(): array {
        $sql = "SELECT 
                    cd.cd_id,
                    cd.cd_collecte,
                    cd.cd_besoin AS besoin_id,
                    cd.cd_quantite AS quantite_collectee,
                    c.c_date,
                    b.b_libelle AS besoin_libelle
                FROM bngrc_collecteDetails cd
                JOIN bngrc_collecte c ON cd.cd_collecte = c.c_id
                JOIN bngrc_besoin b ON cd.cd_besoin = b.b_id
                ORDER BY cd.cd_besoin, c.c_date ASC, cd.cd_id ASC";
        
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer une collecte avec ses détails (date + liste de besoins/quantités)
     */
    public function insererCollecte(string $date, array $details): int {
        try {
            $this->pdo->beginTransaction();

            // 1. Insérer la collecte
            $sql = "INSERT INTO bngrc_collecte (c_date) VALUES (:c_date)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':c_date' => $date]);
            $collecteId = $this->pdo->lastInsertId();

            // 2. Insérer les détails
            $sqlDetail = "INSERT INTO bngrc_collecteDetails (cd_collecte, cd_besoin, cd_quantite) 
                          VALUES (:cd_collecte, :cd_besoin, :cd_quantite)";
            $stmtDetail = $this->pdo->prepare($sqlDetail);

            foreach ($details as $detail) {
                $stmtDetail->execute([
                    ':cd_collecte' => $collecteId,
                    ':cd_besoin'   => $detail['cd_besoin'],
                    ':cd_quantite' => $detail['cd_quantite']
                ]);
            }

            $this->pdo->commit();
            return (int) $collecteId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Récupérer tous les besoins avec leur unité
     */
    public function getAllBesoins(): array {
        $sql = "SELECT b.b_id, b.b_libelle, ub.ub_libelle AS unite 
                FROM bngrc_besoin b 
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id 
                ORDER BY b.b_libelle";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
