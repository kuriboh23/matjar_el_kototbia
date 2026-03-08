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

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0"><?= $admin_page_title ?></h2>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-uppercase">
                            <th class="ps-4">Client</th>
                            <th>Téléphone</th>
                            <th>Ville / Quartier</th>
                            <th>Inscrit le</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($c['customer_full_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($c['customer_email'] ?? 'Pas d\'email') ?></small>
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($c['customer_phone']) ?></td>
                                <td>
                                    <?= htmlspecialchars($c['customer_city']) ?> 
                                    <small class="text-muted">(<?= htmlspecialchars($c['customer_neighborhood'] ?? '-') ?>)</small>
                                </td>
                                <td><?= date('d/m/Y', strtotime($c['customer_created_at'])) ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?= SITE_URL ?>/admin/customer_detail.php?id=<?= $c['customer_id'] ?>" class="btn btn-sm btn-light border">
                                        <i class="bi bi-eye me-1"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
