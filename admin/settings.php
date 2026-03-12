<?php
/**
 * FILE: admin/settings.php
 * PURPOSE: Manage site-wide settings (WhatsApp, delivery fees, etc.)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Paramètres du Magasin';

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['settings'])) {
    foreach ($_POST['settings'] as $key => $value) {
        update_setting($key, sanitize_input($value));
    }
    $success = true;
}

// Fetch all settings
$settings = fetch_all("SELECT * FROM hri_settings ORDER BY setting_id ASC");

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
    <div>
        <div class="card">
            <?php if ($success): ?>
                <div style="background: #ecfdf5; color: #065f46; padding: 20px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; display: flex; align-items: center; gap: 12px; border: 1px solid rgba(5, 150, 105, 0.2);">
                    <i data-lucide="check-circle" size="24"></i>
                    Paramètres mis à jour avec succès !
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <?php foreach ($settings as $s): 
                        $label = ($current_language === 'ar' && !empty($s['setting_label_ar'])) ? $s['setting_label_ar'] : (!empty($s['setting_label_fr']) ? $s['setting_label_fr'] : ucwords(str_replace('_', ' ', $s['setting_key'])));
                        $full_width = ($s['setting_key'] == 'store_address' || $s['setting_key'] == 'store_hours_fr' || $s['setting_key'] == 'store_hours_ar');
                    ?>
                        <div class="form-group" style="<?= $full_width ? 'grid-column: span 2;' : '' ?>">
                            <label style="font-size: 14px;"><?= $label ?></label>
                            <?php if ($s['setting_key'] == 'store_address'): ?>
                                <textarea name="settings[<?= $s['setting_key'] ?>]" class="form-control" rows="3" style="height: auto; font-size: 16px;"><?= htmlspecialchars($s['setting_value']) ?></textarea>
                            <?php else: ?>
                                <input type="<?= $s['setting_type'] == 'number' ? 'number' : 'text' ?>" name="settings[<?= $s['setting_key'] ?>]" class="form-control" value="<?= htmlspecialchars($s['setting_value']) ?>" style="font-size: 16px; padding: 18px 25px;">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 40px; padding-top: 40px; border-top: 2px solid var(--gray-bg); text-align: right;">
                    <button type="submit" class="btn btn-orange" style="padding: 18px 60px; font-size: 16px;">
                        <i data-lucide="save" size="22"></i> Enregistrer les Réglages
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div style="position: sticky; top: 50px;">
        <div class="card" style="background: var(--gray-bg); border: none; padding: 30px;">
            <h3 style="margin: 0 0 20px 0; font-weight: 800; font-size: 18px; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="info" size="24" style="color: var(--princeton-orange);"></i>
                Guide de Configuration
            </h3>
            <div style="display: flex; flex-direction: column; gap: 20px; color: var(--text-muted); font-size: 14px; font-weight: 600; line-height: 1.6;">
                <div>
                    <strong style="color: var(--carbon-black); display: block; margin-bottom: 5px;">📱 WhatsApp Business</strong>
                    Utilisez le format international sans le '+' (ex: 212600000000) pour recevoir les commandes.
                </div>
                <div>
                    <strong style="color: var(--carbon-black); display: block; margin-bottom: 5px;">🚚 Livraison</strong>
                    Les frais de livraison sont ajoutés automatiquement au panier du client.
                </div>
                <div>
                    <strong style="color: var(--carbon-black); display: block; margin-bottom: 5px;">🎁 Livraison Gratuite</strong>
                    Désactivez en mettant une valeur très élevée (ex: 99999).
                </div>
                <div>
                    <strong style="color: var(--carbon-black); display: block; margin-bottom: 5px;">⚠️ Minimum de commande</strong>
                    Empêche la validation du panier si le total est inférieur à ce montant.
                </div>
            </div>
        </div>
        
        <div class="card" style="background: var(--carbon-black); color: white; border: none; margin-top: 30px; padding: 30px;">
            <h3 style="margin: 0 0 20px 0; font-weight: 800; font-size: 18px;">État du Service</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-weight: 700; opacity: 0.7;">Boutique en ligne</span>
                <span class="status-pill status-success" style="font-size: 14px; padding: 8px 20px;">ACTIF</span>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>

