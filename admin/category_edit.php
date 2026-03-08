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

$admin_page_title = 'Modifier Catégorie: ' . $category['category_name_fr'];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_fr = sanitize_input($_POST['name_fr'] ?? '');
    $name_ar = sanitize_input($_POST['name_ar'] ?? '');
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

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="<?= SITE_URL ?>/admin/categories.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">Modifier: <?= htmlspecialchars($category['category_name_fr']) ?></h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($success): ?>
                        <div class="alert alert-success">Catégorie mise à jour avec succès !</div>
                    <?php endif; ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom (FR) *</label>
                                <input type="text" name="name_fr" class="form-control" value="<?= htmlspecialchars($category['category_name_fr']) ?>" required>
                            </div>
                            <div class="col-md-6" dir="rtl">
                                <label class="form-label fw-semibold">الاسم (AR) *</label>
                                <input type="text" name="name_ar" class="form-control" value="<?= htmlspecialchars($category['category_name_ar']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Icône (Bootstrap Icon class)</label>
                                <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($category['category_icon']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ordre d'affichage</label>
                                <input type="number" name="order" class="form-control" value="<?= $category['category_display_order'] ?>">
                            </div>
                            <div class="col-12 mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="active" id="catActive" <?= $category['category_is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="catActive">Catégorie Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
