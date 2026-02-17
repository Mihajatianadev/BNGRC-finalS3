<?php
require_once __DIR__ . '/../repositories/RecapRepository.php';
require_once __DIR__ . '/../repositories/DemandeRepository.php';

class RecapController {

    public static function showRecap() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repoDemande = new DemandeRepository($pdo);
        $villes = $repoDemande->listeVilles();

        Flight::render('admin/recapitulatif', [
            'villes' => $villes,
        ]);
    }

    public static function getRecapJson() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new RecapRepository($pdo);

        $req = Flight::request();
        $id_ville = isset($req->query['ville']) && $req->query['ville'] !== '' ? (int)$req->query['ville'] : null;
        $date_debut = isset($req->query['date_debut']) ? (string)$req->query['date_debut'] : '';
        $date_fin = isset($req->query['date_fin']) ? (string)$req->query['date_fin'] : '';

        $villes = $repo->getRecapParVille($id_ville, $date_debut, $date_fin);
        $regions = $repo->getRecapParRegion($id_ville, $date_debut, $date_fin);
        $total = $repo->getRecapTotal($id_ville, $date_debut, $date_fin);

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
