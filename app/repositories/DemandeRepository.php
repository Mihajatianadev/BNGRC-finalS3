<?php
class DemandeRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function listeRegions() {
        $st = $this->pdo->query('SELECT id_region, nom FROM regions ORDER BY nom');
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listeVillesParRegion($id_region) {
        $st = $this->pdo->prepare('SELECT id_ville, nom FROM villes WHERE id_region = ? ORDER BY nom');
        $st->execute([(int)$id_region]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listeVilles() {
        $st = $this->pdo->query('SELECT id_ville, nom FROM villes ORDER BY nom');
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listeDemandesPourDashboard($id_region = null, $id_ville = null) {
        // On regroupe une "demande" par (ville + date_demande + statut).
        // Cela permet d'avoir plusieurs produits dans une même demande si la base contient plusieurs lignes pour la même ville à la même date.

        $sql = "
            SELECT
                d.id_demande,
                r.nom AS region,
                v.id_ville,
                v.nom AS ville,
                d.date_demande,
                d.statut,
                COUNT(DISTINCT d.id_produit) AS nombre_produits
            FROM demandes d
            JOIN villes v ON d.id_ville = v.id_ville
            JOIN regions r ON v.id_region = r.id_region
            WHERE 1=1
        ";

        $params = [];

        if ($id_region !== null) {
            $sql .= ' AND r.id_region = ?';
            $params[] = (int)$id_region;
        }

        if ($id_ville !== null) {
            $sql .= ' AND v.id_ville = ?';
            $params[] = (int)$id_ville;
        }

        $sql .= "
            GROUP BY r.nom, v.id_ville, v.nom, d.date_demande, d.statut
            ORDER BY d.date_demande DESC, v.nom ASC
        ";

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
