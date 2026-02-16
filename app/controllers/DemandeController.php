<?php
require_once __DIR__ . '/../repositories/DemandeRepository.php';

class DemandeController {

    public static function showDemandeDetail($id_demande)
    {
        $pdo = Flight::db();
        $repo = new DemandeRepository($pdo);
        $demande = $repo->getInfoDemande($id_demande);
        Flight::render('don_demande', [
            'demande' => $demande
        ]);
    }

}
