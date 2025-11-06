<div class="auth-split-container">
    <div class="auth-form-section">
        <div class="auth-form-wrapper">
            <h1>Connexion</h1>

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

            <form method="POST" action="<?= APP_URL ?>/login">
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
                    >
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button>

                <div class="form-footer">
                    Pas de compte ? <a href="<?= APP_URL ?>/register">Inscrivez-vous</a>
                </div>
            </form>
        </div>
    </div>

    <div class="auth-image-section">
        <img src="<?= APP_URL ?>/images/Register-login-image.jpg" alt="Bibliothèque de livres">
    </div>
</div>
