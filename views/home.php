<?php
/**
 * Vue : Page d'accueil
 *
 * Affiche la landing page avec hero, derniers livres, comment ça marche, et nos valeurs
 *
 * Variables attendues :
 * @var array $latestBooks Derniers livres ajoutés (max 4)
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
?>

<!-- Section Hero -->
<section class="hero-home">
    <div class="hero-home-content">
        <h1 class="hero-home-title">Rejoignez nos lecteurs passionnés</h1>
        <p class="hero-home-description">
            Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture.
            Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
        </p>
        <a href="<?= APP_URL ?>/books" class="btn btn-primary">Découvrir</a>
    </div>
    <div class="hero-home-image">
        <img src="<?= APP_URL ?>/images/hero-books.png" alt="Pile de livres dans une librairie">
        <div class="hero-author-caption">Hamza</div>
    </div>
</section>

<!-- Section Les derniers livres ajoutés -->
<section class="section-latest-books">
    <h2 class="section-title">Les derniers livres ajoutés</h2>

    <?php if (!empty($latestBooks)): ?>
        <div class="books-grid-home">
            <?php foreach ($latestBooks as $book): ?>
                <a href="<?= APP_URL ?>/books/<?= $book['id'] ?>" class="book-card-home">
                    <div class="book-card-image">
                        <?php if (!empty($book['image'])): ?>
                            <img src="<?= APP_URL . $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        <?php else: ?>
                            <div class="book-placeholder">📚</div>
                        <?php endif; ?>
                    </div>
                    <div class="book-card-info">
                        <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                        <p class="book-author"><?= htmlspecialchars($book['author']) ?></p>
                        <p class="book-owner">Vendu par : <?= htmlspecialchars($book['owner_pseudo']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <a href="<?= APP_URL ?>/books" class="btn btn-primary">Voir tous les livres</a>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Aucun livre disponible pour le moment.</p>
    <?php endif; ?>
</section>

<!-- Section Comment ça marche ? -->
<section class="section-how-it-works">
    <h2 class="section-title">Comment ça marche ?</h2>
    <p class="section-subtitle">
        Échanger des livres avec TomTroc c'est simple et amusant ! Suivez ces étapes pour commencer :
    </p>

    <div class="steps-grid">
        <div class="step-card">
            <p>Inscrivez-vous gratuitement sur notre plateforme.</p>
        </div>
        <div class="step-card">
            <p>Ajoutez les livres que vous souhaitez échanger à votre bibliothèque.</p>
        </div>
        <div class="step-card">
            <p>Parcourez les livres disponibles chez d'autres membres.</p>
        </div>
        <div class="step-card">
            <p>Proposez un échange et discutez avec d'autres passionnés.</p>
        </div>
    </div>

    <div class="text-center">
        <a href="<?= APP_URL ?>/books" class="btn btn-green-outline">Voir tous les livres</a>
    </div>
</section>

<!-- Section Nos valeurs -->
<section class="section-values">
    <!-- Bannière image en haut -->
    <div class="values-banner">
        <img src="<?= APP_URL ?>/images/values-background.png" alt="Bibliothèque">
    </div>
    
    <!-- Contenu texte sur fond blanc -->
    <div class="values-content">
        <h2 class="section-title">Nos valeurs</h2>
        <div class="values-text">
            <p>
                Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos
                valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les
                lecteurs. Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des
                conversations enrichissantes.
            </p>
            <p>
                Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé.
            </p>
            <p>
                Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se
                connecter, de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment
                sur les étagères.
            </p>
        </div>
        <div class="values-signature">
            <p class="signature-text">L'équipe Tom Troc</p>
            <img src="<?= APP_URL ?>/images/coeur-signature.svg" alt="Cœur" class="heart-icon">
        </div>
    </div>
</section>
