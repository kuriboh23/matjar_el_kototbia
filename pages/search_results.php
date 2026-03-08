<?php
/**
 * FILE: pages/search_results.php
 * PURPOSE: Display products matching the search query from URL ?q= param.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$query = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';

if (empty($query)) {
    redirect(SITE_URL . '/pages/products.php');
}

$product_list = search_products($query);
$total_items = count($product_list);

$page_title = translate('search_placeholder') . ': ' . htmlspecialchars($query) . ' - ' . $lang['site_name'];

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php" class="text-decoration-none text-muted"><?= translate('home') ?></a></li>
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/pages/products.php" class="text-decoration-none text-muted"><?= translate('products') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= translate('search_results') ?></li>
        </ol>
    </nav>

    <div class="mb-4">
        <h1 class="h3 fw-bold mb-0">
            <?= translate('search_results') ?>: <span class="text-primary">"<?= htmlspecialchars($query) ?>"</span>
            <span class="text-muted small fw-normal ms-2">(<?= $total_items ?>)</span>
        </h1>
    </div>

    <?php if (empty($product_list)): ?>
        <div class="text-center py-5">
            <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
            <p class="text-muted">Désolé, aucun produit ne correspond à votre recherche.</p>
            <a href="<?= SITE_URL ?>/pages/products.php" class="btn hri-btn-orange text-white fw-bold">
                <?= translate('view_all') ?>
            </a>
        </div>
    <?php else: ?>
        <div class="row g-2 g-md-3">
            <?php foreach ($product_list as $product_data): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php include __DIR__ . '/_product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
