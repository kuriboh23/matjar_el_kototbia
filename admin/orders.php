<?php
/**
 * FILE: admin/orders.php
 * PURPOSE: List all orders for fulfillment with search and advanced filters.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Gestion des Commandes';

// Get Filters
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';
$status_filter = $_GET['status'] ?? '';
$livreur_filter = isset($_GET['livreur']) ? (int)$_GET['livreur'] : 0;

// Build Query
$where_clauses = [];
$params = [];

if ($search_query) {
    $where_clauses[] = "(order_number LIKE :q1 OR order_customer_name LIKE :q2 OR order_customer_phone LIKE :q3)";
    $params[':q1'] = "%$search_query%";
    $params[':q2'] = "%$search_query%";
    $params[':q3'] = "%$search_query%";
}

if ($status_filter) {
    $where_clauses[] = "order_status = :status";
    $params[':status'] = $status_filter;
}

if ($livreur_filter) {
    $where_clauses[] = "order_livreur_id = :livreur";
    $params[':livreur'] = $livreur_filter;
}

$where_sql = !empty($where_clauses) ? " WHERE " . implode(" AND ", $where_clauses) : "";

// Pagination
$per_page = ORDERS_PER_PAGE_ADMIN;
$offset = ($current_page - 1) * $per_page;

$sql = "SELECT * FROM hri_order" . $where_sql . " ORDER BY order_created_at DESC LIMIT :limit OFFSET :offset";

global $db_connection;
$stmt = $db_connection->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();

$total_items = count_rows("SELECT COUNT(*) FROM hri_order" . $where_sql, $params);
$total_pages = calculate_total_pages($total_items, $per_page);

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; flex-wrap: wrap; gap: 20px;">
    <h2 style="margin: 0; font-weight: 800; font-size: 24px; color: var(--text-muted);">
        <span style="color: var(--carbon-black);"><?= $total_items ?></span> Commandes
    </h2>
    <div style="display: flex; gap: 10px;">
        <a href="?status=pending" class="btn btn-orange" style="background: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
            <i data-lucide="clock" size="18"></i> <?= count_rows("SELECT COUNT(*) FROM hri_order WHERE order_status = 'pending'") ?> En attente
        </a>
    </div>
</div>

<!-- Advanced Filters -->
<div class="card" style="margin-bottom: 30px; padding: 20px;">
    <form action="" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 250px;">
            <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; display: block; text-transform: uppercase;">Rechercher</label>
            <div style="position: relative;">
                <i data-lucide="search" size="18" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="N° Commande, Nom ou Tél..." class="form-control" style="padding-left: 45px;">
            </div>
        </div>
        
        <div style="width: 180px;">
            <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; display: block; text-transform: uppercase;">Statut</label>
            <select name="status" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>En attente</option>
                <option value="confirmed" <?= $status_filter == 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                <option value="preparing" <?= $status_filter == 'preparing' ? 'selected' : '' ?>>En préparation</option>
                <option value="out_for_delivery" <?= $status_filter == 'out_for_delivery' ? 'selected' : '' ?>>En livraison</option>
                <option value="delivered" <?= $status_filter == 'delivered' ? 'selected' : '' ?>>Livrée</option>
                <option value="cancelled" <?= $status_filter == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
            </select>
        </div>

        <div style="width: 180px;">
            <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; display: block; text-transform: uppercase;">Livreur</label>
            <select name="livreur" class="form-control">
                <option value="0">Tous les livreurs</option>
                <?php 
                $livreurs = get_all_livreurs();
                foreach ($livreurs as $l): ?>
                    <option value="<?= $l['livreur_id'] ?>" <?= $livreur_filter == $l['livreur_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($l['livreur_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-orange" style="padding: 12px 25px;">Filtrer</button>
            <?php if ($search_query || $status_filter || $livreur_filter): ?>
                <a href="orders.php" class="btn btn-light" style="padding: 12px 15px;" title="Réinitialiser">
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
    <div style="display: flex; justify-content: center; gap: 12px; margin-top: 40px;">
        <?php 
        $pagination_url = "orders.php?q=" . urlencode($search_query) . "&status=" . $status_filter . "&livreur=" . $livreur_filter;
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

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
