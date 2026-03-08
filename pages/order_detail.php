<?php
/**
 * FILE: pages/order_detail.php
 * PURPOSE: Single order details for customer view. Requires login.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Auth Check
require_once __DIR__ . '/../includes/auth_check.php';

global $lang;
$customer_id = get_current_customer_id();
$order_number = $_GET['number'] ?? '';

if (empty($order_number)) {
    redirect(SITE_URL . '/pages/order_history.php');
}

$order = get_order_by_number($order_number);

// Ensure order exists and belongs to current customer
if (!$order || (int)$order['order_customer_id'] !== $customer_id) {
    redirect(SITE_URL . '/pages/order_history.php');
}

$order_items = get_order_items((int)$order['order_id']);

// Page title
$page_title = translate('order_number') . ' #' . $order['order_number'] . ' - ' . $lang['site_name'];

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="mb-4 d-flex align-items-center gap-3">
        <a href="<?= SITE_URL ?>/pages/order_history.php" class="btn btn-outline-secondary btn-sm rounded-circle">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 fw-bold mb-0"><?= translate('order_number') ?> #<?= $order['order_number'] ?></h1>
    </div>

    <div class="row g-4">
        <!-- Order Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="h6 fw-bold mb-0">Produits commandés</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th class="ps-4">Produit</th>
                                    <th class="text-center">Prix</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= htmlspecialchars($current_language === 'ar' ? $item['order_item_name_ar'] : $item['order_item_name_fr']) ?></div>
                                            <small class="text-muted">Unité: <?= $item['order_item_unit'] ?></small>
                                        </td>
                                        <td class="text-center"><?= format_price($item['order_item_unit_price']) ?></td>
                                        <td class="text-center"><?= $item['order_item_quantity'] ?></td>
                                        <td class="text-end pe-4 fw-bold text-primary"><?= format_price($item['order_item_subtotal']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="h6 fw-bold mb-0">Informations de livraison</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1"><?= translate('full_name') ?></label>
                            <div class="fw-bold"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1"><?= translate('phone_number') ?></label>
                            <div class="fw-bold"><?= htmlspecialchars($order['order_customer_phone']) ?></div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small d-block mb-1"><?= translate('delivery_address') ?></label>
                            <div class="fw-bold">
                                <?= htmlspecialchars($order['order_customer_address']) ?><br>
                                <?= htmlspecialchars($order['order_customer_neighborhood'] ?? '') ?>, <?= htmlspecialchars($order['order_customer_city']) ?>
                            </div>
                        </div>
                        <?php if ($order['order_notes']): ?>
                            <div class="col-12">
                                <label class="text-muted small d-block mb-1"><?= translate('order_notes') ?></label>
                                <div class="p-3 bg-light rounded-3 small">
                                    <?= nl2br(htmlspecialchars($order['order_notes'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary & Status -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="h6 fw-bold mb-0">Résumé</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><?= translate('subtotal') ?></span>
                        <span class="fw-bold"><?= format_price($order['order_subtotal']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><?= translate('delivery_fee') ?></span>
                        <span><?= format_price($order['order_delivery_fee']) ?></span>
                    </div>
                    <hr class="my-3 opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5"><?= translate('total') ?></span>
                        <span class="fw-bold fs-4 text-primary"><?= format_price($order['order_total']) ?></span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="p-4 text-center <?php 
                    $status_class = 'bg-secondary';
                    switch($order['order_status']) {
                        case 'pending': $status_class = 'bg-warning text-dark'; break;
                        case 'confirmed': $status_class = 'bg-info text-white'; break;
                        case 'preparing': $status_class = 'bg-primary text-white'; break;
                        case 'out_for_delivery': $status_class = 'bg-info text-white'; break;
                        case 'delivered': $status_class = 'bg-success text-white'; break;
                        case 'cancelled': $status_class = 'bg-danger text-white'; break;
                    }
                    echo $status_class;
                ?>">
                    <div class="text-uppercase small fw-bold opacity-75 mb-1"><?= translate('order_status') ?></div>
                    <div class="h5 fw-bold mb-0"><?= translate('status_' . $order['order_status']) ?></div>
                </div>
                <div class="card-body p-4 text-center">
                    <p class="small text-muted mb-0">
                        Commande passée le<br>
                        <span class="fw-bold text-dark"><?= date('d/m/Y à H:i', strtotime($order['order_created_at'])) ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
