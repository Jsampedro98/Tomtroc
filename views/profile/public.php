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

<div class="account-container">
    <div class="account-sidebar">
        <div class="profile-section">
            <?php if (!empty($user['photo'])): ?>
                <img src="<?= APP_URL . $user['photo'] ?>" alt="<?= htmlspecialchars($user['pseudo']) ?>" class="profile-photo">
            <?php else: ?>
                <div class="profile-photo-placeholder">
                    <?= strtoupper(substr($user['pseudo'], 0, 2)) ?>
                </div>
            <?php endif; ?>
        </div>

        <h2 class="profile-name"><?= htmlspecialchars($user['pseudo']) ?></h2>
        <p class="profile-member-since">Membre depuis <?= date('n') >= date('n', strtotime($user['created_at'])) ? date('Y') - date('Y', strtotime($user['created_at'])) : date('Y') - date('Y', strtotime($user['created_at'])) - 1 ?> an<?= (date('Y') - date('Y', strtotime($user['created_at']))) > 1 ? 's' : '' ?></p>

        <div class="profile-library-info">
            <span class="library-label">BIBLIOTHEQUE</span>
            <span class="library-count">📚 <?= count($books) ?> livre<?= count($books) > 1 ? 's' : '' ?></span>
        </div>

        <?php if ($this->isLoggedIn() && $this->getUserId() != $user['id']): ?>
            <a href="<?= APP_URL ?>/messages/<?= $user['id'] ?>" class="btn btn-primary-outline profile-public-message-btn">
                Écrire un message
            </a>
        <?php endif; ?>
    </div>

    <div class="library-section profile-public-library">
        <?php if (!empty($books)): ?>
            <table class="library-table">
                <thead>
                    <tr>
                        <th>PHOTO</th>
                        <th>TITRE</th>
                        <th>AUTEUR</th>
                        <th>DESCRIPTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                    <tr>
                        <td class="library-td-photo">
                            <?php if (!empty($book['image'])): ?>
                                <img src="<?= APP_URL . $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="book-thumb">
                            <?php else: ?>
                                <div class="book-thumb-placeholder">📚</div>
                            <?php endif; ?>
                        </td>
                        <td class="library-td-title"><?= htmlspecialchars($book['title']) ?></td>
                        <td class="library-td-author"><?= htmlspecialchars($book['author']) ?></td>
                        <td class="library-td-description">
                            <?= !empty($book['description']) ? htmlspecialchars(substr($book['description'], 0, 120)) . (strlen($book['description']) > 120 ? '...' : '') : '' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="library-empty">
                <p>📚 <?= htmlspecialchars($user['pseudo']) ?> n'a pas encore ajouté de livres.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
