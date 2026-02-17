<?php
class PrixController {
    private $repo;

    public function __construct(ProduitRepository $repo) {
        $this->repo = $repo;
    }

    public function index() {
        $produits = $this->repo->listeProduits();
        Flight::render('admin/prix', [
            'produits' => $produits,
            'success'  => $_SESSION['success'] ?? null,
            'error'    => $_SESSION['error'] ?? null
        ]);
    }

    public function update() {
        $id_produit   = (int)($_POST['id_produit'] ?? 0);
        $prix_unitaire = (float)($_POST['prix_unitaire'] ?? 0);

        if ($id_produit > 0 && $prix_unitaire > 0) {
            $this->repo->updatePrixUnitaire($id_produit, $prix_unitaire);
            $_SESSION['success'] = "Prix mis à jour avec succès.";
        } else {
            $_SESSION['error'] = "Données invalides.";
        }

        header("Location: /admin/prix");
        exit;
    }
}
