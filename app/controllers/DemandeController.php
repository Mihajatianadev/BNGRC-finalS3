<?php
require_once __DIR__ . '/../repositories/DemandeRepository.php';
require_once __DIR__ . '/../repositories/DonArgentRepository.php';
require_once __DIR__ . '/../repositories/AchatRepository.php';

class DemandeController {

    public static function showDemandeDetail($id_demande)
    {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);
        $repo = new DemandeRepository($pdo);
        $repoDonArgent = new DonArgentRepository($pdo);

        $demande = $repo->getInfoDemande($id_demande);
        $categories = $repo->listeCategories();

        $id_categorie = null;
        if (!empty($demande['id_categorie'])) {
            $id_categorie = (int)$demande['id_categorie'];
        }

        $produits = $id_categorie ? $repo->listeProduitsParCategorie($id_categorie) : [];
        $distributions = $demande ? $repo->listeDistributionsParDemande((int)$demande['id_demande']) : [];
        
        $solde_ville = 0;
        if ($demande) {
            $id_produit_argent = $repoDonArgent->getIdProduitArgentPublic();
            $solde_ville = (float)($repo->getQuantiteStock($id_produit_argent) ?? 0);
        }

        $erreur = (string)(Flight::request()->query['erreur'] ?? '');

        Flight::render('admin/don_demande', [
            'demande' => $demande,
            'categories' => $categories,
            'produits' => $produits,
            'distributions' => $distributions,
            'erreur' => $erreur,
            'solde_ville' => $solde_ville,
        ]);
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

    public static function postDistribuerDon() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);
        $req = Flight::request();

        $id_demande = (int)($req->data->id_demande ?? 0);
        $id_produit = (int)($req->data->id_produit ?? 0);
        $quantite = (float)($req->data->quantite ?? 0);

        if ($id_demande <= 0 || $id_produit <= 0 || $quantite <= 0) {
            Flight::redirect('/demande/' . urlencode((string)$id_demande) . '?erreur=' . urlencode('Champs invalides.'));
            return;
        }

        $demande = $repo->getInfoDemande($id_demande);
        if (!$demande) {
            Flight::redirect('/admin/dashboard');
            return;
        }

        $disponible = $repo->getQuantiteStock($id_produit);
        if ($disponible === null) {
            $repo->creerStockSiAbsent($id_produit, 0);
            $disponible = 0.0;
        }

        if ($quantite > $disponible) {
            Flight::redirect('/demande/' . urlencode((string)$id_demande) . '?erreur=' . urlencode('Stock insuffisant.'));
            return;
        }

        $pdo->beginTransaction();
        try {
            $repo->insererDistributionLibre($id_demande, $id_produit, $quantite);
            $repo->decrementerStock($id_produit, $quantite);

            $reste = $repo->getResteADistribuerPourDemande($id_demande);
            if ($reste <= 0) {
                $repo->mettreAJourStatutDemande($id_demande, 'SATISFAITE');
            } else {
                $repo->mettreAJourStatutDemande($id_demande, 'EN_COURS');
            }

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            Flight::redirect('/demande/' . urlencode((string)$id_demande) . '?erreur=' . urlencode('Erreur serveur.'));
            return;
        }

        Flight::redirect('/demande/' . urlencode((string)$id_demande));
    }

    public static function postAcheterBesoin() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);
        $repoDonArgent = new DonArgentRepository($pdo);
        $repoAchat = new AchatRepository($pdo);
        $req = Flight::request();

        $id_demande = (int)($req->data->id_demande ?? 0);
        $id_produit = (int)($req->data->id_produit ?? 0);
        $quantite = (float)($req->data->quantite ?? 0);

        if ($id_demande <= 0 || $id_produit <= 0 || $quantite <= 0) {
            Flight::redirect('/demande/' . urlencode((string)$id_demande) . '?erreur=' . urlencode('Champs invalides.'));
            return;
        }

        $demande = $repo->getInfoDemande($id_demande);
        if (!$demande) {
            Flight::redirect('/admin/dashboard');
            return;
        }

        // Récupérer le prix unitaire du produit
        $st = $pdo->prepare('SELECT prix_unitaire FROM produits WHERE id_produit = ?');
        $st->execute([$id_produit]);
        $prix_unitaire = (float)$st->fetchColumn();

        $montant_total = $quantite * $prix_unitaire;
        $id_produit_argent = $repoDonArgent->getIdProduitArgentPublic();
        $solde_disponible = $repo->getQuantiteStock($id_produit_argent);
        if ($solde_disponible === null) {
            $repo->creerStockSiAbsent($id_produit_argent, 0);
            $solde_disponible = 0.0;
        }

        if ($montant_total > $solde_disponible) {
            Flight::redirect('/demande/' . urlencode((string)$id_demande) . '?erreur=' . urlencode('Solde insuffisant pour cet achat ($montant_total Ar requis, $solde_disponible Ar dispo).'));
            return;
        }

        $pdo->beginTransaction();
        try {
            $id_user = (int)$_SESSION['id_user'];
            $repoAchat->enregistrerAchat($id_demande, $demande['id_ville'], $id_produit, $id_user, $quantite, $prix_unitaire);

            // Débiter l'argent et augmenter le stock du produit acheté
            $repo->decrementerStock($id_produit_argent, $montant_total);
            $stockProduit = $repo->getQuantiteStock($id_produit);
            if ($stockProduit === null) {
                $repo->creerStockSiAbsent($id_produit, 0);
            }
            $repo->incrementerStock($id_produit, $quantite);
            
            // Mettre à jour le statut de la demande
            $reste = $repo->getResteADistribuerPourDemande($id_demande);
            if ($reste <= 0) {
                $repo->mettreAJourStatutDemande($id_demande, 'SATISFAITE');
            } else {
                $repo->mettreAJourStatutDemande($id_demande, 'EN_COURS');
            }
            
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            Flight::redirect('/demande/' . urlencode((string)$id_demande) . '?erreur=' . urlencode('Erreur serveur: ' . $e->getMessage()));
            return;
        }

        Flight::redirect('/demande/' . urlencode((string)$id_demande));
    }

    public static function getProduitsParCategorieJson() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);
        $req = Flight::request();
        $id_categorie = isset($req->query['categorie']) ? (int)$req->query['categorie'] : 0;
        $produits = $id_categorie > 0 ? $repo->listeProduitsParCategorie($id_categorie) : [];
        
        // On s'assure que le prix unitaire est présent (si possible par Repository)
        // Sinon on le récupère ici pour l'exemple
        foreach ($produits as &$p) {
            $st = $pdo->prepare('SELECT prix_unitaire FROM produits WHERE id_produit = ?');
            $st->execute([$p['id_produit']]);
            $p['prix_unitaire'] = (float)$st->fetchColumn();
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($produits);
    }

    public static function getCategoriesJson() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);
        $categories = $repo->listeCategories();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($categories);
    }

    public static function getStockProduitJson($id_produit) {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);
        $id_produit = (int)$id_produit;

        $stock = $repo->getQuantiteStock($id_produit);
        if ($stock === null) {
            $repo->creerStockSiAbsent($id_produit, 0);
            $stock = 0.0;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['id_produit' => $id_produit, 'quantite_disponible' => (float)$stock]);
    }

    public static function getVillesParRegionJson() {
        $pdo = Flight::db();
        self::exigerAdmin($pdo);

        $repo = new DemandeRepository($pdo);
        $req = Flight::request();
        $id_region = isset($req->query['region']) ? (int)$req->query['region'] : 0;
        $villes = $id_region > 0 ? $repo->listeVillesParRegion($id_region) : [];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($villes);
    }

}
