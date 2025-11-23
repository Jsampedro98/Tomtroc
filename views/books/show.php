<?php
/**
 * Vue : Page détail d'un livre
 *
 * Affiche toutes les informations d'un livre :
 * - Photo grande taille à gauche
 * - Titre, auteur, description à droite
 * - Section propriétaire avec photo, pseudo et bouton "Envoyer un message"
 *
 * Variables attendues :
 * @var array $book Données complètes du livre avec infos propriétaire (owner_pseudo, owner_photo)
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
?>

<div class="book-detail-container">
    <div class="book-detail-breadcrumb">
        <a href="<?= APP_URL ?>/books">Nos livres</a> > <?= htmlspecialchars($book['title']) ?>
    </div>

    <div class="book-detail-content">
        <div class="book-detail-image">
            <?php if (!empty($book['image'])): ?>
                <img src="<?= APP_URL . $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
            <?php else: ?>
                <div class="book-detail-placeholder">📚</div>
            <?php endif; ?>
        </div>

        <div class="book-detail-info">
            <h1 class="book-detail-title"><?= htmlspecialchars($book['title']) ?></h1>
            <p class="book-detail-author">par <?= htmlspecialchars($book['author']) ?></p>

            <hr class="book-detail-separator">

            <h3 class="book-detail-section-title">DESCRIPTION</h3>
            <div class="book-detail-description">
                <?php if (!empty($book['description'])): ?>
                    <?= nl2br(htmlspecialchars($book['description'])) ?>
                <?php else: ?>
                    <p class="text-muted">Aucune description disponible.</p>
                <?php endif; ?>
            </div>

            <h3 class="book-detail-section-title">PROPRIÉTAIRE</h3>
            <div class="book-detail-owner">
                <a href="<?= APP_URL ?>/profile/<?= $book['user_id'] ?>" class="book-detail-owner-info">
                    <?php if (!empty($book['owner_photo'])): ?>
                        <img src="<?= APP_URL . $book['owner_photo'] ?>" alt="<?= htmlspecialchars($book['owner_pseudo']) ?>" class="book-detail-owner-photo">
                    <?php else: ?>
                        <div class="book-detail-owner-photo-placeholder">
                            <?= strtoupper(substr($book['owner_pseudo'], 0, 2)) ?>
                        </div>
                    <?php endif; ?>
                    <span class="book-detail-owner-pseudo"><?= htmlspecialchars($book['owner_pseudo']) ?></span>
                </a>

                <?php if ($this->isLoggedIn() && $book['user_id'] != $this->getUserId()): ?>
                    <a href="<?= APP_URL ?>/messages/<?= $book['user_id'] ?>" class="btn btn-primary btn-message">
                        Envoyer un message
                    </a>
                <?php elseif (!$this->isLoggedIn()): ?>
                    <a href="<?= APP_URL ?>/login" class="btn btn-primary btn-message">
                        Connectez-vous pour contacter
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
