<?php
/**
 * FILE: admin/category_edit.php
 * PURPOSE: Edit an existing category.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = get_category_by_id($id);

if (!$category) {
    header('Location: categories.php');
    exit;
}

$admin_page_title = 'Modifier Catégorie';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_fr = $_POST['name_fr'] ?? '';
    $name_ar = $_POST['name_ar'] ?? '';
    $icon    = sanitize_input($_POST['icon'] ?? 'bi-folder');
    $order   = (int)($_POST['order'] ?? 0);
    $active  = isset($_POST['active']) ? 1 : 0;
    
    if (empty($name_fr)) $errors[] = "Le nom (FR) est requis.";
    if (empty($name_ar)) $errors[] = "Le nom (AR) est requis.";

    if (empty($errors)) {
        $sql = "UPDATE hri_category SET 
                category_name_fr = :name_fr, 
                category_name_ar = :name_ar, 
                category_icon = :icon, 
                category_display_order = :order, 
                category_is_active = :active 
                WHERE category_id = :id";
        
        try {
            execute_query($sql, [
                ':name_fr' => $name_fr,
                ':name_ar' => $name_ar,
                ':icon'    => $icon,
                ':order'   => $order,
                ':active'  => $active,
                ':id'      => $id
            ]);
            $success = true;
            $category = get_category_by_id($id);
        } catch (PDOException $e) {
            $errors[] = "Erreur: " . $e->getMessage();
        }
    }
}

$common_icons = get_common_category_icons();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="max-width: 900px;">
    <div style="margin-bottom: 25px;">
        <a href="<?= SITE_URL ?>/admin/categories.php" class="btn btn-light">
            <i data-lucide="arrow-left" size="16"></i> Retour
        </a>
    </div>

    <div class="card">
        <h3 style="margin: 0 0 25px 0; font-weight: 800; font-size: 20px;">
            Modifier: <span style="color: var(--princeton-orange);"><?= htmlspecialchars($category['category_name_fr']) ?></span>
        </h3>

        <?php if ($success): ?>
            <div style="background: #ecfdf5; color: #065f46; padding: 20px; border-radius: 16px; margin-bottom: 25px; font-weight: 700; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="check-circle" size="20"></i>
                    Catégorie mise à jour avec succès !
                </div>
                <a href="<?= SITE_URL ?>/admin/categories.php" style="color: inherit; font-size: 14px;">Voir la liste</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 20px; border-radius: 16px; margin-bottom: 25px; font-weight: 600;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div class="form-group">
                    <label>Nom (FR) *</label>
                    <input type="text" name="name_fr" class="form-control" value="<?= htmlspecialchars($category['category_name_fr']) ?>" required>
                </div>
                <div class="form-group" dir="rtl">
                    <label>الاسم (AR) *</label>
                    <input type="text" name="name_ar" class="form-control" value="<?= htmlspecialchars($category['category_name_ar']) ?>" required>
                </div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Choisir une Icône</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; background: var(--gray-bg); padding: 20px; border-radius: 16px; border: 1px solid var(--gray-border);">
                        <?php foreach ($common_icons as $ic): ?>
                            <label style="cursor: pointer;">
                                <input type="radio" name="icon" value="<?= $ic ?>" style="display: none;" <?= $ic == $category['category_icon'] ? 'checked' : '' ?>>
                                <div class="icon-box" title="<?= $ic ?>">
                                    <i class="bi <?= $ic ?>"></i>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ordre d'affichage</label>
                    <input type="number" name="order" class="form-control" value="<?= $category['category_display_order'] ?>">
                </div>
                <div class="form-group" style="display: flex; align-items: center; margin-top: 15px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; text-transform: none; font-size: 15px; color: var(--carbon-black); margin-bottom: 0;">
                        <input type="checkbox" name="active" value="1" <?= $category['category_is_active'] ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--princeton-orange);"> Catégorie Active (Visible sur le site)
                    </label>
                </div>
            </div>

            <div style="margin-top: 40px; text-align: right; border-top: 1px solid var(--gray-border); padding-top: 30px;">
                <button type="submit" class="btn btn-orange" style="padding: 16px 50px; font-size: 16px;">
                    <i data-lucide="refresh-cw" size="20"></i> Mettre à jour la Catégorie
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .icon-box {
        width: 50px; height: 50px; 
        background: white; border: 2px solid transparent; 
        border-radius: 12px; display: flex; 
        align-items: center; justify-content: center; 
        font-size: 20px; color: var(--text-muted);
        transition: var(--transition);
    }
    input[type="radio"]:checked + .icon-box {
        border-color: var(--princeton-orange);
        background: rgba(255, 130, 0, 0.05);
        color: var(--princeton-orange);
        transform: scale(1.1);
    }
    .icon-box:hover {
        background: #f1f5f9;
    }
</style>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
