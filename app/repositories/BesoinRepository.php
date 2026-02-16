<?php
class BesoinRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function insertBesoin($id_ville, $nomProduit, $unite, $id_categorie, $quantite) {
        // Vérifier si le produit existe déjà
        $sql = "SELECT id_produit FROM produits WHERE nom = :nomProduit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nomProduit' => $nomProduit]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            // Insérer le produit si inexistant
            $sqlInsertProduit = "INSERT INTO produits (nom, unite, id_categorie) 
                                 VALUES (:nomProduit, :unite, :id_categorie)";
            $stmtInsert = $this->pdo->prepare($sqlInsertProduit);
            $stmtInsert->execute([
                ':nomProduit' => $nomProduit,
                ':unite' => $unite,
                ':id_categorie' => $id_categorie
            ]);
            $id_produit = $this->pdo->lastInsertId();
        } else {
            $id_produit = $produit['id_produit'];
        }

        // Insérer la demande
        $sqlDemande = "INSERT INTO demandes (id_ville, id_produit, quantite_demandee, date_demande, statut) 
                       VALUES (:id_ville, :id_produit, :quantite, NOW(), 'EN_ATTENTE')";
        $stmtDemande = $this->pdo->prepare($sqlDemande);
        $stmtDemande->execute([
            ':id_ville' => $id_ville,
            ':id_produit' => $id_produit,
            ':quantite' => $quantite
        ]);

        return $this->pdo->lastInsertId();
    }
}
