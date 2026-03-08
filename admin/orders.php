<?php
/**
 * FILE: admin/orders.php
 * PURPOSE: Lists all orders with filters (status, date). Paginated.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Orders';

// TODO: Add orders logic here

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <h2><?= htmlspecialchars($admin_page_title) ?></h2>
    <p class="text-muted"><!-- Admin page: orders — Implement here --></p>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
