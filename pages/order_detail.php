<?php
/**
 * FILE: pages/order_detail.php
 * PURPOSE: Single order details for customer view. Requires login.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('order_detail');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('order_detail') ?></h1>
    <p><!-- Page: order_detail — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
