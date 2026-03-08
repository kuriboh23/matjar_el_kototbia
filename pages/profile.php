<?php
/**
 * FILE: pages/profile.php
 * PURPOSE: View and edit customer profile info. Requires login.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Auth Check (must be logged in)
require_once __DIR__ . '/../includes/auth_check.php';

global $lang;
$customer_id = get_current_customer_id();
$customer = get_customer_by_id($customer_id);

if (!$customer) {
    logout_customer();
    redirect(SITE_URL . '/pages/login.php');
}

$errors = [];
$success = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // CSRF Check
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        die('CSRF validation failed.');
    }

    $updated_data = [
        'customer_full_name'    => $_POST['full_name'] ?? '',
        'customer_phone'         => $_POST['phone'] ?? '',
        'customer_email'         => $_POST['email'] ?? '',
        'customer_address'       => $_POST['address'] ?? '',
        'customer_neighborhood'  => $_POST['neighborhood'] ?? '',
        'customer_city'          => $_POST['city'] ?? DEFAULT_CITY
    ];

    // Simple validation
    if (empty($updated_data['customer_full_name'])) $errors[] = translate('error_required_field');
    if (!validate_phone_morocco($updated_data['customer_phone'])) $errors[] = translate('error_invalid_phone');

    if (empty($errors)) {
        if (update_customer($customer_id, $updated_data)) {
            $success = translate('profile_updated');
            // Refresh customer data
            $customer = get_customer_by_id($customer_id);
        } else {
            $errors[] = translate('error_general');
        }
    }
}

// Page title
$page_title = translate('profile') . ' - ' . $lang['site_name'];

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-4 text-center border-bottom bg-light">
                        <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; background: var(--color-primary); color: white; font-size: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <?= strtoupper(substr($customer['customer_full_name'], 0, 1)) ?>
                        </div>
                        <h6 class="fw-bold mb-0"><?= htmlspecialchars($customer['customer_full_name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($customer['customer_phone']) ?></small>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?= SITE_URL ?>/pages/profile.php" class="list-group-item list-group-item-action border-0 active d-flex align-items-center">
                            <i class="bi bi-person-circle me-3 fs-5"></i> <?= translate('profile') ?>
                        </a>
                        <a href="<?= SITE_URL ?>/pages/order_history.php" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                            <i class="bi bi-bag-check me-3 fs-5"></i> <?= translate('order_history') ?>
                        </a>
                        <a href="<?= SITE_URL ?>/pages/logout.php" class="list-group-item list-group-item-action border-0 d-flex align-items-center text-danger">
                            <i class="bi bi-box-arrow-right me-3 fs-5"></i> <?= translate('logout') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Profile Form -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h1 class="h4 fw-bold mb-4"><?= translate('edit_profile') ?></h1>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <?= csrf_input_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= translate('full_name') ?> *</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($customer['customer_full_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= translate('phone_number') ?> *</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($customer['customer_phone']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold"><?= translate('email') ?></label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['customer_email'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold"><?= translate('delivery_address') ?></label>
                            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($customer['customer_address'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= translate('neighborhood') ?></label>
                            <input type="text" name="neighborhood" class="form-control" value="<?= htmlspecialchars($customer['customer_neighborhood'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= translate('city') ?></label>
                            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($customer['customer_city'] ?? DEFAULT_CITY) ?>" readonly>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" name="update_profile" class="btn hri-btn-orange text-white px-5 fw-bold py-2">
                            <?= translate('save_changes') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
