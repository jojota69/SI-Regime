CREATE DATABASE IF NOT EXISTS regime;
USE regime;

SET FOREIGN_KEY_CHECKS = 0;

-- Nettoyage complet des tables
DROP TABLE IF EXISTS achats_gold;
DROP TABLE IF EXISTS parametres;
DROP TABLE IF EXISTS souscriptions;
DROP TABLE IF EXISTS codes_portefeuille;
DROP TABLE IF EXISTS regime_activites;
DROP TABLE IF EXISTS activites_sportives;
DROP TABLE IF EXISTS prix_regimes;
DROP TABLE IF EXISTS regimes;
DROP TABLE IF EXISTS user_objectifs;
DROP TABLE IF EXISTS objectifs;
DROP TABLE IF EXISTS historique_poids;
DROP TABLE IF EXISTS profils_sante;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. UTILISATEURS [cite: 23, 59]
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

-- 2. PROFILS SANTÉ [cite: 18, 24]
CREATE TABLE profils_sante (
    user_id INT PRIMARY KEY,
    taille_cm DECIMAL(5,2) NOT NULL,
    poids_actuel_kg DECIMAL(5,2) NOT NULL,
    imc_actuel DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. HISTORIQUE (Pour export PDF) [cite: 31]
CREATE TABLE historique_poids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    poids_kg DECIMAL(5,2) NOT NULL,
    date_mesure DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. OBJECTIFS [cite: 26, 27, 28, 29]
CREATE TABLE objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL 
) ENGINE=InnoDB;

INSERT INTO objectifs (nom) VALUES ('Augmenter son poids'), ('Réduire son poids'), ('Atteindre son IMC idéal');

CREATE TABLE user_objectifs (
    user_id INT PRIMARY KEY,
    objectif_id INT NOT NULL,
    poids_cible_kg DECIMAL(5,2),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id)
) ENGINE=InnoDB;

-- 5. RÉGIMES [cite: 44, 51, 61]
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    pct_viande DECIMAL(5,2) NOT NULL,
    pct_poisson DECIMAL(5,2) NOT NULL,
    pct_volaille DECIMAL(5,2) NOT NULL,
    variation_poids_kg DECIMAL(5,2) NOT NULL, 
    duree_standard_jours INT NOT NULL,
    objectif_id INT NOT NULL,
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id)
) ENGINE=InnoDB;

-- 6. PRIX DES RÉGIMES (Variation selon durée) 
CREATE TABLE prix_regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    regime_id INT NOT NULL,
    duree_jours INT NOT NULL,
    prix_ariary DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. ACTIVITÉS SPORTIVES [cite: 30, 45, 62]
CREATE TABLE activites_sportives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    objectif_id INT NULL,
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id)
) ENGINE=InnoDB;

CREATE TABLE regime_activites (
    regime_id INT NOT NULL,
    activite_id INT NOT NULL,
    PRIMARY KEY (regime_id, activite_id),
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites_sportives(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. PORTE-MONNAIE [cite: 32, 46, 60]
CREATE TABLE codes_portefeuille (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    montant DECIMAL(10,2) NOT NULL,
    est_utilise TINYINT(1) DEFAULT 0,
    est_valide TINYINT(1) DEFAULT 0,
    user_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 9. OPTION GOLD ET PARAMÈTRES [cite: 36, 46]
CREATE TABLE achats_gold (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    montant_paye DECIMAL(10,2) NOT NULL,
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE parametres (
    cle VARCHAR(50) PRIMARY KEY,
    valeur VARCHAR(100) NOT NULL,
    description VARCHAR(255)
) ENGINE=InnoDB;

-- 10. SOUSCRIPTIONS
CREATE TABLE souscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    montant_paye DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (regime_id) REFERENCES regimes(id)
) ENGINE=InnoDB;

-- --- INSERTIONS DE DONNÉES ---

-- Paramètres [cite: 36, 46]
INSERT INTO parametres (cle, valeur, description) VALUES 
('prix_gold', '20000', 'Prix unique pour l''option Gold'),
('remise_gold', '0.15', 'Taux de remise de 15% pour les membres Gold');

-- Utilisateurs (5 minimum) [cite: 59]
INSERT INTO users (nom, prenom, email, mot_de_passe, genre, role) VALUES 
('Admin', 'System', 'admin@regime.com', 'admin123', 'homme', 'admin'),
('Rakoto', 'Jean', 'jean@mail.com', 'user123', 'homme', 'user'),
('Rabe', 'Alice', 'alice@mail.com', 'user123', 'femme', 'user'),
('Rasoa', 'Liva', 'liva@mail.com', 'user123', 'femme', 'user'),
('Randria', 'Marc', 'marc@mail.com', 'user123', 'homme', 'user');

-- Codes Portefeuille (15 minimum) [cite: 60]
INSERT INTO codes_portefeuille (code, montant) VALUES 
('ABC1', 5000), ('ABC2', 5000), ('ABC3', 5000), ('ABC4', 10000), ('ABC5', 10000),
('ABC6', 10000), ('ABC7', 20000), ('ABC8', 20000), ('ABC9', 20000), ('ABC10', 50000),
('ABC11', 50000), ('ABC12', 50000), ('ABC13', 100000), ('ABC14', 100000), ('ABC15', 100000);

-- Régimes (5 minimum) [cite: 51, 61]
INSERT INTO regimes (nom, pct_viande, pct_poisson, pct_volaille, variation_poids_kg, duree_standard_jours, objectif_id) VALUES 
('Minceur Océan', 10, 70, 20, -2.5, 7, 2),
('Bulk Protéine', 60, 10, 30, 3.0, 10, 1),
('Équilibre Vital', 20, 30, 50, 0.5, 7, 3),
('Déficit Express', 20, 40, 40, -1.5, 5, 2),
('Puissance Viande', 70, 0, 30, 4.0, 14, 1);

-- Prix des régimes (Variation par durée) 
INSERT INTO prix_regimes (regime_id, duree_jours, prix_ariary) VALUES 
(1, 7, 30000), (1, 14, 55000), (1, 28, 100000),
(2, 7, 40000), (2, 14, 75000), (2, 28, 140000),
(3, 7, 25000), (3, 14, 45000), (3, 28, 85000),
(4, 7, 35000), (4, 14, 65000), (4, 28, 120000),
(5, 7, 50000), (5, 14, 95000), (5, 28, 180000);

-- Activités Sportives (5 minimum) [cite: 62]
INSERT INTO activites_sportives (nom, objectif_id) VALUES 
('Natation', 2), ('Musculation', 1), ('Course à pied', 2), ('Yoga', 3), ('Cyclisme', 2);

-- Association Régime / Sport [cite: 30]
INSERT INTO regime_activites (regime_id, activite_id) VALUES 
(1, 1), (1, 3), (2, 2), (3, 4), (4, 3), (4, 5), (5, 2);