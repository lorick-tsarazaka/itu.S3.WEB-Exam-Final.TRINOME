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
