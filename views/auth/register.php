<div class="auth-split-container">
    <div class="auth-form-section">
        <div class="auth-form-wrapper">
            <h1>Inscription</h1>

            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-error">
                    <?= $_SESSION['flash_error'] ?>
                    <?php unset($_SESSION['flash_error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/register">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input
                        type="text"
                        id="pseudo"
                        name="pseudo"
                        class="form-control"
                        value="<?= $_SESSION['old_pseudo'] ?? '' ?>"
                        required
                        minlength="3"
                    >
                    <?php unset($_SESSION['old_pseudo']); ?>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="<?= $_SESSION['old_email'] ?? '' ?>"
                        required
                    >
                    <?php unset($_SESSION['old_email']); ?>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required
                        minlength="6"
                    >
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-control"
                        required
                        minlength="6"
                    >
                </div>

                <button type="submit" class="btn btn-primary">S'inscrire</button>

                <div class="form-footer">
                    Déjà inscrit ? <a href="<?= APP_URL ?>/login">Connectez-vous</a>
                </div>
            </form>
        </div>
    </div>

    <div class="auth-image-section">
        <img src="<?= APP_URL ?>/images/Register-login-image.jpg" alt="Bibliothèque de livres">
    </div>
</div>
