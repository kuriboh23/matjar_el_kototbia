<?php
/**
 * FILE: admin/category_edit.php
 * PURPOSE: Form to edit an existing category by category_id.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Category_edit';

// TODO: Add category_edit logic here

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <h2><?= htmlspecialchars($admin_page_title) ?></h2>
    <p class="text-muted"><!-- Admin page: category_edit — Implement here --></p>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
