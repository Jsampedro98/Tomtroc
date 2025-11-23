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
 * @since      Version 1.0
 */
?>

<div class="book-form-page">
    <a href="<?= APP_URL ?>/account" class="book-form-back">← retour</a>

    <h1 class="book-form-title">Modifier les informations</h1>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['flash_error'] ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/books/<?= $book['id'] ?>/update" enctype="multipart/form-data" class="book-form-grid">
        <div class="book-form-photo-section">
            <label>Photo</label>

            <?php if (!empty($book['image'])): ?>
                <div class="book-photo-preview">
                    <img src="<?= APP_URL . $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                </div>
            <?php else: ?>
                <div class="book-photo-placeholder">
                    📚
                </div>
            <?php endif; ?>

            <button type="button" class="book-photo-change" onclick="document.getElementById('photo').click()">modifier la photo</button>

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
                    value="<?= htmlspecialchars($book['title']) ?>"
                    required
                    minlength="2"
                >
            </div>

            <div class="form-group">
                <label for="author">Auteur</label>
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

            <div class="form-group">
                <label for="description">Commentaire</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="10"
                ><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="available">Disponibilité</label>
                <select id="available" name="available" class="form-control">
                    <option value="1" <?= $book['available'] ? 'selected' : '' ?>>disponible</option>
                    <option value="0" <?= !$book['available'] ? 'selected' : '' ?>>non disponible</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Valider</button>
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
