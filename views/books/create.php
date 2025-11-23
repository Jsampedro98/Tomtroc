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
 * @since      Version 1.0
 */
?>

<div class="book-form-page">
    <a href="<?= APP_URL ?>/account" class="book-form-back">← retour</a>

    <h1 class="book-form-title">Ajouter un livre</h1>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['flash_error'] ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/books/store" enctype="multipart/form-data" class="book-form-grid">
        <div class="book-form-photo-section">
            <label>Photo</label>

            <div class="book-photo-placeholder" id="placeholder">
                📚
            </div>

            <button type="button" class="book-photo-change" onclick="document.getElementById('photo').click()">ajouter une photo</button>

            <input
                type="file"
                id="photo"
                name="photo"
                class="book-photo-input"
                accept="image/jpeg,image/png,image/gif,image/webp"
                style="display: none;"
            >
            <div id="preview" class="book-photo-new-preview"></div>
        </div>

        <div class="book-form-fields-section">
            <div class="form-group">
                <label for="title">Titre</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="<?= isset($_SESSION['old_title']) ? htmlspecialchars($_SESSION['old_title']) : '' ?>"
                    required
                    minlength="2"
                >
                <?php unset($_SESSION['old_title']); ?>
            </div>

            <div class="form-group">
                <label for="author">Auteur</label>
                <input
                    type="text"
                    id="author"
                    name="author"
                    class="form-control"
                    value="<?= isset($_SESSION['old_author']) ? htmlspecialchars($_SESSION['old_author']) : '' ?>"
                    required
                    minlength="2"
                >
                <?php unset($_SESSION['old_author']); ?>
            </div>

            <div class="form-group">
                <label for="description">Commentaire</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="10"
                ><?= isset($_SESSION['old_description']) ? htmlspecialchars($_SESSION['old_description']) : '' ?></textarea>
                <?php unset($_SESSION['old_description']); ?>
            </div>

            <div class="form-group">
                <label for="available">Disponibilité</label>
                <select id="available" name="available" class="form-control">
                    <option value="1" selected>disponible</option>
                    <option value="0">non disponible</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Valider</button>
        </div>
    </form>
</div>

<script>
// Prévisualisation de l'image
document.getElementById('photo').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (placeholder) placeholder.style.display = 'none';
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Prévisualisation">';
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        if (placeholder) placeholder.style.display = 'flex';
        preview.innerHTML = '';
        preview.style.display = 'none';
    }
});
</script>
