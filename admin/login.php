<?php
/**
 * FILE: admin/login.php
 * PURPOSE: Admin login form and authentication logic.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

// If admin is already logged in, redirect to dashboard
if (is_admin_logged_in()) {
    redirect(SITE_URL . '/admin/index.php');
}

$error = '';

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF Token
    $csrf_token = $_POST[CSRF_TOKEN_NAME] ?? '';
    if (!verify_csrf_token($csrf_token)) {
        die('CSRF token validation failed.');
    }

    // 2. Sanitize and Validate Inputs
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // 3. Fetch Admin from Database
        $sql = "SELECT admin_id, admin_password, admin_is_active 
                FROM hri_admin 
                WHERE admin_username = :username 
                LIMIT 1";
        $admin = fetch_one($sql, [':username' => $username]);

        if ($admin && $admin['admin_is_active']) {
            // 4. Verify Password
            if (verify_password($password, $admin['admin_password'])) {
                // 5. Success: Start Admin Session
                login_admin_session((int)$admin['admin_id']);

                // Update last login timestamp (optional but recommended)
                execute_query("UPDATE hri_admin SET admin_last_login = NOW() WHERE admin_id = :id", [':id' => $admin['admin_id']]);

                // Redirect to dashboard
                redirect(SITE_URL . '/admin/index.php');
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?= SITE_NAME_FR ?></title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #1b5e20;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            padding: 2rem 1rem;
        }
        .btn-primary {
            background-color: #2e7d32;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1b5e20;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="card-header">
            <h4 class="mb-0"><?= SITE_NAME_FR ?></h4>
            <small>Admin Panel Access</small>
        </div>
        <div class="card-body p-4">
            
            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <?= csrf_input_field() ?>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2 fw-bold">
                        Login <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </button>
                </div>
            </form>

        </div>
        <div class="card-footer text-center py-3 bg-white border-0">
            <a href="<?= SITE_URL ?>" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
