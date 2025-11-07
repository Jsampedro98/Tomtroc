<?php

/**
 * Contrôleur de gestion des livres
 *
 * Gère toutes les fonctionnalités liées aux livres :
 * - CRUD complet (Create, Read, Update, Delete)
 * - Upload de photos de livres
 * - Toggle disponibilité
 * - Affichage liste publique et bibliothèque personnelle
 *
 * @package    TomTroc
 * @subpackage Controllers
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Phase 3
 */
class BookController extends Controller
{
    /**
     * Instance du modèle Book pour les opérations en base de données
     *
     * @var Book
     */
    private Book $bookModel;

    /**
     * Constructeur du contrôleur
     *
     * Initialise le modèle Book.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bookModel = new Book();
    }

    /**
     * Affiche la page publique de tous les livres disponibles
     *
     * Accessible à tous les visiteurs (connectés ou non).
     * Permet la recherche par titre ou auteur.
     *
     * @return void
     */
    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');
        $filters = [];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        $books = $this->bookModel->findAll($filters, 100, 0);

        $this->render('books/index', [
            'books' => $books,
            'search' => $search
        ]);
    }

    /**
     * Affiche le détail d'un livre
     *
     * Accessible à tous les visiteurs.
     *
     * @param int $id ID du livre
     * @return void
     */
    public function show(int $id): void
    {
        $book = $this->bookModel->findById($id);

        if (!$book) {
            $_SESSION['flash_error'] = "Livre introuvable";
            $this->redirect(APP_URL . '/books');
            return;
        }

        $this->render('books/show', ['book' => $book]);
    }

    /**
     * Affiche le formulaire de création d'un livre
     *
     * Accessible uniquement aux utilisateurs connectés.
     *
     * @return void
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->render('books/create');
    }

    /**
     * Traite la création d'un nouveau livre
     *
     * Valide les données du formulaire, upload la photo si fournie,
     * et crée le livre en base de données.
     *
     * Validations :
     * - Titre : requis, minimum 2 caractères
     * - Auteur : requis, minimum 2 caractères
     * - Description : optionnelle
     * - Photo : JPEG, PNG, GIF ou WEBP, max 5MB (optionnelle)
     *
     * @return void
     * @uses Book::create() Pour enregistrer le livre en BDD
     */
    public function store(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/books/create');
            return;
        }

        $userId = $this->getUserId();
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $available = isset($_POST['available']) ? 1 : 0;

        // Validation
        $errors = [];

        if (empty($title) || strlen($title) < 2) {
            $errors[] = "Le titre doit contenir au moins 2 caractères";
        }

        if (empty($author) || strlen($author) < 2) {
            $errors[] = "L'auteur doit contenir au moins 2 caractères";
        }

        // Upload de la photo (optionnel)
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadBookPhoto($_FILES['photo']);
            if ($uploadResult['success']) {
                $photoPath = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['old_title'] = $title;
            $_SESSION['old_author'] = $author;
            $_SESSION['old_description'] = $description;
            $this->redirect(APP_URL . '/books/create');
            return;
        }

        // Créer le livre
        $bookId = $this->bookModel->create([
            'user_id' => $userId,
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'image' => $photoPath,
            'available' => $available
        ]);

        if ($bookId) {
            $_SESSION['flash_success'] = "Votre livre a été ajouté avec succès";
            $this->redirect(APP_URL . '/account');
        } else {
            $_SESSION['flash_error'] = "Une erreur est survenue lors de l'ajout du livre";
            $this->redirect(APP_URL . '/books/create');
        }
    }

    /**
     * Affiche le formulaire de modification d'un livre
     *
     * Vérifie que l'utilisateur est bien le propriétaire du livre.
     *
     * @param int $id ID du livre à modifier
     * @return void
     */
    public function edit(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getUserId();

        $book = $this->bookModel->findById($id);

        if (!$book) {
            $_SESSION['flash_error'] = "Livre introuvable";
            $this->redirect(APP_URL . '/account');
            return;
        }

        // Vérifier que c'est bien le propriétaire
        if ($book['user_id'] != $userId) {
            $_SESSION['flash_error'] = "Vous n'êtes pas autorisé à modifier ce livre";
            $this->redirect(APP_URL . '/account');
            return;
        }

        $this->render('books/edit', ['book' => $book]);
    }

    /**
     * Traite la modification d'un livre existant
     *
     * Valide les données, upload une nouvelle photo si fournie,
     * et met à jour le livre en base de données.
     *
     * @param int $id ID du livre à modifier
     * @return void
     * @uses Book::update() Pour mettre à jour le livre en BDD
     */
    public function update(int $id): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/books/' . $id . '/edit');
            return;
        }

        $userId = $this->getUserId();
        $book = $this->bookModel->findById($id);

        if (!$book || $book['user_id'] != $userId) {
            $_SESSION['flash_error'] = "Livre introuvable ou accès non autorisé";
            $this->redirect(APP_URL . '/account');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $available = isset($_POST['available']) ? 1 : 0;

        // Validation
        $errors = [];

        if (empty($title) || strlen($title) < 2) {
            $errors[] = "Le titre doit contenir au moins 2 caractères";
        }

        if (empty($author) || strlen($author) < 2) {
            $errors[] = "L'auteur doit contenir au moins 2 caractères";
        }

        // Upload nouvelle photo si fournie
        $photoPath = $book['image']; // Garder l'ancienne par défaut
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadBookPhoto($_FILES['photo']);
            if ($uploadResult['success']) {
                // Supprimer l'ancienne photo
                if (!empty($book['image']) && file_exists(PUBLIC_PATH . $book['image'])) {
                    unlink(PUBLIC_PATH . $book['image']);
                }
                $photoPath = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $this->redirect(APP_URL . '/books/' . $id . '/edit');
            return;
        }

        // Mettre à jour
        $success = $this->bookModel->update($id, [
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'image' => $photoPath,
            'available' => $available
        ]);

        if ($success) {
            $_SESSION['flash_success'] = "Le livre a été modifié avec succès";
        } else {
            $_SESSION['flash_error'] = "Une erreur est survenue lors de la modification";
        }

        $this->redirect(APP_URL . '/account');
    }

    /**
     * Supprime un livre
     *
     * Vérifie les permissions, supprime la photo du serveur,
     * et supprime le livre de la base de données.
     *
     * @param int $id ID du livre à supprimer
     * @return void
     * @uses Book::delete() Pour supprimer le livre de la BDD
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getUserId();

        $book = $this->bookModel->findById($id);

        if (!$book || $book['user_id'] != $userId) {
            $_SESSION['flash_error'] = "Livre introuvable ou accès non autorisé";
            $this->redirect(APP_URL . '/account');
            return;
        }

        // Supprimer la photo si elle existe
        if (!empty($book['image']) && file_exists(PUBLIC_PATH . $book['image'])) {
            unlink(PUBLIC_PATH . $book['image']);
        }

        // Supprimer le livre
        if ($this->bookModel->delete($id)) {
            $_SESSION['flash_success'] = "Le livre a été supprimé avec succès";
        } else {
            $_SESSION['flash_error'] = "Une erreur est survenue lors de la suppression";
        }

        $this->redirect(APP_URL . '/account');
    }

    /**
     * Toggle la disponibilité d'un livre (disponible/non disponible)
     *
     * @param int $id ID du livre
     * @return void
     * @uses Book::toggleAvailability() Pour inverser le statut
     */
    public function toggleAvailability(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getUserId();

        if (!$this->bookModel->belongsToUser($id, $userId)) {
            $_SESSION['flash_error'] = "Accès non autorisé";
            $this->redirect(APP_URL . '/account');
            return;
        }

        if ($this->bookModel->toggleAvailability($id)) {
            $_SESSION['flash_success'] = "La disponibilité du livre a été mise à jour";
        } else {
            $_SESSION['flash_error'] = "Une erreur est survenue";
        }

        $this->redirect(APP_URL . '/account');
    }

    /**
     * Gère l'upload d'une photo de livre
     *
     * Valide le fichier (type, taille), génère un nom unique,
     * et déplace le fichier dans le dossier uploads/books/.
     *
     * @param array $file Fichier uploadé ($_FILES['photo'])
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    private function uploadBookPhoto(array $file): array
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validation type
        if (!in_array($file['type'], $allowedTypes)) {
            return [
                'success' => false,
                'error' => "Format de fichier non autorisé. Utilisez JPG, PNG, GIF ou WEBP"
            ];
        }

        // Validation taille
        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'error' => "La photo ne doit pas dépasser 5MB"
            ];
        }

        // Créer le dossier si nécessaire
        $uploadDir = PUBLIC_PATH . '/uploads/books/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer nom unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('book_') . '.' . $extension;
        $destination = $uploadDir . $filename;

        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'path' => '/uploads/books/' . $filename,
                'error' => null
            ];
        }

        return [
            'success' => false,
            'error' => "Erreur lors de l'upload de la photo"
        ];
    }
}
