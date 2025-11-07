<?php

/**
 * Point d'entrée de l'application TomTroc
 */

// Charger la configuration
require_once __DIR__ . '/../config/config.php';

// Charger l'autoloader
require_once __DIR__ . '/../config/autoload.php';

// Charger le routeur
require_once __DIR__ . '/../config/router.php';

// Créer l'instance du routeur
$router = new Router();

// Définir les routes de l'application

// Page d'accueil
$router->get('/', 'HomeController', 'index');

// Routes d'authentification
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');

// Routes de compte utilisateur
$router->get('/account', 'AccountController', 'index');
$router->post('/account/update', 'AccountController', 'update');
$router->post('/account/upload-photo', 'AccountController', 'uploadPhoto');

// Routes de profil
$router->get('/profile', 'ProfileController', 'show');
$router->get('/profile/edit', 'ProfileController', 'edit');
$router->post('/profile/update', 'ProfileController', 'update');
$router->get('/profile/{id}', 'ProfileController', 'showPublic');

// Routes des livres
// IMPORTANT : Les routes spécifiques doivent être avant les routes avec paramètres dynamiques
$router->get('/books/create', 'BookController', 'create');
$router->post('/books/store', 'BookController', 'store');
$router->get('/books', 'BookController', 'index');
$router->get('/books/{id}', 'BookController', 'show');
$router->get('/books/{id}/edit', 'BookController', 'edit');
$router->post('/books/{id}/update', 'BookController', 'update');
$router->post('/books/{id}/delete', 'BookController', 'delete');
$router->post('/books/{id}/toggle', 'BookController', 'toggleAvailability');

// Routes de messagerie
$router->get('/messages', 'MessageController', 'index');
$router->get('/messages/{id}', 'MessageController', 'show');
$router->post('/messages/send', 'MessageController', 'send');

// Exécuter le routeur
$router->run();
