<?php
/**
 * FILE: pages/checkout.php
 * PURPOSE: Checkout form (name, phone, address) + order summary + WhatsApp send button.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('checkout');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('checkout') ?></h1>
    <p><!-- Page: checkout — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
