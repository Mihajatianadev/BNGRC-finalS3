<?php
class RecapRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function getIdProduitArgent() {
        $st = $this->pdo->query("SELECT id_produit FROM produits WHERE nom = 'Argent' LIMIT 1");
        return (int)$st->fetchColumn();
    }

    public function getRecapParVille($id_ville = null, $date_debut = '', $date_fin = '') {
        $id_produit_argent = $this->getIdProduitArgent();
        $sql = "
            SELECT 
                v.id_ville,
                v.nom as ville,
                r.nom as region,
                -- Besoins totaux en montant
                COALESCE((
                    SELECT SUM(d.quantite_demandee * p.prix_unitaire)
                    FROM demandes d
                    JOIN produits p ON d.id_produit = p.id_produit
                    WHERE d.id_ville = v.id_ville
                ), 0) as besoins_totaux_montant,
                
                -- Besoins satisfaits en montant (distributions stock + achats)
                COALESCE((
                    SELECT SUM(dist.quantite_envoyee * pdist.prix_unitaire)
                    FROM distributions dist
                    JOIN produits pdist ON dist.id_produit = pdist.id_produit
                    JOIN demandes d ON dist.id_demande = d.id_demande
                    WHERE d.id_ville = v.id_ville
                      AND pdist.id_categorie != 4
        ";

        $params = [];
        if ($date_debut !== '') {
            $sql .= ' AND DATE(dist.date_distribution) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(dist.date_distribution) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                ), 0) +
                COALESCE((
                    SELECT SUM(a.montant_total)
                    FROM achats a
                    WHERE a.id_ville = v.id_ville
        ";

        if ($date_debut !== '') {
            $sql .= ' AND DATE(a.date_achat) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(a.date_achat) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                ), 0) as besoins_satisfaits_montant,

                -- Dons reçus (argent)
                COALESCE((
                    SELECT SUM(d.quantite)
                    FROM dons d
                    WHERE d.id_ville = v.id_ville
                      AND d.id_produit = $id_produit_argent
        ";

        if ($date_debut !== '') {
            $sql .= ' AND DATE(d.date_don) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(d.date_don) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                ), 0) as dons_recus_montant,

                -- Dons distribués (achats faits avec l'argent)
                COALESCE((
                    SELECT SUM(a.montant_total)
                    FROM achats a
                    WHERE a.id_ville = v.id_ville
        ";

        if ($date_debut !== '') {
            $sql .= ' AND DATE(a.date_achat) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(a.date_achat) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                ), 0) as dons_distribues_montant

            FROM villes v
            JOIN regions r ON v.id_region = r.id_region
            WHERE 1=1
        ";

        if ($id_ville !== null) {
            $sql .= ' AND v.id_ville = ?';
            $params[] = (int)$id_ville;
        }

        $sql .= ' ORDER BY r.nom, v.nom';
        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecapParRegion($id_ville = null, $date_debut = '', $date_fin = '') {
        $id_produit_argent = $this->getIdProduitArgent();
        $sql = "
            SELECT 
                reg.nom as region,
                COALESCE(SUM(d.quantite_demandee * p.prix_unitaire), 0) as besoins_totaux_montant,
                (
                    COALESCE((
                        SELECT SUM(dist.quantite_envoyee * pdist.prix_unitaire)
                        FROM distributions dist
                        JOIN produits pdist ON dist.id_produit = pdist.id_produit
                        JOIN demandes d2 ON dist.id_demande = d2.id_demande
                        JOIN villes v2 ON d2.id_ville = v2.id_ville
                        WHERE v2.id_region = reg.id_region
                          AND pdist.id_categorie != 4
        ";

        $params = [];
        if ($id_ville !== null) {
            $sql .= ' AND v2.id_ville = ?';
            $params[] = (int)$id_ville;
        }
        if ($date_debut !== '') {
            $sql .= ' AND DATE(dist.date_distribution) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(dist.date_distribution) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                    ), 0) +
                    COALESCE((
                        SELECT SUM(a.montant_total)
                        FROM achats a
                        JOIN villes v3 ON a.id_ville = v3.id_ville
                        WHERE v3.id_region = reg.id_region
        ";

        if ($id_ville !== null) {
            $sql .= ' AND v3.id_ville = ?';
            $params[] = (int)$id_ville;
        }
        if ($date_debut !== '') {
            $sql .= ' AND DATE(a.date_achat) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(a.date_achat) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                    ), 0)
                ) as besoins_satisfaits_montant,
                COALESCE((
                    SELECT SUM(don.quantite)
                    FROM dons don
                    JOIN villes v4 ON don.id_ville = v4.id_ville
                    WHERE v4.id_region = reg.id_region
                      AND don.id_produit = $id_produit_argent
        ";

        if ($id_ville !== null) {
            $sql .= ' AND v4.id_ville = ?';
            $params[] = (int)$id_ville;
        }
        if ($date_debut !== '') {
            $sql .= ' AND DATE(don.date_don) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(don.date_don) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                ), 0) as dons_recus_montant,
                COALESCE((
                    SELECT SUM(a2.montant_total)
                    FROM achats a2
                    JOIN villes v5 ON a2.id_ville = v5.id_ville
                    WHERE v5.id_region = reg.id_region
        ";

        if ($id_ville !== null) {
            $sql .= ' AND v5.id_ville = ?';
            $params[] = (int)$id_ville;
        }
        if ($date_debut !== '') {
            $sql .= ' AND DATE(a2.date_achat) >= ?';
            $params[] = $date_debut;
        }
        if ($date_fin !== '') {
            $sql .= ' AND DATE(a2.date_achat) <= ?';
            $params[] = $date_fin;
        }

        $sql .= "
                ), 0) as dons_distribues_montant
            FROM regions reg
            LEFT JOIN villes v ON reg.id_region = v.id_region
            LEFT JOIN demandes d ON v.id_ville = d.id_ville
            LEFT JOIN produits p ON d.id_produit = p.id_produit
            GROUP BY reg.id_region, reg.nom
        ";

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecapTotal($id_ville = null, $date_debut = '', $date_fin = '') {
        $sql = "
            SELECT 
                SUM(besoins_totaux_montant) as besoins_totaux_montant,
                SUM(besoins_satisfaits_montant) as besoins_satisfaits_montant,
                SUM(dons_recus_montant) as dons_recus_montant,
                SUM(dons_distribues_montant) as dons_distribues_montant
            FROM (
                SELECT 
                    COALESCE(SUM(d.quantite_demandee * p.prix_unitaire), 0) as besoins_totaux_montant
                FROM demandes d
                JOIN produits p ON d.id_produit = p.id_produit
            ) t1,
            (
                SELECT 
                    COALESCE((SELECT SUM(dist.quantite_envoyee * pdist.prix_unitaire) FROM distributions dist JOIN produits pdist ON dist.id_produit = pdist.id_produit WHERE pdist.id_categorie != 4), 0) +
                    COALESCE((SELECT SUM(montant_total) FROM achats), 0) as besoins_satisfaits_montant
            ) t2,
            (
                SELECT COALESCE(SUM(montant), 0) as dons_recus_montant FROM vue_dons_argent
            ) t3,
            (
                SELECT COALESCE(SUM(montant_total), 0) as dons_distribues_montant FROM achats
            ) t4
        ";

        // Filtrage total: on applique les filtres côté PHP en recalculant depuis getRecapParVille,
        // pour éviter de dupliquer une requête total parametrée complexe.
        $rows = $this->getRecapParVille($id_ville, $date_debut, $date_fin);
        $tot = [
            'besoins_totaux_montant' => 0,
            'besoins_satisfaits_montant' => 0,
            'dons_recus_montant' => 0,
            'dons_distribues_montant' => 0,
        ];
        foreach ($rows as $r) {
            $tot['besoins_totaux_montant'] += (float)($r['besoins_totaux_montant'] ?? 0);
            $tot['besoins_satisfaits_montant'] += (float)($r['besoins_satisfaits_montant'] ?? 0);
            $tot['dons_recus_montant'] += (float)($r['dons_recus_montant'] ?? 0);
            $tot['dons_distribues_montant'] += (float)($r['dons_distribues_montant'] ?? 0);
        }
        return $tot;
    }

    private function getRecapParVilleSql() {
         return "
            SELECT 
                v.id_ville,
                v.nom as ville,
                r.nom as region,
                COALESCE((SELECT SUM(d.quantite_demandee * p.prix_unitaire) FROM demandes d JOIN produits p ON d.id_produit = p.id_produit WHERE d.id_ville = v.id_ville), 0) as besoins_totaux_montant,
                COALESCE((SELECT SUM(dist.quantite_envoyee * pdist.prix_unitaire) FROM distributions dist JOIN produits pdist ON dist.id_produit = pdist.id_produit JOIN demandes d ON dist.id_demande = d.id_demande WHERE d.id_ville = v.id_ville AND pdist.id_categorie != 5), 0) +
                COALESCE((SELECT SUM(a.montant_total) FROM achats a WHERE a.id_ville = v.id_ville), 0) as besoins_satisfaits_montant,
                COALESCE((SELECT SUM(da.montant) FROM vue_dons_argent da WHERE da.id_ville = v.id_ville), 0) as dons_recus_montant,
                COALESCE((SELECT SUM(a.montant_total) FROM achats a WHERE a.id_ville = v.id_ville), 0) as dons_distribues_montant
            FROM villes v
            JOIN regions r ON v.id_region = r.id_region;
        ";
    }
}
