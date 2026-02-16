<?php

namespace app\controllers;

use flight\Engine;
use Flight;
use app\repositories\BesoinRepository;

class BesoinController
{
    protected Engine $app;
    protected BesoinRepository $repo;

    public function __construct($app)
    {
        $this->app = $app;
        $this->repo = new BesoinRepository($app);
    }

    // Affiche le formulaire de saisie des besoins
    public function index()
    {
        $db = $this->app->db();
        $besoins = $db->fetchAll('SELECT b_id, b_libelle FROM bngrc_besoin ORDER BY b_libelle');
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
        // group by ville
        $byVille = [];
        foreach ($data as $row) {
            $ville = (int)($row['ville'] ?? 0);
            $besoin = (int)($row['besoin'] ?? 0);
            $quantite = (int)($row['quantite'] ?? 0);
            if ($ville <= 0 || $besoin <= 0) continue;
            $byVille[$ville][] = ['besoin' => $besoin, 'quantite' => $quantite];
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
