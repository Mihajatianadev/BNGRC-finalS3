<?php
class VenteRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Vérifie si un produit est lié à une demande en cours
    public function produitLieADemande($id_produit) {
        $st = $this->pdo->prepare("
            SELECT COUNT(*) FROM demandes 
            WHERE id_produit = ? AND statut IN ('EN_ATTENTE','EN_COURS')
        ");
        $st->execute([(int)$id_produit]);
        return (int)$st->fetchColumn() > 0;
    }

    // Récupère la remise liée à la catégorie du produit
    public function getRemisePourProduit($id_produit) {
        $sql = "
            SELECT r.pourcentage 
            FROM produits p
            JOIN remise r ON p.id_categorie = r.id_categorie
            WHERE p.id_produit = ?
            LIMIT 1
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([(int)$id_produit]);
        return (float)($st->fetchColumn() ?: 0);
    }

    // Ajoute une vente
    public function ajouterVente($id_produit, $quantite, $prix_unitaire, $remise_pct) {
        $prix_final = $prix_unitaire * (1 - $remise_pct / 100);
        $st = $this->pdo->prepare("
            INSERT INTO ventes (produit_id, quantite, prix_unitaire, remise_pct, prix_final, date_vente)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $st->execute([(int)$id_produit, (int)$quantite, (float)$prix_unitaire, (float)$remise_pct, (float)$prix_final]);
        return (int)$this->pdo->lastInsertId();
    }

    // Décrémente le stock
    public function decrementerStock($id_produit, $quantite) {
        $st = $this->pdo->prepare("
            UPDATE stock 
            SET quantite_disponible = quantite_disponible - ? 
            WHERE id_produit = ?
        ");
        return $st->execute([(int)$quantite, (int)$id_produit]);
    }

    // Liste des produits en stock
    public function listeProduitsStock() {
        $sql = "
            SELECT 
                p.id_produit, 
                p.nom AS produit, 
                p.unite, 
                c.nom AS categorie, 
                s.quantite_disponible, 
                p.prix_unitaire,
                r.pourcentage AS remise_pct
            FROM produits p
            JOIN categories c ON p.id_categorie = c.id_categorie
            JOIN stock s ON p.id_produit = s.id_produit
            LEFT JOIN remise r ON p.id_categorie = r.id_categorie
            WHERE s.quantite_disponible > 0
        ";
        $st = $this->pdo->query($sql);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incrementerArgent($montant) {
    // Vérifier si la ligne "Argent" existe déjà
    $st = $this->pdo->prepare("SELECT id_produit FROM produits WHERE nom = 'Argent' LIMIT 1");
    $st->execute();
    $id_argent = $st->fetchColumn();

    if (!$id_argent) {
        // Créer le produit "Argent" si inexistant
        $this->pdo->prepare("INSERT INTO produits (nom, unite, id_categorie, prix_unitaire) VALUES ('Argent', 'Ar', 1, 0)")
                  ->execute();
        $id_argent = $this->pdo->lastInsertId();

        // Créer la ligne de stock correspondante
        $this->pdo->prepare("INSERT INTO stock (id_produit, quantite_disponible) VALUES (?, 0)")
                  ->execute([$id_argent]);
    }

    // Incrémenter le stock d'argent
    $st = $this->pdo->prepare("
        UPDATE stock 
        SET quantite_disponible = quantite_disponible + ? 
        WHERE id_produit = ?
    ");
    return $st->execute([(float)$montant, (int)$id_argent]);
}


    public function listeVentes() {
        $sql = "
            SELECT v.*, p.nom AS produit
            FROM ventes v
            JOIN produits p ON v.produit_id = p.id_produit
            ORDER BY v.date_vente DESC
        ";
        $st = $this->pdo->query($sql);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

}
