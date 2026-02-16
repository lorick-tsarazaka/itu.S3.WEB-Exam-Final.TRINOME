<?php

namespace app\models;

class TrajetModel {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getVehiculeByDay() {
        $sql = "SELECT * FROM v_vehicules_byday";
        return $this->db->query($sql)->fetchAll();
    }
public function getBeneficeByVehicule() {
    $sql = "SELECT 
                v.v_id,
                v.v_marque,
                SUM(t.t_montantRecette - t.t_montantCarburant) AS benefice
            FROM coop_vehicules v
            JOIN coop_trajets t ON t.v_id = v.v_id
            GROUP BY v.v_id";
    return $this->db->query($sql)->fetchAll();
}

public function getBeneficeByDay() {
    $sql = "SELECT 
                jour,
                SUM(total_recette - total_carburant) AS benefice 
            FROM v_vehicules_byday
            GROUP BY jour";
    return $this->db->query($sql)->fetchAll();
}

}