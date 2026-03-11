<?php
/**
 * FILE: admin/customers.php
 * PURPOSE: List all customers.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Gestion des Clients';

$customers = fetch_all("SELECT * FROM hri_customer ORDER BY customer_created_at DESC");

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="margin: 0; font-weight: 800; font-size: 20px; color: var(--text-muted);">
        <?= count($customers) ?> clients inscrits
    </h2>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Ville / Quartier</th>
                    <th>Inscrit le</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--carbon-black);"><?= htmlspecialchars($c['customer_full_name']) ?></div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;"><?= htmlspecialchars($c['customer_email'] ?? 'Pas d\'email') ?></div>
                        </td>
                        <td style="font-weight: 800;"><?= htmlspecialchars($c['customer_phone']) ?></td>
                        <td>
                            <div style="font-weight: 700; color: var(--carbon-black);"><?= htmlspecialchars($c['customer_city']) ?></div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;"><?= htmlspecialchars($c['customer_neighborhood'] ?? '-') ?></div>
                        </td>
                        <td style="font-weight: 600; color: var(--text-muted);"><?= date('d/m/Y', strtotime($c['customer_created_at'])) ?></td>
                        <td style="text-align: right;">
                            <a href="<?= SITE_URL ?>/admin/customer_detail.php?id=<?= $c['customer_id'] ?>" class="btn btn-light" style="padding: 8px 15px;">
                                <i data-lucide="user" size="14" style="margin-right: 5px;"></i> Voir Profil
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 50px;">Aucun client trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
