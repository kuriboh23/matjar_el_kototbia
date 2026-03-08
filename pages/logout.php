<?php
/**
 * FILE: pages/logout.php
 * PURPOSE: Destroys customer session and redirects to homepage.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('logout');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('logout') ?></h1>
    <p><!-- Page: logout — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
