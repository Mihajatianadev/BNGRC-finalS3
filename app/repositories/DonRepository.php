<?php
class DonRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getOrCreateCategorie($nom) {
        $nom = trim((string)$nom);
        if ($nom === '') {
            throw new Exception('Nom catégorie invalide.');
        }

        $st = $this->pdo->prepare('SELECT id_categorie FROM categories WHERE nom = ? LIMIT 1');
        $st->execute([$nom]);
        $id = $st->fetchColumn();
        if ($id) {
            return (int)$id;
        }

        $st2 = $this->pdo->prepare('INSERT INTO categories(nom) VALUES(?)');
        $st2->execute([$nom]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getOrCreateProduit($id_categorie, $nom, $unite) {
        $nom = trim((string)$nom);
        $unite = trim((string)$unite);
        $id_categorie = (int)$id_categorie;

        if ($id_categorie <= 0 || $nom === '' || $unite === '') {
            throw new Exception('Produit invalide.');
        }

        $st = $this->pdo->prepare('SELECT id_produit FROM produits WHERE nom = ? AND id_categorie = ? LIMIT 1');
        $st->execute([$nom, $id_categorie]);
        $id = $st->fetchColumn();
        if ($id) {
            return (int)$id;
        }

        $st2 = $this->pdo->prepare('INSERT INTO produits(nom, unite, id_categorie) VALUES(?, ?, ?)');
        $st2->execute([$nom, $unite, $id_categorie]);
        return (int)$this->pdo->lastInsertId();
    }

    public function insertDonEtStock($id_user, $id_produit, $quantite) {
        $id_user = (int)$id_user;
        $id_produit = (int)$id_produit;
        $quantite = (float)$quantite;

        if ($id_user <= 0 || $id_produit <= 0 || $quantite <= 0) {
            throw new Exception('Champs invalides.');
        }

        $stDon = $this->pdo->prepare('INSERT INTO dons(id_user, id_produit, quantite, date_don) VALUES(?, ?, ?, NOW())');
        $stDon->execute([$id_user, $id_produit, $quantite]);

        $st = $this->pdo->prepare('SELECT id_stock FROM stock WHERE id_produit = ? LIMIT 1');
        $st->execute([$id_produit]);
        $id_stock = $st->fetchColumn();

        if ($id_stock) {
            $stUp = $this->pdo->prepare('UPDATE stock SET quantite_disponible = quantite_disponible + ? WHERE id_stock = ?');
            $stUp->execute([$quantite, (int)$id_stock]);
            return;
        }

        $stIns = $this->pdo->prepare('INSERT INTO stock(id_produit, quantite_disponible) VALUES(?, ?)');
        $stIns->execute([$id_produit, $quantite]);
    }
}
