<?php
/**
 * FILE: pages/register.php
 * PURPOSE: Customer registration form. Creates new account in hri_customer.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// If already logged in, redirect to profile
if (is_customer_logged_in()) {
    redirect(SITE_URL . '/pages/profile.php');
}

global $lang;
$errors = [];
$success = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CSRF Check
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        die('CSRF validation failed.');
    }

    // 2. Sanitize Input
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $phone     = sanitize_input($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';
    $address   = sanitize_input($_POST['address'] ?? '');
    $neighborhood = sanitize_input($_POST['neighborhood'] ?? '');

    // 3. Validation
    if (empty($full_name)) $errors[] = translate('error_required_field');
    if (!validate_phone_morocco($phone)) $errors[] = translate('error_invalid_phone');
    if (strlen($password) < 6) $errors[] = translate('error_password_short');
    if ($password !== $confirm) $errors[] = translate('error_password_match');

    // 4. Check if phone exists
    if (empty($errors)) {
        $clean_phone = clean_phone_number($phone);
        if (get_customer_by_phone($clean_phone)) {
            $errors[] = translate('error_phone_exists');
        }
    }

    // 5. Create Account
    if (empty($errors)) {
        try {
            $customer_id = create_customer([
                'customer_full_name' => $full_name,
                'customer_phone'     => clean_phone_number($phone),
                'customer_email'     => null, // Optional in V1
                'customer_password'  => $password,
                'customer_address'   => $address,
                'customer_neighborhood' => $neighborhood,
                'customer_city'      => DEFAULT_CITY,
                'customer_preferred_lang' => $current_language
            ]);

            if ($customer_id) {
                // Auto-login
                login_customer_session($customer_id);
                redirect(SITE_URL . '/pages/profile.php');
            } else {
                $errors[] = translate('error_general');
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$page_title = translate('register') . ' - ' . $lang['site_name'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm p-4">
                <h1 class="h3 fw-bold mb-4 text-center"><?= translate('register') ?></h1>

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
                        <label class="form-label small fw-bold"><?= translate('full_name') ?> *</label>
                        <input type="text" name="full_name" class="form-control" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= translate('phone_number') ?> *</label>
                        <input type="tel" name="phone" class="form-control" placeholder="06XXXXXXXX" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        <div class="form-text text-muted small">Format: 06XXXXXXXX</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= translate('delivery_address') ?></label>
                        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold"><?= translate('neighborhood') ?></label>
                            <input type="text" name="neighborhood" class="form-control" value="<?= htmlspecialchars($_POST['neighborhood'] ?? '') ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold"><?= translate('city') ?></label>
                            <input type="text" class="form-control bg-light" value="<?= DEFAULT_CITY ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= translate('password') ?> *</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="form-text text-muted small"><?= translate('error_password_short') ?></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold"><?= translate('confirm_password') ?> *</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn hri-btn-orange text-white w-100 fw-bold py-2 mb-3">
                        <?= translate('register_now') ?>
                    </button>

                    <div class="text-center">
                        <span class="text-muted small"><?= translate('have_account') ?></span>
                        <a href="<?= SITE_URL ?>/pages/login.php" class="text-primary fw-bold small text-decoration-none ms-1">
                            <?= translate('login') ?>
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
