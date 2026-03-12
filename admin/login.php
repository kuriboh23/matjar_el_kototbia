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
        $error = 'Veuillez saisir le nom d\'utilisateur et le mot de passe.';
    } else {
        // 3. Fetch Admin from Database
        $sql = "SELECT admin_id, admin_password, admin_is_active, admin_full_name 
                FROM hri_admin 
                WHERE admin_username = :username 
                LIMIT 1";
        $admin = fetch_one($sql, [':username' => $username]);

        if ($admin && $admin['admin_is_active']) {
            // 4. Verify Password
            if (password_verify($password, $admin['admin_password'])) {
                // 5. Success: Start Admin Session
                $_SESSION[ADMIN_SESSION_KEY] = (int)$admin['admin_id'];
                $_SESSION['hri_admin_name'] = $admin['admin_full_name'];

                // Update last login timestamp
                execute_query("UPDATE hri_admin SET admin_last_login = NOW() WHERE admin_id = :id", [':id' => $admin['admin_id']]);

                // Redirect to dashboard
                redirect(SITE_URL . '/admin/index.php');
            } else {
                $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
            }
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Matjar El Kotobia</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --brand-color: #670d0c;
            --princeton-orange: #ff8200;
            --carbon-black: #171711;
            --white: #ffffff;
            --gray-bg: #f8fafc;
            --gray-border: #e2e8f0;
            --text-muted: #64748b;
            --danger: #ef4444;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--carbon-black); color: var(--white);
            display: flex; align-items: center; justify-content: center; min-height: 100vh;
        }

        .login-card {
            background: rgba(255,255,255,0.05); backdrop-filter: blur(20px);
            padding: 40px; border-radius: 32px; width: 100%; max-width: 400px;
            border: 1px solid rgba(255,255,255,0.1); text-align: center;
            animation: fadeIn 0.6s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .brand { font-weight: 900; font-size: 24px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .brand span { color: var(--princeton-orange); }

        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; font-size: 11px; font-weight: 800; color: rgba(255,255,255,0.5); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { 
            width: 100%; padding: 14px 18px; border-radius: 14px; 
            border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);
            font-family: inherit; font-weight: 600; color: white; box-sizing: border-box;
            transition: var(--transition);
        }
        .form-control:focus { outline: none; border-color: white; background: rgba(255,255,255,0.1); }

        .btn { 
            padding: 16px 20px; border-radius: 14px; font-weight: 800; font-size: 15px; 
            border: none; cursor: pointer; display: inline-flex; align-items: center; 
            gap: 10px; text-decoration: none; width: 100%; justify-content: center;
            transition: var(--transition);
        }
        .btn-brand { background: var(--brand-color); color: white; }
        .btn-brand:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(103, 13, 12,0.2); }

        .error-box {
            background: rgba(239, 68, 68, 0.1); color: var(--danger);
            padding: 12px; border-radius: 12px; margin-bottom: 25px;
            font-size: 13px; font-weight: 700; border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .back-link { 
            margin-top: 25px; display: inline-block; color: rgba(255,255,255,0.4); 
            text-decoration: none; font-size: 13px; font-weight: 700; transition: var(--transition);
        }
        .back-link:hover { color: white; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand">
            <i data-lucide="shield-check" size="28"></i> MATJAR<span>.</span>ADMIN
        </div>
        <p style="color: rgba(255,255,255,0.4); font-weight: 600; margin-bottom: 35px; font-size: 14px;">Identifiez-vous pour accéder au panel</p>
        
        <?php if ($error): ?>
            <div class="error-box">
                <i data-lucide="alert-circle" size="16" style="vertical-align: middle; margin-right: 5px;"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <?= csrf_input_field() ?>
            
            <div class="form-group">
                <label>Utilisateur</label>
                <input type="text" name="username" class="form-control" required autofocus placeholder="Nom d'utilisateur">
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-brand">
                Se connecter <i data-lucide="arrow-right" size="18"></i>
            </button>
        </form>

        <a href="<?= SITE_URL ?>" class="back-link">
            <i data-lucide="arrow-left" size="14" style="vertical-align: middle; margin-right: 5px;"></i>
            Retour au site
        </a>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>


