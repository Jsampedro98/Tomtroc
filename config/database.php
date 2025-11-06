<?php

/**
 * Classe Database - Gestion de la connexion à la base de données
 * 
 * Implémente le pattern Singleton pour garantir une seule instance
 * de connexion PDO dans toute l'application.
 * 
 * @package    TomTroc\Config
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      1.0.0
 * 
 * @example
 * ```php
 * $db = Database::getInstance();
 * $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
 * ```
 */
class Database
{
    /**
     * Instance unique de PDO
     * 
     * @var PDO|null
     */
    private static ?PDO $instance = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct() {}

    /**
     * Obtenir l'instance PDO unique (Singleton)
     * 
     * Crée une nouvelle connexion PDO si elle n'existe pas encore,
     * sinon retourne l'instance existante.
     * 
     * Configuration PDO :
     * - Mode d'erreur : Exceptions
     * - Fetch mode : Tableau associatif
     * - Requêtes préparées natives (pas d'émulation)
     * 
     * @return PDO Instance PDO configurée
     * @throws PDOException Si la connexion échoue
     * 
     * @example
     * ```php
     * $db = Database::getInstance();
     * $users = $db->query("SELECT * FROM users")->fetchAll();
     * ```
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                // Construction du DSN (Data Source Name)
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    DB_HOST,
                    DB_NAME,
                    DB_CHARSET
                );

                // Options PDO pour sécurité et performances
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);

            } catch (PDOException $e) {
                // En production, logger l'erreur au lieu de l'afficher
                if (ENV === 'production') {
                    error_log('Database connection error: ' . $e->getMessage());
                    die('Une erreur est survenue. Veuillez réessayer plus tard.');
                }
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * Empêcher le clonage de l'instance (Singleton)
     * 
     * @return void
     */
    private function __clone() {}

    /**
     * Empêcher la désérialisation de l'instance (Singleton)
     * 
     * @throws Exception Toujours, car la désérialisation n'est pas autorisée
     * @return void
     */
    public function __wakeup(): void
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
