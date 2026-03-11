<?php
/**
 * FILE: admin/order_detail.php
 * PURPOSE: View and manage a single order.
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

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="margin-bottom: 25px;">
    <a href="<?= SITE_URL ?>/admin/orders.php" class="btn btn-light" style="padding: 8px 15px;">
        <i data-lucide="arrow-left" size="16"></i> Retour aux commandes
    </a>
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
                    <tr>
                        <th>Produit</th>
                        <th style="text-align: center;">Prix</th>
                        <th style="text-align: center;">Quantité</th>
                        <th style="text-align: right;">Sous-total</th>
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
                                <?= $item['order_item_quantity'] ?> <small style="font-size: 10px; color: var(--text-muted);"><?= translate('unit_' . $item['order_item_unit']) ?></small>
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
                    <span style="min-width: 100px; text-align: right;"><?= format_price($order['order_delivery_fee']) ?></span>
                </div>
                <div style="display: flex; gap: 40px; font-weight: 900; font-size: 20px; color: var(--carbon-black); margin-top: 5px;">
                    <span>TOTAL:</span>
                    <span style="min-width: 100px; text-align: right; color: var(--princeton-orange);"><?= format_price($order['order_total']) ?></span>
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

        <div class="card" style="background: #25D366; color: white; border: none;">
            <h3 style="margin: 0 0 10px 0; font-weight: 800; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-whatsapp"></i> WhatsApp
            </h3>
            <p style="font-size: 13px; font-weight: 600; opacity: 0.9; line-height: 1.5; margin-bottom: 20px;">
                Contacter le client directement pour confirmer les détails de la livraison.
            </p>
            <a href="https://wa.me/<?= $order['order_customer_phone'] ?>" target="_blank" class="btn" style="width: 100%; justify-content: center; background: white; color: #25D366; padding: 12px;">
                Ouvrir la discussion
            </a>
        </div>
        
        <div class="card" style="background: var(--carbon-black); color: white; border: none; margin-top: 20px;">
            <h3 style="margin: 0 0 10px 0; font-weight: 800; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="printer" size="18"></i> Impression
            </h3>
            <button onclick="window.print()" class="btn btn-light" style="width: 100%; justify-content: center;">
                Imprimer le Bon de Livraison
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        aside, .top-bar, .btn, form { display: none !important; }
        main { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
        .card { border: 1px solid #eee !important; box-shadow: none !important; border-radius: 0 !important; margin-bottom: 10px !important; }
    }
</style>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
