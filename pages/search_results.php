<?php
/**
 * FILE: pages/search_results.php
 * PURPOSE: Display products matching the search query from URL ?q= param.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('search_results');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('search_results') ?></h1>
    <p><!-- Page: search_results — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
