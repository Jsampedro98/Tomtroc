<?php
/**
 * Vue : Conversation (fil de discussion)
 *
 * Affiche tous les messages échangés entre l'utilisateur connecté
 * et un autre utilisateur, avec un formulaire d'envoi de message.
 *
 * Variables attendues :
 * @var array $messages Liste des messages de la conversation
 * @var array $otherUser Informations de l'autre utilisateur
 * @var int $userId ID de l'utilisateur connecté
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
?>

<div class="conversation-container">
    <div class="conversation-header-top">
        <a href="<?= APP_URL ?>/messages" class="conversation-back">← Retour aux conversations</a>

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

    <div class="conversation-messages" id="messagesContainer">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message['sender_id'] == $userId ? 'message-sent' : 'message-received' ?>">
                    <div class="message-avatar">
                        <?php
                        $senderPhoto = $message['sender_id'] == $userId ?
                            ($this->isLoggedIn() ? ($_SESSION['user_photo'] ?? null) : null) :
                            $otherUser['photo'];
                        $senderPseudo = $message['sender_pseudo'];
                        ?>
                        <?php if (!empty($senderPhoto)): ?>
                            <img src="<?= APP_URL . $senderPhoto ?>" alt="<?= htmlspecialchars($senderPseudo) ?>">
                        <?php else: ?>
                            <div class="message-avatar-placeholder">
                                <?= strtoupper(substr($senderPseudo, 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="message-content">
                        <div class="message-bubble">
                            <?= nl2br(htmlspecialchars($message['content'])) ?>
                        </div>
                        <div class="message-meta">
                            <span class="message-author"><?= htmlspecialchars($senderPseudo) ?></span>
                            <span class="message-date"><?= date('d/m/Y à H:i', strtotime($message['created_at'])) ?></span>
                        </div>
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
</div>

<script>
// Scroll automatique vers le bas des messages
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});
</script>
