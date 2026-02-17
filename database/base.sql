CREATE DATABASE IF NOT EXISTS 4106_4132_4381;
USE 4106_4132_4381;

SET NAMES utf8mb4;

-- =========================
-- TABLES
-- =========================

CREATE TABLE roles (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    nom_role VARCHAR(20) NOT NULL
);

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    date_creation DATETIME,
    FOREIGN KEY (id_role) REFERENCES roles(id_role)
);

CREATE TABLE regions (
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE villes (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_region INT NOT NULL,
    FOREIGN KEY (id_region) REFERENCES regions(id_region)
);

CREATE TABLE categories (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE produits (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    id_categorie INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie)
);

ALTER TABLE produits
ADD COLUMN prix_unitaire DOUBLE NOT NULL DEFAULT 0;


CREATE TABLE stock (
    id_stock INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    quantite_disponible DOUBLE NOT NULL,
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);

CREATE TABLE dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produit INT NOT NULL,
    id_ville INT NULL,
    quantite DOUBLE NOT NULL,
    isDefault TINYINT(1) NOT NULL DEFAULT 0,
    date_don DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit),
    FOREIGN KEY (id_ville) REFERENCES villes(id_ville)
);

CREATE TABLE demandes (
    id_demande INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_produit INT NOT NULL,
    quantite_demandee DOUBLE NOT NULL,
    isDefault TINYINT(1) NOT NULL DEFAULT 0,
    date_demande DATETIME,
    statut VARCHAR(20),
    FOREIGN KEY (id_ville) REFERENCES villes(id_ville),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);

CREATE TABLE distributions (
    id_distribution INT AUTO_INCREMENT PRIMARY KEY,
    id_demande INT NOT NULL,
    id_produit INT NOT NULL,
    quantite_envoyee DOUBLE NOT NULL,
    isDefault TINYINT(1) NOT NULL DEFAULT 0,
    date_distribution DATETIME,
    FOREIGN KEY (id_demande) REFERENCES demandes(id_demande),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);

CREATE TABLE achats (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_demande INT NOT NULL,
    id_ville INT NOT NULL,
    id_produit INT NOT NULL,
    id_user INT NOT NULL,
    quantite_achetee DOUBLE NOT NULL,
    montant_total DOUBLE NOT NULL,
    isDefault TINYINT(1) NOT NULL DEFAULT 0,
    date_achat DATETIME NOT NULL,
    FOREIGN KEY (id_demande) REFERENCES demandes(id_demande),
    FOREIGN KEY (id_ville) REFERENCES villes(id_ville),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit),
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);

CREATE TABLE remise (
  id_remise INT AUTO_INCREMENT PRIMARY KEY,
  id_categorie INT NOT NULL,
  pourcentage DECIMAL(5,2) NOT NULL,
  motif VARCHAR(255),
  CONSTRAINT fk_remise_categorie FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie)
);

CREATE TABLE ventes (
  id_vente INT AUTO_INCREMENT PRIMARY KEY,
  produit_id INT NOT NULL,
  quantite INT NOT NULL,
  prix_unitaire DECIMAL(12,2) NOT NULL,
  remise_pct DECIMAL(5,2) DEFAULT 0,
  prix_final DECIMAL(12,2) NOT NULL,
  date_vente DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (produit_id),
  CONSTRAINT fk_ventes_produit FOREIGN KEY (produit_id) REFERENCES produits(id_produit)
);

-- =========================
-- INSERTS
-- =========================

-- ROLES
INSERT INTO roles (nom_role) VALUES ('USER');
INSERT INTO roles (nom_role) VALUES ('ADMIN');

-- CATEGORIES
INSERT INTO categories (nom) VALUES ('Nature');
INSERT INTO categories (nom) VALUES ('Materiel');
INSERT INTO categories (nom) VALUES ('Argent');

-- REGIONS
INSERT INTO regions (nom) VALUES ('Atsinanana');
INSERT INTO regions (nom) VALUES ('Vatovavy');
INSERT INTO regions (nom) VALUES ('Atsimo');

-- VILLES (après les regions)
INSERT INTO villes (nom, id_region) VALUES ('Toamasina', 1);
INSERT INTO villes (nom, id_region) VALUES ('Nosy Be', 1);
INSERT INTO villes (nom, id_region) VALUES ('Mananjary', 2);
INSERT INTO villes (nom, id_region) VALUES ('Farafangana', 3);
INSERT INTO villes (nom, id_region) VALUES ('Morondava', 3);

-- PRODUITS (après categories)
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Riz', 'Kg', 1);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Eau', 'L', 1);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Huile', 'L', 1);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Haricots', 'Piece', 1);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Tôle', 'Piece', 2);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Bâche', 'Piece', 2);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Bois', 'Piece', 2);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Clous', 'Kg', 2);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('groupe', 'Piece', 2);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Argent', 'Ar', 3);

UPDATE produits SET prix_unitaire = 3000 WHERE nom = 'Riz';
UPDATE produits SET prix_unitaire = 1000 WHERE nom = 'Eau';
UPDATE produits SET prix_unitaire = 6000 WHERE nom = 'Huile';
UPDATE produits SET prix_unitaire = 4000 WHERE nom = 'Haricots';
UPDATE produits SET prix_unitaire = 25000 WHERE nom = 'Tôle';
UPDATE produits SET prix_unitaire = 15000 WHERE nom = 'Bâche';
UPDATE produits SET prix_unitaire = 10000 WHERE nom = 'Bois';
UPDATE produits SET prix_unitaire = 8000 WHERE nom = 'Clous';
UPDATE produits SET prix_unitaire = 6750000 WHERE nom = 'groupe';
UPDATE produits SET prix_unitaire = 1 WHERE nom = 'Argent';

-- DEMANDES
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Bâche' LIMIT 1), 200, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Nosy Be' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Tôle' LIMIT 1), 40, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Mananjary' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 600000, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Eau' LIMIT 1), 1500, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Nosy Be' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 300, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Mananjary' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Tôle' LIMIT 1), 80, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Nosy Be' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 400000, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Farafangana' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Bâche' LIMIT 1), 150, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Mananjary' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 500, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Farafangana' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 800000, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Morondava' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 700, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Morondava' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 1000000, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='groupe' LIMIT 1), 3, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Farafangana' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Eau' LIMIT 1), 1000, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Morondava' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Bois' LIMIT 1), 150, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 1200000, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 800, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Nosy Be' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Haricots' LIMIT 1), 200, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Morondava' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Eau' LIMIT 1), 1200, 1, '2026-02-15 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Farafangana' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 600, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Tôle' LIMIT 1), 120, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Mananjary' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Huile' LIMIT 1), 120, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 800, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Farafangana' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Bois' LIMIT 1), 100, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, isDefault, date_demande, statut)
VALUES ((SELECT id_ville FROM villes WHERE nom='Nosy Be' LIMIT 1), (SELECT id_produit FROM produits WHERE nom='Clous' LIMIT 1), 30, 1, '2026-02-16 00:00:00', 'EN_ATTENTE');

-- =========================
-- VUES
-- =========================

-- Vue détaillée des demandes
CREATE VIEW vue_demandes_detaillees AS
SELECT 
    d.id_demande,
    r.nom AS region,
    v.nom AS ville,
    p.nom AS produit,
    p.unite,
    d.quantite_demandee,
    d.statut,
    d.date_demande
FROM demandes d
JOIN villes v ON d.id_ville = v.id_ville
JOIN regions r ON v.id_region = r.id_region
JOIN produits p ON d.id_produit = p.id_produit;


-- Vue du stock détaillé
CREATE VIEW vue_stock_detaille AS
SELECT 
    p.nom AS produit,
    p.unite,
    s.quantite_disponible
FROM stock s
JOIN produits p ON s.id_produit = p.id_produit;


-- Vue des demandes en attente
CREATE VIEW vue_demandes_en_attente AS
SELECT *
FROM vue_demandes_detaillees
WHERE statut = 'EN_ATTENTE';


-- Vue des demandes par région
CREATE VIEW vue_demandes_par_region AS
SELECT 
    r.nom AS region,
    SUM(d.quantite_demandee) AS total_demande
FROM demandes d
JOIN villes v ON d.id_ville = v.id_ville
JOIN regions r ON v.id_region = r.id_region
GROUP BY r.nom;


-- Vue des achats par ville
CREATE VIEW vue_achats_par_ville AS
SELECT 
    v.nom AS ville,
    p.nom AS produit,
    a.quantite_achetee,
    a.montant_total,
    a.date_achat
FROM achats a
JOIN villes v ON a.id_ville = v.id_ville
JOIN produits p ON a.id_produit = p.id_produit;


-- Vue des dons en argent par ville (Dons directs + Distributions reçues de produits de type Argent)
CREATE OR REPLACE VIEW vue_dons_argent AS
-- 1. Dons directs à la ville
SELECT 
    d.id_ville,
    d.quantite AS montant,
    d.date_don
FROM dons d
JOIN produits p ON d.id_produit = p.id_produit
JOIN categories c ON p.id_categorie = c.id_categorie
WHERE (c.nom = 'Argent' OR p.nom = 'Argent') AND d.id_ville IS NOT NULL

UNION ALL

-- 2. Distributions reçues (depuis le stock global vers la ville)
SELECT 
    dem.id_ville,
    dist.quantite_envoyee AS montant,
    dist.date_distribution AS date_don
FROM distributions dist
JOIN demandes dem ON dist.id_demande = dem.id_demande
JOIN produits p ON dist.id_produit = p.id_produit
JOIN categories c ON p.id_categorie = c.id_categorie
WHERE (c.nom = 'Argent' OR p.nom = 'Argent');


INSERT INTO users (nom, email, mot_de_passe, id_role, date_creation)
VALUES ('Admin Test', 'admin@test.com', 'admin123', 2, NOW());

INSERT INTO users (nom, email, mot_de_passe, id_role, date_creation)
VALUES ('User Test', 'user@test.com', 'user123', 1, NOW());

-- DONS (par défaut)
-- NB: on associe ces dons à l'admin et à une ville (Toamasina) pour que les vues/recaps fonctionnent.
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  5000000, 1, '2026-02-16 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  3000000, 1, '2026-02-16 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  4000000, 1, '2026-02-17 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  1500000, 1, '2026-02-17 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  6000000, 1, '2026-02-17 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  400, 1, '2026-02-16 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Eau' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  600, 1, '2026-02-16 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Tôle' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  50, 1, '2026-02-17 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Bâche' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  70, 1, '2026-02-17 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Haricots' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  100, 1, '2026-02-17 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  2000, 1, '2026-02-18 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Tôle' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  300, 1, '2026-02-18 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Eau' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  5000, 1, '2026-02-18 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  2000000, 1, '2026-02-19 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Bâche' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  500, 1, '2026-02-19 00:00:00'
);
INSERT INTO dons (id_user, id_produit, id_ville, quantite, isDefault, date_don)
VALUES (
  (SELECT id_user FROM users WHERE email='admin@test.com' LIMIT 1),
  (SELECT id_produit FROM produits WHERE nom='Haricots' LIMIT 1),
  (SELECT id_ville FROM villes WHERE nom='Toamasina' LIMIT 1),
  88, 1, '2026-02-17 00:00:00'
);

INSERT INTO stock (id_produit, quantite_disponible)
SELECT p.id_produit, 0
FROM produits p;

INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 5000000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 5000000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 3000000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 3000000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 4000000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 4000000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 1500000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 1500000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 6000000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 6000000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 400)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 400;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Eau' LIMIT 1), 600)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 600;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Tôle' LIMIT 1), 50)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 50;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Bâche' LIMIT 1), 70)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 70;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Haricots' LIMIT 1), 100)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 100;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Riz' LIMIT 1), 2000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 2000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Tôle' LIMIT 1), 300)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 300;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Eau' LIMIT 1), 5000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 5000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Argent' LIMIT 1), 2000000)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 2000000;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Bâche' LIMIT 1), 500)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 500;
 
INSERT INTO stock (id_produit, quantite_disponible)
VALUES ((SELECT id_produit FROM produits WHERE nom='Haricots' LIMIT 1), 88)
ON DUPLICATE KEY UPDATE quantite_disponible = quantite_disponible + 88;