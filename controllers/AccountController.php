<?php

/**
 * Contrôleur de gestion du compte utilisateur
 *
 * Gère les fonctionnalités liées au compte utilisateur :
 * - Affichage et modification des informations personnelles
 * - Upload et gestion de la photo de profil
 * - Affichage de la bibliothèque personnelle
 *
 * @package    TomTroc
 * @subpackage Controllers
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Phase 2
 */
class AccountController extends Controller
{
    /**
     * Instance du modèle User pour les opérations en base de données
     *
     * @var User
     */
    private User $userModel;

    /**
     * Instance du modèle Book pour récupérer les livres de l'utilisateur
     *
     * @var Book
     */
    private Book $bookModel;

    /**
     * Constructeur du contrôleur
     *
     * Initialise les modèles User et Book et vérifie l'authentification de l'utilisateur.
     * Redirige automatiquement vers la page de connexion si non authentifié.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userModel = new User();
        $this->bookModel = new Book();
        $this->requireAuth();
    }

    /**
     * Affiche la page Mon compte
     *
     * Récupère les informations de l'utilisateur connecté et affiche
     * le formulaire d'édition du profil ainsi que sa bibliothèque de livres.
     *
     * @return void
     * @throws void Redirige vers la page d'accueil si l'utilisateur n'est pas trouvé
     */
    public function index(): void
    {
        $userId = $this->getUserId();
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $this->redirect(APP_URL . '/');
            return;
        }

        // Récupérer les livres de l'utilisateur
        $books = $this->bookModel->findByUserId($userId);

        $this->render('account/index', [
            'user' => $user,
            'books' => $books
        ]);
    }

    /**
     * Met à jour les informations du compte utilisateur
     *
     * Traite le formulaire de mise à jour des informations personnelles.
     * Valide les données (pseudo, email, mot de passe) et vérifie les doublons.
     * En cas de succès, met à jour la base de données et affiche un message de confirmation.
     *
     * Validations effectuées :
     * - Pseudo : minimum 3 caractères, unique
     * - Email : format valide, unique
     * - Mot de passe actuel : vérifié si changement de mot de passe
     * - Nouveau mot de passe : minimum 6 caractères si fourni
     *
     * @return void
     * @uses   User::findByPseudo()   Pour vérifier l'unicité du pseudo
     * @uses   User::findByEmail()    Pour vérifier l'unicité de l'email
     * @uses   User::update()         Pour sauvegarder les modifications
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/account');
            return;
        }

        $userId = $this->getUserId();
        $pseudo = trim($_POST['pseudo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';

        // Validation
        $errors = [];

        if (empty($pseudo) || strlen($pseudo) < 3) {
            $errors[] = "Le pseudo doit contenir au moins 3 caractères";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email n'est pas valide";
        }

        // Vérifier si le pseudo est déjà utilisé par un autre utilisateur
        $existingUser = $this->userModel->findByPseudo($pseudo);
        if ($existingUser && $existingUser['id'] != $userId) {
            $errors[] = "Ce pseudo est déjà utilisé";
        }

        // Vérifier si l'email est déjà utilisé par un autre utilisateur
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $userId) {
            $errors[] = "Cette adresse email est déjà utilisée";
        }

        // Si changement de mot de passe
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $errors[] = "Veuillez entrer votre mot de passe actuel";
            } else {
                // Vérifier le mot de passe actuel
                $user = $this->userModel->findById($userId);
                if (!password_verify($currentPassword, $user['password'])) {
                    $errors[] = "Le mot de passe actuel est incorrect";
                }
            }

            if (strlen($newPassword) < 6) {
                $errors[] = "Le nouveau mot de passe doit contenir au moins 6 caractères";
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['old_pseudo'] = $pseudo;
            $_SESSION['old_email'] = $email;
            $this->redirect(APP_URL . '/account');
            return;
        }

        // Préparer les données à mettre à jour
        $data = [
            'pseudo' => $pseudo,
            'email' => $email
        ];

        // Ajouter le nouveau mot de passe si fourni
        if (!empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        // Mettre à jour
        if ($this->userModel->update($userId, $data)) {
            $_SESSION['flash_success'] = "Vos informations ont été mises à jour avec succès";
        } else {
            $_SESSION['flash_error'] = "Une erreur est survenue lors de la mise à jour";
        }

        $this->redirect(APP_URL . '/account');
    }

    /**
     * Upload et mise à jour de la photo de profil
     *
     * Gère l'upload d'une nouvelle photo de profil pour l'utilisateur connecté.
     * Effectue les validations suivantes :
     * - Type de fichier : JPEG, PNG, GIF ou WEBP uniquement
     * - Taille maximale : 5 MB
     *
     * Le fichier est enregistré dans public/uploads/profiles/ avec un nom unique.
     * L'ancienne photo est automatiquement supprimée si elle existe.
     * Le chemin relatif de la nouvelle photo est enregistré en base de données.
     *
     * @return void
     * @uses   User::findById()  Pour récupérer l'ancienne photo
     * @uses   User::update()    Pour enregistrer le nouveau chemin de la photo
     * @see    UPLOAD_PATH       Constante définissant le dossier d'upload
     */
    public function uploadPhoto(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/account');
            return;
        }

        if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = "Aucune photo n'a été téléchargée";
            $this->redirect(APP_URL . '/account');
            return;
        }

        $file = $_FILES['profile_photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validation
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['flash_error'] = "Format de fichier non autorisé. Utilisez JPG, PNG, GIF ou WEBP";
            $this->redirect(APP_URL . '/account');
            return;
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['flash_error'] = "La photo ne doit pas dépasser 5MB";
            $this->redirect(APP_URL . '/account');
            return;
        }

        // Créer le dossier uploads si nécessaire
        $uploadDir = PUBLIC_PATH . '/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('profile_') . '.' . $extension;
        $destination = $uploadDir . $filename;

        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $userId = $this->getUserId();

            // Supprimer l'ancienne photo si elle existe
            $user = $this->userModel->findById($userId);
            if (!empty($user['photo']) && file_exists(PUBLIC_PATH . $user['photo'])) {
                unlink(PUBLIC_PATH . $user['photo']);
            }

            // Mettre à jour la BDD
            $photoPath = '/uploads/profiles/' . $filename;
            $this->userModel->update($userId, ['photo' => $photoPath]);

            $_SESSION['flash_success'] = "Votre photo de profil a été mise à jour";
        } else {
            $_SESSION['flash_error'] = "Erreur lors de l'upload de la photo";
        }

        $this->redirect(APP_URL . '/account');
    }
}
