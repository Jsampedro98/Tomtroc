<?php

/**
 * Modèle Book - Gestion des livres
 *
 * Gère toutes les opérations CRUD pour les livres :
 * - Création, lecture, mise à jour, suppression
 * - Recherche par utilisateur
 * - Recherche par disponibilité
 * - Gestion du statut disponible/non disponible
 *
 * @package    TomTroc
 * @subpackage Models
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
class Book
{
    /**
     * Instance PDO pour les requêtes en base de données
     *
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Constructeur - Initialise la connexion à la base de données
     *
     * @return void
     */
    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
            DB_USER,
            DB_PASS
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Récupère tous les livres d'un utilisateur
     *
     * @param int $userId ID de l'utilisateur
     * @return array Liste des livres
     */
    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM books
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un livre par son ID
     *
     * @param int $id ID du livre
     * @return array|false Données du livre ou false si introuvable
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('
            SELECT b.*, u.pseudo as owner_pseudo, u.photo as owner_photo
            FROM books b
            LEFT JOIN users u ON b.user_id = u.id
            WHERE b.id = :id
        ');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les livres disponibles (pour la page publique)
     *
     * @param array $filters Filtres optionnels (search, available)
     * @param int   $limit   Nombre de résultats
     * @param int   $offset  Décalage pour pagination
     * @return array Liste des livres
     */
    public function findAll(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $sql = '
            SELECT b.*, u.pseudo as owner_pseudo, u.photo as owner_photo
            FROM books b
            LEFT JOIN users u ON b.user_id = u.id
            WHERE 1=1
        ';
        $params = [];

        // Filtre par recherche (titre ou auteur)
        if (!empty($filters['search'])) {
            $sql .= ' AND (b.title LIKE :search OR b.author LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        // Filtre par disponibilité
        if (isset($filters['available'])) {
            $sql .= ' AND b.available = :available';
            $params['available'] = (int)$filters['available'];
        }

        $sql .= ' ORDER BY b.created_at DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);

        // Bind des paramètres
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre total de livres (pour pagination)
     *
     * @param array $filters Filtres optionnels
     * @return int Nombre de livres
     */
    public function count(array $filters = []): int
    {
        $sql = 'SELECT COUNT(*) FROM books WHERE 1=1';
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= ' AND (title LIKE :search OR author LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (isset($filters['available'])) {
            $sql .= ' AND available = :available';
            $params['available'] = (int)$filters['available'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Alias pour count() - Compte tous les livres selon filtres
     *
     * @param array $filters Filtres optionnels
     * @return int Nombre de livres
     */
    public function countAll(array $filters = []): int
    {
        return $this->count($filters);
    }

    /**
     * Crée un nouveau livre
     *
     * @param array $data Données du livre (user_id, title, author, description, photo, available)
     * @return int ID du livre créé
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO books (user_id, title, author, description, image, available)
            VALUES (:user_id, :title, :author, :description, :image, :available)
        ');

        $stmt->execute([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'available' => $data['available'] ?? 1
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Met à jour un livre existant
     *
     * @param int   $id   ID du livre
     * @param array $data Données à mettre à jour
     * @return bool Succès de l'opération
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        // Construire dynamiquement la requête selon les champs fournis
        foreach ($data as $key => $value) {
            if (in_array($key, ['title', 'author', 'description', 'image', 'available'])) {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = 'UPDATE books SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprime un livre
     *
     * @param int $id ID du livre
     * @return bool Succès de l'opération
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Toggle la disponibilité d'un livre
     *
     * @param int $id ID du livre
     * @return bool Succès de l'opération
     */
    public function toggleAvailability(int $id): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE books
            SET available = NOT available
            WHERE id = :id
        ');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Vérifie si un livre appartient à un utilisateur
     *
     * @param int $bookId ID du livre
     * @param int $userId ID de l'utilisateur
     * @return bool True si le livre appartient à l'utilisateur
     */
    public function belongsToUser(int $bookId, int $userId): bool
    {
        $stmt = $this->pdo->prepare('
            SELECT COUNT(*) FROM books
            WHERE id = :book_id AND user_id = :user_id
        ');
        $stmt->execute([
            'book_id' => $bookId,
            'user_id' => $userId
        ]);
        return $stmt->fetchColumn() > 0;
    }


    /**
     * Récupère d'autres livres du même propriétaire (pour suggestions)
     *
     * @param int $bookId  ID du livre actuel (à exclure)
     * @param int $userId  ID du propriétaire
     * @param int $limit   Nombre de suggestions
     * @return array Liste des livres
     */
    public function findOthersByUser(int $bookId, int $userId, int $limit = 4): array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM books
            WHERE user_id = :user_id AND id != :book_id
            ORDER BY created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les derniers livres ajoutés
     *
     * Utilisé pour la page d'accueil - affiche les derniers livres
     * avec les informations du propriétaire
     *
     * @param int $limit Nombre de livres à récupérer (défaut: 4)
     * @return array Liste des derniers livres avec pseudo du propriétaire
     */
    public function getLatestBooks(int $limit = 4): array
    {
        $stmt = $this->pdo->prepare('
            SELECT b.*, u.pseudo as owner_pseudo
            FROM books b
            INNER JOIN users u ON b.user_id = u.id
            ORDER BY b.created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
