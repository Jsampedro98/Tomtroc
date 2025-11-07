<?php
/**
 * Vue : Page publique des livres à l'échange
 *
 * Affiche tous les livres disponibles sous forme de grille de cartes.
 * Permet la recherche par titre ou auteur.
 * Affiche le badge de disponibilité selon la maquette.
 *
 * Variables attendues :
 * @var array $books Liste de tous les livres avec infos propriétaire
 * @var string $search Terme de recherche (optionnel)
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Phase 4
 */
?>

<div class="books-public-container">
    <div class="books-public-header">
        <h1>Nos livres à l'échange</h1>

        <form method="GET" action="<?= APP_URL ?>/books" class="books-search-form">
            <input
                type="text"
                name="search"
                placeholder="Rechercher un livre"
                value="<?= htmlspecialchars($search ?? '') ?>"
                class="books-search-input"
            >
        </form>
    </div>

    <?php if (!empty($books)): ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
                <a href="<?= APP_URL ?>/books/<?= $book['id'] ?>" class="book-card">
                    <div class="book-card-image">
                        <?php if (!empty($book['image'])): ?>
                            <img src="<?= APP_URL . $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        <?php else: ?>
                            <div class="book-card-placeholder">📚</div>
                        <?php endif; ?>

                        <?php if (!$book['available']): ?>
                            <span class="book-card-badge-unavailable">non dispo.</span>
                        <?php endif; ?>
                    </div>

                    <div class="book-card-content">
                        <h3 class="book-card-title"><?= htmlspecialchars($book['title']) ?></h3>
                        <p class="book-card-author"><?= htmlspecialchars($book['author']) ?></p>
                        <p class="book-card-owner">Vendu par : <?= htmlspecialchars($book['owner_pseudo']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="books-empty">
            <p>Aucun livre trouvé<?= !empty($search) ? ' pour "' . htmlspecialchars($search) . '"' : '' ?>.</p>
        </div>
    <?php endif; ?>
</div>
