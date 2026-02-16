-- query to get information of besoin by ville
SELECT 
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
WHERE v.v_id = 1;

-- query to get information of distribution by ville
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