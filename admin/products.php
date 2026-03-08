<?php
/**
 * FILE: admin/products.php
 * PURPOSE: Lists all products with search, filter, pagination. Links to add/edit/delete.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Products';

// TODO: Add products logic here

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <h2><?= htmlspecialchars($admin_page_title) ?></h2>
    <p class="text-muted"><!-- Admin page: products — Implement here --></p>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
