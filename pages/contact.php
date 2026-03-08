<?php
/**
 * FILE: pages/contact.php
 * PURPOSE: Contact page with store phone, WhatsApp, address, map placeholder.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('contact');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('contact') ?></h1>
    <p><!-- Page: contact — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
