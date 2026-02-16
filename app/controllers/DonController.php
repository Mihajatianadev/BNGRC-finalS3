<?php
class DonController {
    public static function postDonGlobal() {
        $pdo = Flight::db();
        $repo = new DonRepository($pdo);

        $id_produit = $_POST['id_produit'];
        $quantite = $_POST['quantite'];

        try {
            $repo->insertDonGlobal($id_produit, $quantite);
            echo "Don ajouté au stock avec succès.";
        } catch (Exception $e) {
            echo "Erreur lors de l'ajout du don : " . $e->getMessage();
        }
    }
}
