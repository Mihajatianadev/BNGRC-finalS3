<?php
class AchatRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function enregistrerAchat($id_demande, $id_ville, $id_produit, $id_user, $quantite, $prix_unitaire) {
        $montant_total = $quantite * $prix_unitaire;
        $st = $this->pdo->prepare('
            INSERT INTO achats (id_demande, id_ville, id_produit, id_user, quantite_achetee, montant_total, date_achat) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ');
        $st->execute([
            (int)$id_demande, 
            (int)$id_ville, 
            (int)$id_produit, 
            (int)$id_user, 
            (float)$quantite, 
            (float)$montant_total
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function listeAchatsParVille($id_ville) {
        $st = $this->pdo->prepare('
            SELECT a.*, p.nom as produit, p.unite 
            FROM achats a 
            JOIN produits p ON a.id_produit = p.id_produit 
            WHERE a.id_ville = ? 
            ORDER BY a.date_achat DESC
        ');
        $st->execute([(int)$id_ville]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalAchatsParDemande($id_demande, $id_produit) {
        $st = $this->pdo->prepare('
            SELECT COALESCE(SUM(quantite_achetee), 0) 
            FROM achats 
            WHERE id_demande = ? AND id_produit = ?
        ');
        $st->execute([(int)$id_demande, (int)$id_produit]);
        return (float)$st->fetchColumn();
    }
}
