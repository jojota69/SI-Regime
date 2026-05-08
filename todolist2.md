# PROJET : APPLICATION DE SÉLECTION DE RÉGIME ALIMENTAIRE

## 1. Description du Sujet
Mise en place d’une application permettant de sélectionner un régime alimentaire adapté selon des objectifs spécifiques, incluant un suivi sportif et un système de paiement intégré.

## 2. Fonctionnalités Utilisateurs
- **Authentification :** Page de connexion obligatoire au démarrage.
- **Saisie des données :** L’utilisateur entre ses informations (**genre, taille, poids**).
- **Calcul d'IMC :** Affichage automatique de l'Indice de Masse Corporelle.
- **Sélection d'Objectifs :**
    - Augmenter son poids.
    - Réduire son poids.
    - Atteindre son IMC idéal.
- **Suggestions :** Affichage du régime et de l'activité sportive nécessaire pour une durée donnée.
- **Exportation :** Génération de rapports au format **PDF**.

## 3. Système Monétaire (Porte-monnaie)
- **Recharge :** Ajout d'argent dans le porte-monnaie via la saisie d'un code.
- **Option Gold :**
    - **Mode d'accès :** Paiement unique (Proposé à : 100 000 Ar).
    - **Avantage :** Remise de **15%** sur tous les régimes.
- **Gestion des prix :** Le prix varie selon la durée du régime.

## 4. Administration et Back-Office (CRUD)
- **CRUD des régimes :** 
    - Configuration de la variation de poids par durée (en plus et en moins).
    - Composition nutritionnelle : **% de viande, % de poisson, % de volaille**.
- **CRUD des activités sportives.**
- **CRUD des paramètres nécessaires.**
- **Gestion financière :** Validation des codes pour le porte-monnaie des utilisateurs.

## 5. Tableau de Bord et Statistiques
- Visualisation des données via des **graphes**.
- Analyse via des **tableaux croisés**.