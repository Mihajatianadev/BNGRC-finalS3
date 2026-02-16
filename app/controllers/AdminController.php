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

        Flight::render('admin-dashboard', [
            'regions' => $regions,
            'villes' => $villes,
            'demandes' => $demandes,
            'id_region' => $id_region,
            'id_ville' => $id_ville,
        ]);
    }
}
