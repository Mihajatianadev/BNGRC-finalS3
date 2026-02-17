<?php
require_once __DIR__ . '/../repositories/DonRepository.php';
require_once __DIR__ . '/../repositories/DonArgentRepository.php';

class DonController {
    public static function postDonGlobal() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);
        $repo = new DonRepository($pdo);

        $req = Flight::request();
        $id_produit = (int)($req->data->id_produit ?? 0);
        $quantite = (float)($req->data->quantite ?? 0);
        $id_categorie = (int)($req->data->id_categorie ?? 0);
        $nouvelle_categorie = trim((string)($req->data->nouvelle_categorie ?? ''));
        $nouveau_produit = trim((string)($req->data->nouveau_produit ?? ''));
        $unite_nouveau = trim((string)($req->data->unite_nouveau ?? ''));

        if ($quantite <= 0) {
            Flight::redirect('/admin/voir-tout?erreur=' . urlencode('Quantité invalide.'));
            return;
        }

        $pdo->beginTransaction();
        try {
            if ($nouvelle_categorie !== '') {
                $id_categorie = $repo->getOrCreateCategorie($nouvelle_categorie);
            }

            if ($nouveau_produit !== '') {
                if ($id_categorie <= 0) {
                    throw new Exception('Catégorie invalide.');
                }

                if ($unite_nouveau === '') {
                    throw new Exception('Unité invalide.');
                }

                $id_produit = $repo->getOrCreateProduit($id_categorie, $nouveau_produit, $unite_nouveau);
            }

            if ($id_produit <= 0) {
                throw new Exception('Produit invalide.');
            }

            $id_user = (int)($_SESSION['id_user'] ?? 0);
            if ($id_user <= 0) {
                throw new Exception('Utilisateur invalide.');
            }

            $repo->insertDonEtStock($id_user, $id_produit, $quantite);
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            Flight::redirect('/admin/voir-tout?erreur=' . urlencode($e->getMessage()));
            return;
        }

        Flight::redirect('/admin/stock?success=' . urlencode('Don ajouté au stock.'));
    }

    public static function postDonArgent() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);
        $repo = new DonArgentRepository($pdo);

        $req = Flight::request();
        $id_ville = (int)($req->data->id_ville ?? 0);
        $montant = (float)($req->data->montant ?? 0);

        if ($id_ville <= 0 || $montant <= 0) {
            Flight::redirect('/admin/voir-tout?erreur=' . urlencode('Données invalides.'));
            return;
        }

        try {
            $id_user = (int)($_SESSION['id_user'] ?? 0);
            $repo->ajouterDonArgent($id_ville, $montant, $id_user);
        } catch (Exception $e) {
            Flight::redirect('/admin/voir-tout?erreur=' . urlencode($e->getMessage()));
            return;
        }

        Flight::redirect('/admin/voir-tout?success=' . urlencode('Don en argent enregistré.'));
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


public static function resetDons() {
    $pdo = Flight::db();
    self::exigerAdmin($pdo); // vérifie que c'est un admin

    $pdo->beginTransaction();
    try {
        // Suppression des données non-par-défaut (reset complet)
        // Ordre important pour respecter les FK: achats -> distributions -> demandes -> dons
        $pdo->exec("DELETE FROM achats WHERE isDefault = 0");
        $pdo->exec("DELETE FROM distributions WHERE isDefault = 0");
        $pdo->exec("DELETE FROM demandes WHERE isDefault = 0");
        $pdo->exec("DELETE FROM dons WHERE isDefault = 0");

        // Recalcul stock depuis les données restantes (par défaut)
        $pdo->exec('UPDATE stock SET quantite_disponible = 0');

        // + Stock = + dons (par défaut)
        $stDons = $pdo->query('SELECT id_produit, SUM(quantite) as qte FROM dons WHERE isDefault = 1 GROUP BY id_produit');
        foreach ($stDons->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id_produit = (int)$row['id_produit'];
            $qte = (float)$row['qte'];
            $stUp = $pdo->prepare('UPDATE stock SET quantite_disponible = quantite_disponible + ? WHERE id_produit = ?');
            $stUp->execute([$qte, $id_produit]);
        }

        // + Stock = + achats (par défaut) sur produits achetés
        // - Stock = - achats (par défaut) sur Argent (prix * quantité)
        $id_argent = (int)$pdo->query("SELECT id_produit FROM produits WHERE nom = 'Argent' LIMIT 1")->fetchColumn();
        $stAch = $pdo->query('SELECT id_produit, SUM(quantite_achetee) as qte, SUM(montant_total) as montant FROM achats WHERE isDefault = 1 GROUP BY id_produit');
        foreach ($stAch->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id_produit = (int)$row['id_produit'];
            $qte = (float)$row['qte'];
            $stUp = $pdo->prepare('UPDATE stock SET quantite_disponible = quantite_disponible + ? WHERE id_produit = ?');
            $stUp->execute([$qte, $id_produit]);
        }
        if ($id_argent > 0) {
            $montant = (float)$pdo->query('SELECT COALESCE(SUM(montant_total),0) FROM achats WHERE isDefault = 1')->fetchColumn();
            $stUpArg = $pdo->prepare('UPDATE stock SET quantite_disponible = quantite_disponible - ? WHERE id_produit = ?');
            $stUpArg->execute([$montant, $id_argent]);
        }

        // - Stock = - distributions (par défaut)
        $stDist = $pdo->query('SELECT id_produit, SUM(quantite_envoyee) as qte FROM distributions WHERE isDefault = 1 GROUP BY id_produit');
        foreach ($stDist->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id_produit = (int)$row['id_produit'];
            $qte = (float)$row['qte'];
            $stUp = $pdo->prepare('UPDATE stock SET quantite_disponible = quantite_disponible - ? WHERE id_produit = ?');
            $stUp->execute([$qte, $id_produit]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        Flight::redirect('/admin/voir-tout?erreur=' . urlencode($e->getMessage()));
        return;
    }

    Flight::redirect('/admin/voir-tout?success=' . urlencode('Réinitialisation effectuée : dons/besoins/achats/distributions non par défaut supprimés et stock recalculé.'));
}


}
