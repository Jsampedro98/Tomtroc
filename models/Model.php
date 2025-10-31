<?php

/**
 * Classe Model - Classe de base pour tous les modèles
 */
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Échapper les données pour l'affichage HTML
     *
     * @param string|null $value
     * @return string
     */
    protected function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
