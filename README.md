# Tom Troc - Plateforme d'échange de livres

Projet 4 - Formation Développeur Full-Stack - OpenClassrooms

Plateforme web permettant aux passionnés de lecture d'échanger leurs livres entre eux. Développé en PHP natif avec architecture MVC personnalisée.

## Installation rapide

### 1. Prérequis

- PHP 8.0+
- MySQL 5.7+
- Apache avec mod_rewrite

### 2. Installation

```bash
# Cloner le projet
git clone https://github.com/Jsampedro98/Tomtroc
cd tomtroc

# Créer le fichier de configuration
cp config/config.php.example config/config.php

# Éditer config/config.php avec vos identifiants de base de données
# Modifiez les valeurs : DB_HOST, DB_NAME, DB_USER, DB_PASS

# Importer la base de données
mysql -u root -p < tomtroc.sql

# Accéder à l'application
http://localhost/tomtroc/public
```

**Configuration requise**

Après avoir copié `config.php.example` vers `config/config.php`, modifiez les paramètres suivants :

```php
define('DB_HOST', 'localhost');        // Hôte MySQL
define('DB_NAME', 'tomtroc');          // Nom de la base de données
define('DB_USER', 'root');             // Utilisateur MySQL
define('DB_PASS', '');                 // Mot de passe MySQL (vide par défaut pour XAMPP)
```

### 3. Compte de test

Pour vous connecter :

- **Email** : `admin@tomtroc.com`
- **Mot de passe** : `Password123`

## Structure

```
tomtroc/
├── config/          # Configuration et routeur
├── controllers/     # Contrôleurs MVC
├── models/          # Modèles (User, Book, Message)
├── views/           # Vues (HTML/PHP)
├── public/          # Point d'entrée + assets (CSS, JS, images)
└── tomtroc.sql      # Schéma de base de données
```

## Fonctionnalités

- Inscription / Connexion
- Gestion de livres (CRUD, upload photo)
- Recherche et pagination
- Messagerie entre utilisateurs
- Profils publics et privés
- Design pixel-perfect (maquettes Figma)

## Technologies

- **Backend** : PHP 8.0+ (MVC natif, sans framework)
- **Base de données** : MySQL avec PDO
- **Frontend** : HTML5, CSS3, JavaScript vanilla
- **Sécurité** : Bcrypt, requêtes préparées, sessions sécurisées

## Auteur

**Jonathan SAMPEDRO HERRERA**
Formation OpenClassrooms - Développeur Full-Stack
Projet P4 - 2025
