<?php
/**
 * FILE: pages/profile.php
 * PURPOSE: View and edit customer profile info. Requires login.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('profile');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('profile') ?></h1>
    <p><!-- Page: profile — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
