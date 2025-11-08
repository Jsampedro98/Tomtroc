<?php

/**
 * Contrôleur de gestion des profils publics
 *
 * Gère l'affichage des profils publics des utilisateurs
 * et la liste de leurs livres disponibles à l'échange.
 *
 * @package    TomTroc
 * @subpackage Controllers
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
class ProfileController extends AbstractController
{
    /**
     * Instance du modèle User
     *
     * @var User
     */
    private User $userModel;

    /**
     * Instance du modèle Book
     *
     * @var Book
     */
    private Book $bookModel;

    /**
     * Constructeur du contrôleur de profil
     *
     * Initialise les modèles User et Book.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userModel = new User();
        $this->bookModel = new Book();
    }

    /**
     * Afficher le profil public d'un utilisateur
     *
     * Accessible à tous les visiteurs.
     * Affiche les informations publiques de l'utilisateur
     * et la liste de ses livres disponibles.
     *
     * @param int $id ID de l'utilisateur
     * @return void
     */
    public function showPublic(int $id): void
    {
        // Récupérer l'utilisateur
        $user = $this->userModel->findById($id);

        if (!$user) {
            $_SESSION['flash_error'] = "Utilisateur introuvable";
            $this->redirect(APP_URL . '/books');
            return;
        }

        // Récupérer les livres de cet utilisateur
        $books = $this->bookModel->findByUserId($id);

        // Calculer le nombre de livres disponibles
        $availableCount = count(array_filter($books, fn($book) => $book['available']));

        $this->render('profile/public', [
            'user' => $user,
            'books' => $books,
            'availableCount' => $availableCount
        ]);
    }

    /**
     * Afficher le profil de l'utilisateur connecté
     *
     * Redirige vers la page "Mon compte"
     *
     * @return void
     */
    public function show(): void
    {
        $this->redirect(APP_URL . '/account');
    }

    /**
     * Afficher le formulaire d'édition du profil
     *
     * Redirige vers la page "Mon compte"
     *
     * @return void
     */
    public function edit(): void
    {
        $this->redirect(APP_URL . '/account');
    }

    /**
     * Mettre à jour le profil
     *
     * Redirige vers la page "Mon compte"
     *
     * @return void
     */
    public function update(): void
    {
        $this->redirect(APP_URL . '/account');
    }
}
