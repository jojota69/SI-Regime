CREATE DATABASE IF NOT EXISTS regime;
USE regime;

SET FOREIGN_KEY_CHECKS = 0;

-- Suppression des tables pour remise à zéro propre
DROP TABLE IF EXISTS historique_poids;
DROP TABLE IF EXISTS codes_portefeuille;
DROP TABLE IF EXISTS souscriptions;
DROP TABLE IF EXISTS regime_activites;
DROP TABLE IF EXISTS activites_sportives;
DROP TABLE IF EXISTS prix_regimes;
DROP TABLE IF EXISTS regimes;
DROP TABLE IF EXISTS user_objectifs;
DROP TABLE IF EXISTS objectifs;
DROP TABLE IF EXISTS profils_sante;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. UTILISATEURS (Page 1 de l'inscription + Auth)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    genre ENUM('homme', 'femme') NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    est_gold TINYINT(1) DEFAULT 0,
    solde_ariary DECIMAL(12,2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. PROFILS SANTÉ (Page 2 de l'inscription)
CREATE TABLE profils_sante (
    user_id INT PRIMARY KEY,
    taille_cm DECIMAL(5,2) NOT NULL,
    poids_actuel_kg DECIMAL(5,2) NOT NULL,
    imc_actuel DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. HISTORIQUE (Pour suivi IMC et export PDF)
CREATE TABLE historique_poids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    poids_kg DECIMAL(5,2) NOT NULL,
    date_mesure DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. OBJECTIFS (Les 3 choix possibles)
CREATE TABLE objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL -- 'Augmenter poids', 'Réduire poids', 'IMC idéal'
) ENGINE=InnoDB;

INSERT INTO objectifs (nom) VALUES ('Augmenter son poids'), ('Réduire son poids'), ('Atteindre son IMC idéal');

-- Lien Utilisateur <-> Objectif choisi
CREATE TABLE user_objectifs (
    user_id INT PRIMARY KEY,
    objectif_id INT NOT NULL,
    poids_cible_kg DECIMAL(5,2),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id)
) ENGINE=InnoDB;

-- 5. RÉGIMES (Back Office CRUD)
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    pct_viande DECIMAL(5,2) NOT NULL,
    pct_poisson DECIMAL(5,2) NOT NULL,
    pct_volaille DECIMAL(5,2) NOT NULL,
    variation_poids_kg DECIMAL(5,2) NOT NULL, -- Poids gagné ou perdu par le régime
    objectif_id INT NOT NULL, -- Pour la suggestion automatique
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id)
) ENGINE=InnoDB;

-- 6. PRIX DES RÉGIMES (Variant selon la durée)
CREATE TABLE prix_regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    regime_id INT NOT NULL,
    duree_jours INT NOT NULL,
    prix_ariary DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. ACTIVITÉS SPORTIVES (Back Office CRUD)
CREATE TABLE activites_sportives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    intensite VARCHAR(50) -- calories/h ou niveau
) ENGINE=InnoDB;

-- Association Régime <-> Sport
CREATE TABLE regime_activites (
    regime_id INT NOT NULL,
    activite_id INT NOT NULL,
    PRIMARY KEY (regime_id, activite_id),
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites_sportives(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. PORTE-MONNAIE (Codes à rentrer)
CREATE TABLE codes_portefeuille (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    montant DECIMAL(10,2) NOT NULL,
    est_utilise TINYINT(1) DEFAULT 0,
    est_valide TINYINT(1) DEFAULT 0, -- Validation par l'admin
    user_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 9. SOUSCRIPTIONS (Achats de régimes)
CREATE TABLE souscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    montant_paye DECIMAL(10,2) NOT NULL, -- Prix après remise Gold éventuelle
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (regime_id) REFERENCES regimes(id)
) ENGINE=InnoDB;

-- DONNÉES DE TESTS (Requirements minimum)
-- 5 Users
INSERT INTO users (nom, prenom, email, mot_de_passe, genre, role) VALUES 
('Admin', 'S4', 'admin@app.com', 'admin123', 'homme', 'admin'),
('Rasoa', 'Jean', 'jean@gmail.com', '123456', 'homme', 'user'),
('Rabe', 'Marie', 'marie@gmail.com', '123456', 'femme', 'user'),
('Randria', 'Luc', 'luc@gmail.com', '123456', 'homme', 'user'),
('Rakoto', 'Lova', 'lova@gmail.com', '123456', 'femme', 'user');

-- 15 Codes
INSERT INTO codes_portefeuille (code, montant) VALUES 
('ABC1', 5000), ('ABC2', 5000), ('ABC3', 5000), ('ABC4', 10000), ('ABC5', 10000),
('ABC6', 10000), ('ABC7', 20000), ('ABC8', 20000), ('ABC9', 20000), ('ABC10', 50000),
('ABC11', 50000), ('ABC12', 50000), ('ABC13', 100000), ('ABC14', 100000), ('ABC15', 100000);

-- 5 Régimes
INSERT INTO regimes (nom, pct_viande, pct_poisson, pct_volaille, variation_poids_kg, objectif_id) VALUES 
('Minceur Poisson', 10, 70, 20, -3.5, 2),
('Prise de Masse Viande', 60, 10, 30, 4.0, 1),
('Équilibre Volaille', 20, 20, 60, 0.5, 3),
('Mixte Perte', 30, 40, 30, -2.0, 2),
('Force Protéine', 45, 20, 35, 2.5, 1);

-- 5 Sports
INSERT INTO activites_sportives (nom) VALUES ('Natation'), ('Course à pied'), ('Musculation'), ('Yoga'), ('Vélo');