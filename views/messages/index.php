<?php
/**
 * Vue : Liste des conversations
 *
 * Affiche toutes les conversations de l'utilisateur connecté
 * avec le dernier message et le compteur de non-lus.
 *
 * Variables attendues :
 * @var array $conversations Liste des conversations
 * @var int $unreadTotal Nombre total de messages non lus
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
?>

<div class="messages-container">
    <div class="messages-header">
        <h1>Messagerie</h1>
        <?php if ($unreadTotal > 0): ?>
            <span class="messages-unread-badge"><?= $unreadTotal ?> nouveau<?= $unreadTotal > 1 ? 'x' : '' ?></span>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['flash_success'] ?>
            <?php unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['flash_error'] ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($conversations)): ?>
        <div class="conversations-list">
            <?php foreach ($conversations as $conversation): ?>
                <a href="<?= APP_URL ?>/messages/<?= $conversation['other_user_id'] ?>" class="conversation-item <?= $conversation['unread_count'] > 0 ? 'unread' : '' ?>">
                    <div class="conversation-avatar">
                        <?php if (!empty($conversation['other_user_photo'])): ?>
                            <img src="<?= APP_URL . $conversation['other_user_photo'] ?>" alt="<?= htmlspecialchars($conversation['other_user_pseudo']) ?>">
                        <?php else: ?>
                            <div class="conversation-avatar-placeholder">
                                <?= strtoupper(substr($conversation['other_user_pseudo'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="conversation-content">
                        <div class="conversation-header">
                            <h3 class="conversation-name"><?= htmlspecialchars($conversation['other_user_pseudo']) ?></h3>
                            <span class="conversation-date"><?= date('d/m/Y', strtotime($conversation['last_message_date'])) ?></span>
                        </div>
                        <div class="conversation-preview">
                            <?php
                            $preview = htmlspecialchars($conversation['last_message_content']);
                            $isSentByMe = $conversation['last_message_sender_id'] == $this->getUserId();
                            echo $isSentByMe ? 'Vous : ' : '';
                            echo strlen($preview) > 60 ? substr($preview, 0, 60) . '...' : $preview;
                            ?>
                        </div>
                    </div>

                    <?php if ($conversation['unread_count'] > 0): ?>
                        <div class="conversation-unread">
                            <?= $conversation['unread_count'] ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="messages-empty">
            <p>📬 Vous n'avez pas encore de conversations.</p>
            <p>Commencez par parcourir <a href="<?= APP_URL ?>/books">les livres disponibles</a> et contactez les propriétaires !</p>
        </div>
    <?php endif; ?>
</div>
