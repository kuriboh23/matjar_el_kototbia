<?php
/**
 * FILE: pages/login.php
 * PURPOSE: Customer login form. Authenticates via phone + password.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// If already logged in, redirect to profile
if (is_customer_logged_in()) {
    redirect(SITE_URL . '/pages/profile.php');
}

global $lang;
$errors = [];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CSRF Check
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        die('CSRF validation failed.');
    }

    // 2. Sanitize Input
    $phone = sanitize_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    // 3. Validation
    if (empty($phone) || empty($password)) {
        $errors[] = translate('error_required_field');
    }

    if (empty($errors)) {
        $clean_phone = clean_phone_number($phone);
        $customer = get_customer_by_phone($clean_phone);

        if ($customer && verify_password($password, $customer['customer_password'])) {
            // Success
            login_customer_session($customer['customer_id']);
            
            // Handle redirect
            $target = SITE_URL . '/pages/profile.php';
            if (!empty($_GET['redirect'])) {
                if ($_GET['redirect'] === 'checkout') {
                    $target = SITE_URL . '/pages/checkout.php';
                }
            }
            redirect($target);
        } else {
            $errors[] = translate('error_login_failed');
        }
    }
}

$page_title = translate('login') . ' - ' . $lang['site_name'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm p-4">
                <h1 class="h3 fw-bold mb-4 text-center"><?= translate('login') ?></h1>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <?= csrf_input_field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= translate('phone_number') ?></label>
                        <input type="tel" name="phone" class="form-control" placeholder="06XXXXXXXX" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold"><?= translate('password') ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn hri-btn-brand text-white w-100 fw-bold py-2 mb-3">
                        <?= translate('login_now') ?>
                    </button>

                    <div class="text-center">
                        <span class="text-muted small"><?= translate('no_account') ?></span>
                        <a href="<?= SITE_URL ?>/pages/register.php" class="text-primary fw-bold small text-decoration-none ms-1">
                            <?= translate('register_now') ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

