<?php
/**
 * FILE: admin/orders.php
 * PURPOSE: List all orders for fulfillment.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Gestion des Commandes';

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$status_filter = $_GET['status'] ?? '';

$sql = "SELECT * FROM hri_order";
$params = [];

if ($status_filter) {
    $sql .= " WHERE order_status = :status";
    $params[':status'] = $status_filter;
}

$sql .= " ORDER BY order_created_at DESC LIMIT :limit OFFSET :offset";

$offset = ($current_page - 1) * ORDERS_PER_PAGE_ADMIN;

global $db_connection;
$stmt = $db_connection->prepare($sql);
if ($status_filter) $stmt->bindValue(':status', $status_filter);
$stmt->bindValue(':limit', ORDERS_PER_PAGE_ADMIN, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();

$total_items = count_rows("SELECT COUNT(*) FROM hri_order" . ($status_filter ? " WHERE order_status = :status" : ""), $params);
$total_pages = calculate_total_pages($total_items, ORDERS_PER_PAGE_ADMIN);

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0"><?= $admin_page_title ?> <span class="text-muted small fw-normal">(<?= $total_items ?>)</span></h2>
        
        <!-- Status Filter -->
        <div class="btn-group">
            <a href="?status=" class="btn btn-sm <?= empty($status_filter) ? 'btn-dark' : 'btn-outline-dark' ?>">Tout</a>
            <a href="?status=pending" class="btn btn-sm <?= $status_filter == 'pending' ? 'btn-warning' : 'btn-outline-warning' ?>">En attente</a>
            <a href="?status=confirmed" class="btn btn-sm <?= $status_filter == 'confirmed' ? 'btn-info' : 'btn-outline-info' ?>">Confirmées</a>
            <a href="?status=delivered" class="btn btn-sm <?= $status_filter == 'delivered' ? 'btn-success' : 'btn-outline-success' ?>">Livrées</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-uppercase">
                            <th class="ps-4">Date</th>
                            <th>N° Commande</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Articles</th>
                            <th>Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold"><?= date('d/m/Y', strtotime($order['order_created_at'])) ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($order['order_created_at'])) ?></small>
                                </td>
                                <td class="fw-bold">#<?= $order['order_number'] ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($order['order_customer_phone']) ?></small>
                                </td>
                                <td class="fw-bold text-primary"><?= format_price($order['order_total']) ?></td>
                                <td class="text-center"><?= $order['order_item_count'] ?></td>
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
                                        <i class="bi bi-eye me-1"></i> Gérer
                                    </a>
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
                        <a class="page-link" href="?page=<?= $i ?>&status=<?= $status_filter ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
