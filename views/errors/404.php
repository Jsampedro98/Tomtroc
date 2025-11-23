<?php
/**
 * Vue : Page d'erreur 404
 *
 * Affichée lorsqu'une page n'est pas trouvée
 *
 * @package    TomTroc
 * @subpackage Views
 */

// Définir le titre de la page
$title = 'Page non trouvée';

// Inclure le header
require_once VIEWS_PATH . '/layout/header.php';
?>

<div class="error-404-container">
    <div class="error-404-content">
        <div class="error-404-illustration">
            <span class="error-404-number">404</span>
        </div>

        <h1 class="error-404-title">Page non trouvée</h1>

        <p class="error-404-description">
            La page que vous recherchez n'existe pas ou a été déplacée.
        </p>

        <a href="<?= APP_URL ?>" class="btn btn-primary">
            Retour à l'accueil
        </a>
    </div>
</div>

<style>
.error-404-container {
    min-height: calc(100vh - 80px - 90px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 8%;
    background-color: #F5F3EF;
}

.error-404-content {
    max-width: 600px;
    text-align: center;
    background: white;
    padding: 80px 60px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.error-404-illustration {
    margin-bottom: 40px;
}

.error-404-number {
    font-family: 'Playfair Display', serif;
    font-size: 8rem;
    font-weight: 700;
    color: #00AC66;
    line-height: 1;
    display: block;
    opacity: 0.2;
}

.error-404-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 400;
    color: #292929;
    margin: 0 0 20px 0;
}

.error-404-description {
    font-family: 'Inter', sans-serif;
    font-size: 1.125rem;
    color: #666666;
    line-height: 1.7;
    margin: 0 0 40px 0;
}

@media (max-width: 768px) {
    .error-404-content {
        padding: 60px 30px;
    }

    .error-404-number {
        font-size: 5rem;
    }

    .error-404-title {
        font-size: 2rem;
    }

    .error-404-description {
        font-size: 1rem;
    }
}
</style>

<?php
// Inclure le footer
require_once VIEWS_PATH . '/layout/footer.php';
?>
