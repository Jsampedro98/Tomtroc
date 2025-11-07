<?php
/**
 * Vue : Page Mon compte
 *
 * Affiche le profil de l'utilisateur connecté avec :
 * - Photo de profil (avec upload)
 * - Informations personnelles (pseudo, email, mot de passe)
 * - Bibliothèque de livres de l'utilisateur
 *
 * Variables attendues :
 * @var array $user  Données de l'utilisateur connecté (id, pseudo, email, photo, created_at)
 * @var array $books Liste des livres de l'utilisateur (vide en Phase 2, remplie en Phase 3)
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Phase 2
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

            <button type="button" class="profile-photo-edit" onclick="document.getElementById('photoUploadInput').click()">modifier</button>

            <form id="photoUploadForm" method="POST" action="<?= APP_URL ?>/account/upload-photo" enctype="multipart/form-data" style="display: none;">
                <input type="file" id="photoUploadInput" name="profile_photo" accept="image/*" onchange="document.getElementById('photoUploadForm').submit()">
            </form>
        </div>

        <h2 class="profile-name"><?= htmlspecialchars($user['pseudo']) ?></h2>
        <p class="profile-member-since">Membre depuis <?= date('n') >= date('n', strtotime($user['created_at'])) ? date('Y') - date('Y', strtotime($user['created_at'])) : date('Y') - date('Y', strtotime($user['created_at'])) - 1 ?> an<?= (date('Y') - date('Y', strtotime($user['created_at']))) > 1 ? 's' : '' ?></p>

        <div class="profile-library-info">
            <span class="library-label">BIBLIOTHEQUE</span>
            <span class="library-count">📚 <?= count($books) ?> livre<?= count($books) > 1 ? 's' : '' ?></span>
        </div>
    </div>

    <div class="account-main">
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

        <h3 class="account-section-title">Vos informations personnelles</h3>

        <form method="POST" action="<?= APP_URL ?>/account/update" class="account-form">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="<?= isset($_SESSION['old_email']) ? htmlspecialchars($_SESSION['old_email']) : htmlspecialchars($user['email']) ?>"
                    required
                >
                <?php unset($_SESSION['old_email']); ?>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="current_password"
                    class="form-control"
                    value="•••••••••"
                    readonly
                    onfocus="this.value=''; this.removeAttribute('readonly');"
                >
                <small class="form-help">Laissez vide pour ne pas modifier</small>
            </div>

            <div class="form-group" id="newPasswordGroup" style="display: none;">
                <label for="new_password">Nouveau mot de passe</label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    class="form-control"
                    minlength="6"
                >
            </div>

            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input
                    type="text"
                    id="pseudo"
                    name="pseudo"
                    class="form-control"
                    value="<?= isset($_SESSION['old_pseudo']) ? htmlspecialchars($_SESSION['old_pseudo']) : htmlspecialchars($user['pseudo']) ?>"
                    required
                    minlength="3"
                >
                <?php unset($_SESSION['old_pseudo']); ?>
            </div>

            <button type="submit" class="btn btn-primary-outline">Enregistrer</button>
        </form>
    </div>
</div>

<div class="library-section">
    <div class="library-header">
        <h3 class="library-section-title">BIBLIOTHEQUE</h3>
        <a href="<?= APP_URL ?>/books/create" class="btn btn-primary">Ajouter un livre</a>
    </div>

    <?php if (!empty($books)): ?>
        <table class="library-table">
            <thead>
                <tr>
                    <th>PHOTO</th>
                    <th>TITRE</th>
                    <th>AUTEUR</th>
                    <th>DESCRIPTION</th>
                    <th>DISPONIBILITE</th>
                    <th>ACTION</th>
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
                    <td class="library-td-availability">
                        <span class="library-badge library-badge-<?= $book['available'] ? 'available' : 'unavailable' ?>">
                            <?= $book['available'] ? 'disponible' : 'non dispo.' ?>
                        </span>
                    </td>
                    <td class="library-td-actions">
                        <a href="<?= APP_URL ?>/books/<?= $book['id'] ?>/edit" class="library-action-link">Éditer</a>
                        <form method="POST" action="<?= APP_URL ?>/books/<?= $book['id'] ?>/delete" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                            <button type="submit" class="library-action-link library-action-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="library-empty">
            <p>📚 Vous n'avez pas encore ajouté de livres à votre bibliothèque.</p>
            <a href="<?= APP_URL ?>/books/create" class="btn btn-primary">Ajouter mon premier livre</a>
        </div>
    <?php endif; ?>
</div>

<script>
// Afficher le champ nouveau mot de passe si l'utilisateur modifie le mot de passe actuel
document.getElementById('password').addEventListener('input', function() {
    if (this.value && this.value !== '•••••••••') {
        document.getElementById('newPasswordGroup').style.display = 'block';
        document.getElementById('new_password').required = true;
    } else {
        document.getElementById('newPasswordGroup').style.display = 'none';
        document.getElementById('new_password').required = false;
    }
});
</script>
