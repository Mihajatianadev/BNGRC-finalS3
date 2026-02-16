<?php
require_once __DIR__ . '/../repositories/ObjetRepository.php';

class ObjetController {

    public static function showListe()
{
    $objets = ObjetRepository::getAllList();

        Flight::render('auth/accueil', [
            'objets' => $objets
        ]);
}

}