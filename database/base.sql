CREATE DATABASE IF NOT EXISTS 4106_4132_4381;
USE 4106_4132_4381;

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
    quantite DOUBLE NOT NULL,
    date_don DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);

CREATE TABLE demandes (
    id_demande INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_produit INT NOT NULL,
    quantite_demandee DOUBLE NOT NULL,
    date_demande DATETIME,
    statut VARCHAR(20),
    FOREIGN KEY (id_ville) REFERENCES villes(id_ville),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);

CREATE TABLE distributions (
    id_distribution INT AUTO_INCREMENT PRIMARY KEY,
    id_demande INT NOT NULL,
    quantite_envoyee DOUBLE NOT NULL,
    date_distribution DATETIME,
    FOREIGN KEY (id_demande) REFERENCES demandes(id_demande)
);

-- =========================
-- INSERTS
-- =========================

-- ROLES
INSERT INTO roles (nom_role) VALUES ('USER');
INSERT INTO roles (nom_role) VALUES ('ADMIN');

-- CATEGORIES
INSERT INTO categories (nom) VALUES ('Nature');
INSERT INTO categories (nom) VALUES ('Vetement');
INSERT INTO categories (nom) VALUES ('Medical');

-- REGIONS
INSERT INTO regions (nom) VALUES ('Analamanga');
INSERT INTO regions (nom) VALUES ('Atsinanana');

-- VILLES (après les regions)
INSERT INTO villes (nom, id_region) VALUES ('Antananarivo', 1);
INSERT INTO villes (nom, id_region) VALUES ('Toamasina', 2);

-- PRODUITS (après categories)
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Riz', 'Kg', 1);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Huile', 'Litre', 1);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Couverture', 'Piece', 2);
INSERT INTO produits (nom, unite, id_categorie) VALUES ('Medicament', 'Boite', 3);

-- DEMANDES
INSERT INTO demandes (id_ville, id_produit, quantite_demandee, date_demande, statut)
VALUES (1, 1, 500, '2026-02-16 10:00:00', 'EN_ATTENTE');

INSERT INTO demandes (id_ville, id_produit, quantite_demandee, date_demande, statut)
VALUES (2, 3, 200, '2026-02-16 10:30:00', 'EN_ATTENTE');

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


INSERT INTO users (nom, email, mot_de_passe, id_role, date_creation)
VALUES ('Admin Test', 'admin@test.com', 'admin123', 2, NOW());

INSERT INTO users (nom, email, mot_de_passe, id_role, date_creation)
VALUES ('User Test', 'user@test.com', 'user123', 1, NOW());

INSERT INTO stock (id_produit, quantite_disponible)
VALUES 
(1, 1000),  
(2, 500),    
(3, 300),    
(4, 200);    
