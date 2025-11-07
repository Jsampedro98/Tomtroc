<?php
/**
 * Vue : Formulaire d'ajout d'un livre
 *
 * Permet à un utilisateur connecté d'ajouter un nouveau livre à sa bibliothèque.
 * Champs : titre, auteur, description, photo, disponibilité
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Phase 3
 */
?>

<div class="book-form-container">
    <div class="book-form-header">
        <h1>Ajouter un livre</h1>
        <a href="<?= APP_URL ?>/account" class="btn-back">← Retour à mon compte</a>
    </div>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['flash_error'] ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/books/store" enctype="multipart/form-data" class="book-form">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre du livre *</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="<?= isset($_SESSION['old_title']) ? htmlspecialchars($_SESSION['old_title']) : '' ?>"
                    required
                    minlength="2"
                    placeholder="Ex: Le Seigneur des Anneaux"
                >
                <?php unset($_SESSION['old_title']); ?>
            </div>

            <div class="form-group">
                <label for="author">Auteur *</label>
                <input
                    type="text"
                    id="author"
                    name="author"
                    class="form-control"
                    value="<?= isset($_SESSION['old_author']) ? htmlspecialchars($_SESSION['old_author']) : '' ?>"
                    required
                    minlength="2"
                    placeholder="Ex: J.R.R. Tolkien"
                >
                <?php unset($_SESSION['old_author']); ?>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                class="form-control"
                rows="6"
                placeholder="Donnez une brève description du livre..."
            ><?= isset($_SESSION['old_description']) ? htmlspecialchars($_SESSION['old_description']) : '' ?></textarea>
            <?php unset($_SESSION['old_description']); ?>
        </div>

        <div class="form-group">
            <label for="photo">Photo du livre</label>
            <div class="file-upload-wrapper">
                <input
                    type="file"
                    id="photo"
                    name="photo"
                    class="form-control-file"
                    accept="image/jpeg,image/png,image/gif,image/webp"
                >
                <small class="form-help">Formats acceptés : JPG, PNG, GIF, WEBP (max 5MB)</small>
                <div id="preview" class="image-preview"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="available" checked>
                <span>Livre disponible à l'échange</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Ajouter le livre</button>
            <a href="<?= APP_URL ?>/account" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
// Prévisualisation de l'image
document.getElementById('photo').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Prévisualisation">';
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
        preview.style.display = 'none';
    }
});
</script>
