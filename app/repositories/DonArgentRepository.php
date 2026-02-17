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

    public function ajouterDonArgent($id_ville, $montant, $id_user) {
        $id_produit = $this->getIdProduitArgent();
        $st = $this->pdo->prepare('INSERT INTO dons (id_ville, id_produit, id_user, quantite, date_don) VALUES (?, ?, ?, ?, NOW())');
        $st->execute([(int)$id_ville, (int)$id_produit, (int)$id_user, (float)$montant]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getSoldeByVille($id_ville) {
        $st = $this->pdo->prepare('
            SELECT 
                (SELECT COALESCE(SUM(montant), 0) FROM vue_dons_argent WHERE id_ville = ?) - 
                (SELECT COALESCE(SUM(montant_total), 0) FROM achats WHERE id_ville = ?) as solde
        ');
        $st->execute([(int)$id_ville, (int)$id_ville]);
        return (float)($st->fetchColumn() ?: 0);
    }

    public function listeDonsArgent() {
        $st = $this->pdo->query('
            SELECT d.*, v.nom as ville 
            FROM vue_dons_argent d 
            JOIN villes v ON d.id_ville = v.id_ville 
            ORDER BY d.date_don DESC
        ');
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
