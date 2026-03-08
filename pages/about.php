<?php
/**
 * FILE: pages/about.php
 * PURPOSE: Static about page with store information, mission, location.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('about');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('about') ?></h1>
    <p><!-- Page: about — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
