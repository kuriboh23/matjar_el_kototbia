<?php
/**
 * FILE: pages/category.php
 * PURPOSE: Show products filtered by a specific category slug from URL.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$slug = $_GET['slug'] ?? '';
$category = get_category_by_slug($slug);

if (!$category) {
    redirect(SITE_URL . '/pages/products.php');
}

$category_id = (int)$category['category_id'];
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$product_list = get_products_by_category($category_id, $current_page);
$total_items  = count_rows("SELECT COUNT(*) FROM hri_product WHERE product_category_id = :id AND product_is_active = 1", [':id' => $category_id]);
$total_pages = calculate_total_pages($total_items, PRODUCTS_PER_PAGE);

$category_name = get_category_name($category);
$page_title = $category_name . ' - ' . $lang['site_name'];

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php" class="text-decoration-none text-muted"><?= translate('home') ?></a></li>
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/pages/products.php" class="text-decoration-none text-muted"><?= translate('products') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($category_name) ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">
            <?= htmlspecialchars($category_name) ?>
            <span class="text-muted small fw-normal ms-2">(<?= $total_items ?>)</span>
        </h1>
    </div>

    <?php if (empty($product_list)): ?>
        <div class="text-center py-5">
            <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
            <p class="text-muted"><?= translate('no_products_found') ?></p>
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

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Product navigation" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i === $current_page) ? 'active' : '' ?>">
                            <a class="page-link" href="?slug=<?= urlencode($slug) ?>&page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
