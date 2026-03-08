<?php
/**
 * FILE: admin/categories.php
 * PURPOSE: Manage product categories.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Gestion des Catégories';

$categories = get_all_categories();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0"><?= $admin_page_title ?></h2>
        <a href="<?= SITE_URL ?>/admin/category_add.php" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Nouvelle Catégorie
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-uppercase">
                            <th class="ps-4" style="width: 60px;">Ordre</th>
                            <th style="width: 60px;">Icône</th>
                            <th>Nom (FR)</th>
                            <th>Nom (AR)</th>
                            <th>Slug</th>
                            <th>Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): ?>
                            <tr>
                                <td class="ps-4 text-center fw-bold text-muted"><?= $c['category_display_order'] ?></td>
                                <td class="text-center">
                                    <i class="<?= $c['category_icon'] ?? 'bi-folder' ?> fs-5"></i>
                                </td>
                                <td><div class="fw-semibold"><?= htmlspecialchars($c['category_name_fr']) ?></div></td>
                                <td><div class="fw-semibold"><?= htmlspecialchars($c['category_name_ar']) ?></div></td>
                                <td><code><?= htmlspecialchars($c['category_slug']) ?></code></td>
                                <td>
                                    <?php if ($c['category_is_active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="<?= SITE_URL ?>/admin/category_edit.php?id=<?= $c['category_id'] ?>" class="btn btn-sm btn-light border">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger border" onclick="if(confirm('Supprimer cette catégorie ? Les produits liés resteront mais n\'auront plus de catégorie.')) window.location.href='<?= SITE_URL ?>/admin/category_delete.php?id=<?= $c['category_id'] ?>'">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
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
