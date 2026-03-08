<?php
/**
 * FILE: errors/404.php
 * PURPOSE: Custom 404 Not Found error page.
 */
http_response_code(404);
require_once __DIR__ . '/../config/config.php';
$page_title = '404';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container py-5 text-center">
    <h1 class="display-1 text-muted">404</h1>
    <p class="lead">Page non trouvée / الصفحة غير موجودة</p>
    <a href="<?= SITE_URL ?>" class="btn btn-success btn-lg mt-3">
        <i class="bi bi-house"></i> <?= translate('home') ?>
    </a>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
