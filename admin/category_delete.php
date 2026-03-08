<?php
/**
 * FILE: admin/category_delete.php
 * PURPOSE: Handler to delete a category (checks for linked products first).
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Category_delete';

// TODO: Add category_delete logic here

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <h2><?= htmlspecialchars($admin_page_title) ?></h2>
    <p class="text-muted"><!-- Admin page: category_delete — Implement here --></p>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
