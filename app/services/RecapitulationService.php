<?php

namespace app\services;

use PDO;

class RecapitulationService {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer la récapitulation avec : ville, besoin, total_demande, total_distribue, reste, 
     * montant_demande, montant_distribue, montant_reste
     * 
     * Calculs:
     * - reste = total_demande - total_distribue
     * - montant_demande = total_demande * pu
     * - montant_distribue = total_distribue * pu
     * - montant_reste = reste * pu
     */
    public function getRecapitulation(): array {
        $sql = "SELECT 
                    v.v_id AS ville_id,
                    v.v_nom AS ville,
                    b.b_id AS besoin_id,
                    b.b_libelle AS besoin,
                    ub.ub_libelle AS unite,
                    b.b_prixUnitraire AS prix_unitaire,
                    bv.bv_quantite AS total_demande,
                    SUM(dd.dd_quantite) AS total_distribue,
                    (bv.bv_quantite - SUM(dd.dd_quantite)) AS reste,
                    (bv.bv_quantite * b.b_prixUnitraire) AS montant_demande,
                    (SUM(dd.dd_quantite) * b.b_prixUnitraire) AS montant_distribue,
                    ((bv.bv_quantite - SUM(dd.dd_quantite)) * b.b_prixUnitraire) AS montant_reste
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                GROUP BY v.v_id, v.v_nom, b.b_id, b.b_libelle, ub.ub_libelle, b.b_prixUnitraire, bv.bv_quantite
                ORDER BY v.v_nom, b.b_libelle";
        
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
                    SUM(dd.dd_quantite) AS total_distribue,
                    (bv.bv_quantite - SUM(dd.dd_quantite)) AS reste,
                    (bv.bv_quantite * b.b_prixUnitraire) AS montant_demande,
                    (SUM(dd.dd_quantite) * b.b_prixUnitraire) AS montant_distribue,
                    ((bv.bv_quantite - SUM(dd.dd_quantite)) * b.b_prixUnitraire) AS montant_reste
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
