<?php

namespace app\repositories;

use PDO;

class DistributionRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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
            $sqlDetails = "INSERT INTO bngrc_distributionDetails (dd_don, dd_besoin, dd_quantite, dd_ville) 
                           VALUES (:dd_don, :dd_besoin, :dd_quantite, :dd_ville)";
            $stmtDetails = $this->pdo->prepare($sqlDetails);

            foreach ($details as $detail) {
                $stmtDetails->execute([
                    ':dd_don'      => $detail['dd_don'],
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

    //Récupère toutes les distributions
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
                WHERE dd.dd_don = :distributionId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':distributionId' => $distributionId]);
        return $stmt->fetchAll();
    }
}