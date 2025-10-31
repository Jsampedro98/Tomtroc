<?php

/**
 * Configuration générale de l'application TomTroc
 */

// Environnement (development ou production)
define('ENV', 'development');

// Configuration des chemins
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'tomtroc');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuration de l'application
define('APP_NAME', 'TomTroc');
define('APP_URL', 'http://localhost/tomtroc/public');

// Configuration de sécurité
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 12);

// Configuration des sessions
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

if (ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// Gestion des erreurs
if (ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
