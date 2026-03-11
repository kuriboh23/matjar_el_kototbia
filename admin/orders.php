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

// Build query
$where_clause = "";
$params = [];
if ($status_filter) {
    $where_clause = " WHERE order_status = :status";
    $params[':status'] = $status_filter;
}

$sql = "SELECT * FROM hri_order" . $where_clause . " ORDER BY order_created_at DESC LIMIT :limit OFFSET :offset";

$offset = ($current_page - 1) * ORDERS_PER_PAGE_ADMIN;

global $db_connection;
$stmt = $db_connection->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', ORDERS_PER_PAGE_ADMIN, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();

$total_items = count_rows("SELECT COUNT(*) FROM hri_order" . $where_clause, $params);
$total_pages = calculate_total_pages($total_items, ORDERS_PER_PAGE_ADMIN);

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
    <div style="display: flex; gap: 15px;">
        <a href="?status=" class="btn <?= empty($status_filter) ? 'btn-orange' : 'btn-light' ?>">Tout</a>
        <a href="?status=pending" class="btn <?= $status_filter == 'pending' ? 'btn-orange' : 'btn-light' ?>">En attente</a>
        <a href="?status=confirmed" class="btn <?= $status_filter == 'confirmed' ? 'btn-orange' : 'btn-light' ?>">Confirmées</a>
        <a href="?status=delivered" class="btn <?= $status_filter == 'delivered' ? 'btn-orange' : 'btn-light' ?>">Livrées</a>
    </div>
    <div style="font-weight: 800; font-size: 16px; color: var(--text-muted);">
        <span style="color: var(--carbon-black);"><?= $total_items ?></span> commandes
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date & Heure</th>
                    <th>N° Commande</th>
                    <th>Client & Contact</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 800; color: var(--carbon-black);"><?= date('d/m/Y', strtotime($order['order_created_at'])) ?></div>
                            <div style="font-size: 13px; color: var(--text-muted); font-weight: 700; margin-top: 2px;"><?= date('H:i', strtotime($order['order_created_at'])) ?></div>
                        </td>
                        <td style="font-weight: 900; color: var(--princeton-orange); font-size: 16px;">#<?= $order['order_number'] ?></td>
                        <td>
                            <div style="font-weight: 800; color: var(--carbon-black); font-size: 16px;"><?= htmlspecialchars($order['order_customer_name']) ?></div>
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
                            <a href="<?= SITE_URL ?>/admin/order_detail.php?id=<?= $order['order_id'] ?>" class="btn btn-light" style="padding: 12px 20px;">
                                <i data-lucide="eye" size="18" style="margin-right: 5px;"></i> Gérer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 80px; font-weight: 700;">Aucune commande trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
    <div style="display: flex; justify-content: center; gap: 15px; margin-top: 40px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&status=<?= $status_filter ?>" class="btn <?= ($i === $current_page) ? 'btn-orange' : 'btn-light' ?>" style="min-width: 55px; justify-content: center; font-weight: 900;">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
