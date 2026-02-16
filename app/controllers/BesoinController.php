<?php
class BesoinController {
    public static function postBesoin() {
        $pdo = Flight::db();
        $repo = new BesoinRepository($pdo);

        // Récupération des données envoyées (ex: via formulaire ou JSON)
        $id_ville = $_POST['id_ville'];
        $nomProduit = $_POST['nom_produit'];
        $unite = $_POST['unite'];
        $id_categorie = $_POST['id_categorie'];
        $quantite = $_POST['quantite'];

        try {
            $id_demande = $repo->insertBesoin($id_ville, $nomProduit, $unite, $id_categorie, $quantite);
            Flight::json(['success' => true, 'id_demande' => $id_demande]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
