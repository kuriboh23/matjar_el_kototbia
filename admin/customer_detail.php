<?php
/**
 * FILE: admin/customer_detail.php
 * PURPOSE: View a single customer's profile and order history.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$customer = get_customer_by_id($id);

if (!$customer) {
    header('Location: customers.php');
    exit;
}

$orders = get_orders_by_customer($id);
$admin_page_title = 'Détails Client: ' . $customer['customer_full_name'];

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="<?= SITE_URL ?>/admin/customers.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour aux clients
        </a>
    </div>

    <div class="row g-4">
        <!-- Customer Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; background: #e9ecef; color: #adb5bd; font-size: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person"></i>
                        </div>
                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($customer['customer_full_name']) ?></h5>
                        <p class="text-muted small">Inscrit le <?= date('d/m/Y', strtotime($customer['customer_created_at'])) ?></p>
                    </div>
                    
                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Téléphone</label>
                        <div class="fw-bold text-primary"><?= htmlspecialchars($customer['customer_phone']) ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Email</label>
                        <div><?= htmlspecialchars($customer['customer_email'] ?? '-') ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Adresse</label>
                        <div class="small"><?= htmlspecialchars($customer['customer_address'] ?? '-') ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($customer['customer_neighborhood'] ?? '-') ?>, <?= htmlspecialchars($customer['customer_city']) ?></div>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Langue Préférée</label>
                        <span class="badge bg-light text-dark border"><?= strtoupper($customer['customer_preferred_lang']) ?></span>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <a href="https://wa.me/<?= $customer['customer_phone'] ?>" target="_blank" class="btn btn-success fw-bold py-2">
                    <i class="bi bi-whatsapp me-2"></i> Contacter via WhatsApp
                </a>
            </div>
        </div>

        <!-- Order History -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">Historique des Commandes (<?= count($orders) ?>)</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($orders)): ?>
                        <div class="p-5 text-center text-muted">
                            Aucune commande pour ce client.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small text-uppercase">
                                        <th class="ps-4">Date</th>
                                        <th>N° Commande</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td class="ps-4 small"><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></td>
                                            <td class="fw-bold">#<?= $order['order_number'] ?></td>
                                            <td class="fw-bold"><?= format_price($order['order_total']) ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark border rounded-pill px-3"><?= translate('status_' . $order['order_status']) ?></span>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
