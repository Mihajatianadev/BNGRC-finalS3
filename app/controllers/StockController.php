<?php
class StockController {
    private $venteRepo;

    public function __construct(VenteRepository $venteRepo) {
        $this->venteRepo = $venteRepo;
    }

    /**
     * Affiche la page stock avec tableau et formulaire de vente
     */
    public function index() {
        // Récupération des stocks
        $stocks = $this->venteRepo->listeProduitsStock();

        // Messages éventuels
        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['error'] ?? null;

        // Nettoyage des messages pour éviter répétition
        unset($_SESSION['success'], $_SESSION['error']);

        // Rendu de la vue
        Flight::render('admin/stock', [
            'stocks'  => $stocks,
            'success' => $success,
            'erreur'  => $erreur
        ]);
    }


    public function vendre() {
        $id_produit = (int)($_POST['produit_id'] ?? 0);
        $quantite   = (int)($_POST['quantite'] ?? 0);

        if ($id_produit <= 0 || $quantite <= 0) {
            $_SESSION['error'] = "Produit ou quantité invalide.";
            header("Location: /admin/stock");
            exit;
        }

        if ($this->venteRepo->produitLieADemande($id_produit)) {
            $_SESSION['error'] = "Impossible de vendre ce produit : il est lié à une demande en cours.";
            header("Location: /admin/stock");
            exit;
        }

        $produits = $this->venteRepo->listeProduitsStock();
        $prix_unitaire = 0;
        foreach ($produits as $p) {
            if ($p['id_produit'] == $id_produit) {
                $prix_unitaire = (float)$p['prix_unitaire'];
                break;
            }
        }

        if ($prix_unitaire <= 0) {
            $_SESSION['error'] = "Prix unitaire introuvable pour ce produit.";
            header("Location: /admin/stock");
            exit;
        }

        // Récupérer remise liée à la catégorie
        $remise_pct = $this->venteRepo->getRemisePourProduit($id_produit);

        // Enregistrer la vente
        $id_vente = $this->venteRepo->ajouterVente($id_produit, $quantite, $prix_unitaire, $remise_pct);

        if ($id_vente > 0) {
            // Décrémenter le stock du produit
            $this->venteRepo->decrementerStock($id_produit, $quantite);

            // Calculer montant total encaissé
            $prix_final_total = $prix_unitaire * $quantite * (1 - $remise_pct / 100);

            // Incrémenter le stock d'argent
            $this->venteRepo->incrementerArgent($prix_final_total);

            $_SESSION['success'] = "Vente enregistrée avec succès (remise appliquée : $remise_pct%).";
        } else {
            $_SESSION['error'] = "Erreur lors de l’enregistrement de la vente.";
        }

        header("Location: /admin/stock");
        exit;
    }

}
