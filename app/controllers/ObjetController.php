<?php
require_once __DIR__ . '/../repositories/ObjetRepository.php';

class ObjetController {

    public static function showListe()
{
        Flight::render('auth/accueil', []);
}

    public static function showActualites()
{
        Flight::render('auth/actualites', []);
}

    public static function showContacts()
{
        Flight::render('auth/contacts', []);
}

}