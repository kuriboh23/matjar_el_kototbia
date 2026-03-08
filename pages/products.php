<?php
/**
 * FILE: pages/products.php
 * PURPOSE: Browse all active products with pagination and optional category/sort filters.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('products');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('products') ?></h1>
    <p><!-- Page: products — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
