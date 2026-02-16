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
}