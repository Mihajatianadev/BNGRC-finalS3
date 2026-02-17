<?php
class PrixRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function listeProduits() {
        $sql = "SELECT id_produit, nom, unite, prix_unitaire FROM produits ORDER BY nom ASC";
        $st = $this->pdo->query($sql);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePrix($id_produit, $prix_unitaire) {
        $st = $this->pdo->prepare("UPDATE produits SET prix_unitaire = ? WHERE id_produit = ?");
        return $st->execute([(float)$prix_unitaire, (int)$id_produit]);
    }
}
