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

<div class="container-fluid">
    <div class="row g-4 mb-4">
        <!-- Stats Cards -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Commandes Aujourd'hui</h6>
                        <i class="bi bi-cart-check fs-4 text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-0"><?= $today_orders ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Revenu Aujourd'hui</h6>
                        <i class="bi bi-cash-stack fs-4 text-success"></i>
                    </div>
                    <h3 class="fw-bold mb-0"><?= format_price($today_revenue) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Commandes En Attente</h6>
                        <i class="bi bi-clock-history fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-0"><?= $pending_orders ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Stock Faible</h6>
                        <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                    </div>
                    <h3 class="fw-bold mb-0"><?= $low_stock ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Dernières Commandes</h5>
                    <a href="<?= SITE_URL ?>/admin/orders.php" class="btn btn-sm btn-link text-decoration-none">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th class="ps-4">N° Commande</th>
                                    <th>Client</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold">#<?= $order['order_number'] ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($order['order_customer_phone']) ?></small>
                                        </td>
                                        <td class="fw-bold text-primary"><?= format_price($order['order_total']) ?></td>
                                        <td>
                                            <?php 
                                            $status_class = 'bg-secondary';
                                            switch($order['order_status']) {
                                                case 'pending': $status_class = 'bg-warning text-dark'; break;
                                                case 'confirmed': $status_class = 'bg-info'; break;
                                                case 'preparing': $status_class = 'bg-primary'; break;
                                                case 'out_for_delivery': $status_class = 'bg-info'; break;
                                                case 'delivered': $status_class = 'bg-success'; break;
                                                case 'cancelled': $status_class = 'bg-danger'; break;
                                            }
                                            ?>
                                            <span class="badge <?= $status_class ?> rounded-pill px-3"><?= translate('status_' . $order['order_status']) ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="<?= SITE_URL ?>/admin/order_detail.php?id=<?= $order['order_id'] ?>" class="btn btn-sm btn-light border">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">Résumé Global</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Total Produits</span>
                            <span class="badge bg-light text-dark rounded-pill"><?= $total_products ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Total Clients</span>
                            <span class="badge bg-light text-dark rounded-pill"><?= $total_customers ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="card border-0 shadow-sm rounded-3 bg-dark text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Actions Rapides</h5>
                    <div class="d-grid gap-2">
                        <a href="<?= SITE_URL ?>/admin/product_add.php" class="btn btn-primary border-0">
                            <i class="bi bi-plus-circle me-2"></i> Nouveau Produit
                        </a>
                        <a href="<?= SITE_URL ?>/admin/settings.php" class="btn btn-outline-light border-secondary">
                            <i class="bi bi-gear me-2"></i> Paramètres du Magasin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
