<?php
/**
 * FILE: pages/login.php
 * PURPOSE: Customer login form. Authenticates via phone + password.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('login');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('login') ?></h1>
    <p><!-- Page: login — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
