-- ============================================================
-- donnee.sql — Données issues de jeu_donnees_cyclone_S3.xlsx
-- Adaptées aux tables de db.sql (aucune modification de schéma)
-- Clé primaire bv_id = colonne "Ordre" du fichier Excel
-- ============================================================
USE final_exam_s3;

-- ============================================================
-- 1. bngrc_region (r_id, r_nom)
-- ============================================================
INSERT INTO bngrc_region (r_id, r_nom) VALUES
(1, 'Atsinanana'),
(2, 'Vatovavy-Fitovinany'),
(3, 'Atsimo-Atsinanana'),
(4, 'Diana'),
(5, 'Menabe');

-- ============================================================
-- 2. bngrc_ville (v_id, v_nom)
-- ============================================================
INSERT INTO bngrc_ville (v_id, v_nom) VALUES
(1, 'Toamasina'),
(2, 'Mananjary'),
(3, 'Farafangana'),
(4, 'Nosy Be'),
(5, 'Morondava');

-- ============================================================
-- 3. bngrc_regionVille (rv_id, rv_region, rv_ville)
-- ============================================================
INSERT INTO bngrc_regionVille (rv_id, rv_region, rv_ville) VALUES
(1, 1, 1),  -- Toamasina     → Atsinanana
(2, 2, 2),  -- Mananjary     → Vatovavy-Fitovinany
(3, 3, 3),  -- Farafangana   → Atsimo-Atsinanana
(4, 4, 4),  -- Nosy Be       → Diana
(5, 5, 5);  -- Morondava     → Menabe

-- ============================================================
-- 4. bngrc_sinistre (s_id, s_nom, s_ville)
-- ============================================================
INSERT INTO bngrc_sinistre (s_id, s_nom, s_ville) VALUES
(1, 'Cyclone S3', 1),  -- Toamasina
(2, 'Cyclone S3', 2),  -- Mananjary
(3, 'Cyclone S3', 3),  -- Farafangana
(4, 'Cyclone S3', 4),  -- Nosy Be
(5, 'Cyclone S3', 5);  -- Morondava

-- ============================================================
-- 5. bngrc_categorieBesoin (cb_id, cb_libelle)
--    Catégories du fichier Excel : argent, nature, materiel
-- ============================================================
INSERT INTO bngrc_categorieBesoin (cb_id, cb_libelle) VALUES
(1, 'argent'),
(2, 'nature'),
(3, 'materiel');

-- ============================================================
-- 6. bngrc_uniteBesoin (ub_id, ub_libelle)
-- ============================================================
INSERT INTO bngrc_uniteBesoin (ub_id, ub_libelle) VALUES
(1, 'kg'),
(2, 'L'),
(3, 'pièce'),
(4, 'MGA');

-- ============================================================
-- 7. bngrc_besoin (b_id, b_libelle, b_prixUnitraire, b_categorie, b_unite)
--    10 produits uniques extraits du fichier Excel
-- ============================================================
INSERT INTO bngrc_besoin (b_id, b_libelle, b_prixUnitraire, b_categorie, b_unite) VALUES
(1,  'Riz (kg)',             3000.00,  2, 1),  -- nature,   kg
(2,  'Eau (L)',              1000.00,  2, 2),  -- nature,   L
(3,  'Tôle',               25000.00,  3, 3),  -- materiel, pièce
(4,  'Bâche',              15000.00,  3, 3),  -- materiel, pièce
(5,  'Argent',                 1.00,  1, 4),  -- argent,   MGA
(6,  'Huile (L)',           6000.00,  2, 2),  -- nature,   L
(7,  'Clous (kg)',          8000.00,  3, 1),  -- materiel, kg
(8,  'Haricots',            4000.00,  2, 1),  -- nature,   kg
(9,  'Bois',               10000.00,  3, 3),  -- materiel, pièce
(10, 'Groupe électrogène', 6750000.00, 3, 3); -- materiel, pièce

-- ============================================================
-- 8. bngrc_statusBesoinVille (sbv_id, sbv_libelle)
-- ============================================================
INSERT INTO bngrc_statusBesoinVille (sbv_id, sbv_libelle) VALUES
(1, 'attendu'),
(2, 'partiel'),
(3, 'couvert');

-- ============================================================
-- 9. bngrc_besoinVille (bv_id, bv_besoin, bv_quantite, bv_date_demande, bv_ville, bv_status)
--    bv_id = colonne "Ordre" du fichier Excel (clé primaire)
--    Trié par Ordre croissant
-- ============================================================
-- Villes : 1=Toamasina, 2=Mananjary, 3=Farafangana, 4=Nosy Be, 5=Morondava
-- Besoins: 1=Riz, 2=Eau, 3=Tôle, 4=Bâche, 5=Argent, 6=Huile, 7=Clous, 8=Haricots, 9=Bois, 10=Groupe

INSERT INTO bngrc_besoinVille (bv_id, bv_besoin, bv_quantite, bv_date_demande, bv_ville, bv_status) VALUES
( 1,  4,      200, '2026-02-15', 1, 1),  -- Ordre 1  : Bâche,    Toamasina,   15/02
( 2,  3,       40, '2026-02-15', 4, 1),  -- Ordre 2  : Tôle,     Nosy Be,     15/02
( 3,  5,  6000000, '2026-02-15', 2, 1),  -- Ordre 3  : Argent,   Mananjary,   15/02
( 4,  2,     1500, '2026-02-15', 1, 1),  -- Ordre 4  : Eau (L),  Toamasina,   15/02
( 5,  1,      300, '2026-02-15', 4, 1),  -- Ordre 5  : Riz (kg), Nosy Be,     15/02
( 6,  3,       80, '2026-02-15', 2, 1),  -- Ordre 6  : Tôle,     Mananjary,   15/02
( 7,  5,  4000000, '2026-02-15', 4, 1),  -- Ordre 7  : Argent,   Nosy Be,     15/02
( 8,  4,      150, '2026-02-16', 3, 1),  -- Ordre 8  : Bâche,    Farafangana, 16/02
( 9,  1,      500, '2026-02-15', 2, 1),  -- Ordre 9  : Riz (kg), Mananjary,   15/02
(10,  5,  8000000, '2026-02-16', 3, 1),  -- Ordre 10 : Argent,   Farafangana, 16/02
(11,  1,      700, '2026-02-16', 5, 1),  -- Ordre 11 : Riz (kg), Morondava,   16/02
(12,  5, 12000000, '2026-02-16', 1, 1),  -- Ordre 12 : Argent,   Toamasina,   16/02
(13,  5, 10000000, '2026-02-16', 5, 1),  -- Ordre 13 : Argent,   Morondava,   16/02
(14,  2,     1000, '2026-02-15', 3, 1),  -- Ordre 14 : Eau (L),  Farafangana, 15/02
(15,  4,      180, '2026-02-16', 5, 1),  -- Ordre 15 : Bâche,    Morondava,   16/02
(16, 10,        3, '2026-02-15', 1, 1),  -- Ordre 16 : Groupe,   Toamasina,   15/02
(17,  1,      800, '2026-02-16', 1, 1),  -- Ordre 17 : Riz (kg), Toamasina,   16/02
(18,  8,      200, '2026-02-16', 4, 1),  -- Ordre 18 : Haricots, Nosy Be,     16/02
(19,  7,       60, '2026-02-16', 2, 1),  -- Ordre 19 : Clous,    Mananjary,   16/02
(20,  2,     1200, '2026-02-15', 5, 1),  -- Ordre 20 : Eau (L),  Morondava,   15/02
(21,  1,      600, '2026-02-16', 3, 1),  -- Ordre 21 : Riz (kg), Farafangana, 16/02
(22,  9,      150, '2026-02-15', 5, 1),  -- Ordre 22 : Bois,     Morondava,   15/02
(23,  3,      120, '2026-02-16', 1, 1),  -- Ordre 23 : Tôle,     Toamasina,   16/02
(24,  7,       30, '2026-02-16', 4, 1),  -- Ordre 24 : Clous,    Nosy Be,     16/02
(25,  6,      120, '2026-02-16', 2, 1),  -- Ordre 25 : Huile,    Mananjary,   16/02
(26,  9,      100, '2026-02-15', 3, 1);  -- Ordre 26 : Bois,     Farafangana, 15/02

-- ============================================================
-- 10. bngrc_collecte (c_id, c_date)
--     Dons regroupés par date (feuille "dons" du fichier Excel)
-- ============================================================
INSERT INTO bngrc_collecte (c_id, c_date) VALUES
(1, '2026-02-16'),
(2, '2026-02-17'),
(3, '2026-02-18'),
(4, '2026-02-19');

-- ============================================================
-- 11. bngrc_collecteDetails (cd_id, cd_collecte, cd_besoin, cd_quantite)
--     Chaque ligne de la feuille "dons" = 1 détail de collecte
-- ============================================================
INSERT INTO bngrc_collecteDetails (cd_id, cd_collecte, cd_besoin, cd_quantite) VALUES
-- Collecte 1 : 2026-02-16
( 1, 1, 5,  5000000),   -- Argent    5 000 000 MGA
( 2, 1, 5,  3000000),   -- Argent    3 000 000 MGA
( 3, 1, 1,      400),   -- Riz (kg)  400
( 4, 1, 2,      600),   -- Eau (L)   600

-- Collecte 2 : 2026-02-17
( 5, 2, 5,  4000000),   -- Argent    4 000 000 MGA
( 6, 2, 5,  1500000),   -- Argent    1 500 000 MGA
( 7, 2, 5,  6000000),   -- Argent    6 000 000 MGA
( 8, 2, 3,       50),   -- Tôle      50
( 9, 2, 4,       70),   -- Bâche     70
(10, 2, 8,      100),   -- Haricots  100
(11, 2, 8,       88),   -- Haricots  88

-- Collecte 3 : 2026-02-18
(12, 3, 1,     2000),   -- Riz (kg)  2 000
(13, 3, 3,      300),   -- Tôle      300
(14, 3, 2,     5000),   -- Eau (L)   5 000

-- Collecte 4 : 2026-02-19
(15, 4, 5, 20000000),   -- Argent   20 000 000 MGA
(16, 4, 4,      500);   -- Bâche    500

-- ============================================================
-- 12. bngrc_distribution (d_id, d_date)
--     Pas de distribution initiale (vide)
-- ============================================================

-- ============================================================
-- 13. bngrc_distributionDetails (dd_id, dd_distribution, dd_besoin, dd_quantite, dd_ville, dd_collecteDetails)
--     Pas de distribution initiale (vide)
-- ============================================================

-- ============================================================
-- Fin du fichier donnee.sql
-- ============================================================
