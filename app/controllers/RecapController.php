<?php
require_once __DIR__ . '/../repositories/RecapRepository.php';

class RecapController {

    public static function showRecap() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        Flight::render('admin/recapitulatif', []);
    }

    public static function getRecapJson() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new RecapRepository($pdo);
        $villes = $repo->getRecapParVille();
        $regions = $repo->getRecapParRegion();
        $total = $repo->getRecapTotal();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'villes' => $villes,
            'regions' => $regions,
            'total' => $total
        ]);
    }

    private static function exigerAdmin(PDO $pdo) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $id_user = $_SESSION['id_user'] ?? null;
        if (!$id_user) {
            http_response_code(403);
            exit('Accès refusé.');
        }

        $st = $pdo->prepare('SELECT id_role FROM users WHERE id_user = ? LIMIT 1');
        $st->execute([(int)$id_user]);
        $id_role = (int)($st->fetchColumn() ?? 0);
        if ($id_role !== 2) {
            http_response_code(403);
            exit('Accès refusé.');
        }
    }
}
