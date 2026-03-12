<?php
/**
 * FILE: admin/index.php
 * PURPOSE: Admin dashboard. Shows today's orders, revenue, pending count, low stock alerts.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Tableau de Bord';

// Fetch Statistics
$today_orders   = get_today_orders_count();
$today_revenue  = get_today_revenue();
$pending_orders = get_pending_orders_count();
$low_stock      = get_low_stock_products_count();
$total_products = get_admin_total_products_count();
$total_customers = get_admin_total_customers_count();

// Recent Orders
$recent_orders = fetch_all("SELECT * FROM hri_order ORDER BY order_created_at DESC LIMIT 5");

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="stats-grid">
    <a href="orders.php" class="stat-box">
        <small>Commandes (Auj)</small>
        <h2><?= $today_orders ?></h2>
    </a>
    <div class="stat-box">
        <small>Revenu (Auj)</small>
        <h2 style="color: var(--success);"><?= format_price($today_revenue) ?></h2>
    </div>
    <a href="orders.php?status=pending" class="stat-box">
        <small>En Attente</small>
        <h2 style="color: var(--princeton-orange);"><?= $pending_orders ?></h2>
    </a>
    <a href="products.php?stock=low" class="stat-box">
        <small>Stock Faible</small>
        <h2 style="color: var(--danger);"><?= $low_stock ?></h2>
    </a>
</div>

<div style="display: grid; grid-template-columns: 2.5fr 1fr; gap: 30px; align-items: start;">
    <!-- Recent Orders Table -->
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
            <h3 style="margin:0; font-weight: 900; font-size: 22px;">Dernières Commandes</h3>
            <a href="<?= SITE_URL ?>/admin/orders.php" class="btn btn-light">Toutes les commandes</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td style="font-weight: 900; color: var(--princeton-orange); font-size: 16px;">#<?= $order['order_number'] ?></td>
                            <td>
                                <div style="font-weight: 800; font-size: 16px; color: var(--carbon-black);"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                                <div style="font-size: 13px; color: var(--text-muted); font-weight: 700; margin-top: 2px;"><?= htmlspecialchars($order['order_customer_phone']) ?></div>
                            </td>
                            <td style="font-weight: 900; font-size: 16px;"><?= format_price($order['order_total']) ?></td>
                            <td>
                                <?php 
                                $status_class = 'status-pending';
                                switch($order['order_status']) {
                                    case 'pending': $status_class = 'status-pending'; break;
                                    case 'confirmed': $status_class = 'status-info'; break;
                                    case 'preparing': $status_class = 'status-info'; break;
                                    case 'out_for_delivery': $status_class = 'status-info'; break;
                                    case 'delivered': $status_class = 'status-success'; break;
                                    case 'cancelled': $status_class = 'status-danger'; break;
                                }
                                ?>
                                <span class="status-pill <?= $status_class ?>"><?= translate('status_' . $order['order_status']) ?></span>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= SITE_URL ?>/admin/order_detail.php?id=<?= $order['order_id'] ?>" class="btn btn-light" style="padding: 10px 15px;">
                                    <i data-lucide="eye" size="18"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_orders)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 50px; font-weight: 700;">Aucune commande récente</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Global Summary & Quick Actions -->
    <div style="display: flex; flex-direction: column; gap: 30px;">
        <div class="card" style="padding: 30px;">
            <h3 style="margin:0 0 25px 0; font-weight: 900; font-size: 18px;">Résumé Global</h3>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; background: var(--gray-bg); padding: 15px 20px; border-radius: 14px;">
                    <span style="font-weight: 700; color: var(--text-muted); font-size: 14px;">Total Produits</span>
                    <span style="font-weight: 900; color: var(--carbon-black); font-size: 18px;"><?= $total_products ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; background: var(--gray-bg); padding: 15px 20px; border-radius: 14px;">
                    <span style="font-weight: 700; color: var(--text-muted); font-size: 14px;">Total Clients</span>
                    <span style="font-weight: 900; color: var(--carbon-black); font-size: 18px;"><?= $total_customers ?></span>
                </div>
            </div>
        </div>

        <div class="card" style="background: var(--carbon-black); color: white; border: none; padding: 35px;">
            <h3 style="margin:0 0 25px 0; font-weight: 900; font-size: 18px;">Actions Rapides</h3>
            <div style="display: flex; gap: 15px;">
                <a href="<?= SITE_URL ?>/admin/product_add.php" class="btn btn-orange" style="width: 100%; justify-content: center; padding: 18px;">
                    <i data-lucide="plus-circle" size="20"></i> Nouveau Produit
                </a>
                <a href="<?= SITE_URL ?>/admin/settings.php" class="btn" style="width: 100%; justify-content: center; background: rgba(255,255,255,0.1); color: white; padding: 18px;">
                    <i data-lucide="settings" size="20"></i> Paramètres du store
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>

