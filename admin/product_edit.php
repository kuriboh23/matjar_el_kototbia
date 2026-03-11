<?php
/**
 * FILE: admin/product_edit.php
 * PURPOSE: Edit an existing product.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = get_product_by_id($product_id);

if (!$product) {
    header('Location: products.php');
    exit;
}

$admin_page_title = 'Modifier Produit';

$errors = [];
$success = false;

// Fetch Categories
$categories = get_active_categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_data = $_POST;
    $image_file = $_FILES['product_image'] ?? [];

    $result = edit_product($product_id, $product_data, $image_file);

    if ($result['success']) {
        $success = true;
        // Refresh data
        $product = get_product_by_id($product_id);
    } else {
        $errors = $result['errors'];
    }
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<div style="max-width: 1100px;">
    <div style="margin-bottom: 30px;">
        <a href="<?= SITE_URL ?>/admin/products.php" class="btn btn-light">
            <i data-lucide="arrow-left" size="18"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <h3 style="margin: 0 0 30px 0; font-weight: 800; font-size: 24px;">
            Modifier: <span style="color: var(--princeton-orange);"><?= htmlspecialchars($product['product_name_fr']) ?></span>
        </h3>

        <?php if ($success): ?>
            <div style="background: #ecfdf5; color: #065f46; padding: 20px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i data-lucide="check-circle" size="24"></i>
                    Produit mis à jour avec succès !
                </div>
                <a href="<?= SITE_URL ?>/admin/products.php" class="btn btn-light" style="background: white;">Voir la liste</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 20px; border-radius: 16px; margin-bottom: 30px; font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.2);">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div style="display: flex; gap: 30px; border-bottom: 2px solid var(--gray-bg); margin-bottom: 35px; padding-bottom: 5px;">
                <button type="button" onclick="switchTab('fr')" id="tab-btn-fr" class="btn-tab active">Français</button>
                <button type="button" onclick="switchTab('ar')" id="tab-btn-ar" class="btn-tab">العربية</button>
            </div>

            <div id="tab-fr" class="tab-pane-content">
                <div class="form-group">
                    <label>Nom du Produit (FR) *</label>
                    <input type="text" name="product_name_fr" class="form-control lg" required value="<?= htmlspecialchars($product['product_name_fr']) ?>">
                </div>
                <div class="form-group">
                    <label>Description (FR)</label>
                    <textarea name="product_description_fr" class="form-control" rows="4" style="height: auto;"><?= htmlspecialchars($product['product_description_fr']) ?></textarea>
                </div>
            </div>

            <div id="tab-ar" class="tab-pane-content" style="display: none;" dir="rtl">
                <div class="form-group">
                    <label>اسم المنتج (AR) *</label>
                    <input type="text" name="product_name_ar" class="form-control lg" required value="<?= htmlspecialchars($product['product_name_ar']) ?>">
                </div>
                <div class="form-group">
                    <label>الوصف (AR)</label>
                    <textarea name="product_description_ar" class="form-control" rows="4" style="height: auto;"><?= htmlspecialchars($product['product_description_ar']) ?></textarea>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 40px; padding-top: 40px; border-top: 2px solid var(--gray-bg);">
                <div class="form-group">
                    <label>Catégorie *</label>
                    <select name="product_category_id" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" <?= $product['product_category_id'] == $cat['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name_fr']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Unité *</label>
                    <select name="product_unit" class="form-control">
                        <option value="kg" <?= $product['product_unit'] == 'kg' ? 'selected' : '' ?>>Kilogramme (kg)</option>
                        <option value="piece" <?= $product['product_unit'] == 'piece' ? 'selected' : '' ?>>Pièce</option>
                        <option value="pack" <?= $product['product_unit'] == 'pack' ? 'selected' : '' ?>>Paquet</option>
                        <option value="liter" <?= $product['product_unit'] == 'liter' ? 'selected' : '' ?>>Litre</option>
                        <option value="unit" <?= $product['product_unit'] == 'unit' ? 'selected' : '' ?>>Unité</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Stock Disponible</label>
                    <input type="number" name="product_stock_quantity" class="form-control" value="<?= $product['product_stock_quantity'] ?>" min="0">
                </div>

                <div class="form-group">
                    <label>Prix de Vente (DH) *</label>
                    <div style="position: relative;">
                        <input type="number" name="product_price" class="form-control" step="0.01" required value="<?= $product['product_price'] ?>" style="padding-right: 50px;">
                        <span style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-weight: 800; color: var(--text-muted);">DH</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Prix Promo (DH)</label>
                    <div style="position: relative;">
                        <input type="number" name="product_sale_price" class="form-control" step="0.01" value="<?= $product['product_sale_price'] ?>" style="padding-right: 50px;">
                        <span style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-weight: 800; color: var(--danger);">DH</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Image du Produit</label>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <img src="<?= get_product_image_url($product['product_image']) ?>" alt="" style="width: 60px; height: 60px; border-radius: 12px; object-fit: cover; border: 1px solid var(--gray-border);">
                        <input type="file" name="product_image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group" style="grid-column: span 3; display: flex; gap: 40px; align-items: center; background: var(--gray-bg); padding: 25px; border-radius: 16px; margin-top: 10px;">
                    <label class="check-label">
                        <input type="checkbox" name="product_is_on_sale" value="1" <?= $product['product_is_on_sale'] ? 'checked' : '' ?>> 
                        <span>Activer badge Promo</span>
                    </label>
                    <label class="check-label">
                        <input type="checkbox" name="product_is_featured" value="1" <?= $product['product_is_featured'] ? 'checked' : '' ?>> 
                        <span>Produit en Vedette</span>
                    </label>
                    <label class="check-label">
                        <input type="checkbox" name="product_is_active" value="1" <?= $product['product_is_active'] ? 'checked' : '' ?>> 
                        <span>Produit Actif (Visible)</span>
                    </label>
                </div>
            </div>

            <div style="margin-top: 40px; text-align: right;">
                <button type="submit" class="btn btn-orange" style="padding: 18px 60px; font-size: 16px;">
                    <i data-lucide="refresh-cw" size="22"></i> Mettre à jour le Produit
                </button>
            </div>

        </form>
    </div>
</div>

<style>
    .btn-tab { background: none; border: none; font-weight: 800; font-size: 16px; color: var(--text-muted); cursor: pointer; padding: 15px 0; border-bottom: 3px solid transparent; transition: var(--transition); }
    .btn-tab.active { color: var(--princeton-orange); border-bottom-color: var(--princeton-orange); }
    .form-control.lg { font-size: 18px; padding: 18px 25px; }
    
    .check-label { display: flex; align-items: center; gap: 12px; cursor: pointer; text-transform: none; font-size: 15px; font-weight: 700; color: var(--carbon-black); }
    .check-label input { width: 22px; height: 22px; accent-color: var(--princeton-orange); cursor: pointer; }
</style>

<script>
    function switchTab(lang) {
        document.getElementById('tab-fr').style.display = lang === 'fr' ? 'block' : 'none';
        document.getElementById('tab-ar').style.display = lang === 'ar' ? 'block' : 'none';
        document.getElementById('tab-btn-fr').classList.toggle('active', lang === 'fr');
        document.getElementById('tab-btn-ar').classList.toggle('active', lang === 'ar');
    }
</script>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
