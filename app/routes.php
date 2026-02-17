<?php
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/services/Validator.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/VenteRepository.php';
require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/controllers/DemandeController.php';
require_once __DIR__ . '/controllers/DonController.php';
require_once __DIR__ . '/controllers/BesoinController.php';
require_once __DIR__ . '/controllers/StockController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/RecapController.php';

// Auth
Flight::route('GET /login', function() { (new AuthController())->showLogin(); });
Flight::route('POST /login', function() { (new AuthController())->postLogin(); });
Flight::route('GET /logout', function() { (new AuthController())->logout(); });

Flight::route('GET /register', function() { (new AuthController())->showRegister(); });
Flight::route('POST /register', function() { (new AuthController())->postRegister(); });
Flight::route('POST /api/validate/register', function() { (new AuthController())->validateRegisterAjax(); });

// Redirection accueil
Flight::route('GET /', function() { Flight::redirect('/accueil'); });

// Objet
Flight::route('GET /accueil', function() { (new ObjetController())->showListe(); });
Flight::route('GET /actualites', function() { (new ObjetController())->showActualites(); });
Flight::route('GET /contacts', function() { (new ObjetController())->showContacts(); });

// Admin
Flight::route('GET /admin/voir-tout', function() { (new AdminController())->voirTout(); });
Flight::route('GET /admin/dashboard', function() { (new AdminController())->dashboard(); });
Flight::route('GET /admin/achats', function() { (new AdminController())->achats(); });

// Demande
Flight::route('GET /demande/@id_demande', function($id_demande) {
    DemandeController::showDemandeDetail($id_demande);
});
Flight::route('POST /admin/donner', function() { DemandeController::postDistribuerDon(); });
Flight::route('GET /api/produits', function() { DemandeController::getProduitsParCategorieJson(); });
Flight::route('GET /api/categories', function() { DemandeController::getCategoriesJson(); });
Flight::route('GET /api/villes', function() { DemandeController::getVillesParRegionJson(); });
Flight::route('GET /api/stock/@id_produit', function($id_produit) {
    DemandeController::getStockProduitJson($id_produit);
});
Flight::route('POST /admin/acheter', function() { (new DemandeController())->postAcheterBesoin(); });

// Don
Flight::route('POST /admin/don-global', function() { DonController::postDonGlobal(); });
Flight::route('POST /admin/besoin', function() { BesoinController::postBesoin(); });
Flight::route('POST /admin/don-argent', function() { (new DonController())->postDonArgent(); });
Flight::route('GET /admin/don-argent', function() { Flight::render('admin/don-argent-saisie'); });
Flight::route('POST /admin/reset', function() { (new DonController())->resetDons(); });

// Recap
Flight::route('GET /admin/recapitulatif', function() { (new RecapController())->showRecap(); });
Flight::route('GET /api/recap', function() { (new RecapController())->getRecapJson(); });

// Stock
Flight::route('GET /admin/stock', function() {
    $repo = new VenteRepository(Flight::db());
    $controller = new StockController($repo);
    $controller->index();
});
Flight::route('POST /admin/vente', function() {
    $repo = new VenteRepository(Flight::db());
    $controller = new StockController($repo);
    $controller->vendre();
});
