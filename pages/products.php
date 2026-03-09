<?php
/**
 * FILE: pages/products.php
 * PURPOSE: Browse products with filter sidebar (Jumia inspired).
 */

require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

// Filters & Pagination
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search_query = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';

// Data fetching logic (simplified for UI)
if (!empty($search_query)) {
    $product_list = search_products($search_query);
    $total_items = count($product_list);
    $page_title = ($current_language === 'ar' ? 'نتائج البحث عن: ' : 'Résultats pour: ') . '"' . htmlspecialchars($search_query) . '"';
} elseif ($category_id > 0) {
    $product_list = get_products_by_category($category_id, $current_page);
    $total_items = count_rows("SELECT COUNT(*) FROM hri_product WHERE product_category_id = :id AND product_is_active = 1", [':id' => $category_id]);
    $category_data = get_category_by_id($category_id);
    $page_title = get_category_name($category_data);
} else {
    $product_list = get_all_products($current_page);
    $total_items = get_total_products_count();
    $page_title = $lang['all_products'];
}

$total_pages = calculate_total_pages($total_items, PRODUCTS_PER_PAGE);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <div class="row g-4">
        
        <!-- Desktop: Filter Sidebar (25%) -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 4px;">
                <h6 class="fw-bold text-uppercase mb-3"><?php echo $lang['categories'] ?? 'Catégories'; ?></h6>
                <div class="list-group list-group-flush mb-4">
                    <?php 
                    $active_cats = get_active_categories();
                    foreach ($active_cats as $cat): 
                    ?>
                        <a href="?category=<?php echo $cat['category_id']; ?>" class="list-group-item list-group-item-action border-0 px-0 small <?php echo $category_id == $cat['category_id'] ? 'text-primary fw-bold' : ''; ?>">
                            <?php echo htmlspecialchars($current_language === 'ar' ? $cat['category_name_ar'] : $cat['category_name_fr']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <hr>

                <h6 class="fw-bold text-uppercase mb-3">MARQUE</h6>
                <div class="mb-4">
                    <input type="text" class="form-control form-control-sm mb-2" placeholder="Chercher une marque...">
                    <div class="form-check small">
                        <input class="form-check-input" type="checkbox" value="" id="brand1">
                        <label class="form-check-label" for="brand1">Centrale Danone</label>
                    </div>
                    <div class="form-check small">
                        <input class="form-check-input" type="checkbox" value="" id="brand2">
                        <label class="form-check-label" for="brand2">Jaouda</label>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold text-uppercase mb-3">LIVRAISON RAPIDE</h6>
                <div class="form-check small mb-4">
                    <input class="form-check-input" type="checkbox" value="" id="fastDelivery">
                    <label class="form-check-label" for="fastDelivery"><i class="bi bi-truck text-primary me-1"></i> Express</label>
                </div>

                <hr>

                <h6 class="fw-bold text-uppercase mb-3">REMISE</h6>
                <div class="mb-3">
                    <div class="form-check small">
                        <input class="form-check-input" type="checkbox" value="" id="discount1">
                        <label class="form-check-label" for="discount1">50% ou plus</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid (75%) -->
        <div class="col-lg-9 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h5 fw-bold mb-0"><?php echo $page_title; ?></h1>
                <div class="d-none d-lg-block">
                    <select class="form-select form-select-sm" style="width: 200px;">
                        <option>Trier par: Popularité</option>
                        <option>Prix: Le moins cher</option>
                        <option>Prix: Le plus cher</option>
                        <option>Nouveautés</option>
                    </select>
                </div>
            </div>

            <?php if (empty($product_list)): ?>
                <div class="card border-0 shadow-sm p-5 text-center">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <p class="text-muted">Aucun produit trouvé.</p>
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
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo $category_id; ?>&q=<?php echo urlencode($search_query); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
