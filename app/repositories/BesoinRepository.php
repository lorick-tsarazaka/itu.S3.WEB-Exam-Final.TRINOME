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
                    bv.bv_date_demande AS date_demande,
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
     * Récupérer les besoins pas encore satisfaits dans une ville
     * Un besoin est non satisfait si la quantité distribuée < quantité demandée
     */
    public function findBesoinsNonSatisfaitsParVille(int $villeId): array {
        $sql = "SELECT 
                    bv.bv_id,
                    bv.bv_besoin AS besoin_id,
                    bv.bv_date_demande AS date_demande,
                    bv.bv_quantite AS quantite_demandee,
                    bv.bv_ville AS ville_id,
                    v.v_nom AS ville_nom,
                    b.b_libelle AS besoin_libelle,
                    b.b_prixUnitraire AS prix_unitaire,
                    ub.ub_libelle AS unite,
                    COALESCE(SUM(dd.dd_quantite), 0) AS quantite_distribuee
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                WHERE bv.bv_ville = :ville_id
                GROUP BY bv.bv_id, bv.bv_besoin, bv.bv_quantite, bv.bv_ville, v.v_nom, b.b_libelle, b.b_prixUnitraire, ub.ub_libelle
                HAVING quantite_distribuee < bv.bv_quantite
                ORDER BY b.b_libelle";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ville_id' => $villeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les besoins pas encore satisfaits dans une ville
     * Ordonnés par date de demande (anciennes demandes d'abord) puis par id
     */
    public function findBesoinsNonSatisfaitsParVilleOrdreDate(int $villeId): array {
        $sql = "SELECT 
                    bv.bv_id,
                    bv.bv_besoin AS besoin_id,
                    bv.bv_date_demande AS date_demande,
                    bv.bv_quantite AS quantite_demandee,
                    bv.bv_ville AS ville_id,
                    v.v_nom AS ville_nom,
                    b.b_libelle AS besoin_libelle,
                    b.b_prixUnitraire AS prix_unitaire,
                    ub.ub_libelle AS unite,
                    COALESCE(SUM(dd.dd_quantite), 0) AS quantite_distribuee
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                WHERE bv.bv_ville = :ville_id
                GROUP BY bv.bv_id, bv.bv_besoin, bv.bv_quantite, bv.bv_ville, v.v_nom, b.b_libelle, b.b_prixUnitraire, ub.ub_libelle, bv.bv_date_demande
                HAVING quantite_distribuee < bv.bv_quantite
                ORDER BY bv.bv_date_demande ASC, bv.bv_id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ville_id' => $villeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calculer le reste (quantité) de besoin à satisfaire
     * reste = besoin_total - déjà_distribué
     */
    public function calculerResteASatisfaire(int $villeId, int $besoinId): int {
        $sql = "SELECT 
                    bv.bv_quantite AS quantite_demandee,
                    COALESCE(SUM(dd.dd_quantite), 0) AS quantite_distribuee
                FROM bngrc_besoinVille bv
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                WHERE bv.bv_ville = :ville_id AND bv.bv_besoin = :besoin_id
                GROUP BY bv.bv_quantite";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ville_id' => $villeId, ':besoin_id' => $besoinId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return 0;
        }
        
        $reste = (int)$result['quantite_demandee'] - (int)$result['quantite_distribuee'];
        return max(0, $reste);
    }

    /**
     * Récupérer tous les besoins de toutes les villes avec leur état de satisfaction
     */
    public function findAllBesoinsAvecEtat(): array {
        $sql = "SELECT 
                    bv.bv_id,
                    bv.bv_besoin AS besoin_id,
                    bv.bv_date_demande AS date_demande,
                    bv.bv_quantite AS quantite_demandee,
                    bv.bv_ville AS ville_id,
                    v.v_nom AS ville_nom,
                    b.b_libelle AS besoin_libelle,
                    b.b_prixUnitraire AS prix_unitaire,
                    ub.ub_libelle AS unite,
                    COALESCE(SUM(dd.dd_quantite), 0) AS quantite_distribuee,
                    (bv.bv_quantite - COALESCE(SUM(dd.dd_quantite), 0)) AS reste
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                GROUP BY bv.bv_id, bv.bv_besoin, bv.bv_quantite, bv.bv_ville, v.v_nom, b.b_libelle, b.b_prixUnitraire, ub.ub_libelle
                ORDER BY v.v_nom, b.b_libelle";
        
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer la quantité totale demandée d'un besoin dans une ville
     */
    public function getQuantiteDemandeeParBesoinVille(int $villeId, int $besoinId): int {
        $sql = "SELECT 
                    bv.bv_quantite AS quantite_demandee
                FROM bngrc_besoinVille bv
                WHERE bv.bv_ville = ? AND bv.bv_besoin = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$villeId, $besoinId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)$result['quantite_demandee'] : 0;
    }

    /**
     * Récupérer la somme des collectes (converties en MGA via b_prixUnitraire)
     * pour une catégorie donnée (ex: catégorie "Argent" = 1).
     * Prend en compte les quantités négatives (retraits).
     */
    public function getTotalCollecteParCategorie(int $categorieId): float {
        $sql = "SELECT COALESCE(SUM(cd.cd_quantite * b.b_prixUnitraire), 0) AS total_mga
                FROM bngrc_collecteDetails cd
                JOIN bngrc_besoin b ON cd.cd_besoin = b.b_id
                WHERE b.b_categorie = :categorie_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':categorie_id' => $categorieId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? floatval($row['total_mga']) : 0.0;
    }

    /**
     * Récupérer tous les besoins (hors catégorie argent) avec leur état de satisfaction
     */
    public function findAllBesoinsNonArgentAvecEtat(): array {
        $sql = "SELECT 
                    bv.bv_id,
                    bv.bv_besoin AS besoin_id,
                    bv.bv_date_demande AS date_demande,
                    bv.bv_quantite AS quantite_demandee,
                    bv.bv_ville AS ville_id,
                    v.v_nom AS ville_nom,
                    b.b_libelle AS besoin_libelle,
                    b.b_prixUnitraire AS prix_unitaire,
                    ub.ub_libelle AS unite,
                    COALESCE(SUM(dd.dd_quantite), 0) AS quantite_distribuee,
                    (bv.bv_quantite - COALESCE(SUM(dd.dd_quantite), 0)) AS reste,
                    b.b_categorie AS categorie_id
                FROM bngrc_besoinVille bv
                JOIN bngrc_ville v ON bv.bv_ville = v.v_id
                JOIN bngrc_besoin b ON bv.bv_besoin = b.b_id
                JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
                LEFT JOIN bngrc_distributionDetails dd ON dd.dd_besoin = bv.bv_besoin AND dd.dd_ville = bv.bv_ville
                WHERE b.b_categorie != :argent_cat
                GROUP BY bv.bv_id, bv.bv_besoin, bv.bv_quantite, bv.bv_ville, v.v_nom, b.b_libelle, b.b_prixUnitraire, ub.ub_libelle, b.b_categorie
                ORDER BY v.v_nom, b.b_libelle";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':argent_cat' => 1]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a single besoin
     */
    public function insertBesoinVille(int $besoinId, int $villeId, int $quantite, ?string $date = null)
    {
        $db = $this->pdo;
        $dateToInsert = $date ?? date('Y-m-d');
        try {
            $stmt = $db->runQuery(
                'INSERT INTO bngrc_besoinVille (bv_besoin, bv_quantite, bv_date_demande, bv_ville, bv_status) VALUES (?, ?, ?, ?, ?)',
                [ $besoinId, $quantite, $dateToInsert, $villeId, 1 ]
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
                $date = $b['date'] ?? null;
                $dateToInsert = $date ?? date('Y-m-d');
                if ($besoinId <= 0) {
                    continue;
                }
                $db->runQuery(
                    'INSERT INTO bngrc_besoinVille (bv_besoin, bv_quantite, bv_date_demande, bv_ville, bv_status) VALUES (?, ?, ?, ?, ?)',
                    [ $besoinId, $quantite, $dateToInsert, $villeId, 1 ]
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