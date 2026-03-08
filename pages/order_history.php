<?php
/**
 * FILE: pages/order_history.php
 * PURPOSE: List of customer past orders with status. Requires login.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('order_history');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('order_history') ?></h1>
    <p><!-- Page: order_history — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
