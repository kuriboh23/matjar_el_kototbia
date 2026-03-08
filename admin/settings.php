<?php
/**
 * FILE: admin/settings.php
 * PURPOSE: Manage site-wide settings (WhatsApp, delivery fees, etc.)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Paramètres du Magasin';

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic update logic for settings table
    foreach ($_POST['settings'] as $key => $value) {
        update_setting($key, sanitize_input($value));
    }
    $success = true;
}

// Fetch all settings
$settings = fetch_all("SELECT * FROM hri_settings ORDER BY setting_id ASC");

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><?= $admin_page_title ?></h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">Paramètres mis à jour avec succès !</div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="row g-4">
                            <?php foreach ($settings as $s): 
                                $label = str_replace('_', ' ', $s['setting_key']);
                                $label = ucwords($label);
                            ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-uppercase"><?= $label ?></label>
                                    <?php if ($s['setting_key'] == 'store_address'): ?>
                                        <textarea name="settings[<?= $s['setting_key'] ?>]" class="form-control" rows="2"><?= htmlspecialchars($s['setting_value']) ?></textarea>
                                    <?php else: ?>
                                        <input type="text" name="settings[<?= $s['setting_key'] ?>]" class="form-control" value="<?= htmlspecialchars($s['setting_value']) ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-5 pt-4 border-top text-end">
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Enregistrer les Paramètres</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i> Aide aux Paramètres</h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li class="mb-2"><strong>WhatsApp Number:</strong> Format international sans le '+' (ex: 212600000000).</li>
                        <li class="mb-2"><strong>Delivery Fee:</strong> Montant fixe en DH ajouté au total si le seuil n'est pas atteint.</li>
                        <li><strong>Free Delivery Threshold:</strong> Commande minimum pour bénéficier de la livraison gratuite.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
