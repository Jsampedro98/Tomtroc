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
 * @var string $availableOnly Filtre disponibilité (optionnel)
 * @var int $currentPage Page actuelle
 * @var int $totalPages Nombre total de pages
 *
 * @package    TomTroc
 * @subpackage Views
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
?>

<div class="books-public-container">
    <div class="books-public-header">
        <h1>Nos livres à l'échange</h1>

        <div class="books-filters">
            <form method="GET" action="<?= APP_URL ?>/books" class="books-search-form" id="searchForm">
                <input
                    type="text"
                    name="search"
                    placeholder="Rechercher un livre"
                    value="<?= htmlspecialchars($search ?? '') ?>"
                    class="books-search-input"
                >
                <input type="hidden" name="available_only" id="availableOnlyHidden" value="<?= htmlspecialchars($availableOnly ?? '') ?>">
            </form>

            <div class="books-filter-toggle">
                <label class="filter-checkbox-label">
                    <input
                        type="checkbox"
                        id="availableOnlyCheckbox"
                        <?= (!empty($availableOnly) && $availableOnly === '1') ? 'checked' : '' ?>
                    >
                    <span>Disponibles uniquement</span>
                </label>
            </div>
        </div>
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

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= APP_URL ?>/books?page=<?= $currentPage - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($availableOnly) ? '&available_only=1' : '' ?>" class="pagination-btn">
                        ← Précédent
                    </a>
                <?php endif; ?>

                <div class="pagination-numbers">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $currentPage): ?>
                            <span class="pagination-number pagination-current"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= APP_URL ?>/books?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($availableOnly) ? '&available_only=1' : '' ?>" class="pagination-number">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= APP_URL ?>/books?page=<?= $currentPage + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($availableOnly) ? '&available_only=1' : '' ?>" class="pagination-btn">
                        Suivant →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="books-empty">
            <p>Aucun livre trouvé<?= !empty($search) ? ' pour "' . htmlspecialchars($search) . '"' : '' ?>.</p>
        </div>
    <?php endif; ?>
</div>

<script>
// Soumettre le formulaire quand on coche/décoche "Disponibles uniquement"
document.getElementById('availableOnlyCheckbox').addEventListener('change', function() {
    const hiddenInput = document.getElementById('availableOnlyHidden');
    hiddenInput.value = this.checked ? '1' : '';
    document.getElementById('searchForm').submit();
});
</script>
