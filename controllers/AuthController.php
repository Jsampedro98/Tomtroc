<?php

/**
 * Contrôleur d'authentification
 */
class AuthController extends AbstractController
{
    private User $userModel;

    /**
     * Constructeur du contrôleur d'authentification
     *
     * Initialise le modèle User pour gérer les opérations liées aux utilisateurs.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegister(): void
    {
        // Rediriger si déjà connecté
        if ($this->isLoggedIn()) {
            $this->redirect(APP_URL);
        }

        $title = 'Inscription';
        $this->render('auth/register', compact('title'));
    }

    /**
     * Traiter l'inscription
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/register');
            return;
        }

        $pseudo = trim($_POST['pseudo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        $errors = [];

        if (empty($pseudo)) {
            $errors[] = 'Le pseudo est requis.';
        } elseif (strlen($pseudo) < 3) {
            $errors[] = 'Le pseudo doit contenir au moins 3 caractères.';
        } elseif ($this->userModel->pseudoExists($pseudo)) {
            $errors[] = 'Ce pseudo est déjà utilisé.';
        }

        if (empty($email)) {
            $errors[] = 'L\'email est requis.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email n\'est pas valide.';
        } elseif ($this->userModel->emailExists($email)) {
            $errors[] = 'Cet email est déjà utilisé.';
        }

        if (empty($password)) {
            $errors[] = 'Le mot de passe est requis.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['old_pseudo'] = $pseudo;
            $_SESSION['old_email'] = $email;
            $this->redirect(APP_URL . '/register');
            return;
        }

        // Créer l'utilisateur
        $userId = $this->userModel->create($pseudo, $email, $password);

        if ($userId) {
            $_SESSION['flash_success'] = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
            $this->redirect(APP_URL . '/login');
        } else {
            $_SESSION['flash_error'] = 'Une erreur est survenue lors de l\'inscription.';
            $this->redirect(APP_URL . '/register');
        }
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLogin(): void
    {
        // Rediriger si déjà connecté
        if ($this->isLoggedIn()) {
            $this->redirect(APP_URL);
        }

        $title = 'Connexion';
        $this->render('auth/login', compact('title'));
    }

    /**
     * Traiter la connexion
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Veuillez remplir tous les champs.';
            $_SESSION['old_email'] = $email;
            $this->redirect(APP_URL . '/login');
            return;
        }

        // Vérifier les identifiants
        $user = $this->userModel->verifyCredentials($email, $password);

        if ($user) {
            // Créer la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_pseudo'] = $user['pseudo'];
            $_SESSION['user_email'] = $user['email'];

            $_SESSION['flash_success'] = 'Bienvenue ' . htmlspecialchars($user['pseudo']) . ' !';
            $this->redirect(APP_URL);
        } else {
            $_SESSION['flash_error'] = 'Email ou mot de passe incorrect.';
            $_SESSION['old_email'] = $email;
            $this->redirect(APP_URL . '/login');
        }
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout(): void
    {
        // Détruire la session
        session_destroy();
        session_start();

        $_SESSION['flash_success'] = 'Vous avez été déconnecté.';
        $this->redirect(APP_URL);
    }
}
