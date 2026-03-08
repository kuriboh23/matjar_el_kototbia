<?php
/**
 * FILE: pages/order_history.php
 * PURPOSE: List of customer past orders with status. Requires login.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Auth Check
require_once __DIR__ . '/../includes/auth_check.php';

global $lang;
$customer_id = get_current_customer_id();
$customer = get_customer_by_id($customer_id);
$orders = get_orders_by_customer($customer_id);

// Page title
$page_title = translate('order_history') . ' - ' . $lang['site_name'];

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-4 text-center border-bottom bg-light">
                        <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; background: var(--color-primary); color: white; font-size: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <?= strtoupper(substr($customer['customer_full_name'], 0, 1)) ?>
                        </div>
                        <h6 class="fw-bold mb-0"><?= htmlspecialchars($customer['customer_full_name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($customer['customer_phone']) ?></small>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?= SITE_URL ?>/pages/profile.php" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                            <i class="bi bi-person-circle me-3 fs-5"></i> <?= translate('profile') ?>
                        </a>
                        <a href="<?= SITE_URL ?>/pages/order_history.php" class="list-group-item list-group-item-action border-0 active d-flex align-items-center">
                            <i class="bi bi-bag-check me-3 fs-5"></i> <?= translate('order_history') ?>
                        </a>
                        <a href="<?= SITE_URL ?>/pages/logout.php" class="list-group-item list-group-item-action border-0 d-flex align-items-center text-danger">
                            <i class="bi bi-box-arrow-right me-3 fs-5"></i> <?= translate('logout') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h1 class="h4 fw-bold mb-4"><?= translate('order_history') ?></h1>

                <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                        <p class="mt-3 text-muted">Aucune commande trouvée.</p>
                        <a href="<?= SITE_URL ?>/index.php" class="btn hri-btn-orange text-white fw-bold px-4">
                            Commencer mes achats
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><?= translate('order_number') ?></th>
                                    <th><?= translate('order_date') ?></th>
                                    <th><?= translate('order_total') ?></th>
                                    <th><?= translate('order_status') ?></th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $order['order_number'] ?></td>
                                        <td class="small"><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></td>
                                        <td class="fw-bold text-primary"><?= format_price($order['order_total']) ?></td>
                                        <td>
                                            <?php 
                                            $status_class = 'bg-secondary';
                                            switch($order['order_status']) {
                                                case 'pending': $status_class = 'bg-warning text-dark'; break;
                                                case 'confirmed': $status_class = 'bg-info text-white'; break;
                                                case 'preparing': $status_class = 'bg-primary text-white'; break;
                                                case 'out_for_delivery': $status_class = 'bg-info text-white'; break;
                                                case 'delivered': $status_class = 'bg-success text-white'; break;
                                                case 'cancelled': $status_class = 'bg-danger text-white'; break;
                                            }
                                            ?>
                                            <span class="badge <?= $status_class ?> rounded-pill small px-3">
                                                <?= translate('status_' . $order['order_status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?= SITE_URL ?>/pages/order_detail.php?number=<?= $order['order_number'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                Détails
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
