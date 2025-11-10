<?php
/**
 * Vue : Nouveau message
 *
 * Formulaire pour démarrer une nouvelle conversation
 * ou envoyer un message à un utilisateur spécifique.
 *
 * Variables attendues :
 * @var array|null $recipient Informations du destinataire (optionnel)
 * @var int|null $bookId ID du livre associé (optionnel)
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
?>

<div class="new-message-container">
    <div class="new-message-header">
        <a href="<?= APP_URL ?>/messages" class="btn-back">← Retour aux conversations</a>
        <h1>Nouveau message</h1>
    </div>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['flash_error'] ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <div class="new-message-form-wrapper">
        <?php if ($recipient): ?>
            <div class="new-message-recipient">
                <strong>À :</strong>
                <div class="recipient-info">
                    <?php if (!empty($recipient['photo'])): ?>
                        <img src="<?= APP_URL . $recipient['photo'] ?>" alt="<?= htmlspecialchars($recipient['pseudo']) ?>" class="recipient-avatar">
                    <?php else: ?>
                        <div class="recipient-avatar-placeholder">
                            <?= strtoupper(substr($recipient['pseudo'], 0, 2)) ?>
                        </div>
                    <?php endif; ?>
                    <span><?= htmlspecialchars($recipient['pseudo']) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/messages/send" class="new-message-form">
            <input type="hidden" name="receiver_id" value="<?= $recipient['id'] ?? '' ?>">
            <?php if ($bookId): ?>
                <input type="hidden" name="book_id" value="<?= $bookId ?>">
            <?php endif; ?>

            <?php if (!$recipient): ?>
                <div class="form-group">
                    <label for="receiver_id">Destinataire</label>
                    <select name="receiver_id" id="receiver_id" class="form-control" required>
                        <option value="">Sélectionnez un utilisateur</option>
                        <!-- TODO: Ajouter la liste des utilisateurs disponibles -->
                    </select>
                    <small class="form-help">Parcourez les livres pour contacter directement les propriétaires</small>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="content">Message</label>
                <textarea
                    id="content"
                    name="content"
                    class="form-control"
                    rows="8"
                    required
                    minlength="5"
                    maxlength="1000"
                    placeholder="Écrivez votre message ici..."
                ><?= $_SESSION['old_content'] ?? '' ?></textarea>
                <?php unset($_SESSION['old_content']); ?>
                <small class="form-help">Entre 5 et 1000 caractères</small>
            </div>

            <div class="form-actions">
                <a href="<?= APP_URL ?>/messages" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Envoyer le message</button>
            </div>
        </form>
    </div>
</div>
