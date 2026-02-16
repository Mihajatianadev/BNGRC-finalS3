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
                MIN(d.id_demande) AS id_demande,
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

    public function listeDemandesDetaillees($id_ville = null, $date_debut = '', $date_fin = '') {
        // Version simple et robuste:
        // 1) liste des demandes (groupées par ville + date_demande + statut)
        // 2) pour chaque ligne: récupérer les besoins et les distributions.

        $sql = "
            SELECT
                villes.id_ville,
                villes.nom AS ville,
                demandes.date_demande,
                demandes.statut
            FROM demandes
            JOIN villes ON demandes.id_ville = villes.id_ville
            WHERE 1=1
        ";

        $params = [];

        if ($id_ville !== null) {
            $sql .= ' AND demandes.id_ville = ?';
            $params[] = (int)$id_ville;
        }

        if ($date_debut !== '') {
            $sql .= ' AND DATE(demandes.date_demande) >= ?';
            $params[] = $date_debut;
        }

        if ($date_fin !== '') {
            $sql .= ' AND DATE(demandes.date_demande) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
            GROUP BY villes.id_ville, villes.nom, demandes.date_demande, demandes.statut
            ORDER BY demandes.date_demande DESC, villes.nom ASC
        ";

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        $groupes = $st->fetchAll(PDO::FETCH_ASSOC);

        $sql_besoins = "
            SELECT
                produits.nom AS produit,
                produits.unite,
                SUM(demandes.quantite_demandee) AS quantite_demandee
            FROM demandes
            JOIN produits ON demandes.id_produit = produits.id_produit
            WHERE demandes.id_ville = ?
              AND demandes.date_demande = ?
              AND ((? IS NULL AND demandes.statut IS NULL) OR demandes.statut = ?)
            GROUP BY produits.id_produit, produits.nom, produits.unite
            ORDER BY produits.nom
        ";

        $sql_distrib = "
            SELECT
                produits.nom AS produit,
                produits.unite,
                distributions.quantite_envoyee,
                distributions.date_distribution
            FROM demandes
            JOIN produits ON demandes.id_produit = produits.id_produit
            JOIN distributions ON distributions.id_demande = demandes.id_demande
            WHERE demandes.id_ville = ?
              AND demandes.date_demande = ?
              AND ((? IS NULL AND demandes.statut IS NULL) OR demandes.statut = ?)
            ORDER BY distributions.date_distribution, produits.nom
        ";

        $st_besoins = $this->pdo->prepare($sql_besoins);
        $st_distrib = $this->pdo->prepare($sql_distrib);

        $resultats = [];
        foreach ($groupes as $g) {
            $id_ville_ligne = (int)$g['id_ville'];
            $date_demande = $g['date_demande'];
            $statut = $g['statut'];

            $st_besoins->execute([$id_ville_ligne, $date_demande, $statut, $statut]);
            $besoins_rows = $st_besoins->fetchAll(PDO::FETCH_ASSOC);

            $besoins_txt = '';
            foreach ($besoins_rows as $b) {
                $part = $b['produit'] . ' : ' . $b['quantite_demandee'] . ' ' . $b['unite'];
                $besoins_txt = $besoins_txt === '' ? $part : ($besoins_txt . ' | ' . $part);
            }

            $st_distrib->execute([$id_ville_ligne, $date_demande, $statut, $statut]);
            $distrib_rows = $st_distrib->fetchAll(PDO::FETCH_ASSOC);

            $distrib_txt = '';
            foreach ($distrib_rows as $drow) {
                $date_reception = $drow['date_distribution'] ? substr((string)$drow['date_distribution'], 0, 10) : '';
                $part = $drow['produit'] . ' : ' . $drow['quantite_envoyee'] . ' ' . $drow['unite'];
                if ($date_reception !== '') {
                    $part .= ' (' . $date_reception . ')';
                }
                $distrib_txt = $distrib_txt === '' ? $part : ($distrib_txt . ' | ' . $part);
            }

            $g['besoins'] = $besoins_txt;
            $g['distribue'] = $distrib_txt;
            $resultats[] = $g;
        }

        return $resultats;
    }

    public function getInfoDemande($id_demande) {
        $st = $this->pdo->prepare('
            SELECT
                demandes.id_demande,
                demandes.id_ville,
                villes.nom AS ville,
                demandes.id_produit,
                produits.nom AS produit,
                produits.unite,
                produits.id_categorie,
                categories.nom AS categorie,
                demandes.quantite_demandee,
                demandes.date_demande,
                demandes.statut
            FROM demandes
            JOIN villes ON demandes.id_ville = villes.id_ville
            JOIN produits ON demandes.id_produit = produits.id_produit
            JOIN categories ON produits.id_categorie = categories.id_categorie
            WHERE demandes.id_demande = ?
            LIMIT 1
        ');
        $st->execute([(int)$id_demande]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function listeCategories() {
        $st = $this->pdo->query('SELECT id_categorie, nom FROM categories ORDER BY nom');
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listeProduitsParCategorie($id_categorie) {
        $st = $this->pdo->prepare('SELECT id_produit, nom, unite FROM produits WHERE id_categorie = ? ORDER BY nom');
        $st->execute([(int)$id_categorie]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listeDistributionsParDemande($id_demande) {
        $this->assurerDistributionsIdProduit();

        $st = $this->pdo->prepare('
            SELECT
                distributions.id_distribution,
                distributions.quantite_envoyee,
                distributions.date_distribution,
                produits.nom AS produit,
                produits.unite
            FROM distributions
            JOIN produits ON distributions.id_produit = produits.id_produit
            WHERE distributions.id_demande = ?
            ORDER BY distributions.date_distribution DESC
        ');
        $st->execute([(int)$id_demande]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuantiteStock($id_produit) {
        $st = $this->pdo->prepare('SELECT quantite_disponible FROM stock WHERE id_produit = ? LIMIT 1');
        $st->execute([(int)$id_produit]);
        $val = $st->fetchColumn();
        if ($val === false) {
            return null;
        }
        return (float)$val;
    }

    public function creerStockSiAbsent($id_produit, $quantite_initiale = 0) {
        $st = $this->pdo->prepare('SELECT id_stock FROM stock WHERE id_produit = ? LIMIT 1');
        $st->execute([(int)$id_produit]);
        $id_stock = $st->fetchColumn();
        if ($id_stock) {
            return (int)$id_stock;
        }

        $st2 = $this->pdo->prepare('INSERT INTO stock(id_produit, quantite_disponible) VALUES(?, ?)');
        $st2->execute([(int)$id_produit, (float)$quantite_initiale]);
        return (int)$this->pdo->lastInsertId();
    }

    public function decrementerStock($id_produit, $quantite) {
        $st = $this->pdo->prepare('UPDATE stock SET quantite_disponible = quantite_disponible - ? WHERE id_produit = ?');
        $st->execute([(float)$quantite, (int)$id_produit]);
    }

    public function insererDistribution($id_demande, $quantite_envoyee) {
        $demande = $this->getInfoDemande($id_demande);
        if (!$demande) {
            return 0;
        }

        return $this->insererDistributionLibre((int)$id_demande, (int)$demande['id_produit'], (float)$quantite_envoyee);
    }

    public function insererDistributionLibre($id_demande, $id_produit, $quantite_envoyee) {
        $this->assurerDistributionsIdProduit();

        $st = $this->pdo->prepare('INSERT INTO distributions(id_demande, id_produit, quantite_envoyee, date_distribution) VALUES(?, ?, ?, NOW())');
        $st->execute([(int)$id_demande, (int)$id_produit, (float)$quantite_envoyee]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getResteADistribuerPourDemande($id_demande) {
        $this->assurerDistributionsIdProduit();

        $st = $this->pdo->prepare('
            SELECT
                demandes.quantite_demandee - COALESCE(SUM(CASE WHEN distributions.id_produit = demandes.id_produit OR distributions.id_produit IS NULL THEN distributions.quantite_envoyee ELSE 0 END), 0) AS reste
            FROM demandes
            LEFT JOIN distributions ON distributions.id_demande = demandes.id_demande
            WHERE demandes.id_demande = ?
            GROUP BY demandes.id_demande, demandes.quantite_demandee
        ');
        $st->execute([(int)$id_demande]);
        $reste = $st->fetchColumn();
        if ($reste === false) {
            return 0.0;
        }
        return (float)$reste;
    }

    public function mettreAJourStatutDemande($id_demande, $statut) {
        $st = $this->pdo->prepare('UPDATE demandes SET statut = ? WHERE id_demande = ?');
        $st->execute([(string)$statut, (int)$id_demande]);
    }

    private function assurerDistributionsIdProduit() {
        // Ajout minimal (si absent) pour permettre les dons libres.
        // + backfill pour les anciennes distributions.
        $st = $this->pdo->prepare("SHOW COLUMNS FROM distributions LIKE 'id_produit'");
        $st->execute();
        $col = $st->fetch(PDO::FETCH_ASSOC);
        if ($col) {
            return;
        }

        $this->pdo->exec('ALTER TABLE distributions ADD COLUMN id_produit INT NULL AFTER id_demande');
        $this->pdo->exec('UPDATE distributions JOIN demandes ON distributions.id_demande = demandes.id_demande SET distributions.id_produit = demandes.id_produit WHERE distributions.id_produit IS NULL');
    }
}
