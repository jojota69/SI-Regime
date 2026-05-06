

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ============================================================
-- SUPPRESSION DES TABLES
-- ============================================================
DROP TABLE IF EXISTS transactions_portefeuille;
DROP TABLE IF EXISTS codes_portefeuille;
DROP TABLE IF EXISTS souscriptions;
DROP TABLE IF EXISTS regime_activites;
DROP TABLE IF EXISTS activites_sportives;
DROP TABLE IF EXISTS prix_regimes;
DROP TABLE IF EXISTS regimes;
DROP TABLE IF EXISTS user_objectifs;
DROP TABLE IF EXISTS objectifs;
DROP TABLE IF EXISTS profils_sante;
DROP TABLE IF EXISTS parametres;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- TABLE : users
-- ============================================================
CREATE TABLE users (
    id                  INT           AUTO_INCREMENT PRIMARY KEY,
    nom                 VARCHAR(100)  NOT NULL,
    prenom              VARCHAR(100)  NOT NULL,
    email               VARCHAR(150)  NOT NULL UNIQUE,
    mot_de_passe        VARCHAR(255)  NOT NULL,
    genre               ENUM('homme','femme','autre') NOT NULL,
    date_naissance      DATE          NULL,
    role                ENUM('user','admin') NOT NULL DEFAULT 'user',
    option_gold         TINYINT(1)    NOT NULL DEFAULT 0,
    date_gold           DATETIME      NULL,
    solde_portefeuille  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    actif               TINYINT(1)    NOT NULL DEFAULT 1,
    created_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : profils_sante
-- (2e page inscription - IMC calculé côté PHP)
-- ============================================================
CREATE TABLE profils_sante (
    id          INT           AUTO_INCREMENT PRIMARY KEY,
    user_id     INT           NOT NULL UNIQUE,
    taille_cm   DECIMAL(5,2)  NOT NULL,
    poids_kg    DECIMAL(5,2)  NOT NULL,
    imc         DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : objectifs
-- ============================================================
CREATE TABLE objectifs (
    id      INT          AUTO_INCREMENT PRIMARY KEY,
    code    VARCHAR(50)  NOT NULL UNIQUE,
    libelle VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : user_objectifs
-- (jusqu'à 3 objectifs par utilisateur)
-- ============================================================
CREATE TABLE user_objectifs (
    id          INT      AUTO_INCREMENT PRIMARY KEY,
    user_id     INT      NOT NULL,
    objectif_id INT      NOT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_objectif (user_id, objectif_id),
    FOREIGN KEY (user_id)     REFERENCES users(id)     ON DELETE CASCADE,
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : regimes
-- ============================================================
CREATE TABLE regimes (
    id                  INT           AUTO_INCREMENT PRIMARY KEY,
    nom                 VARCHAR(150)  NOT NULL,
    description         TEXT          NULL,
    pct_viande          DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
    pct_poisson         DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
    pct_volaille        DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
    variation_poids_min DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
    variation_poids_max DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
    objectif_cible      ENUM('augmenter_poids','reduire_poids','imc_ideal','tous') NOT NULL DEFAULT 'tous',
    actif               TINYINT(1)    NOT NULL DEFAULT 1,
    created_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : prix_regimes
-- (prix variant selon la durée - prix_gold = prix_normal * 0.85)
-- ============================================================
CREATE TABLE prix_regimes (
    id          INT           AUTO_INCREMENT PRIMARY KEY,
    regime_id   INT           NOT NULL,
    duree_jours INT           NOT NULL,
    prix_normal DECIMAL(10,2) NOT NULL,
    prix_gold   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_regime_duree (regime_id, duree_jours),
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : activites_sportives
-- ============================================================
CREATE TABLE activites_sportives (
    id                 INT           AUTO_INCREMENT PRIMARY KEY,
    nom                VARCHAR(150)  NOT NULL,
    description        TEXT          NULL,
    calories_par_heure INT           NOT NULL DEFAULT 0,
    intensite          ENUM('faible','modere','eleve','tres_eleve') NOT NULL DEFAULT 'modere',
    materiel_requis    VARCHAR(255)  NULL,
    actif              TINYINT(1)    NOT NULL DEFAULT 1,
    created_at         DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : regime_activites
-- ============================================================
CREATE TABLE regime_activites (
    id                INT      AUTO_INCREMENT PRIMARY KEY,
    regime_id         INT      NOT NULL,
    activite_id       INT      NOT NULL,
    frequence_semaine INT      NOT NULL DEFAULT 3,
    duree_minutes     INT      NOT NULL DEFAULT 45,
    ordre             INT      NOT NULL DEFAULT 1,
    UNIQUE KEY uq_regime_activite (regime_id, activite_id),
    FOREIGN KEY (regime_id)   REFERENCES regimes(id)             ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites_sportives(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : codes_portefeuille
-- ============================================================
CREATE TABLE codes_portefeuille (
    id         INT           AUTO_INCREMENT PRIMARY KEY,
    code       VARCHAR(50)   NOT NULL UNIQUE,
    montant    DECIMAL(10,2) NOT NULL,
    utilise    TINYINT(1)    NOT NULL DEFAULT 0,
    user_id    INT           NULL,
    created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    utilise_le DATETIME      NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : souscriptions
-- ============================================================
CREATE TABLE souscriptions (
    id             INT           AUTO_INCREMENT PRIMARY KEY,
    user_id        INT           NOT NULL,
    regime_id      INT           NOT NULL,
    prix_regime_id INT           NOT NULL,
    date_debut     DATE          NOT NULL,
    date_fin       DATE          NOT NULL,
    montant_paye   DECIMAL(10,2) NOT NULL,
    gold_applique  TINYINT(1)    NOT NULL DEFAULT 0,
    statut         ENUM('actif','termine','annule') NOT NULL DEFAULT 'actif',
    created_at     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)        REFERENCES users(id)        ON DELETE CASCADE,
    FOREIGN KEY (regime_id)      REFERENCES regimes(id),
    FOREIGN KEY (prix_regime_id) REFERENCES prix_regimes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : transactions_portefeuille
-- ============================================================
CREATE TABLE transactions_portefeuille (
    id          INT           AUTO_INCREMENT PRIMARY KEY,
    user_id     INT           NOT NULL,
    type        ENUM('credit_code','debit_achat','credit_gold') NOT NULL,
    montant     DECIMAL(10,2) NOT NULL,
    reference   VARCHAR(100)  NULL,
    solde_apres DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : parametres
-- ============================================================
CREATE TABLE parametres (
    id          INT          AUTO_INCREMENT PRIMARY KEY,
    cle         VARCHAR(100) NOT NULL UNIQUE,
    valeur      VARCHAR(255) NOT NULL,
    description VARCHAR(255) NULL,
    updated_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- ============================================================
--  DONNEES MINIMALES
-- ============================================================
-- ============================================================

-- ------------------------------------------------------------
-- Objectifs (3 fixes)
-- ------------------------------------------------------------
INSERT INTO objectifs (code, libelle) VALUES
('augmenter_poids', 'Augmenter son poids'),
('reduire_poids',   'Reduire son poids'),
('imc_ideal',       'Atteindre son IMC ideal');

-- ------------------------------------------------------------
-- Parametres systeme
-- ------------------------------------------------------------
INSERT INTO parametres (cle, valeur, description) VALUES
('prix_option_gold',   '29990',         'Prix unique option Gold en Ariary'),
('imc_normal_min',     '18.5',          'IMC minimum normal'),
('imc_normal_max',     '24.9',          'IMC maximum normal'),
('remise_gold_pct',    '15',            'Pourcentage remise Gold'),
('durees_disponibles', '7,14,30,60,90', 'Durees disponibles en jours');

-- ------------------------------------------------------------
-- 5 Utilisateurs (1 admin + 4 users dont 2 Gold)
-- Mot de passe en clair
-- ------------------------------------------------------------
INSERT INTO users (nom, prenom, email, mot_de_passe, genre, date_naissance, role, option_gold, date_gold, solde_portefeuille) VALUES
('Rakoto',    'Jean',   'admin@app.mg',            'Admin1234',  'homme', '1985-03-15', 'admin', 0, NULL,                  0.00),
('Rasoa',     'Marie',  'marie.rasoa@gmail.com',   'Marie1234',  'femme', '1995-07-22', 'user',  1, '2025-04-01 08:00:00', 25000.00),
('Rabe',      'Paul',   'paul.rabe@gmail.com',     'Paul1234',   'homme', '1990-11-08', 'user',  0, NULL,                  10000.00),
('Randria',   'Sophie', 'sophie.randria@yahoo.fr', 'Sophie1234', 'femme', '2000-04-30', 'user',  0, NULL,                   5000.00),
('Ravalison', 'Luc',    'luc.ravalison@gmail.com', 'Luc1234',    'homme', '1988-09-14', 'user',  1, '2025-03-15 10:00:00', 50000.00);

-- ------------------------------------------------------------
-- Profils de sante
-- IMC = poids / (taille_m)^2
-- Jean   : 78  / 1.75^2 = 25.47
-- Marie  : 45  / 1.62^2 = 17.15  (sous-poids)
-- Paul   : 95  / 1.80^2 = 29.32  (surpoids)
-- Sophie : 55  / 1.58^2 = 22.04  (normal)
-- Luc    : 110 / 1.72^2 = 37.19  (obesite)
-- ------------------------------------------------------------
INSERT INTO profils_sante (user_id, taille_cm, poids_kg, imc) VALUES
(1, 175.00,  78.00, 25.47),
(2, 162.00,  45.00, 17.15),
(3, 180.00,  95.00, 29.32),
(4, 158.00,  55.00, 22.04),
(5, 172.00, 110.00, 37.19);

-- ------------------------------------------------------------
-- Objectifs choisis par les utilisateurs
-- ------------------------------------------------------------
INSERT INTO user_objectifs (user_id, objectif_id) VALUES
(2, 1),
(3, 2),
(3, 3),
(4, 3),
(5, 2);

-- ------------------------------------------------------------
-- 5 Regimes alimentaires
-- Regle : pct_viande + pct_poisson + pct_volaille = 100
-- variation_poids : en kg sur la duree du programme
--   valeur negative = perte de poids
--   valeur positive = prise de poids
-- ------------------------------------------------------------
INSERT INTO regimes (nom, description, pct_viande, pct_poisson, pct_volaille, variation_poids_min, variation_poids_max, objectif_cible) VALUES
(
    'Prise de masse',
    'Programme riche en proteines pour favoriser le gain de poids et la masse musculaire. Ideal pour les personnes en sous-poids.',
    40.00, 20.00, 40.00,
    2.00, 5.00,
    'augmenter_poids'
),
(
    'Minceur equilibre',
    'Programme hypocalorique equilibre pour une perte de poids progressive et durable. Riche en poisson et pauvre en graisses.',
    20.00, 50.00, 30.00,
    -3.00, -1.00,
    'reduire_poids'
),
(
    'Mediterraneen',
    'Inspire du regime mediterraneen, riche en poissons gras et volailles maigres. Excellent pour atteindre un IMC ideal.',
    15.00, 45.00, 40.00,
    -2.00, 1.00,
    'imc_ideal'
),
(
    'Detox poisson',
    'Programme detoxifiant a base de poissons blancs et volailles legeres. Favorise une perte de poids rapide sur courte duree.',
    10.00, 65.00, 25.00,
    -5.00, -2.00,
    'reduire_poids'
),
(
    'Hyperproteine',
    'Programme a haute teneur en proteines pour sportifs et personnes actives. Combinaison viande rouge, volaille et poisson.',
    35.00, 25.00, 40.00,
    -1.00, 3.00,
    'tous'
);

-- ------------------------------------------------------------
-- Prix des regimes par duree
-- prix_gold = ROUND(prix_normal * 0.85, 2)
-- ------------------------------------------------------------
INSERT INTO prix_regimes (regime_id, duree_jours, prix_normal, prix_gold) VALUES
-- Regime 1 : Prise de masse
(1,  7,  15000.00,  12750.00),
(1, 14,  28000.00,  23800.00),
(1, 30,  55000.00,  46750.00),
(1, 60,  98000.00,  83300.00),
(1, 90, 135000.00, 114750.00),
-- Regime 2 : Minceur equilibre
(2,  7,  12000.00,  10200.00),
(2, 14,  22000.00,  18700.00),
(2, 30,  42000.00,  35700.00),
(2, 60,  75000.00,  63750.00),
(2, 90, 105000.00,  89250.00),
-- Regime 3 : Mediterraneen
(3,  7,  14000.00,  11900.00),
(3, 14,  26000.00,  22100.00),
(3, 30,  50000.00,  42500.00),
(3, 60,  90000.00,  76500.00),
(3, 90, 125000.00, 106250.00),
-- Regime 4 : Detox poisson
(4,  7,  10000.00,   8500.00),
(4, 14,  18000.00,  15300.00),
(4, 30,  35000.00,  29750.00),
-- Regime 5 : Hyperproteine
(5,  7,  18000.00,  15300.00),
(5, 14,  33000.00,  28050.00),
(5, 30,  62000.00,  52700.00),
(5, 60, 110000.00,  93500.00),
(5, 90, 155000.00, 131750.00);

-- ------------------------------------------------------------
-- 5 Activites sportives
-- ------------------------------------------------------------
INSERT INTO activites_sportives (nom, description, calories_par_heure, intensite, materiel_requis) VALUES
(
    'Course a pied',
    'Jogging ou course en exterieur ou sur tapis. Excellente activite cardio pour bruler des calories et ameliorer l endurance.',
    600, 'eleve', 'Chaussures de running'
),
(
    'Natation',
    'Nage libre en piscine ou en mer. Activite complete sollicitant tous les groupes musculaires. Ideale pour les personnes avec des problemes articulaires.',
    500, 'modere', 'Maillot de bain, lunettes de natation'
),
(
    'Musculation',
    'Entrainement avec poids et machines en salle de sport. Parfait pour le gain de masse musculaire et le renforcement corporel.',
    400, 'eleve', 'Acces salle de sport ou halteres'
),
(
    'Yoga',
    'Pratique du yoga pour la flexibilite, l equilibre et la relaxation. Adapte a tous les niveaux, excellent pour reduire le stress.',
    200, 'faible', 'Tapis de yoga'
),
(
    'Velo',
    'Velo en exterieur ou velo stationnaire en interieur. Activite cardio a faible impact articulaire, ideale pour la perte de poids.',
    550, 'modere', 'Velo ou velo stationnaire, casque'
);

-- ------------------------------------------------------------
-- Association regimes <-> activites sportives
-- ------------------------------------------------------------
INSERT INTO regime_activites (regime_id, activite_id, frequence_semaine, duree_minutes, ordre) VALUES
-- Prise de masse
(1, 3, 4, 60, 1),
(1, 1, 2, 30, 2),
-- Minceur equilibre
(2, 1, 3, 45, 1),
(2, 5, 2, 45, 2),
(2, 4, 1, 60, 3),
-- Mediterraneen
(3, 2, 3, 45, 1),
(3, 4, 2, 45, 2),
-- Detox poisson
(4, 2, 4, 30, 1),
(4, 4, 3, 30, 2),
-- Hyperproteine
(5, 3, 5, 75, 1),
(5, 1, 3, 40, 2),
(5, 2, 1, 60, 3);

-- ------------------------------------------------------------
-- 15 Codes portefeuille
-- 13 disponibles, 2 deja utilises (Marie et Paul)
-- ------------------------------------------------------------
INSERT INTO codes_portefeuille (code, montant, utilise, user_id, utilise_le) VALUES
('REG-2025-ALFA-0001',   5000.00, 0, NULL, NULL),
('REG-2025-ALFA-0002',   5000.00, 0, NULL, NULL),
('REG-2025-BETA-0001',  10000.00, 0, NULL, NULL),
('REG-2025-BETA-0002',  10000.00, 0, NULL, NULL),
('REG-2025-BETA-0003',  10000.00, 0, NULL, NULL),
('REG-2025-GAMM-0001',  20000.00, 1, 2, '2025-04-10 10:00:00'),
('REG-2025-GAMM-0002',  20000.00, 0, NULL, NULL),
('REG-2025-GAMM-0003',  20000.00, 0, NULL, NULL),
('REG-2025-DELT-0001',  50000.00, 0, NULL, NULL),
('REG-2025-DELT-0002',  50000.00, 0, NULL, NULL),
('REG-2025-DELT-0003',  50000.00, 0, NULL, NULL),
('REG-2025-EPSL-0001', 100000.00, 0, NULL, NULL),
('REG-2025-EPSL-0002', 100000.00, 0, NULL, NULL),
('REG-2025-ZETA-0001',  30000.00, 0, NULL, NULL),
('REG-2025-ZETA-0002',  15000.00, 1, 3, '2025-04-15 14:30:00');

-- ------------------------------------------------------------
-- Souscriptions
-- ------------------------------------------------------------
-- Marie (Gold) => Prise de masse 30j => prix_gold (id=3)
INSERT INTO souscriptions (user_id, regime_id, prix_regime_id, date_debut, date_fin, montant_paye, gold_applique, statut)
VALUES (2, 1, 3, '2025-04-20', '2025-05-20', 46750.00, 1, 'actif');

-- Paul => Minceur equilibre 14j => prix normal (id=7), termine
INSERT INTO souscriptions (user_id, regime_id, prix_regime_id, date_debut, date_fin, montant_paye, gold_applique, statut)
VALUES (3, 2, 7, '2025-03-01', '2025-03-15', 22000.00, 0, 'termine');

-- Luc (Gold) => Hyperproteine 60j => prix_gold (id=22)
INSERT INTO souscriptions (user_id, regime_id, prix_regime_id, date_debut, date_fin, montant_paye, gold_applique, statut)
VALUES (5, 5, 22, '2025-04-25', '2025-06-24', 93500.00, 1, 'actif');

-- ------------------------------------------------------------
-- Transactions portefeuille
-- ------------------------------------------------------------
INSERT INTO transactions_portefeuille (user_id, type, montant, reference, solde_apres) VALUES
(2, 'credit_code',  20000.00, 'REG-2025-GAMM-0001',  20000.00),
(2, 'debit_achat', -46750.00, 'SOUSCRIPTION-1',      -26750.00),
(3, 'credit_code',  15000.00, 'REG-2025-ZETA-0002',   15000.00),
(3, 'debit_achat', -22000.00, 'SOUSCRIPTION-2',       -7000.00),
(5, 'debit_achat', -93500.00, 'SOUSCRIPTION-3',      -43500.00);

-- ============================================================
-- INDEX
-- ============================================================
CREATE INDEX idx_users_role           ON users(role);
CREATE INDEX idx_profils_user         ON profils_sante(user_id);
CREATE INDEX idx_souscriptions_user   ON souscriptions(user_id);
CREATE INDEX idx_souscriptions_statut ON souscriptions(statut);
CREATE INDEX idx_codes_utilise        ON codes_portefeuille(utilise);
CREATE INDEX idx_transactions_user    ON transactions_portefeuille(user_id);
CREATE INDEX idx_regimes_objectif     ON regimes(objectif_cible);

-- ============================================================
-- FIN DU SCRIPT
-- ============================================================