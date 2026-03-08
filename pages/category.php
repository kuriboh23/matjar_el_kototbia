<?php
/**
 * FILE: pages/category.php
 * PURPOSE: Show products filtered by a specific category slug from URL.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('category');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('category') ?></h1>
    <p><!-- Page: category — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
