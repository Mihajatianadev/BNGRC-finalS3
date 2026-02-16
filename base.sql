-- Création de la base (avec un underscore pour éviter les problèmes)
CREATE DATABASE `echange4106_4132`;
USE `echange4106_4132`;

-- Table des objets
CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_categorie INT NOT NULL,
    detail VARCHAR(255),
    prix_estimatif DECIMAL(12,2) NOT NULL
);

-- Table des images
CREATE TABLE image (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_obj INT NOT NULL,
    lien_image VARCHAR(100) NOT NULL,
    
    CONSTRAINT fk_image_obj
        FOREIGN KEY (id_obj)
        REFERENCES objet(id_objet)
        ON DELETE CASCADE
);

-- Table des échanges
CREATE TABLE echange (
    id_echange INT AUTO_INCREMENT PRIMARY KEY,
    id_obj1 INT NOT NULL,
    id_obj2 INT NOT NULL,
    status VARCHAR(50) DEFAULT 'EN_ATTENTE',
    
    CONSTRAINT fk_echange_obj1
        FOREIGN KEY (id_obj1)
        REFERENCES objet(id_objet)
        ON DELETE CASCADE,
        
    CONSTRAINT fk_echange_obj2
        FOREIGN KEY (id_obj2)
        REFERENCES objet(id_objet)
        ON DELETE CASCADE
);

-- Insertion des objets
INSERT INTO objet (id_user, id_categorie, detail, prix_estimatif)
VALUES 
(1, 1, 'Téléphone Samsung A12', 450000);
(2, 2, 'Ordinateur HP i5', 1200000),
(3, 1, 'Vélo tout terrain', 600000),
(4, 3, 'Montre connectée', 300000);

-- Insertion des images
INSERT INTO image (id_obj, lien_image)
VALUES 
(1, '1.jpeg'),
(1, '2.jpeg'),
(2, '3.jpeg'),
(3, '4.jpeg'),
(3, '5.jpeg'),
(4, '6.jpeg');
