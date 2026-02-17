<?php
class DonArgentRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function getIdProduitArgent() {
        $st = $this->pdo->query("SELECT id_produit FROM produits WHERE nom = 'Argent' LIMIT 1");
        return (int)$st->fetchColumn();
    }

    public function getIdProduitArgentPublic() {
        return $this->getIdProduitArgent();
    }

    public function ajouterDonArgent($id_ville, $montant, $id_user) {
        $id_produit = $this->getIdProduitArgent();
        $st = $this->pdo->prepare('INSERT INTO dons (id_ville, id_produit, id_user, quantite, date_don) VALUES (?, ?, ?, ?, NOW())');
        $st->execute([(int)$id_ville, (int)$id_produit, (int)$id_user, (float)$montant]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getSoldeByVille($id_ville) {
        $id_produit = $this->getIdProduitArgent();
        $st = $this->pdo->prepare('
            SELECT 
                (SELECT COALESCE(SUM(d.quantite), 0) FROM dons d WHERE d.id_ville = ? AND d.id_produit = ?) -
                (SELECT COALESCE(SUM(a.montant_total), 0) FROM achats a WHERE a.id_ville = ?) as solde
        ');
        $st->execute([(int)$id_ville, (int)$id_produit, (int)$id_ville]);
        return (float)($st->fetchColumn() ?: 0);
    }

    public function listeDonsArgent() {
        $id_produit = $this->getIdProduitArgent();
        $st = $this->pdo->prepare('
            SELECT d.id_don, d.id_ville, v.nom as ville, d.id_user, u.nom as user_nom, u.prenom as user_prenom,
                   d.quantite as montant, d.date_don
            FROM dons d
            JOIN villes v ON v.id_ville = d.id_ville
            LEFT JOIN users u ON u.id_user = d.id_user
            WHERE d.id_produit = ?
            ORDER BY d.date_don DESC
        ');
        $st->execute([(int)$id_produit]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
