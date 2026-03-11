<?php
/**
 * FILE: includes/functions.php
 * PURPOSE: All reusable helper functions for Matjar El Kotobia.
 * NOTE: Function names use snake_case and verb_noun format.
 *       These names are PERMANENT — do not rename.
 *
 * SECTIONS:
 *   1. Security & Sanitization
 *   2. Validation
 *   3. Database Helpers
 *   4. Product Functions
 *   5. Category Functions
 *   6. Customer Functions
 *   7. Settings Functions
 *   8. Formatting & Display
 *   9. File Upload
 *  10. Pagination
 *  11. URL & Redirect
 */

/* ================================================================
 * 1. SECURITY & SANITIZATION
 * ================================================================ */

/**
 * Sanitize user input to prevent XSS attacks.
 *
 * @param  string $input  Raw user input
 * @return string         Sanitized safe string
 */
function sanitize_input(string $input): string
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $input;
}

/**
 * Generate a new CSRF token and store in session.
 *
 * @return string  The generated CSRF token
 */
function generate_csrf_token(): string
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify a submitted CSRF token against the session token.
 *
 * @param  string $token  The token from the submitted form
 * @return bool           True if tokens match
 */
function verify_csrf_token(string $token): bool
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Output a hidden CSRF input field for forms.
 *
 * @return string  HTML hidden input element
 */
function csrf_input_field(): string
{
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . generate_csrf_token() . '">';
}

/**
 * Hash a plain-text password using bcrypt.
 *
 * @param  string $plain_password  The raw password
 * @return string                  The hashed password
 */
function hash_password(string $plain_password): string
{
    return password_hash($plain_password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify a plain password against a bcrypt hash.
 *
 * @param  string $plain_password   The raw password
 * @param  string $hashed_password  The stored hash
 * @return bool                     True if password matches
 */
function verify_password(string $plain_password, string $hashed_password): bool
{
    return password_verify($plain_password, $hashed_password);
}


/* ================================================================
 * 2. VALIDATION
 * ================================================================ */

/**
 * Validate a Moroccan phone number.
 * Accepts: 0612345678, 06 12 34 56 78, +212612345678
 *
 * @param  string $phone  Phone number to validate
 * @return bool           True if valid Moroccan mobile format
 */
function validate_phone_morocco(string $phone): bool
{
    $phone = preg_replace('/[\s\-\.]/', '', $phone);
    return (bool) preg_match('/^(0[5-7]\d{8}|(\+212|00212)[5-7]\d{8})$/', $phone);
}

/**
 * Validate email format.
 *
 * @param  string $email  Email address to validate
 * @return bool           True if valid format
 */
function validate_email(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Clean phone number to digits only (for storage).
 *
 * @param  string $phone  Raw phone input
 * @return string         Cleaned phone number
 */
function clean_phone_number(string $phone): string
{
    return preg_replace('/[^\d+]/', '', $phone);
}


/* ================================================================
 * 3. DATABASE HELPERS
 * ================================================================ */

/**
 * Execute a prepared SQL query and return the statement.
 *
 * @param  string $sql     SQL query with :placeholders
 * @param  array  $params  Associative array of parameters
 * @return PDOStatement     Executed statement object
 */
function execute_query(string $sql, array $params = []): PDOStatement
{
    global $db_connection;
    $stmt = $db_connection->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch a single row from the database.
 *
 * @param  string     $sql     SQL query
 * @param  array      $params  Query parameters
 * @return array|null          Single row or null
 */
function fetch_one(string $sql, array $params = []): ?array
{
    $stmt = execute_query($sql, $params);
    $result = $stmt->fetch();
    return $result ?: null;
}

/**
 * Fetch multiple rows from the database.
 *
 * @param  string $sql     SQL query
 * @param  array  $params  Query parameters
 * @return array           Array of rows
 */
function fetch_all(string $sql, array $params = []): array
{
    $stmt = execute_query($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Get the last inserted auto-increment ID.
 *
 * @return string  Last insert ID
 */
function get_last_insert_id(): string
{
    global $db_connection;
    return $db_connection->lastInsertId();
}

/**
 * Count rows matching a query.
 *
 * @param  string $sql     SQL COUNT query
 * @param  array  $params  Query parameters
 * @return int             Row count
 */
function count_rows(string $sql, array $params = []): int
{
    $stmt = execute_query($sql, $params);
    return (int) $stmt->fetchColumn();
}


/* ================================================================
 * 4. PRODUCT FUNCTIONS
 * ================================================================ */

/**
 * Get all active products with pagination (Public).
 *
 * @param  int   $page      Current page number
 * @param  int   $per_page  Items per page
 * @return array            Array of product rows
 */
function get_all_products(int $page = 1, int $per_page = PRODUCTS_PER_PAGE): array
{
    $offset = ($page - 1) * $per_page;
    $sql = "SELECT p.*, c.category_name_fr, c.category_name_ar
            FROM hri_product p
            LEFT JOIN hri_category c ON p.product_category_id = c.category_id
            WHERE p.product_is_active = 1
            ORDER BY p.product_sort_order ASC, p.product_created_at DESC
            LIMIT :limit OFFSET :offset";

    global $db_connection;
    $stmt = $db_connection->prepare($sql);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get ALL products for admin (includes inactive).
 *
 * @param  int   $page      Current page number
 * @param  int   $per_page  Items per page
 * @return array            Array of product rows
 */
function admin_get_all_products(int $page = 1, int $per_page = ORDERS_PER_PAGE_ADMIN): array
{
    $offset = ($page - 1) * $per_page;
    $sql = "SELECT p.*, c.category_name_fr, c.category_name_ar
            FROM hri_product p
            LEFT JOIN hri_category c ON p.product_category_id = c.category_id
            ORDER BY p.product_created_at DESC
            LIMIT :limit OFFSET :offset";

    global $db_connection;
    $stmt = $db_connection->prepare($sql);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Add a new product to the database.
 *
 * @param  array $product_data  Data from the form
 * @param  array $image_file    $_FILES['product_image']
 * @return array                ['success' => bool, 'errors' => array, 'id' => int|null]
 */
function add_product(array $product_data, array $image_file): array
{
    $errors = [];
    $image_name = DEFAULT_PRODUCT_IMAGE;

    // Validate inputs
    if (empty($product_data['product_name_fr'])) $errors[] = "French name is required.";
    if (empty($product_data['product_name_ar'])) $errors[] = "Arabic name is required.";
    if (empty($product_data['product_price']))   $errors[] = "Price is required.";
    if (empty($product_data['product_category_id'])) $errors[] = "Category is required.";

    // Handle Image Upload
    if (!empty($image_file['name'])) {
        $uploaded_image = upload_product_image($image_file);
        if ($uploaded_image) {
            $image_name = $uploaded_image;
        } else {
            $errors[] = "Image upload failed. Check file type and size.";
        }
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors, 'id' => null];
    }

    // Generate slug from French name
    $slug = generate_slug($product_data['product_name_fr']);
    
    // Ensure unique slug
    $check_slug = fetch_one("SELECT product_id FROM hri_product WHERE product_slug = :slug", [':slug' => $slug]);
    if ($check_slug) {
        $slug .= '-' . time();
    }

    $sql = "INSERT INTO hri_product 
            (product_category_id, product_name_fr, product_name_ar, product_slug, 
             product_description_fr, product_description_ar, product_price, product_sale_price, 
             product_unit, product_stock_quantity, product_image, product_is_featured, 
             product_is_on_sale, product_is_active) 
            VALUES 
            (:cat_id, :name_fr, :name_ar, :slug, :desc_fr, :desc_ar, :price, :sale_price, 
             :unit, :stock, :image, :featured, :on_sale, :active)";

    try {
        execute_query($sql, [
            ':cat_id'    => (int)$product_data['product_category_id'],
            ':name_fr'   => $product_data['product_name_fr'],
            ':name_ar'   => $product_data['product_name_ar'],
            ':slug'      => $slug,
            ':desc_fr'   => $product_data['product_description_fr'] ?? '',
            ':desc_ar'   => $product_data['product_description_ar'] ?? '',
            ':price'     => (float)$product_data['product_price'],
            ':sale_price'=> !empty($product_data['product_sale_price']) ? (float)$product_data['product_sale_price'] : null,
            ':unit'      => $product_data['product_unit'] ?? 'piece',
            ':stock'     => (int)($product_data['product_stock_quantity'] ?? 0),
            ':image'     => $image_name,
            ':featured'  => isset($product_data['product_is_featured']) ? 1 : 0,
            ':on_sale'   => isset($product_data['product_is_on_sale']) ? 1 : 0,
            ':active'    => isset($product_data['product_is_active']) ? 1 : 0
        ]);
        return ['success' => true, 'errors' => [], 'id' => (int)get_last_insert_id()];
    } catch (PDOException $e) {
        error_log("Add Product Error: " . $e->getMessage());
        return ['success' => false, 'errors' => ["Database error occurred."], 'id' => null];
    }
}

/**
 * Update an existing product.
 *
 * @param  int   $product_id    ID of product to edit
 * @param  array $product_data  Updated data
 * @param  array $image_file    $_FILES['product_image'] (optional)
 * @return array                ['success' => bool, 'errors' => array]
 */
function edit_product(int $product_id, array $product_data, array $image_file): array
{
    $errors = [];
    $existing_product = get_product_by_id($product_id);

    if (!$existing_product) {
        return ['success' => false, 'errors' => ["Product not found."]];
    }

    // Validate inputs
    if (empty($product_data['product_name_fr'])) $errors[] = "French name is required.";
    if (empty($product_data['product_name_ar'])) $errors[] = "Arabic name is required.";
    if (empty($product_data['product_price']))   $errors[] = "Price is required.";

    $image_name = $existing_product['product_image'];

    // Handle Image Upload
    if (!empty($image_file['name'])) {
        $uploaded_image = upload_product_image($image_file);
        if ($uploaded_image) {
            // Delete old image if it's not the default one
            if ($image_name !== DEFAULT_PRODUCT_IMAGE && file_exists(UPLOAD_DIR . $image_name)) {
                @unlink(UPLOAD_DIR . $image_name);
            }
            $image_name = $uploaded_image;
        } else {
            $errors[] = "Image upload failed.";
        }
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    $sql = "UPDATE hri_product SET 
            product_category_id = :cat_id,
            product_name_fr = :name_fr,
            product_name_ar = :name_ar,
            product_description_fr = :desc_fr,
            product_description_ar = :desc_ar,
            product_price = :price,
            product_sale_price = :sale_price,
            product_unit = :unit,
            product_stock_quantity = :stock,
            product_image = :image,
            product_is_featured = :featured,
            product_is_on_sale = :on_sale,
            product_is_active = :active
            WHERE product_id = :id";

    try {
        execute_query($sql, [
            ':id'        => $product_id,
            ':cat_id'    => (int)$product_data['product_category_id'],
            ':name_fr'   => $product_data['product_name_fr'],
            ':name_ar'   => $product_data['product_name_ar'],
            ':desc_fr'   => $product_data['product_description_fr'] ?? '',
            ':desc_ar'   => $product_data['product_description_ar'] ?? '',
            ':price'     => (float)$product_data['product_price'],
            ':sale_price'=> !empty($product_data['product_sale_price']) ? (float)$product_data['product_sale_price'] : null,
            ':unit'      => $product_data['product_unit'] ?? 'piece',
            ':stock'     => (int)($product_data['product_stock_quantity'] ?? 0),
            ':image'     => $image_name,
            ':featured'  => isset($product_data['product_is_featured']) ? 1 : 0,
            ':on_sale'   => isset($product_data['product_is_on_sale']) ? 1 : 0,
            ':active'    => isset($product_data['product_is_active']) ? 1 : 0
        ]);
        return ['success' => true, 'errors' => []];
    } catch (PDOException $e) {
        error_log("Edit Product Error: " . $e->getMessage());
        return ['success' => false, 'errors' => ["Database error occurred."]];
    }
}

/**
 * Delete a product and its associated image.
 *
 * @param  int $product_id  ID of product to delete
 * @return bool             True if deleted
 */
function delete_product(int $product_id): bool
{
    $product = get_product_by_id($product_id);
    if (!$product) return false;

    // Delete image file
    if ($product['product_image'] !== DEFAULT_PRODUCT_IMAGE && file_exists(UPLOAD_DIR . $product['product_image'])) {
        @unlink(UPLOAD_DIR . $product['product_image']);
    }

    $sql = "DELETE FROM hri_product WHERE product_id = :id";
    try {
        execute_query($sql, [':id' => $product_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Delete Product Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get total count of active products (for pagination).
 *
 * @return int  Total active product count
 */
function get_total_products_count(): int
{
    return count_rows("SELECT COUNT(*) FROM hri_product WHERE product_is_active = 1");
}

/**
 * Get a single product by its ID.
 *
 * @param  int        $product_id  Product ID
 * @return array|null              Product data or null
 */
function get_product_by_id(int $product_id): ?array
{
    $sql = "SELECT p.*, c.category_name_fr, c.category_name_ar
            FROM hri_product p
            LEFT JOIN hri_category c ON p.product_category_id = c.category_id
            WHERE p.product_id = :product_id";
    return fetch_one($sql, [':product_id' => $product_id]);
}

/**
 * Get a single product by its URL slug.
 *
 * @param  string     $product_slug  URL-safe slug
 * @return array|null                Product data or null
 */
function get_product_by_slug(string $product_slug): ?array
{
    $sql = "SELECT p.*, c.category_name_fr, c.category_name_ar
            FROM hri_product p
            LEFT JOIN hri_category c ON p.product_category_id = c.category_id
            WHERE p.product_slug = :product_slug AND p.product_is_active = 1";
    return fetch_one($sql, [':product_slug' => $product_slug]);
}

/**
 * Get products filtered by category ID.
 *
 * @param  int   $category_id  Category ID to filter
 * @param  int   $page         Page number
 * @param  int   $per_page     Items per page
 * @return array               Array of products
 */
function get_products_by_category(int $category_id, int $page = 1, int $per_page = PRODUCTS_PER_PAGE): array
{
    $offset = ($page - 1) * $per_page;
    $sql = "SELECT p.*, c.category_name_fr, c.category_name_ar
            FROM hri_product p
            LEFT JOIN hri_category c ON p.product_category_id = c.category_id
            WHERE p.product_category_id = :category_id AND p.product_is_active = 1
            ORDER BY p.product_sort_order ASC, p.product_created_at DESC
            LIMIT :limit OFFSET :offset";

    global $db_connection;
    $stmt = $db_connection->prepare($sql);
    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get featured products for homepage display.
 *
 * @param  int   $limit  Maximum products to return
 * @return array         Array of featured products
 */
function get_featured_products(int $limit = 8): array
{
    $sql = "SELECT * FROM hri_product
            WHERE product_is_featured = 1 AND product_is_active = 1
            ORDER BY product_sort_order ASC
            LIMIT :limit";
    global $db_connection;
    $stmt = $db_connection->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get products that are on sale.
 *
 * @param  int   $limit  Maximum products to return
 * @return array         Array of sale products
 */
function get_sale_products(int $limit = 8): array
{
    $sql = "SELECT * FROM hri_product
            WHERE product_is_on_sale = 1 AND product_is_active = 1
            AND product_sale_price IS NOT NULL
            ORDER BY product_sort_order ASC
            LIMIT :limit";
    global $db_connection;
    $stmt = $db_connection->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Improved Search products by name, description, or category.
 * Not including price in search.
 *
 * @param  string $search_query  User search input
 * @return array                 Matching products
 */
function search_products(string $search_query): array
{
    $words = explode(' ', $search_query);
    $params = [];
    $conditions = [];

    foreach ($words as $index => $word) {
        $word = trim($word);
        if (empty($word)) continue;
        
        $term = '%' . $word . '%';
        $p1 = ":nfr" . $index;
        $p2 = ":nar" . $index;

        $conditions[] = "(p.product_name_fr LIKE $p1 
                         OR p.product_name_ar LIKE $p2 )";
        
        $params[$p1] = $term;
        $params[$p2] = $term;
    }

    if (empty($conditions)) return [];

    $sql = "SELECT p.*, c.category_name_fr, c.category_name_ar
            FROM hri_product p
            LEFT JOIN hri_category c ON p.product_category_id = c.category_id
            WHERE p.product_is_active = 1
            AND " . implode(' AND ', $conditions) . "
            ORDER BY p.product_is_featured DESC, p.product_name_fr ASC
            LIMIT 30";

    return fetch_all($sql, $params);
}

/**
 * Get the effective display price for a product.
 * Returns sale_price if on sale, otherwise regular price.
 *
 * @param  array $product_data  Product row from database
 * @return float                The price to display/charge
 */
function get_product_effective_price(array $product_data): float
{
    if ($product_data['product_is_on_sale'] && !empty($product_data['product_sale_price'])) {
        return (float) $product_data['product_sale_price'];
    }
    return (float) $product_data['product_price'];
}


/* ================================================================
 * 5. CATEGORY FUNCTIONS
 * ================================================================ */

/**
 * Get all active categories ordered by display_order.
 *
 * @return array  Array of category rows
 */
function get_active_categories(): array
{
    $sql = "SELECT * FROM hri_category
            WHERE category_is_active = 1
            ORDER BY category_display_order ASC";
    return fetch_all($sql);
}

/**
 * Get ALL categories (including inactive) for admin.
 *
 * @return array  Array of all category rows
 */
function get_all_categories(): array
{
    $sql = "SELECT * FROM hri_category ORDER BY category_display_order ASC";
    return fetch_all($sql);
}

/**
 * Get a single category by ID.
 *
 * @param  int        $category_id  Category ID
 * @return array|null               Category data or null
 */
function get_category_by_id(int $category_id): ?array
{
    return fetch_one(
        "SELECT * FROM hri_category WHERE category_id = :id",
        [':id' => $category_id]
    );
}

/**
 * Get a single category by its URL slug.
 *
 * @param  string     $category_slug  URL slug
 * @return array|null                 Category data or null
 */
function get_category_by_slug(string $category_slug): ?array
{
    return fetch_one(
        "SELECT * FROM hri_category WHERE category_slug = :slug AND category_is_active = 1",
        [':slug' => $category_slug]
    );
}


/* ================================================================
 * 6. CUSTOMER FUNCTIONS
 * ================================================================ */

/**
 * Get customer by ID.
 *
 * @param  int        $customer_id
 * @return array|null
 */
function get_customer_by_id(int $customer_id): ?array
{
    return fetch_one(
        "SELECT * FROM hri_customer WHERE customer_id = :id",
        [':id' => $customer_id]
    );
}

/**
 * Get customer by phone number.
 *
 * @param  string     $customer_phone
 * @return array|null
 */
function get_customer_by_phone(string $customer_phone): ?array
{
    return fetch_one(
        "SELECT * FROM hri_customer WHERE customer_phone = :phone",
        [':phone' => $customer_phone]
    );
}

/**
 * Create a new customer account.
 *
 * @param  array $customer_data  Associative array of customer fields
 * @return int                   The new customer_id
 */
function create_customer(array $customer_data): int
{
    $sql = "INSERT INTO hri_customer
            (customer_full_name, customer_phone, customer_email,
             customer_password, customer_address, customer_neighborhood,
             customer_city, customer_preferred_lang)
            VALUES
            (:name, :phone, :email, :password, :address, :neighborhood,
             :city, :lang)";

    execute_query($sql, [
        ':name'          => $customer_data['customer_full_name'],
        ':phone'         => $customer_data['customer_phone'],
        ':email'         => $customer_data['customer_email'] ?? null,
        ':password'      => hash_password($customer_data['customer_password']),
        ':address'       => $customer_data['customer_address'] ?? null,
        ':neighborhood'  => $customer_data['customer_neighborhood'] ?? null,
        ':city'          => $customer_data['customer_city'] ?? DEFAULT_CITY,
        ':lang'          => $customer_data['customer_preferred_lang'] ?? DEFAULT_LANGUAGE,
    ]);

    return (int) get_last_insert_id();
}

/**
 * Update an existing customer account.
 *
 * @param  int   $customer_id    ID of customer to edit
 * @param  array $customer_data  Updated data
 * @return bool                  True if success
 */
function update_customer(int $customer_id, array $customer_data): bool
{
    $sql = "UPDATE hri_customer SET
            customer_full_name = :name,
            customer_phone = :phone,
            customer_email = :email,
            customer_address = :address,
            customer_neighborhood = :neighborhood,
            customer_city = :city
            WHERE customer_id = :id";

    try {
        execute_query($sql, [
            ':id'           => $customer_id,
            ':name'         => sanitize_input($customer_data['customer_full_name']),
            ':phone'        => clean_phone_number($customer_data['customer_phone']),
            ':email'        => !empty($customer_data['customer_email']) ? sanitize_input($customer_data['customer_email']) : null,
            ':address'      => !empty($customer_data['customer_address']) ? sanitize_input($customer_data['customer_address']) : null,
            ':neighborhood' => !empty($customer_data['customer_neighborhood']) ? sanitize_input($customer_data['customer_neighborhood']) : null,
            ':city'         => sanitize_input($customer_data['customer_city'] ?? DEFAULT_CITY)
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Update Customer Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Verify a customer's password (Alias for verify_password).
 *
 * @param  string $customer_phone   The raw phone
 * @param  string $plain_password   The raw password
 * @return bool                     True if matches
 */
function verify_customer_password(string $customer_phone, string $plain_password): bool
{
    $customer = get_customer_by_phone($customer_phone);
    if (!$customer) return false;
    return verify_password($plain_password, $customer['customer_password']);
}

/**
 * Check if a customer is currently logged in.
 *
 * @return bool  True if logged in
 */
function is_customer_logged_in(): bool
{
    return !empty($_SESSION[CUSTOMER_SESSION_KEY]);
}

/**
 * Get today's order count.
 *
 * @return int
 */
function get_today_orders_count(): int
{
    $today_start = date('Y-m-d 00:00:00');
    $today_end   = date('Y-m-d 23:59:59');
    return count_rows(
        "SELECT COUNT(*) FROM hri_order WHERE order_created_at BETWEEN :start AND :end",
        [':start' => $today_start, ':end' => $today_end]
    );
}

/**
 * Get today's revenue.
 *
 * @return float
 */
function get_today_revenue(): float
{
    $today_start = date('Y-m-d 00:00:00');
    $today_end   = date('Y-m-d 23:59:59');
    $row = fetch_one(
        "SELECT SUM(order_total) as revenue FROM hri_order WHERE order_created_at BETWEEN :start AND :end",
        [':start' => $today_start, ':end' => $today_end]
    );
    return (float) ($row['revenue'] ?? 0);
}

/**
 * Get pending orders count.
 *
 * @return int
 */
function get_pending_orders_count(): int
{
    return count_rows("SELECT COUNT(*) FROM hri_order WHERE order_status = 'pending'");
}

/**
 * Get count of products with low stock.
 *
 * @param  int $threshold Stock level to consider "low"
 * @return int
 */
function get_low_stock_products_count(int $threshold = 10): int
{
    return count_rows("SELECT COUNT(*) FROM hri_product WHERE product_stock_quantity <= :threshold AND product_is_active = 1", [':threshold' => $threshold]);
}

/**
 * Get total count of all products.
 *
 * @return int
 */
function get_admin_total_products_count(): int
{
    return count_rows("SELECT COUNT(*) FROM hri_product");
}

/**
 * Get total count of all customers.
 *
 * @return int
 */
function get_admin_total_customers_count(): int
{
    return count_rows("SELECT COUNT(*) FROM hri_customer");
}

/**
 * Get the currently logged-in customer's ID.
 *
 * @return int|null  Customer ID or null
 */
function get_current_customer_id(): ?int
{
    return $_SESSION[CUSTOMER_SESSION_KEY] ?? null;
}

/**
 * Log in a customer by setting session variables.
 *
 * @param  int $customer_id  The customer's ID
 * @return void
 */
function login_customer_session(int $customer_id): void
{
    session_regenerate_id(true); // Prevent session fixation
    $_SESSION[CUSTOMER_SESSION_KEY] = $customer_id;
}

/**
 * Log out the current customer.
 *
 * @return void
 */
function logout_customer(): void
{
    unset($_SESSION[CUSTOMER_SESSION_KEY]);
    session_regenerate_id(true);
}

/**
 * Check if an admin is currently logged in.
 *
 * @return bool  True if logged in
 */
function is_admin_logged_in(): bool
{
    return !empty($_SESSION[ADMIN_SESSION_KEY]);
}

/**
 * Get the currently logged-in admin's ID.
 *
 * @return int|null  Admin ID or null
 */
function get_current_admin_id(): ?int
{
    return $_SESSION[ADMIN_SESSION_KEY] ?? null;
}

/**
 * Log in an admin by setting session variables.
 *
 * @param  int $admin_id  The admin's ID
 * @return void
 */
function login_admin_session(int $admin_id): void
{
    session_regenerate_id(true);
    $_SESSION[ADMIN_SESSION_KEY] = $admin_id;
}

/**
 * Log out the current admin.
 *
 * @return void
 */
function logout_admin(): void
{
    unset($_SESSION[ADMIN_SESSION_KEY]);
    session_regenerate_id(true);
}


/* ================================================================
 * 7. SETTINGS FUNCTIONS
 * ================================================================ */

/**
 * Get a single setting value by its key.
 *
 * @param  string      $setting_key  The setting key name
 * @return string|null               Setting value or null
 */
function get_setting(string $setting_key): ?string
{
    $row = fetch_one(
        "SELECT setting_value FROM hri_settings WHERE setting_key = :key",
        [':key' => $setting_key]
    );
    return $row ? $row['setting_value'] : null;
}

/**
 * Update a setting value.
 *
 * @param  string $setting_key    Setting key to update
 * @param  string $setting_value  New value
 * @return void
 */
function update_setting(string $setting_key, string $setting_value): void
{
    execute_query(
        "UPDATE hri_settings SET setting_value = :value WHERE setting_key = :key",
        [':value' => $setting_value, ':key' => $setting_key]
    );
}

/**
 * Get all site settings as an associative array.
 *
 * @return array
 */
function get_all_settings(): array
{
    $rows = fetch_all("SELECT setting_key, setting_value FROM hri_settings");
    $settings = [];
    foreach ($rows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}


/* ================================================================
 * 8. FORMATTING & DISPLAY
 * ================================================================ */

/**
 * Format a price amount with currency suffix.
 * Example: 74.50 → "74.50 DH"
 *
 * @param  float  $amount  Numeric price
 * @return string          Formatted price string
 */
function format_price(float $amount): string
{
    return number_format($amount, 2, '.', '') . ' ' . DEFAULT_CURRENCY;
}

/**
 * Generate a URL-safe slug from text.
 *
 * @param  string $text  Input text (French product name)
 * @return string        Slugified text
 */
function generate_slug(string $text): string
{
    $text = strtolower($text);
    $text = preg_replace('/[àâä]/u', 'a', $text);
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[ïî]/u', 'i', $text);
    $text = preg_replace('/[ôö]/u', 'o', $text);
    $text = preg_replace('/[ùûü]/u', 'u', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

/**
 * Get the localized name of a product based on current language.
 *
 * @param  array  $product_data  Product row
 * @return string                Product name in current language
 */
function get_product_name(array $product_data): string
{
    global $current_language;
    $field = ($current_language === 'ar') ? 'product_name_ar' : 'product_name_fr';
    return $product_data[$field] ?? $product_data['product_name_fr'];
}

/**
 * Get the localized name of a category based on current language.
 *
 * @param  array  $category_data  Category row
 * @return string                 Category name in current language
 */
function get_category_name(array $category_data): string
{
    global $current_language;
    $field = ($current_language === 'ar') ? 'category_name_ar' : 'category_name_fr';
    return $category_data[$field] ?? $category_data['category_name_fr'];
}

/**
 * Get the product image URL (full path).
 * Falls back to default image if not set.
 *
 * @param  string|null $image_filename  Image filename from DB
 * @return string                       Full URL to image
 */
function get_product_image_url(?string $image_filename): string
{
    if (empty($image_filename) || $image_filename === DEFAULT_PRODUCT_IMAGE) {
        return UPLOAD_URL . DEFAULT_PRODUCT_IMAGE;
    }
    return UPLOAD_URL . $image_filename;
}


/* ================================================================
 * 9. FILE UPLOAD HELPER
 * ================================================================ */

/**
 * Handle product image upload with validation and resizing.
 *
 * @param  array       $file_data  $_FILES['field_name'] array
 * @return string|null             Saved filename or null on failure
 */
function upload_product_image(array $file_data): ?string
{
    // Validate file was uploaded without errors
    if ($file_data['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Validate file size
    if ($file_data['size'] > MAX_IMAGE_SIZE) {
        return null;
    }

    // Validate MIME type
    $file_mime = mime_content_type($file_data['tmp_name']);
    if (!in_array($file_mime, ALLOWED_IMAGE_TYPES)) {
        return null;
    }

    // Generate unique filename
    $extension = pathinfo($file_data['name'], PATHINFO_EXTENSION);
    $new_filename = 'product_' . uniqid() . '_' . time() . '.' . $extension;
    $destination = UPLOAD_DIR . $new_filename;

    // Move uploaded file
    if (move_uploaded_file($file_data['tmp_name'], $destination)) {
        return $new_filename;
    }

    return null;
}


/* ================================================================
 * 10. PAGINATION HELPER
 * ================================================================ */

/**
 * Calculate total pages for pagination.
 *
 * @param  int $total_items   Total number of items
 * @param  int $per_page      Items per page
 * @return int                Total number of pages
 */
function calculate_total_pages(int $total_items, int $per_page): int
{
    return (int) ceil($total_items / $per_page);
}


/* ================================================================
 * 11. URL & REDIRECT HELPERS
 * ================================================================ */

/**
 * Redirect to a given URL and stop execution.
 *
 * @param  string $url  URL to redirect to
 * @return void
 */
function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}

/**
 * Delete an order and its items.
 *
 * @param  int $order_id  ID of order to delete
 * @return bool           True if deleted
 */
function delete_order(int $order_id): bool
{
    // Items will be deleted automatically if ON DELETE CASCADE is set, 
    // but let's be explicit if not sure.
    execute_query("DELETE FROM hri_order_item WHERE order_item_order_id = :id", [':id' => $order_id]);
    
    $sql = "DELETE FROM hri_order WHERE order_id = :id";
    try {
        execute_query($sql, [':id' => $order_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Delete Order Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get a list of common Bootstrap Icons for categories.
 *
 * @return array
 */
function get_common_category_icons(): array
{
    return [
        'bi-apple', 'bi-basket', 'bi-box', 'bi-cup-straw', 'bi-droplet', 
        'bi-egg-fried', 'bi-flower1', 'bi-fruit-apple', 'bi-gift', 'bi-heart', 
        'bi-house', 'bi-lightning', 'bi-moon', 'bi-music-note', 'bi-pencils', 
        'bi-person', 'bi-phone', 'bi-shop', 'bi-star', 'bi-tag', 
        'bi-trash', 'bi-truck', 'bi-tv', 'bi-umbrella', 'bi-wallet',
        'bi-water', 'bi-wind', 'bi-wrench', 'bi-bag-heart', 'bi-balloon',
        'bi-box-seam', 'bi-camera', 'bi-cart', 'bi-cloud', 'bi-coffee'
    ];
}

/**
 * Get the current page URL.
 *
 * @return string  Current full URL
 */
function get_current_url(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Get current language code.
 *
 * @return string
 */
function get_current_language(): string
{
    global $current_language;
    return $current_language;
}

/**
 * Get current text direction.
 *
 * @return string 'rtl' or 'ltr'
 */
function get_direction(): string
{
    global $is_rtl;
    return $is_rtl ? 'rtl' : 'ltr';
}

/**
 * Shortcut to translate a key using the global $lang array.
 *
 * @param  string $key  Translation key from lang/fr.php or lang/ar.php
 * @return string       Translated string or the key itself as fallback
 */
function translate(string $key): string
{
    global $lang;
    return $lang[$key] ?? $key;
}

/**
 * Alias for translate(). Shorter to type in templates.
 *
 * @param  string $key  Translation key
 * @return string       Translated string
 */
function t(string $key): string
{
    return translate($key);
}
