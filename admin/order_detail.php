<?php
/**
 * FILE: admin/order_detail.php
 * PURPOSE: View and manage a single order. Enhanced with Receipt Copy, WhatsApp Script, and Preparation Slip.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = get_order_by_id($order_id);

if (!$order) {
    header('Location: orders.php');
    exit;
}

$order_items = get_order_items($order_id);
$admin_page_title = 'Détails Commande #' . $order['order_number'];

// Load Settings for Store WhatsApp
$settings = get_all_settings();
$store_whatsapp = $settings['store_whatsapp'] ?? STORE_WHATSAPP_NUMBER;
$prep_whatsapp = $settings['preparation_whatsapp'] ?? $store_whatsapp;

// Prepare WhatsApp Scripts for both languages (Confirmation to Customer)
$customer_phone = clean_phone_number($order['order_customer_phone']);
if (strpos($customer_phone, '0') === 0 && strlen($customer_phone) === 10) {
    $customer_phone = '212' . substr($customer_phone, 1);
}

// Load templates without polluting global $lang
function get_script_templates($lang_code) {
    include __DIR__ . "/../lang/{$lang_code}.php";
    return $lang;
}

$templates_ar = get_script_templates('ar');
$wa_script_ar = sprintf($templates_ar['manager_whatsapp_script'], $order['order_customer_name'], $order['order_number']);
$wa_url_ar = build_whatsapp_url($customer_phone, $wa_script_ar);

$templates_fr = get_script_templates('fr');
$wa_script_fr = sprintf($templates_fr['manager_whatsapp_script'], $order['order_customer_name'], $order['order_number']);
$wa_url_fr = build_whatsapp_url($customer_phone, $wa_script_fr);

// Force admin panel back into French
load_language('fr');

require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- html2canvas for receipt capture -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
    <a href="<?= SITE_URL ?>/admin/orders.php" class="btn btn-light" style="padding: 8px 15px;">
        <i data-lucide="arrow-left" size="16"></i> Retour aux commandes
    </a>
    
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <!-- Preparation Button (Send to workers) -->
        <button id="btnSendPreparation" class="btn btn-light" style="border: 2px solid #25D366; color: #166534; font-weight: 800;">
            <i data-lucide="clipboard-list" size="18"></i> <?= translate('send_to_preparation') ?>
        </button>

        <!-- Lang Switcher for WA -->
        <div style="background: white; padding: 5px; border-radius: 12px; border: 1px solid var(--gray-border); display: flex; gap: 5px;">
            <button onclick="switchWALang('ar')" id="wa-lang-ar" class="btn btn-light wa-lang-btn active" style="padding: 6px 12px; font-size: 12px; min-width: 45px; justify-content: center;">AR</button>
            <button onclick="switchWALang('fr')" id="wa-lang-fr" class="btn btn-light wa-lang-btn" style="padding: 6px 12px; font-size: 12px; min-width: 45px; justify-content: center;">FR</button>
        </div>

        <button id="btnCopyReceipt" class="btn btn-orange" style="background: var(--carbon-black);">
            <i data-lucide="copy" size="18"></i> <?= translate('copy_receipt') ?>
        </button>
        
        <a id="btnSendWhatsApp" href="<?= $wa_url_ar ?>" target="_blank" class="btn" style="background: #25D366; color: white;">
            <i class="bi bi-whatsapp"></i> <?= translate('send_confirmation') ?>
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Main Order Info -->
    <div>
        <div class="card">
            <h3 style="margin: 0 0 20px 0; font-weight: 800; font-size: 18px;">
                Articles Commandés (<?= count($order_items) ?>)
            </h3>
            <table>
                <thead>
                    <tr style="background: var(--carbon-black);">
                        <th style="color: whitesmoke;  border-radius:10px 0 0 10px;">Produit</th>
                        <th style="text-align: center; color: whitesmoke;">Prix</th>
                        <th style="text-align: center; color: whitesmoke;">Quantité</th>
                        <th style="text-align: right; color: whitesmoke;  border-radius:0 10px 10px 0;">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--carbon-black);"><?= htmlspecialchars($item['order_item_name_fr']) ?></div>
                                <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;"><?= htmlspecialchars($item['order_item_name_ar']) ?></div>
                            </td>
                            <td style="text-align: center; font-weight: 600;"><?= format_price($item['order_item_unit_price']) ?></td>
                            <td style="text-align: center; font-weight: 700;">
                                <?= (float)$item['order_item_quantity'] ?> <small style="font-size: 10px; color: var(--text-muted);"><?= translate($item['order_item_unit']) ?></small>
                            </td>
                            <td style="text-align: right; font-weight: 800; color: var(--princeton-orange);"><?= format_price($item['order_item_subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--gray-border); display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                <div style="display: flex; gap: 40px; font-weight: 600; color: var(--text-muted);">
                    <span>Sous-total:</span>
                    <span style="min-width: 100px; text-align: right;"><?= format_price($order['order_subtotal']) ?></span>
                </div>
                <div style="display: flex; gap: 40px; font-weight: 600; color: var(--text-muted);">
                    <span>Livraison:</span>
                    <span style="min-width: 100px; text-align: right; color: <?= (float)$order['order_delivery_fee'] > 0 ? '#1d1d1d' : ('#14a549') ?>;">
                    <?= (float)$order['order_delivery_fee'] > 0 ? format_price((float)$order['order_delivery_fee']) : ('Gratuit') ?>
                </span>
                </div>
                <div style="display: flex; gap: 40px; font-weight: 900; font-size: 20px; color: var(--carbon-black); margin-top: 5px;">
                    <span>TOTAL:</span>
                    <span style="min-width: 100px; text-align: right; color: var(--brand-color);"><?= format_price($order['order_total']) ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="margin: 0 0 20px 0; font-weight: 800; font-size: 18px;">Informations Client & Livraison</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                <div>
                    <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Client</label>
                    <div style="font-weight: 700; font-size: 16px;"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                    <div style="font-weight: 800; color: var(--princeton-orange); margin-top: 5px;"><?= htmlspecialchars($order['order_customer_phone']) ?></div>
                </div>
                <div>
                    <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Ville / Quartier</label>
                    <div style="font-weight: 700; font-size: 16px;"><?= htmlspecialchars($order['order_customer_city']) ?></div>
                    <div style="font-weight: 600; color: var(--text-muted);"><?= htmlspecialchars($order['order_customer_neighborhood'] ?? '-') ?></div>
                </div>
                <div style="grid-column: span 2;">
                    <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Adresse Complète</label>
                    <div style="background: var(--gray-bg); padding: 15px; border-radius: 12px; font-weight: 600; color: var(--carbon-black); line-height: 1.5;">
                        <?= htmlspecialchars($order['order_customer_address']) ?>
                    </div>
                </div>
                <?php if ($order['order_notes']): ?>
                    <div style="grid-column: span 2;">
                        <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Notes de Commande</label>
                        <div style="background: #fffbeb; color: #92400e; padding: 15px; border-radius: 12px; font-weight: 600; font-size: 14px; border: 1px solid #fde68a;">
                            <?= nl2br(htmlspecialchars($order['order_notes'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar: Order Actions -->
    <div>
        <div class="card">
            <h3 style="margin: 0 0 20px 0; font-weight: 800; font-size: 16px;">Statut de la Commande</h3>
            <?php 
            $status_class = 'status-pending';
            switch($order['order_status']) {
                case 'pending': $status_class = 'status-pending'; break;
                case 'confirmed': $status_class = 'status-info'; break;
                case 'preparing': $status_class = 'status-info'; break;
                case 'out_for_delivery': $status_class = 'status-info'; break;
                case 'delivered': $status_class = 'status-success'; break;
                case 'cancelled': $status_class = 'status-danger'; break;
            }
            ?>
            <div style="text-align: center; margin-bottom: 25px;">
                <span class="status-pill <?= $status_class ?>" style="font-size: 18px; padding: 12px 25px;">
                    <?= translate('status_' . $order['order_status']) ?>
                </span>
            </div>

            <form action="order_update_status.php" method="POST">
                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                <div class="form-group">
                    <label>Livreur Assigné</label>
                    <select name="livreur_id" class="form-control" style="margin-bottom: 15px;">
                        <option value="">-- Choisir un livreur --</option>
                        <?php 
                        $livreurs = get_all_livreurs(true);
                        foreach ($livreurs as $l): ?>
                            <option value="<?= $l['livreur_id'] ?>" <?= ($order['order_livreur_id'] == $l['livreur_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l['livreur_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Changer le Statut</label>
                    <select name="new_status" class="form-control">
                        <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                        <option value="confirmed" <?= $order['order_status'] == 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                        <option value="preparing" <?= $order['order_status'] == 'preparing' ? 'selected' : '' ?>>En préparation</option>
                        <option value="out_for_delivery" <?= $order['order_status'] == 'out_for_delivery' ? 'selected' : '' ?>>En livraison</option>
                        <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : '' ?>>Livrée</option>
                        <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-orange" style="width: 100%; justify-content: center; padding: 14px;">
                    <i data-lucide="refresh-cw" size="18"></i> Mettre à Jour
                </button>
            </form>
        </div>

        <div class="card" style="background: var(--carbon-black); color: white; border: none; margin-top: 20px;">
            <h3 style="margin: 0 0 10px 0; font-weight: 800; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="printer" size="18"></i> Impression
            </h3>
            <a href="order_print.php?id=<?= $order['order_id'] ?>" class="btn btn-light" style="box-sizing: border-box;width: 100%; justify-content: center;">
                Imprimer le Bon de Livraison
            </a>
        </div>
    </div>
</div>

<!-- Hidden Customer Receipt for Capture -->
<div style="position: absolute; left: -9999px; top: 0;">
    <div id="receipt-to-capture" style="width: 500px; background: white; padding: 30px; border: 1px solid #eee; font-family: 'Plus Jakarta Sans', sans-serif;">
        <div style="border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="font-weight: 900; font-size: 18px;">MATJAR<span style="color: #F68B1E;">.</span>KOTOBIA</div>
            <div style="text-align: right;">
                <div style="font-weight: 800; font-size: 14px;"><?= translate('receipt_title') ?></div>
                <div style="font-size: 11px; opacity: 0.6;">#<?= $order['order_number'] ?></div>
                <div style="font-size: 11px; opacity: 0.6;"><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <div style="font-size: 9px; text-transform: uppercase; font-weight: 800; color: #670d0c; margin-bottom: 4px;"><?= translate('client') ?></div>
                <div style="font-size: 12px; font-weight: 700; line-height: 1.4;"><?= htmlspecialchars($order['order_customer_name']) ?></div>
                <div style="font-size: 12px; font-weight: 700; line-height: 1.4;"><?= htmlspecialchars($order['order_customer_phone']) ?></div>
            </div>
            <div>
                <div style="font-size: 9px; text-transform: uppercase; font-weight: 800; color: #670d0c; margin-bottom: 4px;"><?= translate('delivery') ?></div>
                <div style="font-size: 12px; font-weight: 700; line-height: 1.4;"><?= htmlspecialchars($order['order_customer_address']) ?></div>
                <div style="font-size: 10px; color: #717171;"><?= htmlspecialchars($order['order_customer_city']) ?></div>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: var(--carbon-black);">
                    <th style="text-align: left; font-size: 10px; text-transform: uppercase; padding: 8px 10px;color: #ffffff; "><?= translate('quantity') ?></th>
                    <th style="text-align: left; font-size: 10px; text-transform: uppercase; padding: 8px 0;  color: #ffffff;"><?= translate('designation') ?></th>
                    <th style="text-align: right; font-size: 10px; text-transform: uppercase; padding: 8px 10px; color: #ffffff; "><?= translate('total') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td style="padding: 10px 8px; font-size: 12px; font-weight: 800; color: #F68B1E;"><?= (float)$item['order_item_quantity'] ?>x</td>
                        <td style="padding: 10px 0; font-size: 12px; font-weight: 600;"><?= htmlspecialchars($item['order_item_name_fr']) ?></td>
                        <td style="padding: 10px 8px; font-size: 12px; font-weight: 600; text-align: right;"><?= format_price($item['order_item_subtotal']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-left: auto; width: 200px;">
            <div style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px;">
                <span><?= translate('subtotal') ?></span>
                <span><?= format_price($order['order_subtotal']) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px;">
                <span><?= translate('delivery_fee') ?></span>
                <span style="color: <?= (float)$order['order_delivery_fee'] > 0 ? '#1d1d1d' : ('#14a549') ?>;">
                    <?= (float)$order['order_delivery_fee'] > 0 ? format_price((float)$order['order_delivery_fee']) : ('Gratuit') ?>
                </span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 10px 0; font-size: 16px; font-weight: 900; border-top: 2px solid #000; margin-top: 5px;">
                <span><?= translate('total') ?></span>
                <span style="color: #670d0c;"><?= format_price($order['order_total']) ?></span>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: center; font-size: 10px; color: #717171; font-weight: 600; border-top: 1px dashed #eee; padding-top: 15px;">
            <p><?= translate('thanks_confidence') ?></p>
            <p>Matjar El Kotobia • Safi, Maroc</p>
        </div>
    </div>
</div>

<!-- Hidden Preparation Slip (Picking List) for Workers -->
<div style="position: absolute; left: -9999px; top: 0;">
    <div id="prep-slip-to-capture" style="width: 550px; background: white; padding: 30px; border: 3px solid #000; font-family: 'Plus Jakarta Sans', sans-serif;">
        <div style="text-align: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px;">
            <div style="font-weight: 900; font-size: 26px; text-transform: uppercase; letter-spacing: 1px;"><?= translate('preparation_slip') ?></div>
            <div style="font-weight: 800; font-size: 20px; margin-top: 8px; background: #000; color: #fff; display: inline-block; padding: 5px 20px; border-radius: 50px;">#<?= $order['order_number'] ?></div>
            <div style="font-size: 14px; font-weight: 700; opacity: 0.8; margin-top: 10px;"><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></div>
        </div>

        <div style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="width: 60%;">
                <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: #717171; margin-bottom: 3px;"><?= translate('client') ?></div>
                <div style="font-weight: 900; font-size: 18px; line-height: 1.2;"><?= htmlspecialchars($order['order_customer_name']) ?></div>
            </div>
        </div>

        <?php if($order['order_notes']): ?>
            <div style="margin-bottom: 25px; background: #fdf2f2; padding: 15px; border-radius: 12px; border: 2px solid #000;">
                <div style="font-size: 11px; text-transform: uppercase; font-weight: 900; color: #ef4444; margin-bottom: 5px; display: flex; align-items: center; gap: 5px;">
                    <i data-lucide="alert-triangle" size="14"></i> INSTRUCTIONS SPÉCIALES
                </div>
                <div style="font-size: 15px; font-weight: 800; color: #000; line-height: 1.4;">
                    <?= nl2br(htmlspecialchars($order['order_notes'])) ?>
                </div>
            </div>
        <?php endif; ?>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #1a1a1a;">
                    <th style="padding: 12px 10px; text-align: center; font-size: 14px; font-weight: 900; width: 60px; color: #ffffff;">QTY</th>
                    <th style="padding: 12px 10px; text-align: center; font-size: 14px; font-weight: 900; width: 80px; color: #ffffff;">IMG</th>
                    <th style="padding: 12px 10px; text-align: left; font-size: 14px; font-weight: 900; color: #ffffff;"><?= translate('designation') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): 
                    $img_url = !empty($item['product_image']) ? UPLOAD_URL . $item['product_image'] : UPLOAD_URL . DEFAULT_PRODUCT_IMAGE;
                ?>
                    <tr style="border-bottom: 2px solid #000;">
                        <td style="padding: 15px 10px; text-align: center; font-size: 18px; font-weight: 800; background: #f7f7f7; color:var(--brand-color);">
                            <?= (float)$item['order_item_quantity'] ?>x
                        </td>
                        <td style="padding: 10px; text-align: center; background: #f7f7f7; width: 80px; height: 80px;">
                            <div style="width:70px;height:70px;display:flex;align-items:center;justify-content:center;background:#fff;border-radius:8px;border:1px solid #b8b8b8;overflow:hidden;">
                                <img src="<?= $img_url ?>" style="max-width:100%;max-height:100%;object-fit:contain;">
                            </div>
                        </td>  
                        <td style="padding: 15px 15px; background: #f7f7f7; font-size: 18px; font-weight: 800; line-height: 1.2;">
                            <div style="color: #000;"><?= htmlspecialchars($item['order_item_name_fr'] ) ?></div>
                            <div style="font-size: 16px; margin-top: 4px; color: #555;"><?= htmlspecialchars($item['order_item_name_ar']) ?></div>
                            <div style="font-size: 12px; margin-top: 6px; font-weight: 700; text-transform: uppercase; color: #F68B1E; display: inline-block; background: #fff9f2; padding: 2px 8px; border-radius: 4px;">
                                <?= translate('unit_' . $item['order_item_unit']) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .wa-lang-btn.active { background: var(--princeton-orange) !important; color: white !important; border-color: var(--princeton-orange) !important; }
</style>

<script>
const waUrls = {
    'ar': '<?= $wa_url_ar ?>',
    'fr': '<?= $wa_url_fr ?>'
};

function switchWALang(lang) {
    document.querySelectorAll('.wa-lang-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('wa-lang-' + lang).classList.add('active');
    document.getElementById('btnSendWhatsApp').href = waUrls[lang];
}

document.addEventListener('DOMContentLoaded', function() {
    // Check if we need to notify customer of status update
    <?php if (isset($_SESSION['notify_customer_wa_url'])): ?>
        const waUrl = "<?= $_SESSION['notify_customer_wa_url'] ?>";
        // Create a temporary overlay to force user interaction if needed
        const notifyOverlay = document.createElement('div');
        notifyOverlay.style.cssText = 'position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:10000; display:flex; align-items:center; justify-content:center; padding:20px;';
        notifyOverlay.innerHTML = `
            <div class="card" style="max-width:400px; text-align:center;">
                <h3 style="margin-top:0;">Statut Mis à Jour !</h3>
                <p>Voulez-vous envoyer la notification WhatsApp au client maintenant ?</p>
                <div style="display:flex; gap:10px; justify-content:center; margin-top:20px;">
                    <button id="btnCancelNotify" class="btn btn-light">Plus tard</button>
                    <a href="${waUrl}" target="_blank" id="btnConfirmNotify" class="btn btn-orange" style="background:#25D366;">
                        <i class="bi bi-whatsapp"></i> Envoyer
                    </a>
                </div>
            </div>
        `;
        document.body.appendChild(notifyOverlay);
        
        document.getElementById('btnCancelNotify').onclick = () => notifyOverlay.remove();
        document.getElementById('btnConfirmNotify').onclick = () => {
            setTimeout(() => notifyOverlay.remove(), 500);
        };

        <?php unset($_SESSION['notify_customer_wa_url']); ?>
    <?php endif; ?>

    const btnCopy = document.getElementById('btnCopyReceipt');
    const btnSendPrep = document.getElementById('btnSendPreparation');
    
    // Logic for Customer Receipt Copy
    btnCopy.addEventListener('click', async function() {
        const originalText = btnCopy.innerHTML;
        btnCopy.innerHTML = '<i class="spinner-border spinner-border-sm"></i> ...';
        btnCopy.disabled = true;

        try {
            const container = document.getElementById('receipt-to-capture');
            const canvas = await html2canvas(container, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff'
            });

            canvas.toBlob(async (blob) => {
                try {
                    const item = new ClipboardItem({ "image/png": blob });
                    await navigator.clipboard.write([item]);
                    
                    btnCopy.innerHTML = '<i data-lucide="check" size="18"></i> <?= translate('receipt_copied') ?>';
                    btnCopy.style.background = '#00a650';
                    
                    setTimeout(() => {
                        btnCopy.innerHTML = originalText;
                        btnCopy.style.background = 'var(--carbon-black)';
                        btnCopy.disabled = false;
                        lucide.createIcons();
                    }, 3000);
                } catch (err) {
                    console.error('Clipboard error:', err);
                    alert('Erreur lors de la copie. Veuillez réessayer.');
                    btnCopy.innerHTML = originalText;
                    btnCopy.disabled = false;
                    lucide.createIcons();
                }
            }, 'image/png');

        } catch (error) {
            console.error('Canvas error:', error);
            alert('Erreur lors de la génération de l\'image.');
            btnCopy.innerHTML = originalText;
            btnCopy.disabled = false;
            lucide.createIcons();
        }
    });

    // Logic for Preparation Slip (Send to workers)
    btnSendPrep.addEventListener('click', async function() {
        const originalText = btnSendPrep.innerHTML;
        btnSendPrep.innerHTML = '<i class="spinner-border spinner-border-sm"></i> ...';
        btnSendPrep.disabled = true;

        try {
            const container = document.getElementById('prep-slip-to-capture');
            const canvas = await html2canvas(container, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff'
            });

            canvas.toBlob(async (blob) => {
                try {
                    const item = new ClipboardItem({ "image/png": blob });
                    await navigator.clipboard.write([item]);
                    
                    btnSendPrep.innerHTML = '<i data-lucide="check" size="18"></i> <?= translate('prep_slip_copied') ?>';
                    btnSendPrep.style.borderColor = '#00a650';
                    btnSendPrep.style.color = '#00a650';

                    // Prepare WhatsApp Message for Owner
                    const ownerWAUrl = "https://wa.me/<?= clean_phone_number($prep_whatsapp) ?>?text=" + encodeURIComponent("*BON DE PRÉPARATION* - Commande *#<?= $order['order_number'] ?>*");
                    
                    setTimeout(() => {
                        window.open(ownerWAUrl, '_blank');
                        setTimeout(() => {
                            btnSendPrep.innerHTML = originalText;
                            btnSendPrep.style.borderColor = '#25D366';
                            btnSendPrep.style.color = '#166534';
                            btnSendPrep.disabled = false;
                            lucide.createIcons();
                        }, 2000);
                    }, 1000);

                } catch (err) {
                    console.error('Clipboard error:', err);
                    alert('Erreur lors de la copie. Veuillez réessayer.');
                    btnSendPrep.innerHTML = originalText;
                    btnSendPrep.disabled = false;
                    lucide.createIcons();
                }
            }, 'image/png');

        } catch (error) {
            console.error('Canvas error:', error);
            alert('Erreur lors de la génération du bon.');
            btnSendPrep.innerHTML = originalText;
            btnSendPrep.disabled = false;
            lucide.createIcons();
        }
    });
});
</script>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
