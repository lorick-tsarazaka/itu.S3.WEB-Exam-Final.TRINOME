<?php

namespace app\services;

use PDO;

class RecapitulationService {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    
    public function getRecapitulation(): array {
        $sql = "SELECT 
                    v.v_id AS ville_id,
                    v.v_nom AS ville,
                    b.b_id AS besoin_id,
                    b.b_libelle AS besoin,
                    ub.ub_libelle AS unite,
                    b.b_prixUnitraire AS prix_unitaire,
                    bv.bv_quantite AS total_demande,
                    SUM(dd.dd_quantite) AS total_distribue
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                GROUP BY v.v_id, v.v_nom, b.b_id, b.b_libelle, ub.ub_libelle, b.b_prixUnitraire, bv.bv_quantite
                ORDER BY v.v_nom, b.b_libelle";
        
        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $this->calculerMontants($rows);
    }

    /**
     * Récupérer la récapitulation pour une ville spécifique
     */
    public function getRecapitulationParVille(int $villeId): array {
        $sql = "SELECT 
                    v.v_id AS ville_id,
                    v.v_nom AS ville,
                    b.b_id AS besoin_id,
                    b.b_libelle AS besoin,
                    ub.ub_libelle AS unite,
                    b.b_prixUnitraire AS prix_unitaire,
                    bv.bv_quantite AS total_demande,
                    SUM(dd.dd_quantite) AS total_distribue
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                WHERE bv.bv_ville = ?
                GROUP BY v.v_id, v.v_nom, b.b_id, b.b_libelle, ub.ub_libelle, b.b_prixUnitraire, bv.bv_quantite
                ORDER BY b.b_libelle";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$villeId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->calculerMontants($rows);
    }

    
    private function calculerMontants(array $rows): array {
        $result = [];
        foreach ($rows as $row) {
            $totalDemande = (int)$row['total_demande'];
            $totalDistribue = (int)($row['total_distribue'] ?? 0);
            $pu = (float)$row['prix_unitaire'];
            $reste = $totalDemande - $totalDistribue;

            $row['total_distribue'] = $totalDistribue;
            $row['reste'] = $reste;
            $row['montant_demande'] = $totalDemande * $pu;
            $row['montant_distribue'] = $totalDistribue * $pu;
            $row['montant_reste'] = $reste * $pu;

            $result[] = $row;
        }
        return $result;
    }
}
