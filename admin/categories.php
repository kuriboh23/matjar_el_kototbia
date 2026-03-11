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

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="margin: 0; font-weight: 800; font-size: 20px; color: var(--text-muted);">
        <?= count($categories) ?> catégories au total
    </h2>
    <a href="<?= SITE_URL ?>/admin/category_add.php" class="btn btn-orange">
        <i data-lucide="plus" size="18"></i> Nouvelle Catégorie
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Ordre</th>
                    <th style="width: 80px; text-align: center;">Icône</th>
                    <th>Nom (FR)</th>
                    <th>Nom (AR)</th>
                    <th>Slug</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td style="font-weight: 800; color: var(--text-muted);"><?= $c['category_display_order'] ?></td>
                        <td style="text-align: center;">
                            <div style="width: 40px; height: 40px; background: var(--gray-bg); border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="bi <?= $c['category_icon'] ?? 'bi-folder' ?>" style="font-size: 18px; color: var(--princeton-orange);"></i>
                            </div>
                        </td>
                        <td><div style="font-weight: 700; color: var(--carbon-black);"><?= htmlspecialchars($c['category_name_fr']) ?></div></td>
                        <td><div style="font-weight: 700; color: var(--carbon-black);"><?= htmlspecialchars($c['category_name_ar']) ?></div></td>
                        <td><code style="background: var(--gray-bg); padding: 2px 6px; border-radius: 4px; font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($c['category_slug']) ?></code></td>
                        <td>
                            <?php if ($c['category_is_active']): ?>
                                <span class="status-pill status-success">Actif</span>
                            <?php else: ?>
                                <span class="status-pill" style="background: #f1f5f9; color: #64748b;">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="<?= SITE_URL ?>/admin/category_edit.php?id=<?= $c['category_id'] ?>" class="btn btn-light" style="padding: 8px;" title="Modifier">
                                    <i data-lucide="edit-3" size="14"></i>
                                </a>
                                <button type="button" class="btn btn-light" style="padding: 8px; color: var(--danger);" onclick="if(confirm('Supprimer cette catégorie ? Les produits liés resteront mais n\'auront plus de catégorie.')) window.location.href='<?= SITE_URL ?>/admin/category_delete.php?id=<?= $c['category_id'] ?>'" title="Supprimer">
                                    <i data-lucide="trash-2" size="14"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 50px;">Aucune catégorie trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
