<?php

/**
 * Classe Model - Classe abstraite de base pour tous les modèles
 * 
 * Fournit les fonctionnalités communes à tous les modèles de l'application :
 * - Connexion à la base de données via PDO
 * - Méthodes utilitaires pour la sécurité (échappement HTML)
 * 
 * Tous les modèles doivent hériter de cette classe pour bénéficier
 * de la connexion automatique à la base de données.
 * 
 * @package    TomTroc\Models
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      1.0.0
 * @abstract
 * 
 * @property PDO $db Instance PDO pour les requêtes SQL
 * 
 * @example
 * ```php
 * class User extends Model {
 *     public function findById(int $id) {
 *         $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
 *         $stmt->execute(['id' => $id]);
 *         return $stmt->fetch();
 *     }
 * }
 * ```
 */
abstract class Model
{
    /**
     * Instance PDO pour les requêtes à la base de données
     * 
     * @var PDO
     */
    protected PDO $db;

    /**
     * Constructeur - Initialise la connexion à la base de données
     * 
     * Récupère automatiquement l'instance Singleton de la connexion PDO
     * via la classe Database.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Échapper les données pour un affichage HTML sécurisé
     * 
     * Convertit les caractères spéciaux en entités HTML pour prévenir
     * les attaques XSS (Cross-Site Scripting).
     * 
     * Gère également les valeurs null en les convertissant en chaîne vide.
     * 
     * @param string|null $value Valeur à échapper
     * 
     * @return string Valeur échappée et sécurisée pour l'affichage HTML
     * 
     * @example
     * ```php
     * $safeUsername = $this->escape($user['username']);
     * echo "<p>Bienvenue " . $safeUsername . "</p>";
     * ```
     * 
     * @see htmlspecialchars()
     */
    protected function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
