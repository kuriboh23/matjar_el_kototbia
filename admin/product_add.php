<?php
/**
 * FILE: admin/product_add.php
 * PURPOSE: Add a new product to the catalog.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Ajouter un Produit';

$errors = [];
$success = false;

// Fetch Categories for dropdown
$categories = get_active_categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic CSRF or just simple post check
    $product_data = $_POST;
    $image_file = $_FILES['product_image'] ?? [];

    $result = add_product($product_data, $image_file);

    if ($result['success']) {
        $success = true;
        // Optionally redirect to products list
        // header('Location: products.php?msg=added'); exit;
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
                    <h5 class="fw-bold mb-0"><?= $admin_page_title ?></h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Produit ajouté avec succès ! <a href="<?= SITE_URL ?>/admin/products.php" class="alert-link">Voir la liste</a>
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
                        
                        <!-- Tabs for Language -->
                        <ul class="nav nav-tabs mb-4" id="langTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="fr-tab" data-bs-toggle="tab" data-bs-target="#fr-panel" type="button" role="tab">Français</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ar-tab" data-bs-toggle="tab" data-bs-target="#ar-panel" type="button" role="tab">العربية</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="langTabsContent">
                            <!-- French Panel -->
                            <div class="tab-pane fade show active" id="fr-panel" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nom du Produit (FR) *</label>
                                    <input type="text" name="product_name_fr" class="form-control" required placeholder="Ex: Tomates Fraîches">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description (FR)</label>
                                    <textarea name="product_description_fr" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <!-- Arabic Panel -->
                            <div class="tab-pane fade" id="ar-panel" role="tabpanel" dir="rtl">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">اسم المنتج (AR) *</label>
                                    <input type="text" name="product_name_ar" class="form-control" required placeholder="مثال: طماطم طازجة">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">الوصف (AR)</label>
                                    <textarea name="product_description_ar" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Catégorie *</label>
                                <select name="product_category_id" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name_fr']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Unité *</label>
                                <select name="product_unit" class="form-select">
                                    <option value="kg">Kilogramme (kg)</option>
                                    <option value="piece">Pièce</option>
                                    <option value="pack">Paquet</option>
                                    <option value="liter">Litre</option>
                                    <option value="unit">Unité</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Quantité en Stock</label>
                                <input type="number" name="product_stock_quantity" class="form-control" value="0" min="0">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Prix (DH) *</label>
                                <div class="input-group">
                                    <input type="number" name="product_price" class="form-control" step="0.01" required min="0">
                                    <span class="input-group-text">DH</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Prix Promo (DH)</label>
                                <div class="input-group">
                                    <input type="number" name="product_sale_price" class="form-control" step="0.01" min="0">
                                    <span class="input-group-text text-danger">DH</span>
                                </div>
                                <small class="text-muted">Laisser vide si pas de promo.</small>
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="product_is_on_sale" id="onSale" value="1">
                                    <label class="form-check-label fw-semibold" for="onSale">Activer le badge Promo</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Image du Produit</label>
                                <input type="file" name="product_image" class="form-control" accept="image/*">
                                <small class="text-muted">Types acceptés: JPG, PNG, WEBP. Taille max: 2MB.</small>
                            </div>

                            <div class="col-md-6 mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="product_is_featured" id="isFeatured" value="1">
                                    <label class="form-check-label fw-semibold" for="isFeatured">Produit en Vedette (Accueil)</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="product_is_active" id="isActive" value="1" checked>
                                    <label class="form-check-label fw-semibold" for="isActive">Produit Actif (Visible)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 text-end">
                            <button type="reset" class="btn btn-light px-4 me-2">Réinitialiser</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Enregistrer le Produit</button>
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
