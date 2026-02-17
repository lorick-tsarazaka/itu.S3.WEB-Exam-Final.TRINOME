<?php

namespace app\repositories;

use PDO;

class DistributionRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer les distributions d'une ville
    public function findByVille($id_ville) {
        $st = $this->pdo->prepare("
            SELECT 
                v.v_nom AS ville,
                d.d_date AS date_distribution,
                b.b_libelle AS besoin,
                cb.cb_libelle AS categorie,
                ub.ub_libelle AS unite,
                dd.dd_quantite AS quantite
            FROM bngrc_distributionDetails dd
            JOIN bngrc_distribution d 
                ON dd.dd_distribution = d.d_id
            JOIN bngrc_ville v 
                ON dd.dd_ville = v.v_id
            JOIN bngrc_besoin b 
                ON dd.dd_besoin = b.b_id
            JOIN bngrc_categorieBesoin cb 
                ON b.b_categorie = cb.cb_id
            JOIN bngrc_uniteBesoin ub 
                ON b.b_unite = ub.ub_id
            WHERE v.v_id = ?
            ORDER BY d.d_date ASC
        ");

        $st->execute([(int)$id_ville]);
        return $st->fetchAll(PDO::FETCH_ASSOC);

    }
    public function insererDistribution(string $date, array $details): int {
        try {
            $this->pdo->beginTransaction();

            // 1. Insérer dans bngrc_distribution et récupérer l'id
            $sqlDistribution = "INSERT INTO bngrc_distribution (d_date) VALUES (:d_date)";
            $stmt = $this->pdo->prepare($sqlDistribution);
            $stmt->execute([':d_date' => $date]);
            $distributionId = $this->pdo->lastInsertId();

            // 2. Insérer les détails dans bngrc_distributionDetails
            $sqlDetails = "INSERT INTO bngrc_distributionDetails (dd_distribution, dd_besoin, dd_quantite, dd_ville) 
                           VALUES (:dd_distribution, :dd_besoin, :dd_quantite, :dd_ville)";
            $stmtDetails = $this->pdo->prepare($sqlDetails);

            foreach ($details as $detail) {
                $stmtDetails->execute([
                    ':dd_distribution'      => $distributionId,
                    ':dd_besoin'   => $detail['dd_besoin'],
                    ':dd_quantite' => $detail['dd_quantite'],
                    ':dd_ville'    => $detail['dd_ville']
                ]);
            }

            $this->pdo->commit();
            return (int) $distributionId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Récupère tous les besoins avec leur unité
    public function getAllBesoins(): array {
        $sql = "SELECT b.b_id, b.b_libelle, ub.ub_libelle AS unite 
                FROM bngrc_besoin b 
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id 
                ORDER BY b.b_libelle";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère toutes les villes
    public function getAllVilles(): array {
        $sql = "SELECT v_id, v_nom FROM bngrc_ville ORDER BY v_nom";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère toutes les distributions
    public function getAllDistributions(): array {
        $sql = "SELECT * FROM bngrc_distribution ORDER BY d_date DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

  
    //Récupère les détails d'une distribution par son id
    public function getDistributionDetails(int $distributionId): array {
        $sql = "SELECT dd.*, b.b_libelle, v.v_nom 
                FROM bngrc_distributionDetails dd
                JOIN bngrc_besoin b ON dd.dd_besoin = b.b_id
                JOIN bngrc_ville v ON dd.dd_ville = v.v_id
                WHERE dd.dd_distribution = :distributionId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':distributionId' => $distributionId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les distributions par ville et par besoin
     */
    public function findByVilleEtBesoin(int $villeId, int $besoinId): array {
        $sql = "SELECT 
                    dd.dd_id,
                    dd.dd_quantite,
                    dd.dd_distribution,
                    d.d_date,
                    b.b_libelle AS besoin_libelle,
                    v.v_nom AS ville_nom
                FROM bngrc_distributionDetails dd
                JOIN bngrc_distribution d ON dd.dd_distribution = d.d_id
                JOIN bngrc_besoin b ON dd.dd_besoin = b.b_id
                JOIN bngrc_ville v ON dd.dd_ville = v.v_id
                WHERE dd.dd_ville = :ville_id AND dd.dd_besoin = :besoin_id
                ORDER BY d.d_date ASC, dd.dd_id ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ville_id' => $villeId, ':besoin_id' => $besoinId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer le total distribué par ville et par besoin
     */
    public function getTotalDistribueParVilleEtBesoin(int $villeId, int $besoinId): int {
        $sql = "SELECT COALESCE(SUM(dd.dd_quantite), 0) AS total
                FROM bngrc_distributionDetails dd
                WHERE dd.dd_ville = :ville_id AND dd.dd_besoin = :besoin_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ville_id' => $villeId, ':besoin_id' => $besoinId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)($result['total'] ?? 0);
    }

    /**
     * Récupérer le total distribué pour un besoin d'une ville
     */
    public function getTotalDistribueParBesoinVille(int $villeId, int $besoinId): int {
        $sql = "SELECT 
                    COALESCE(SUM(dd.dd_quantite), 0) AS total
                FROM bngrc_distributionDetails dd
                WHERE dd.dd_ville = ? AND dd.dd_besoin = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$villeId, $besoinId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)($result['total'] ?? 0);
    }

    /**
     * Insérer un détail de distribution (utilisé par la simulation)
     */
    public function insererDistributionDetail(
        int $distributionId,
        int $besoinId,
        int $quantite,
        int $villeId,
        int $collecteDetailId
    ): int {

        $sql = "INSERT INTO bngrc_distributionDetails 
                (dd_distribution, dd_besoin, dd_quantite, dd_ville, dd_collecteDetails)
                VALUES 
                (:distribution_id, :besoin_id, :quantite, :ville_id, :collecte_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':distribution_id' => $distributionId,
            ':besoin_id' => $besoinId,
            ':quantite' => $quantite,
            ':ville_id' => $villeId,
            ':collecte_id' => $collecteDetailId
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Créer une nouvelle distribution et retourner son ID
     */
    public function creerDistribution(string $date): int {
        $sql = "INSERT INTO bngrc_distribution (d_date) VALUES (:d_date)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':d_date' => $date]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Récupérer le résumé des distributions par ville
     */
    public function getResumeDistributionsParVille(): array {
        $sql = "SELECT 
                    v.v_id AS ville_id,
                    v.v_nom AS ville_nom,
                    b.b_id AS besoin_id,
                    b.b_libelle AS besoin_libelle,
                    ub.ub_libelle AS unite,
                    bv.bv_quantite AS quantite_demandee,
                    COALESCE(SUM(dd.dd_quantite), 0) AS quantite_distribuee,
                    (bv.bv_quantite - COALESCE(SUM(dd.dd_quantite), 0)) AS reste
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                GROUP BY v.v_id, v.v_nom, b.b_id, b.b_libelle, ub.ub_libelle, bv.bv_quantite
                ORDER BY v.v_nom, b.b_libelle";
        
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}