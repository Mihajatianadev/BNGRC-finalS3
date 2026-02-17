<?php
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/services/Validator.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/controllers/DemandeController.php';
require_once __DIR__ . '/controllers/DonController.php';
require_once __DIR__ . '/controllers/BesoinController.php';

require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/RecapController.php';

Flight::route('GET /login', ['AuthController', 'showLogin']);
Flight::route('POST /login', ['AuthController', 'postLogin']);
Flight::route('GET /logout', ['AuthController', 'logout']);

Flight::route('GET /register', ['AuthController', 'showRegister']);
Flight::route('POST /register', ['AuthController', 'postRegister']);
Flight::route('POST /api/validate/register', ['AuthController', 'validateRegisterAjax']);




Flight::route('GET /', function() {
    Flight::redirect('/accueil');
});

Flight::route('GET /accueil', ['ObjetController', 'showListe']);
Flight::route('GET /actualites', ['ObjetController', 'showActualites']);
Flight::route('GET /contacts', ['ObjetController', 'showContacts']);

Flight::route('GET /admin/voir-tout', ['AdminController', 'voirTout']);
Flight::route('GET /admin/dashboard', ['AdminController', 'dashboard']);

Flight::route('GET /demande/@id_demande', function($id_demande) {
    DemandeController::showDemandeDetail($id_demande);
});

Flight::route('POST /admin/donner', function() {
    DemandeController::postDistribuerDon();
});

Flight::route('GET /api/produits', function() {
    DemandeController::getProduitsParCategorieJson();
});

Flight::route('GET /api/categories', function() {
    DemandeController::getCategoriesJson();
});

Flight::route('GET /api/villes', function() {
    DemandeController::getVillesParRegionJson();
});

Flight::route('GET /api/stock/@id_produit', function($id_produit) {
    DemandeController::getStockProduitJson($id_produit);
});

Flight::route('POST /admin/don-global', function() {
    DonController::postDonGlobal();
});

Flight::route('POST /admin/besoin', function() {
    BesoinController::postBesoin();
});

Flight::route('POST /admin/don-argent', ['DonController', 'postDonArgent']);
Flight::route('GET /admin/don-argent', function() {
    Flight::render('admin/don-argent-saisie');
});

Flight::route('GET /admin/recapitulatif', ['RecapController', 'showRecap']);
Flight::route('GET /api/recap', ['RecapController', 'getRecapJson']);

Flight::route('POST /admin/acheter', ['DemandeController', 'postAcheterBesoin']);

Flight::route('GET /admin/stock', ['AdminController', 'stock']);

Flight::route('GET /admin/reset-dons', ['DonController', 'resetDons']);
