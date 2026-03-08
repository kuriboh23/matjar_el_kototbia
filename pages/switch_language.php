<?php
/**
 * FILE: pages/switch_language.php
 * PURPOSE: Handles language switching. Sets cookie/session, redirects back.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Page title
$page_title = translate('switch_language');

// TODO: Add page logic here

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <h1><?= translate('switch_language') ?></h1>
    <p><!-- Page: switch_language — Content coming soon --></p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
