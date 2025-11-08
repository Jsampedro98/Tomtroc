<?php

/**
 * Classe Controller - Classe de base pour tous les contrôleurs
 */
abstract class AbstractController
{
    /**
     * Rendre une vue
     *
     * @param string $view Chemin de la vue (ex: 'auth/login')
     * @param array $data Données à passer à la vue
     */
    protected function render(string $view, array $data = []): void
    {
        // Extraire les données pour les rendre accessibles dans la vue
        extract($data);

        // Inclure le header
        require_once VIEWS_PATH . '/layout/header.php';

        // Inclure la vue principale
        $viewPath = VIEWS_PATH . '/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("La vue {$view} n'existe pas.");
        }

        // Inclure le footer
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Rediriger vers une URL
     *
     * @param string $url URL de destination
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Vérifier si l'utilisateur est connecté
     *
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifier si l'utilisateur est connecté, sinon rediriger
     */
    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['flash_error'] = 'Vous devez être connecté pour accéder à cette page.';
            $this->redirect(APP_URL . '/login');
        }
    }

    /**
     * Obtenir l'ID de l'utilisateur connecté
     *
     * @return int|null
     */
    protected function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
}
