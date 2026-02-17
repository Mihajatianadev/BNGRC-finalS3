ALTER TABLE dons
ADD COLUMN isDefault TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE demandes
ADD COLUMN isDefault TINYINT(1) NOT NULL DEFAULT 0;


ALTER TABLE dons DROP FOREIGN KEY dons_ibfk_2; 
ALTER TABLE dons ADD CONSTRAINT fk_dons_produit
FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
ON DELETE CASCADE;

ALTER TABLE dons DROP FOREIGN KEY dons_ibfk_3;
ALTER TABLE dons ADD CONSTRAINT fk_dons_ville
FOREIGN KEY (id_ville) REFERENCES villes(id_ville)
ON DELETE CASCADE;


-- Supprimer la FK existante si elle existe
ALTER TABLE dons DROP FOREIGN KEY IF EXISTS dons_ibfk_2; -- id_produit
ALTER TABLE dons DROP FOREIGN KEY IF EXISTS dons_ibfk_3; -- id_ville

-- Ajouter avec cascade
ALTER TABLE dons
ADD CONSTRAINT fk_dons_produit
FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
ON DELETE CASCADE;

ALTER TABLE dons
ADD CONSTRAINT fk_dons_ville
FOREIGN KEY (id_ville) REFERENCES villes(id_ville)
ON DELETE CASCADE;


ALTER TABLE demandes DROP FOREIGN KEY IF EXISTS demandes_ibfk_1; -- id_ville
ALTER TABLE demandes DROP FOREIGN KEY IF EXISTS demandes_ibfk_2; -- id_produit

ALTER TABLE demandes
ADD CONSTRAINT fk_demandes_ville
FOREIGN KEY (id_ville) REFERENCES villes(id_ville)
ON DELETE CASCADE;

ALTER TABLE demandes
ADD CONSTRAINT fk_demandes_produit
FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
ON DELETE CASCADE;

ALTER TABLE distributions DROP FOREIGN KEY IF EXISTS distributions_ibfk_1;

ALTER TABLE distributions
ADD CONSTRAINT fk_distributions_demande
FOREIGN KEY (id_demande) REFERENCES demandes(id_demande)
ON DELETE CASCADE;


ALTER TABLE achats DROP FOREIGN KEY IF EXISTS achats_ibfk_1; -- id_demande
ALTER TABLE achats DROP FOREIGN KEY IF EXISTS achats_ibfk_2; -- id_ville
ALTER TABLE achats DROP FOREIGN KEY IF EXISTS achats_ibfk_3; -- id_produit
ALTER TABLE achats DROP FOREIGN KEY IF EXISTS achats_ibfk_4; -- id_user

ALTER TABLE achats
ADD CONSTRAINT fk_achats_demande
FOREIGN KEY (id_demande) REFERENCES demandes(id_demande)
ON DELETE CASCADE;

ALTER TABLE achats
ADD CONSTRAINT fk_achats_ville
FOREIGN KEY (id_ville) REFERENCES villes(id_ville)
ON DELETE CASCADE;

ALTER TABLE achats
ADD CONSTRAINT fk_achats_produit
FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
ON DELETE CASCADE;

ALTER TABLE achats
ADD CONSTRAINT fk_achats_user
FOREIGN KEY (id_user) REFERENCES users(id_user)
ON DELETE CASCADE;



