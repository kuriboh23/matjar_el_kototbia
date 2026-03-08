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

$admin_page_title = 'Modifier Produit: ' . $product['product_name_fr'];

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

<div class="container-fluid">
    <div class="mb-4">
        <a href="<?= SITE_URL ?>/admin/products.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">Modifier: <?= htmlspecialchars($product['product_name_fr']) ?></h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Produit mis à jour avec succès ! <a href="<?= SITE_URL ?>/admin/products.php" class="alert-link">Voir la liste</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <ul class="nav nav-tabs mb-4" id="langTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="fr-tab" data-bs-toggle="tab" data-bs-target="#fr-panel" type="button" role="tab">Français</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ar-tab" data-bs-toggle="tab" data-bs-target="#ar-panel" type="button" role="tab">العربية</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="langTabsContent">
                            <div class="tab-pane fade show active" id="fr-panel" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nom du Produit (FR) *</label>
                                    <input type="text" name="product_name_fr" class="form-control" required value="<?= htmlspecialchars($product['product_name_fr']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description (FR)</label>
                                    <textarea name="product_description_fr" class="form-control" rows="3"><?= htmlspecialchars($product['product_description_fr']) ?></textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ar-panel" role="tabpanel" dir="rtl">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">اسم المنتج (AR) *</label>
                                    <input type="text" name="product_name_ar" class="form-control" required value="<?= htmlspecialchars($product['product_name_ar']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">الوصف (AR)</label>
                                    <textarea name="product_description_ar" class="form-control" rows="3"><?= htmlspecialchars($product['product_description_ar']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Catégorie *</label>
                                <select name="product_category_id" class="form-select" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['category_id'] ?>" <?= $product['product_category_id'] == $cat['category_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['category_name_fr']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Unité *</label>
                                <select name="product_unit" class="form-select">
                                    <option value="kg" <?= $product['product_unit'] == 'kg' ? 'selected' : '' ?>>Kilogramme (kg)</option>
                                    <option value="piece" <?= $product['product_unit'] == 'piece' ? 'selected' : '' ?>>Pièce</option>
                                    <option value="pack" <?= $product['product_unit'] == 'pack' ? 'selected' : '' ?>>Paquet</option>
                                    <option value="liter" <?= $product['product_unit'] == 'liter' ? 'selected' : '' ?>>Litre</option>
                                    <option value="unit" <?= $product['product_unit'] == 'unit' ? 'selected' : '' ?>>Unité</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Quantité en Stock</label>
                                <input type="number" name="product_stock_quantity" class="form-control" value="<?= $product['product_stock_quantity'] ?>" min="0">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Prix (DH) *</label>
                                <div class="input-group">
                                    <input type="number" name="product_price" class="form-control" step="0.01" required value="<?= $product['product_price'] ?>">
                                    <span class="input-group-text">DH</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Prix Promo (DH)</label>
                                <div class="input-group">
                                    <input type="number" name="product_sale_price" class="form-control" step="0.01" value="<?= $product['product_sale_price'] ?>">
                                    <span class="input-group-text text-danger">DH</span>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="product_is_on_sale" id="onSale" value="1" <?= $product['product_is_on_sale'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="onSale">Activer le badge Promo</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Image du Produit</label>
                                <div class="mb-2">
                                    <img src="<?= get_product_image_url($product['product_image']) ?>" alt="" class="img-thumbnail" style="width: 100px;">
                                </div>
                                <input type="file" name="product_image" class="form-control" accept="image/*">
                                <small class="text-muted">Laisser vide pour conserver l'image actuelle.</small>
                            </div>

                            <div class="col-md-6 mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="product_is_featured" id="isFeatured" value="1" <?= $product['product_is_featured'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="isFeatured">Produit en Vedette (Accueil)</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="product_is_active" id="isActive" value="1" <?= $product['product_is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="isActive">Produit Actif (Visible)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Mettre à jour le Produit</button>
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
