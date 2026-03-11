<?php
/**
 * FILE: admin/products.php
 * PURPOSE: List all products for admin management with search, filters, and pagination.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Catalogue Produits';

// Get Filters
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build Query
$where_clauses = [];
$params = [];

if ($search_query) {
    $where_clauses[] = "(p.product_name_fr LIKE :q1 OR p.product_name_ar LIKE :q2)";
    $params[':q1'] = "%$search_query%";
    $params[':q2'] = "%$search_query%";
}

if ($category_filter) {
    $where_clauses[] = "p.product_category_id = :category";
    $params[':category'] = $category_filter;
}

if ($status_filter !== '') {
    $where_clauses[] = "p.product_is_active = :status";
    $params[':status'] = (int)$status_filter;
}

$where_sql = !empty($where_clauses) ? " WHERE " . implode(" AND ", $where_clauses) : "";

// Pagination
$per_page = ORDERS_PER_PAGE_ADMIN;
$offset = ($current_page - 1) * $per_page;

$sql = "SELECT p.*, c.category_name_fr, c.category_name_ar 
        FROM hri_product p 
        LEFT JOIN hri_category c ON p.product_category_id = c.category_id 
        $where_sql 
        ORDER BY p.product_created_at DESC 
        LIMIT $per_page OFFSET $offset";

global $db_connection;
$stmt = $db_connection->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->execute();
$products = $stmt->fetchAll();

// Count total items for pagination
$count_sql = "SELECT COUNT(*) FROM hri_product p $where_sql";
$total_items = count_rows($count_sql, $params);
$total_pages = calculate_total_pages($total_items, $per_page);

$categories = get_all_categories();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
    <h2 style="margin: 0; font-weight: 800; font-size: 24px; color: var(--text-muted);">
        <span style="color: var(--carbon-black);"><?= $total_items ?></span> Produits au total
    </h2>
    <a href="<?= SITE_URL ?>/admin/product_add.php" class="btn btn-orange" style="padding: 16px 30px;">
        <i data-lucide="plus-circle" size="20"></i> Ajouter un produit
    </a>
</div>

<!-- Filters Bar -->
<div class="card" style="margin-bottom: 30px; padding: 20px;">
    <form action="" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 250px;">
            <label style="font-size: 12px; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; display: block;">RECHERCHER</label>
            <div style="position: relative;">
                <i data-lucide="search" size="18" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="Nom du produit (FR ou AR)..." class="form-control" style="padding-left: 45px;">
            </div>
        </div>
        
        <div style="width: 200px;">
            <label style="font-size: 12px; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; display: block;">CATÉGORIE</label>
            <select name="category" class="form-control">
                <option value="0">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= $category_filter == $cat['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name_fr']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="width: 150px;">
            <label style="font-size: 12px; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; display: block;">STATUT</label>
            <select name="status" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Visible</option>
                <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Masqué</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-orange" style="padding: 12px 25px;">Filtrer</button>
            <?php if ($search_query || $category_filter || $status_filter !== ''): ?>
                <a href="products.php" class="btn btn-light" style="padding: 12px 15px;" title="Réinitialiser">
                    <i data-lucide="refresh-ccw" size="18"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">Aperçu</th>
                    <th>Informations Produit</th>
                    <th>Catégorie</th>
                    <th>Prix & Promo</th>
                    <th>Stock</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <div style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden; border: 1px solid var(--gray-border); background: var(--gray-bg);">
                                <img src="<?= get_product_image_url($p['product_image']) ?>" 
                                     alt="" 
                                     loading="lazy"
                                     style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: var(--carbon-black); font-size: 16px;"><?= htmlspecialchars($p['product_name_fr']) ?></div>
                            <div style="font-size: 13px; color: var(--text-muted); font-weight: 700; margin-top: 2px;"><?= htmlspecialchars($p['product_name_ar']) ?></div>
                        </td>
                        <td>
                            <span style="font-size: 13px; font-weight: 800; background: var(--gray-bg); padding: 6px 14px; border-radius: 10px; color: var(--text-muted);">
                                <?= htmlspecialchars($p['category_name_fr']) ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 900; font-size: 16px;"><?= format_price($p['product_price']) ?></div>
                            <?php if ($p['product_is_on_sale']): ?>
                                <div style="font-size: 12px; color: var(--danger); font-weight: 800; margin-top: 4px;">
                                    <i data-lucide="trending-down" size="12"></i> <?= format_price($p['product_sale_price']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="font-weight: 800; font-size: 16px; color: <?= $p['product_stock_quantity'] <= 5 ? 'var(--danger)' : 'var(--success)' ?>;">
                                    <?= $p['product_stock_quantity'] ?>
                                </div>
                                <small style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">
                                    <?= translate('unit_' . $p['product_unit']) ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <?php if ($p['product_is_active']): ?>
                                <span class="status-pill status-success">VISIBLE</span>
                            <?php else: ?>
                                <span class="status-pill" style="background: #f1f5f9; color: #64748b;">MASQUÉ</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                <a href="<?= SITE_URL ?>/admin/product_edit.php?id=<?= $p['product_id'] ?>" class="btn btn-light" style="padding: 10px;" title="Modifier">
                                    <i data-lucide="edit-3" size="18"></i>
                                </a>
                                <a href="<?= SITE_URL ?>/admin/product_toggle.php?id=<?= $p['product_id'] ?>" class="btn btn-light" style="padding: 10px; color: <?= $p['product_is_active'] ? 'var(--princeton-orange)' : 'var(--success)' ?>" title="<?= $p['product_is_active'] ? 'Désactiver' : 'Activer' ?>">
                                    <i data-lucide="power" size="18"></i>
                                </a>
                                <button type="button" class="btn btn-light" style="padding: 10px; color: var(--danger);" onclick="if(confirm('Supprimer ce produit définitivement ?')) window.location.href='<?= SITE_URL ?>/admin/product_delete.php?id=<?= $p['product_id'] ?>'" title="Supprimer">
                                    <i data-lucide="trash-2" size="18"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 80px; font-weight: 700;">Aucun produit trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
    <div style="display: flex; justify-content: center; gap: 12px; margin-top: 40px;">
        <?php 
        $pagination_url = "products.php?q=" . urlencode($search_query) . "&category=" . $category_filter . "&status=" . $status_filter;
        ?>
        
        <?php if ($current_page > 1): ?>
            <a href="<?= $pagination_url ?>&page=<?= $current_page - 1 ?>" class="btn btn-light" style="padding: 12px 20px;">Précédent</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == 1 || $i == $total_pages || ($i >= $current_page - 2 && $i <= $current_page + 2)): ?>
                <a href="<?= $pagination_url ?>&page=<?= $i ?>" class="btn <?= ($i === $current_page) ? 'btn-orange' : 'btn-light' ?>" style="min-width: 50px; justify-content: center; font-weight: 900;">
                    <?= $i ?>
                </a>
            <?php elseif ($i == $current_page - 3 || $i == $current_page + 3): ?>
                <span style="padding: 10px;">...</span>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="<?= $pagination_url ?>&page=<?= $current_page + 1 ?>" class="btn btn-light" style="padding: 12px 20px;">Suivant</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<style>
    tr:hover td { background-color: #fcfcfc; }
    img:hover { transform: scale(1.1); }
</style>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
