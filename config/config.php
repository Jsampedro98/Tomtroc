<?php

/**
 * Configuration générale de l'application TomTroc
 * 
 * Ce fichier contient toutes les constantes de configuration de l'application :
 * - Chemins des répertoires
 * - Paramètres de base de données
 * - Configuration de sécurité
 * - Gestion des erreurs
 * 
 * @package TomTroc
 * @author  TomTroc Team
 * @version 1.0.0
 */

// ============================================
// ENVIRONNEMENT
// ============================================

/** @var string Environnement actuel (development|production) */
define('ENV', 'development');

// ============================================
// CHEMINS
// ============================================

/** @var string Chemin racine du projet */
define('ROOT_PATH', dirname(__DIR__));

/** @var string Chemin du répertoire public */
define('PUBLIC_PATH', ROOT_PATH . '/public');

/** @var string Chemin du répertoire des vues */
define('VIEWS_PATH', ROOT_PATH . '/views');

/** @var string Chemin du répertoire d'upload */
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// ============================================
// BASE DE DONNÉES
// ============================================

/** @var string Hôte de la base de données */
define('DB_HOST', 'localhost');

/** @var string Nom de la base de données */
define('DB_NAME', 'tomtroc');

/** @var string Utilisateur de la base de données */
define('DB_USER', 'root');

/** @var string Mot de passe de la base de données */
define('DB_PASS', '');

/** @var string Charset de la base de données */
define('DB_CHARSET', 'utf8mb4');

// ============================================
// APPLICATION
// ============================================

/** @var string Nom de l'application */
define('APP_NAME', 'TomTroc');

/** @var string URL de base de l'application */
define('APP_URL', 'http://localhost/tomtroc/public');

// ============================================
// SÉCURITÉ
// ============================================

/** @var string Algorithme de hashage des mots de passe */
define('HASH_ALGO', PASSWORD_BCRYPT);

/** @var int Coût du hashage (entre 10 et 12 recommandé) */
define('HASH_COST', 12);

// Configuration des sessions sécurisées
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// En production, utiliser HTTPS uniquement
if (ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// ============================================
// GESTION DES ERREURS
// ============================================

if (ENV === 'development') {
    // Afficher toutes les erreurs en développement
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // Masquer les erreurs en production
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ============================================
// DÉMARRAGE DE LA SESSION
// ============================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
