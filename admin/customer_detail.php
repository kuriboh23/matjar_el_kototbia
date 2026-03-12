<?php
/**
 * FILE: admin/customer_detail.php
 * PURPOSE: View a single customer's profile and order history.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$customer = get_customer_by_id($id);

if (!$customer) {
    header('Location: customers.php');
    exit;
}

$orders = get_orders_by_customer($id);
$admin_page_title = 'Détails Client';

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="margin-bottom: 25px;">
    <a href="<?= SITE_URL ?>/admin/customers.php" class="btn btn-light" style="padding: 8px 15px;">
        <i data-lucide="arrow-left" size="16"></i> Retour aux clients
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    <!-- Customer Info -->
    <div>
        <div class="card" style="text-align: center;">
            <div style="width: 80px; height: 80px; background: var(--gray-bg); color: var(--text-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i data-lucide="user" size="40"></i>
            </div>
            <h2 style="margin: 0; font-weight: 800; font-size: 20px;"><?= htmlspecialchars($customer['customer_full_name']) ?></h2>
            <p style="color: var(--text-muted); font-size: 13px; font-weight: 600; margin: 5px 0 20px 0;">Inscrit le <?= date('d/m/Y', strtotime($customer['customer_created_at'])) ?></p>
            
            <div style="text-align: left; display: flex; flex-direction: column; gap: 20px; border-top: 1px solid var(--gray-border); padding-top: 20px;">
                <div>
                    <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Téléphone</label>
                    <div style="font-weight: 800; color: var(--princeton-orange); font-size: 16px;"><?= htmlspecialchars($customer['customer_phone']) ?></div>
                </div>
                <div>
                    <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Email</label>
                    <div style="font-weight: 700; color: var(--carbon-black);"><?= htmlspecialchars($customer['customer_email'] ?? '-') ?></div>
                </div>
                <div>
                    <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Localisation</label>
                    <div style="font-weight: 700; color: var(--carbon-black);"><?= htmlspecialchars($customer['customer_city']) ?></div>
                    <div style="font-size: 13px; font-weight: 600; color: var(--text-muted);"><?= htmlspecialchars($customer['customer_neighborhood'] ?? '-') ?></div>
                </div>
                <div>
                    <label style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block;">Adresse</label>
                    <div style="background: var(--gray-bg); padding: 12px; border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--carbon-black); line-height: 1.5;">
                        <?= htmlspecialchars($customer['customer_address'] ?? '-') ?>
                    </div>
                </div>
            </div>
            
            <a href="https://wa.me/<?= $customer['customer_phone'] ?>" target="_blank" class="btn" style="width: 100%; justify-content: center; background: #25D366; color: white; margin-top: 25px; padding: 14px;">
                <i class="bi bi-whatsapp"></i> Contacter via WhatsApp
            </a>
        </div>
    </div>

    <!-- Order History -->
    <div>
        <div class="card">
            <h3 style="margin: 0 0 20px 0; font-weight: 800; font-size: 18px;">Historique des Commandes (<?= count($orders) ?>)</h3>
            
            <?php if (empty($orders)): ?>
                <div style="text-align: center; color: var(--text-muted); padding: 50px; font-weight: 600;">
                    <i data-lucide="shopping-bag" size="48" style="opacity: 0.1; display: block; margin: 0 auto 15px auto;"></i>
                    Aucune commande pour ce client.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>N° Commande</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 700; color: var(--carbon-black);"><?= date('d/m/Y', strtotime($order['order_created_at'])) ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;"><?= date('H:i', strtotime($order['order_created_at'])) ?></div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--princeton-orange);">#<?= $order['order_number'] ?></td>
                                    <td style="font-weight: 800;"><?= format_price($order['order_total']) ?></td>
                                    <td>
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
                                        <span class="status-pill <?= $status_class ?>"><?= translate('status_' . $order['order_status']) ?></span>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="<?= SITE_URL ?>/admin/order_detail.php?id=<?= $order['order_id'] ?>" class="btn btn-light" style="padding: 8px 15px;">
                                            <i data-lucide="eye" size="14" style="margin-right: 5px;"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>

