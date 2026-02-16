-- Seed data for BNGRC (Madagascar) - test dataset
USE final_exam_s3;

-- Regions
INSERT INTO bngrc_region (r_id, r_nom) VALUES
(1, 'Analamanga'),
(2, 'Vakinankaratra'),
(3, 'Atsinanana'),
(4, 'Vatovavy-Fitovinany'),
(5, 'Haute Matsiatra'),
(6, 'Boeny'),
(7, 'Sofia'),
(8, 'Atsimo-Andrefana');

-- Cities (villes)
INSERT INTO bngrc_ville (v_id, v_nom) VALUES
(1, 'Antananarivo'),
(2, 'Antsirabe'),
(3, 'Fianarantsoa'),
(4, 'Toamasina'),
(5, 'Mahajanga'),
(6, 'Toliara'),
(7, 'Antsiranana'),
(8, 'Manakara'),
(9, 'Morondava'),
(10, 'Farafangana');

-- Map regions to cities
INSERT INTO bngrc_regionVille (rv_id, rv_region, rv_ville) VALUES
(1, 1, 1), -- Antananarivo - Analamanga
(2, 2, 2), -- Antsirabe - Vakinankaratra
(3, 5, 3), -- Fianarantsoa - Haute Matsiatra
(4, 3, 4), -- Toamasina - Atsinanana
(5, 6, 5), -- Mahajanga - Boeny
(6, 8, 6), -- Toliara - Atsimo-Andrefana
(7, 7, 7), -- Antsiranana - Sofia
(8, 4, 8), -- Manakara - Vatovavy-Fitovinany
(9, 7, 9), -- Morondava - Sofia
(10,4,10); -- Farafangana - Vatovavy-Fitovinany

-- Example sinistres
INSERT INTO bngrc_sinistre (s_id, s_nom, s_ville) VALUES
(1, 'Cyclone Basy (exemple)', 4),
(2, 'Inondation centre ville', 1),
(3, 'Tremblement localisé', 3);

-- Categories of needs
INSERT INTO bngrc_categorieBesoin (cb_id, cb_libelle) VALUES
(1, 'Argent'),
(2, 'Alimentaire'),
(3, 'Eau'),
(4, 'Abri'),
(5, 'Hygiène'),
(6, 'Médicaments'),
(7, 'Vêtements'),
(8, 'Divers');

-- Units
INSERT INTO bngrc_uniteBesoin (ub_id, ub_libelle) VALUES
(1, 'pièce'),
(2, 'litre'),
(3, 'kg'),
(4, 'pack'),
(5, 'colis'),
(6, 'MGA'),
(7, 'EUR'),
(8, 'USD');

-- Besoins (explicit ids to reference later)
INSERT INTO bngrc_besoin (b_id, b_libelle, b_prixUnitraire, b_categorie, b_unite) VALUES
(1 , 'Ariary Malgache', 1.00, 1, 6),
(2 , 'Euro', 1.00, 1, 7),
(3 , 'Dollar US', 1.00, 1, 8),
(4, 'Eau potable', 0.50, 3, 2),
(5, 'Riz', 0.90, 2, 3),
(6, 'Conserves alimentaires', 2.50, 2, 3),
(7, 'Bâches / Tarpulin', 12.00, 4, 1),
(8, 'Couvertures', 8.00, 3, 1),
(9, 'Kits hygiène', 5.00, 5, 4),
(10, 'Trousse médicale', 15.00, 6, 4),
(11, 'Lait infantile', 6.00, 2, 3),
(12,'Filets anti-moustiques', 7.50, 3, 1),
(13,'Vêtements', 4.00, 7, 1);

-- Status values for besoinVille
INSERT INTO bngrc_statusBesoinVille (sbv_id, sbv_libelle) VALUES
(1, 'attendu'),
(2, 'partiel'),
(3, 'couvert');

-- Initial stock needs per city (besoinVille)
INSERT INTO bngrc_besoinVille (bv_id, bv_besoin, bv_quantite, bv_ville, bv_status) VALUES
(1, 1, 5000, 1, 2), -- Antananarivo: partiel
(2, 2, 2000, 1, 2), -- partiel
(3, 3, 800, 1, 2),
(4, 4, 150, 1, 2),
(5, 5, 300, 1, 2),
(6, 6, 400, 1, 2),
(7, 7, 100, 1, 2),
(8, 8, 200, 1, 2),
(9, 9, 250, 1, 2),
(10,10, 500, 1, 2),
(11, 1, 2000, 4, 1), -- Toamasina (cyclone): attendu
(12, 2, 800, 4, 1),
(13, 3, 300, 4, 1),
(14, 4, 100, 4, 1),
(15, 5, 120, 4, 1),
(16, 6, 150, 4, 1),
(17, 1, 1200, 10, 2), -- Farafangana: partiel
(18, 2, 600, 10, 2),
(19, 5, 80, 3, 1), -- Fianarantsoa: attendu
(20, 1, 700, 3, 1);

-- Exemple de collectes
INSERT INTO bngrc_collecte (c_id, c_date) VALUES
(1, '2025-02-01'),
(2, '2025-02-10');

-- Détails des collectes
INSERT INTO bngrc_collecteDetails (cd_id, cd_collecte, cd_besoin, cd_quantite) VALUES
(1, 1, 1, 1000), -- collecte 1: 1000 L eau
(2, 1, 2, 500),
(3, 2, 4, 50),
(4, 2, 5, 100);

-- Exemple de distributions
INSERT INTO bngrc_distribution (d_id, d_date) VALUES
(1, '2025-02-15'),
(2, '2025-02-20');

-- Détails des distributions (dd_distri references distribution id)
INSERT INTO bngrc_distributionDetails (dd_id, dd_distribution, dd_besoin, dd_quantite, dd_ville) VALUES
(1, 1, 1, 800, 4), -- distrib 800 L eau to Toamasina from collecte 1
(2, 1, 2, 300, 1),
(3, 2, 4, 40, 4),
(4, 2, 5, 80, 10);

-- Small note: adjust quantities/prices as needed for testing scenarios.