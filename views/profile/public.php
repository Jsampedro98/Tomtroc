<?php
/**
 * Vue : Profil public d'un utilisateur
 *
 * Affiche les informations publiques d'un utilisateur
 * et la liste de ses livres disponibles à l'échange.
 *
 * Variables attendues :
 * @var array $user Données de l'utilisateur (id, pseudo, photo, created_at)
 * @var array $books Liste des livres de l'utilisateur
 * @var int $availableCount Nombre de livres disponibles
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
?>

<div class="profile-public-container">
    <div class="profile-public-header">
        <div class="profile-public-info">
            <?php if (!empty($user['photo'])): ?>
                <img src="<?= APP_URL . $user['photo'] ?>" alt="<?= htmlspecialchars($user['pseudo']) ?>" class="profile-public-photo">
            <?php else: ?>
                <div class="profile-public-photo-placeholder">
                    <?= strtoupper(substr($user['pseudo'], 0, 2)) ?>
                </div>
            <?php endif; ?>

            <div class="profile-public-details">
                <h1><?= htmlspecialchars($user['pseudo']) ?></h1>
                <p class="profile-public-member-since">
                    Membre depuis <?= date('Y', strtotime($user['created_at'])) ?>
                </p>
                <p class="profile-public-library-count">
                    📚 <?= count($books) ?> livre<?= count($books) > 1 ? 's' : '' ?>
                    <?php if ($availableCount > 0): ?>
                        (<?= $availableCount ?> disponible<?= $availableCount > 1 ? 's' : '' ?>)
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <?php if ($this->isLoggedIn() && $this->getUserId() != $user['id']): ?>
            <a href="<?= APP_URL ?>/messages/new?user_id=<?= $user['id'] ?>" class="btn btn-primary">
                Envoyer un message
            </a>
        <?php endif; ?>
    </div>

    <div class="profile-public-books">
        <h2>Livres de <?= htmlspecialchars($user['pseudo']) ?></h2>

        <?php if (!empty($books)): ?>
            <div class="books-grid">
                <?php foreach ($books as $book): ?>
                    <a href="<?= APP_URL ?>/books/<?= $book['id'] ?>" class="book-card">
                        <div class="book-card-image">
                            <?php if (!empty($book['image'])): ?>
                                <img src="<?= APP_URL . $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                            <?php else: ?>
                                <div class="book-card-placeholder">📚</div>
                            <?php endif; ?>

                            <?php if (!$book['available']): ?>
                                <span class="book-card-badge-unavailable">non dispo.</span>
                            <?php endif; ?>
                        </div>

                        <div class="book-card-content">
                            <h3 class="book-card-title"><?= htmlspecialchars($book['title']) ?></h3>
                            <p class="book-card-author"><?= htmlspecialchars($book['author']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="books-empty">
                <p><?= htmlspecialchars($user['pseudo']) ?> n'a pas encore ajouté de livres.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
