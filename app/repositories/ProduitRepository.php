<?php
class ProduitRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function listeProduits() {
        $st = $this->pdo->query("SELECT id_produit, nom, unite, prix_unitaire FROM produits ORDER BY nom ASC");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePrixUnitaire($id_produit, $prix_unitaire) {
        $st = $this->pdo->prepare("UPDATE produits SET prix_unitaire = ? WHERE id_produit = ?");
        return $st->execute([(float)$prix_unitaire, (int)$id_produit]);
    }
}
