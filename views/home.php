<div class="home-container">
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue sur <?= APP_NAME ?></h1>
            <p class="hero-description">
                Rejoignez notre communauté de lecteurs passionnés et échangez vos livres préférés.
            </p>
            <div class="hero-buttons">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?= APP_URL ?>/register" class="btn btn-primary">Rejoindre la communauté</a>
                    <a href="<?= APP_URL ?>/books" class="btn btn-secondary">Découvrir les livres</a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/books" class="btn btn-primary">Voir les livres disponibles</a>
                    <a href="<?= APP_URL ?>/my-books" class="btn btn-secondary">Ma bibliothèque</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2>Comment ça marche ?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>1. Inscrivez-vous</h3>
                    <p>Créez votre compte gratuitement en quelques secondes.</p>
                </div>
                <div class="feature-card">
                    <h3>2. Ajoutez vos livres</h3>
                    <p>Partagez les livres que vous souhaitez échanger avec la communauté.</p>
                </div>
                <div class="feature-card">
                    <h3>3. Découvrez</h3>
                    <p>Parcourez les livres disponibles et trouvez votre prochaine lecture.</p>
                </div>
                <div class="feature-card">
                    <h3>4. Échangez</h3>
                    <p>Contactez les propriétaires et organisez vos échanges de livres.</p>
                </div>
            </div>
        </div>
    </section>
</div>
