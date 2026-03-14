<?php
/**
 * FILE: pages/profile.php
 * PURPOSE: View and edit customer profile info with New Design.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// Auth Check (must be logged in)
require_once __DIR__ . '/../includes/auth_check.php';

global $lang, $current_language;
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

// Formatting "Member since"
$member_since_date = date('F Y', strtotime($customer['customer_created_at']));
if ($current_language === 'ar') {
    // Basic translation for months could be added if needed, but for now we'll use numeric date for Arabic or keep it simple
    $member_since_date = date('m/Y', strtotime($customer['customer_created_at']));
}

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div id="hri-profile-page">
    <div class="hri-profile-hero">
        <div class="hri-avatar-container">
             <!-- <?= strtoupper(substr($customer['customer_full_name'], 0, 1)) ?> -->
              <i data-lucide="user"></i>
        
        </div>
        <h1 style="margin: 0; font-weight: 900; font-size: 24px;"><?= htmlspecialchars($customer['customer_full_name']) ?></h1>
        <p style="margin: 5px 0 0; color: #717171; font-size: 14px;">
            <?= translate('member_since') ?> <?= $member_since_date ?>
        </p>
    </div>

    <div class="container pb-5 mb-5">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mx-2 rounded-4 shadow-sm border-0 mb-4">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= $err ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success mx-2 rounded-4 shadow-sm border-0 mb-4"><?= $success ?></div>
        <?php endif; ?>

        <form action="" method="POST" id="profile-form">
            <?= csrf_input_field() ?>
            <input type="hidden" name="update_profile" value="1">

            <div class="hri-summary-card mx-2">
                <div class="hri-summary-header"><?= translate('personal_information') ?></div>
                <div class="hri-summary-body">
                    <div class="hri-info-row">
                        <span class="hri-row-label"><?= translate('full_name') ?></span>
                        <input type="text" name="full_name" class="hri-row-input" value="<?= htmlspecialchars($customer['customer_full_name']) ?>" required>
                    </div>
                    <div class="hri-info-row">
                        <span class="hri-row-label"><?= translate('phone_number') ?></span>
                        <input type="tel" name="phone" class="hri-row-input" value="<?= htmlspecialchars($customer['customer_phone']) ?>" required>
                    </div>
                    <div class="hri-info-row">
                        <span class="hri-row-label"><?= translate('email') ?></span>
                        <input type="email" name="email" class="hri-row-input" value="<?= htmlspecialchars($customer['customer_email'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="hri-summary-card mx-2">
                <div class="hri-summary-header"><?= translate('delivery_details') ?></div>
                <div class="hri-summary-body">
                    <div class="hri-info-row">
                        <span class="hri-row-label"><?= translate('delivery_address') ?></span>
                        <input type="text" name="address" class="hri-row-input" value="<?= htmlspecialchars($customer['customer_address'] ?? '') ?>">
                    </div>
                    <div class="hri-info-row">
                        <span class="hri-row-label"><?= translate('neighborhood') ?></span>
                        <input type="text" name="neighborhood" class="hri-row-input" value="<?= htmlspecialchars($customer['customer_neighborhood'] ?? '') ?>">
                    </div>
                    <div class="hri-info-row">
                        <span class="hri-row-label"><?= translate('city') ?></span>
                        <input type="text" name="city" class="hri-row-input" value="<?= htmlspecialchars($customer['customer_city'] ?? DEFAULT_CITY) ?>" readonly>
                    </div>
                </div>
                <div class="hri-express-widget">
                    <div class="hri-express-text">
                        <?= translate('profile_priority_msg') ?>
                    </div>
                    <div class="hri-brand-footer">
                        MATJAR <span style="color:var(--princeton-orange)">⚡ <?= translate('matjar_express') ?></span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="hri-action-bar">
        <button type="submit" form="profile-form" class="hri-btn-save">
            <?= translate('save_changes') ?>
        </button>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

