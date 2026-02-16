<?php
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/services/Validator.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/controllers/ObjetController.php';

Flight::route('GET /register', ['AuthController', 'showRegister']);
Flight::route('POST /register', ['AuthController', 'postRegister']);
Flight::route('POST /api/validate/register', ['AuthController', 'validateRegisterAjax']);




Flight::route('GET /accueil', ['ObjetController', 'showListe']);


