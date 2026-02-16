<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::set('flight.log_errors', true);

require_once __DIR__ . '/config.php';

Flight::register('db', 'PDO', [
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
    DB_USER,
    DB_PASS,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
]);

Flight::set('flight.views.path', __DIR__ . '/views');

require_once __DIR__ . '/routes.php';
