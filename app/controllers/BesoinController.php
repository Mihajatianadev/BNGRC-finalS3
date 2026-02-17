<?php
require_once __DIR__ . '/../repositories/BesoinRepository.php';
require_once __DIR__ . '/../repositories/DonRepository.php';
require_once __DIR__ . '/../repositories/DemandeRepository.php';

class BesoinController {
    public static function postBesoin() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new BesoinRepository($pdo);
        $donRepo = new DonRepository($pdo);
        $demandeRepo = new DemandeRepository($pdo);
        $req = Flight::request();

        $id_ville = (int)($req->data->id_ville ?? 0);
        $nouvelle_ville = trim((string)($req->data->nouvelle_ville ?? ''));
        $id_region = (int)($req->data->id_region ?? 0);
        $nouvelle_region = trim((string)($req->data->nouvelle_region ?? ''));

        $nomProduit = trim((string)($req->data->nom_produit ?? ''));
        $unite = trim((string)($req->data->unite ?? ''));
        $id_categorie = (int)($req->data->id_categorie ?? 0);
        $nouvelle_categorie = trim((string)($req->data->nouvelle_categorie ?? ''));
        $quantite = (float)($req->data->quantite ?? 0);

        if (($id_ville <= 0 && $nouvelle_ville === '') || $nomProduit === '' || $quantite <= 0) {
            Flight::redirect('/admin/voir-tout?erreur=' . urlencode('Champs invalides.'));
            return;
        }

        if ($unite === '') {
            Flight::redirect('/admin/voir-tout?erreur=' . urlencode('Unité invalide.'));
            return;
        }

        $pdo->beginTransaction();
        try {
            // Gestion Région / Ville dynamique
            if ($nouvelle_region !== '') {
                $id_region = $demandeRepo->getOrCreateRegion($nouvelle_region);
            }
            if ($id_ville <= 0 && $nouvelle_ville !== '') {
                if ($id_region <= 0) throw new Exception('Une région est requise pour créer une nouvelle ville.');
                $id_ville = $demandeRepo->getOrCreateVille($id_region, $nouvelle_ville);
            }

            if ($id_ville <= 0) throw new Exception('Ville invalide.');

            // Gestion Catégorie dynamique
            if ($nouvelle_categorie !== '') {
                $id_categorie = $donRepo->getOrCreateCategorie($nouvelle_categorie);
            }
            if ($id_categorie <= 0) {
                throw new Exception('Catégorie invalide.');
            }

            $repo->insertBesoin($id_ville, $nomProduit, $unite, $id_categorie, $quantite);
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            Flight::redirect('/admin/voir-tout?erreur=' . urlencode($e->getMessage()));
            return;
        }

        Flight::redirect('/admin/voir-tout?success=' . urlencode('Besoin ajouté.'));
    }

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
}
