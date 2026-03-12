<?php
/**
 * FILE: admin/livreurs.php
 * PURPOSE: Manage delivery personnel.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Gestion des Livreurs';

global $db_connection;

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_livreur'])) {
        $name = sanitize_input($_POST['livreur_name']);
        $phone = sanitize_input($_POST['livreur_phone']);
        if ($name) {
            $stmt = $db_connection->prepare("INSERT INTO hri_livreur (livreur_name, livreur_phone) VALUES (:name, :phone)");
            $stmt->execute([':name' => $name, ':phone' => $phone]);
        }
    } elseif (isset($_POST['toggle_active'])) {
        $id = (int)$_POST['livreur_id'];
        $status = (int)$_POST['current_status'] ? 0 : 1;
        $stmt = $db_connection->prepare("UPDATE hri_livreur SET livreur_is_active = :status WHERE livreur_id = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);
    } elseif (isset($_POST['delete_livreur'])) {
        $id = (int)$_POST['livreur_id'];
        $stmt = $db_connection->prepare("DELETE FROM hri_livreur WHERE livreur_id = :id");
        $stmt->execute([':id' => $id]);
    }
    header('Location: livreurs.php');
    exit;
}

$livreurs = get_all_livreurs();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2 style="margin: 0; font-weight: 800; font-size: 24px; color: var(--carbon-black);">
        Nos Livreurs
    </h2>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    <!-- Add Livreur Form -->
    <div>
        <div class="card">
            <h3 style="margin-top: 0; font-size: 18px; font-weight: 800;">Ajouter un Livreur</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nom Complet</label>
                    <input type="text" name="livreur_name" class="form-control" required placeholder="Ex: Ahmed ...">
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="livreur_phone" class="form-control" placeholder="06XXXXXXXX">
                </div>
                <button type="submit" name="add_livreur" class="btn btn-orange" style="width: 100%; justify-content: center; padding: 12px;">
                    <i data-lucide="plus" size="18"></i> Ajouter
                </button>
            </form>
        </div>
    </div>

    <!-- Livreurs List -->
    <div>
        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livreurs as $l): ?>
                            <tr>
                                <td style="font-weight: 800;"><?= htmlspecialchars($l['livreur_name']) ?></td>
                                <td style="font-weight: 600; color: var(--princeton-orange);"><?= htmlspecialchars($l['livreur_phone'] ?: '-') ?></td>
                                <td>
                                    <?php if ($l['livreur_is_active']): ?>
                                        <span class="status-pill status-success">Actif</span>
                                    <?php else: ?>
                                        <span class="status-pill" style="background: #f1f5f9; color: #64748b;">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                        <form action="" method="POST" style="margin: 0;">
                                            <input type="hidden" name="livreur_id" value="<?= $l['livreur_id'] ?>">
                                            <input type="hidden" name="current_status" value="<?= $l['livreur_is_active'] ?>">
                                            <button type="submit" name="toggle_active" class="btn btn-light" style="padding: 8px; color: <?= $l['livreur_is_active'] ? 'var(--princeton-orange)' : 'var(--success)' ?>" title="Activer/Désactiver">
                                                <i data-lucide="power" size="16"></i>
                                            </button>
                                        </form>
                                        <form action="" method="POST" style="margin: 0;" onsubmit="return confirm('Supprimer ce livreur ?');">
                                            <input type="hidden" name="livreur_id" value="<?= $l['livreur_id'] ?>">
                                            <button type="submit" name="delete_livreur" class="btn btn-light" style="padding: 8px; color: var(--danger);">
                                                <i data-lucide="trash-2" size="16"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($livreurs)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 40px; font-weight: 700;">Aucun livreur</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>

