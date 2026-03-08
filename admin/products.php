<?php
/**
 * FILE: admin/products.php
 * PURPOSE: List all products for admin management.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Gestion des Produits';

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$products = admin_get_all_products($current_page);
$total_items = count_rows("SELECT COUNT(*) FROM hri_product");
$total_pages = calculate_total_pages($total_items, ORDERS_PER_PAGE_ADMIN);

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0"><?= $admin_page_title ?> <span class="text-muted small fw-normal">(<?= $total_items ?>)</span></h2>
        <a href="<?= SITE_URL ?>/admin/product_add.php" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Nouveau Produit
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-uppercase">
                            <th class="ps-4" style="width: 80px;">Image</th>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <img src="<?= get_product_image_url($p['product_image']) ?>" alt="" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($p['product_name_fr']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($p['product_name_ar']) ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($p['category_name_fr']) ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= format_price($p['product_price']) ?></div>
                                    <?php if ($p['product_is_on_sale']): ?>
                                        <small class="text-danger">Promo: <?= format_price($p['product_sale_price']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="<?= $p['product_stock_quantity'] <= 10 ? 'text-danger fw-bold' : '' ?>">
                                        <?= $p['product_stock_quantity'] ?> <?= $p['product_unit'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($p['product_is_active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="<?= SITE_URL ?>/admin/product_edit.php?id=<?= $p['product_id'] ?>" class="btn btn-sm btn-light border" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= SITE_URL ?>/admin/product_toggle.php?id=<?= $p['product_id'] ?>" class="btn btn-sm <?= $p['product_is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?> border" title="<?= $p['product_is_active'] ? 'Désactiver' : 'Activer' ?>">
                                            <i class="bi bi-power"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger border" onclick="if(confirm('Supprimer ce produit ?')) window.location.href='<?= SITE_URL ?>/admin/product_delete.php?id=<?= $p['product_id'] ?>'" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i === $current_page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
