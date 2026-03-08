<?php
/**
 * FILE: pages/register.php
 * PURPOSE: Customer registration form. Creates new account in hri_customer.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('register');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('register') ?></h1>
    <p><!-- Page: register — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
