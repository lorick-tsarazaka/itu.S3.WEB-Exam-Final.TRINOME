<?php

namespace app\controllers;

use flight\Engine;
use Flight;
use app\repositories\BesoinRepository;

class BesoinController
{
    protected Engine $app;
    protected BesoinRepository $repo;

    public function __construct(Engine $app) {
         $this->app = $app;
         $pdo = Flight::db();
         $this->repo = new BesoinRepository($pdo);
    }

    // Affiche le formulaire de saisie des besoins
    public function index()
    {
        $db = $this->app->db();
        $besoins = $db->fetchAll("SELECT b.b_id, b.b_libelle, cb.cb_libelle AS categorie, ub.ub_libelle AS unite
            FROM bngrc_besoin b
            JOIN bngrc_categorieBesoin cb ON b.b_categorie = cb.cb_id
            JOIN bngrc_uniteBesoin ub ON b.b_unite = ub.ub_id
            ORDER BY cb.cb_libelle, b.b_libelle");
        $villes = $db->fetchAll('SELECT v_id, v_nom FROM bngrc_ville ORDER BY v_nom');

        Flight::render('SaisieBesoin', [
            'besoins' => $besoins,
            'villes' => $villes,
            'csp_nonce' => Flight::get('csp_nonce'),
            'page_title' => 'Saisie Besoins'
        ]);
    }

    // Traite la soumission du formulaire
    public function save()
    {
        $data = $_POST['besoins'] ?? [];
        // group by ville, include optional date_demande per row
        $byVille = [];
        foreach ($data as $row) {
            $ville = (int)($row['ville'] ?? 0);
            $besoin = (int)($row['besoin'] ?? 0);
            $quantite = (int)($row['quantite'] ?? 0);
            $date = isset($row['date']) && $row['date'] !== '' ? $row['date'] : null;
            if ($ville <= 0 || $besoin <= 0) continue;
            $item = ['besoin' => $besoin, 'quantite' => $quantite];
            if ($date !== null) $item['date'] = $date;
            $byVille[$ville][] = $item;
        }

        $results = [];
        $allOk = true;
        foreach ($byVille as $villeId => $besoins) {
            $res = $this->repo->insertMultipleForVille((int)$villeId, $besoins);
            $results[$villeId] = $res;
            if ($res === false) {
                $allOk = false;
            }
        }
        // Set flash message in session
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        if ($allOk) {
            $_SESSION['flash_success'] = 'Besoins enregistrés avec succès.';
        } else {
            $_SESSION['flash_error'] = 'Erreur lors de l\'enregistrement de certains besoins.';
        }

        // redirect back to the saisie page
        Flight::redirect('/besoins');
    }
}
