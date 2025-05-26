# 🚗 EcoRide – Plateforme de Covoiturage Responsable

EcoRide est une plateforme web innovante conçue pour faciliter la gestion et la réservation de trajets en covoiturage, tout en réduisant l’impact environnemental des déplacements quotidiens.

## 🌍 Objectifs du projet

- Promouvoir l’écomobilité grâce au covoiturage
- Permettre une gestion fluide des utilisateurs, trajets, véhicules et réservations
- Offrir un espace professionnel sécurisé pour les employés et administrateurs

---

## 🧰 Technologies utilisées

| Technologie    | Utilisation                                     |
| -------------- | ----------------------------------------------- |
| **Symfony**    | Framework principal (v5.10.4), architecture MVC |
| **PHP 8.2**    | Langage backend principal                       |
| **MySQL**      | Base de données relationnelle (structure)       |
| **MongoDB**    | Statistiques en temps réel (non relationnelle)  |
| **HTML / CSS** | Structure et style de l’interface               |
| **Bootstrap**  | Design responsive                               |
| **JavaScript** | Interactivité (formulaires, modales, etc.)      |
| **Chart.js**   | Graphiques dynamiques dans l’espace admin       |
| **XAMPP**      | Environnement local de développement            |
| **Composer**   | Gestion des dépendances Symfony                 |

---

## ⚙️ Fonctionnement de l'application

### 🗃️ Base de données

- **MySQL** : gestion des utilisateurs, véhicules, réservations, rôles (relationnel)
- **MongoDB** : stockage des statistiques d’utilisation (non relationnel)

### 🔐 Bundles installés via Composer

| Bundle              | Utilité                                      |
| ------------------- | -------------------------------------------- |
| security-bundle     | Authentification, gestion des rôles          |
| maker-bundle        | Génération de code (contrôleurs, entités...) |
| form + validator    | Formulaires + validation                     |
| doctrine + orm-pack | ORM pour MySQL                               |
| twig-bundle         | Moteur de templates                          |
| mongodb/mongodb     | Intégration de MongoDB                       |

---

## 🧭 Espaces de la plateforme

### 👤 Espace visiteur

- Présentation de l’entreprise
- Formulaire de contact
- Recherche d’itinéraires disponibles

### 🧑‍💼 Espace utilisateur

- Inscription / Connexion
- Réservation de covoiturage
- Suivi des trajets et historique
- Suppression de réservation, notation du trajet

### 🧑‍🔧 Espace employé

- Validation ou refus des avis utilisateurs
- Consultation des covoiturages signalés (incidents)
- Gestion des messages de contact

### 👨‍💼 Espace administrateur

- Création et gestion des comptes employés
- Suspension / réactivation des utilisateurs
- Statistiques dynamiques via MongoDB :
  - Nombre de covoiturages terminés
  - Crédits gagnés par jour
- Affichage des statistiques avec Chart.js

---

## 🚀 Déploiement local (XAMPP / VS Code)

```bash
# Cloner le projet
git clone https://github.com/ovis02/EcoRide.git
cd ecoride

# Installer les dépendances
composer install

# Configurer la base de données dans .env.local
DATABASE_URL="mysql://root:root@127.0.0.1:3306/ecoride"
MONGODB_URL="mongodb://localhost:27017"

# Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

# Lancer le serveur
symfony server:start

```

# 🚀 Lien vers le projet en ligne

👉 [Accéder à l'application déployée sur Heroku](https://ecoride02-edbedfe97bbc.herokuapp.com/)

---

## 🔓 Identifiants de test

| Rôle               | Email               | Mot de passe |
| ------------------ | ------------------- | ------------ |
| Admin              | admin@ecoride.com   | admin123     |
| Employé            | employe@ecoride.com | employe123   |
| chauffeur+passager | alice@example.com   | password1    |
| passager           | bob@example.com     | password2    |

---

## ✍️ Auteur

Ce projet a été réalisé par **Mohammad Aowis** dans le cadre d’une validation de compétences (ECF) pour démontrer la maîtrise de Symfony, MySQL, MongoDB, la gestion d’utilisateurs, la sécurité et l’intégration frontend/backend.
