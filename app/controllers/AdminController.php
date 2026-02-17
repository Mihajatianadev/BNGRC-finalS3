<?php
require_once __DIR__ . '/../repositories/DemandeRepository.php';

class AdminController {

    private static function exigerAdmin(PDO $pdo) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $id_user = $_SESSION['id_user'] ?? null;
        if (!$id_user) {
            http_response_code(403);
            echo 'Accès refusé.';
            exit;
        }

        $st = $pdo->prepare('SELECT id_role FROM users WHERE id_user = ? LIMIT 1');
        $st->execute([(int)$id_user]);
        $id_role = (int)($st->fetchColumn() ?? 0);

        if ($id_role !== 2) {
            http_response_code(403);
            echo 'Accès refusé.';
            exit;
        }
    }

    public static function voirTout() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);

        $req = Flight::request();
        $id_ville = isset($req->query['ville']) && $req->query['ville'] !== '' ? (int)$req->query['ville'] : null;
        $date_debut = isset($req->query['date_debut']) ? (string)$req->query['date_debut'] : '';
        $date_fin = isset($req->query['date_fin']) ? (string)$req->query['date_fin'] : '';

        $villes = $repo->listeVilles();
        $categories = $repo->listeCategories();
        $lignes = $repo->listeDemandesDetaillees($id_ville, $date_debut, $date_fin);

        Flight::render('admin/Voir_tout_admin', [
            'villes' => $villes,
            'categories' => $categories,
            'lignes' => $lignes,
            'id_ville' => $id_ville,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
        ]);
    }

    public static function dashboard() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);

        $req = Flight::request();
        $id_region = isset($req->query['region']) && $req->query['region'] !== '' ? (int)$req->query['region'] : null;
        $id_ville = isset($req->query['ville']) && $req->query['ville'] !== '' ? (int)$req->query['ville'] : null;

        $regions = $repo->listeRegions();
        $villes = $id_region ? $repo->listeVillesParRegion($id_region) : [];
        $demandes = $repo->listeDemandesPourDashboard($id_region, $id_ville);

        Flight::render('admin/dashboard', [
            'regions' => $regions,
            'villes' => $villes,
            'demandes' => $demandes,
            'id_region' => $id_region,
            'id_ville' => $id_ville,
        ]);
    }

    public static function stock() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);
        $stocks = $repo->listeStockDetaille();

        $erreur = (string)(Flight::request()->query['erreur'] ?? '');
        $success = (string)(Flight::request()->query['success'] ?? '');

        Flight::render('admin/stock', [
            'stocks' => $stocks,
            'erreur' => $erreur,
            'success' => $success,
        ]);
    }
}
