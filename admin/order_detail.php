<?php
/**
 * FILE: admin/order_detail.php
 * PURPOSE: View and manage a single order.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = get_order_by_id($order_id);

if (!$order) {
    header('Location: orders.php');
    exit;
}

$order_items = get_order_items($order_id);
$admin_page_title = 'Détails Commande #' . $order['order_number'];

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="<?= SITE_URL ?>/admin/orders.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour aux commandes
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Order Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Articles Commandés (<?= count($order_items) ?>)</h5>
                    <span class="text-muted small"><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th class="ps-4">Produit</th>
                                    <th class="text-center">Prix</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end pe-4">Sous-total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= htmlspecialchars($item['order_item_name_fr']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($item['order_item_name_ar']) ?></div>
                                        </td>
                                        <td class="text-center"><?= format_price($item['order_item_unit_price']) ?></td>
                                        <td class="text-center"><?= $item['order_item_quantity'] ?> <?= $item['order_item_unit'] ?></td>
                                        <td class="text-end pe-4 fw-bold"><?= format_price($item['order_item_subtotal']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end ps-4 py-3">Sous-total :</td>
                                    <td class="text-end pe-4 py-3 fw-bold"><?= format_price($order['order_subtotal']) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end ps-4 py-2 text-muted">Frais de livraison :</td>
                                    <td class="text-end pe-4 py-2"><?= format_price($order['order_delivery_fee']) ?></td>
                                </tr>
                                <tr class="fs-5">
                                    <td colspan="3" class="text-end ps-4 py-3 fw-bold">TOTAL :</td>
                                    <td class="text-end pe-4 py-3 fw-bold text-primary"><?= format_price($order['order_total']) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer & Delivery -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">Informations Client & Livraison</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Client</label>
                            <div class="h6 mb-0"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                            <div class="text-primary fw-bold mt-1"><?= htmlspecialchars($order['order_customer_phone']) ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Ville / Quartier</label>
                            <div class="h6 mb-0"><?= htmlspecialchars($order['order_customer_city']) ?></div>
                            <div class="text-muted"><?= htmlspecialchars($order['order_customer_neighborhood'] ?? '-') ?></div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Adresse Complète</label>
                            <div class="bg-light p-3 rounded border"><?= htmlspecialchars($order['order_customer_address']) ?></div>
                        </div>
                        <?php if ($order['order_notes']): ?>
                            <div class="col-12">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Notes de Commande</label>
                                <div class="bg-warning-subtle p-3 rounded border border-warning-subtle"><?= nl2br(htmlspecialchars($order['order_notes'])) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Order Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">Statut de la Commande</h5>
                </div>
                <div class="card-body p-4 text-center">
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
                    <div class="display-6 fw-bold mb-3 p-3 rounded-3 <?= $status_class ?>">
                        <?= translate('status_' . $order['order_status']) ?>
                    </div>

                    <form action="order_update_status.php" method="POST" class="mt-4">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <div class="mb-3">
                            <select name="new_status" class="form-select form-select-lg">
                                <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                                <option value="confirmed" <?= $order['order_status'] == 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                                <option value="preparing" <?= $order['order_status'] == 'preparing' ? 'selected' : '' ?>>En préparation</option>
                                <option value="out_for_delivery" <?= $order['order_status'] == 'out_for_delivery' ? 'selected' : '' ?>>En livraison</option>
                                <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : '' ?>>Livrée</option>
                                <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 py-3 fw-bold">
                            Mettre à Jour le Statut
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 bg-whatsapp text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-whatsapp me-2"></i> WhatsApp</h5>
                    <p class="small mb-4">Contacter le client pour confirmer la livraison ou demander des précisions.</p>
                    <a href="https://wa.me/<?= $order['order_customer_phone'] ?>" target="_blank" class="btn btn-light w-100 fw-bold py-2">
                        Ouvrir WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-whatsapp { background-color: #25D366; }
</style>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
