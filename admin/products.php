<?php
/**
 * FILE: admin/products.php
 * PURPOSE: List all products for admin management.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Catalogue Produits';

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$products = admin_get_all_products($current_page);
$total_items = count_rows("SELECT COUNT(*) FROM hri_product");
$total_pages = calculate_total_pages($total_items, ORDERS_PER_PAGE_ADMIN);

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2 style="margin: 0; font-weight: 800; font-size: 24px; color: var(--text-muted);">
        <span style="color: var(--carbon-black);"><?= $total_items ?></span> Produits au total
    </h2>
    <a href="<?= SITE_URL ?>/admin/product_add.php" class="btn btn-orange" style="padding: 16px 30px;">
        <i data-lucide="plus-circle" size="20"></i> Ajouter un produit
    </a>
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
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
    <div style="display: flex; justify-content: center; gap: 12px; margin-top: 40px;">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?>" class="btn btn-light" style="padding: 12px 20px;">Précédent</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == 1 || $i == $total_pages || ($i >= $current_page - 2 && $i <= $current_page + 2)): ?>
                <a href="?page=<?= $i ?>" class="btn <?= ($i === $current_page) ? 'btn-orange' : 'btn-light' ?>" style="min-width: 50px; justify-content: center; font-weight: 900;">
                    <?= $i ?>
                </a>
            <?php elseif ($i == $current_page - 3 || $i == $current_page + 3): ?>
                <span style="padding: 10px;">...</span>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?>" class="btn btn-light" style="padding: 12px 20px;">Suivant</a>
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
