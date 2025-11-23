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
    <!-- Left Panel: Conversations List -->
    <div class="conversations-list-panel">
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
                    <a href="<?= APP_URL ?>/messages/<?= $conversation['other_user_id'] ?>" class="conversation-item <?= $conversation['unread_count'] > 0 ? 'unread' : '' ?> <?= ($selectedUserId && $selectedUserId == $conversation['other_user_id']) ? 'active' : '' ?>">
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
                                <div class="conversation-meta">
                                    <span class="conversation-date"><?= date('d/m/Y', strtotime($conversation['last_message_date'])) ?></span>
                                    <?php if ($conversation['unread_count'] > 0): ?>
                                        <div class="conversation-unread">
                                            <?= $conversation['unread_count'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
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

    <!-- Right Panel: Conversation or Empty State -->
    <div class="conversation-container">
        <?php if ($otherUser && $messages !== null): ?>
            <!-- Conversation active -->
            <div class="conversation-header-top">
                <a href="<?= APP_URL ?>/messages" class="conversation-back">← Retour</a>

                <div class="conversation-user-info">
                    <a href="<?= APP_URL ?>/profile/<?= $otherUser['id'] ?>">
                        <?php if (!empty($otherUser['photo'])): ?>
                            <img src="<?= APP_URL . $otherUser['photo'] ?>" alt="<?= htmlspecialchars($otherUser['pseudo']) ?>" class="conversation-user-avatar">
                        <?php else: ?>
                            <div class="conversation-user-avatar-placeholder">
                                <?= strtoupper(substr($otherUser['pseudo'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                        <h1><?= htmlspecialchars($otherUser['pseudo']) ?></h1>
                    </a>
                </div>
            </div>

            <div class="conversation-messages" id="messagesContainer">
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= $message['sender_id'] == $this->getUserId() ? 'message-sent' : 'message-received' ?>">
                            <?php if ($message['sender_id'] != $this->getUserId()): ?>
                                <div class="message-header">
                                    <div class="message-avatar">
                                        <?php if (!empty($otherUser['photo'])): ?>
                                            <img src="<?= APP_URL . $otherUser['photo'] ?>" alt="<?= htmlspecialchars($otherUser['pseudo']) ?>">
                                        <?php else: ?>
                                            <div class="message-avatar-placeholder">
                                                <?= strtoupper(substr($otherUser['pseudo'], 0, 2)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="message-date"><?= date('d.m H:i', strtotime($message['created_at'])) ?></span>
                                </div>
                            <?php else: ?>
                                <span class="message-date"><?= date('d.m H:i', strtotime($message['created_at'])) ?></span>
                            <?php endif; ?>
                            <div class="message-text">
                                <?= nl2br(htmlspecialchars($message['content'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="conversation-empty">
                        <p>Aucun message dans cette conversation. Soyez le premier à écrire !</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="conversation-form-container">
                <form method="POST" action="<?= APP_URL ?>/messages/send" class="conversation-form">
                    <input type="hidden" name="receiver_id" value="<?= $otherUser['id'] ?>">

                    <textarea
                        name="content"
                        placeholder="Écrivez votre message..."
                        class="conversation-textarea"
                        required
                        minlength="5"
                        maxlength="1000"
                        rows="3"
                    ><?= $_SESSION['old_content'] ?? '' ?></textarea>
                    <?php unset($_SESSION['old_content']); ?>

                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        <?php else: ?>
            <!-- État vide -->
            <div class="conversation-empty">
                <p>Sélectionnez une conversation pour commencer à discuter</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($otherUser && $messages !== null): ?>
<script>
// Scroll automatique vers le bas des messages
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});
</script>
<?php endif; ?>
