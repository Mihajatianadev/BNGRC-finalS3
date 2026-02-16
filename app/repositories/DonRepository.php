<?php
class DonRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function insertDonGlobal($id_produit, $quantite) {
        // Vérifier si le produit existe déjà dans le stock
        $sql = "SELECT id_stock, quantite_disponible 
                FROM stock 
                WHERE id_produit = :id_produit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_produit' => $id_produit]);
        $stock = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stock) {
            // Mise à jour du stock existant
            $newQuantite = $stock['quantite_disponible'] + $quantite;
            $sqlUpdate = "UPDATE stock 
                          SET quantite_disponible = :newQuantite 
                          WHERE id_stock = :id_stock";
            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':newQuantite' => $newQuantite,
                ':id_stock' => $stock['id_stock']
            ]);
        } else {
            // Si le produit n’a pas encore de stock, on l’insère
            $sqlInsert = "INSERT INTO stock (id_produit, quantite_disponible) 
                          VALUES (:id_produit, :quantite)";
            $stmtInsert = $this->pdo->prepare($sqlInsert);
            $stmtInsert->execute([
                ':id_produit' => $id_produit,
                ':quantite' => $quantite
            ]);
        }
    }
}
