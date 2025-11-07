<?php
/**
 * Vue : Formulaire de modification d'un livre
 *
 * Permet à un utilisateur de modifier un livre de sa bibliothèque.
 *
 * Variables attendues :
 * @var array $book Données du livre à modifier
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
        <h1>Modifier le livre</h1>
        <a href="<?= APP_URL ?>/account" class="btn-back">← Retour à mon compte</a>
    </div>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['flash_error'] ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/books/<?= $book['id'] ?>/update" enctype="multipart/form-data" class="book-form">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre du livre *</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="<?= htmlspecialchars($book['title']) ?>"
                    required
                    minlength="2"
                >
            </div>

            <div class="form-group">
                <label for="author">Auteur *</label>
                <input
                    type="text"
                    id="author"
                    name="author"
                    class="form-control"
                    value="<?= htmlspecialchars($book['author']) ?>"
                    required
                    minlength="2"
                >
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                class="form-control"
                rows="6"
            ><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Changer la photo</label>

            <?php if (!empty($book['image'])): ?>
                <div class="current-photo">
                    <img src="<?= APP_URL . $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                    <p>Photo actuelle</p>
                </div>
            <?php endif; ?>

            <div class="file-upload-wrapper">
                <input
                    type="file"
                    id="photo"
                    name="photo"
                    class="form-control-file"
                    accept="image/jpeg,image/png,image/gif,image/webp"
                >
                <small class="form-help">Formats acceptés : JPG, PNG, GIF, WEBP (max 5MB). Laissez vide pour garder la photo actuelle.</small>
                <div id="preview" class="image-preview"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="available" <?= $book['available'] ? 'checked' : '' ?>>
                <span>Livre disponible à l'échange</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="<?= APP_URL ?>/account" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
// Prévisualisation de la nouvelle image
document.getElementById('photo').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Prévisualisation"><p>Nouvelle photo</p>';
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
        preview.style.display = 'none';
    }
});
</script>
