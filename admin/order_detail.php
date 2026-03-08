<?php
/**
 * FILE: admin/order_detail.php
 * PURPOSE: View full order details: customer info, items, totals, status history.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Order_detail';

// TODO: Add order_detail logic here

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <h2><?= htmlspecialchars($admin_page_title) ?></h2>
    <p class="text-muted"><!-- Admin page: order_detail — Implement here --></p>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
