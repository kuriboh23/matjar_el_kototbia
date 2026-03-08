<?php
/**
 * FILE: includes/cart_functions.php
 * PURPOSE: Session-based cart management.
 *          Cart structure in session: [product_id => quantity]
 */

/**
 * Add a product to the cart or update its quantity.
 *
 * @param  int   $product_id
 * @param  float $quantity
 * @return void
 */
function add_to_cart(int $product_id, float $quantity = 1): void
{
    if (!isset($_SESSION[CART_SESSION_KEY])) {
        $_SESSION[CART_SESSION_KEY] = [];
    }

    if (isset($_SESSION[CART_SESSION_KEY][$product_id])) {
        $_SESSION[CART_SESSION_KEY][$product_id] += $quantity;
    } else {
        $_SESSION[CART_SESSION_KEY][$product_id] = $quantity;
    }
}

/**
 * Update a product's quantity in the cart.
 *
 * @param  int   $product_id
 * @param  float $quantity
 * @return void
 */
function update_cart_quantity(int $product_id, float $quantity): void
{
    if ($quantity <= 0) {
        remove_from_cart($product_id);
    } else {
        $_SESSION[CART_SESSION_KEY][$product_id] = $quantity;
    }
}

/**
 * Remove a product from the cart.
 *
 * @param  int $product_id
 * @return void
 */
function remove_from_cart(int $product_id): void
{
    if (isset($_SESSION[CART_SESSION_KEY][$product_id])) {
        unset($_SESSION[CART_SESSION_KEY][$product_id]);
    }
}

/**
 * Clear the entire cart.
 *
 * @return void
 */
function clear_cart(): void
{
    $_SESSION[CART_SESSION_KEY] = [];
}

/**
 * Get all cart items with full product data from the database.
 *
 * @return array  Array of items: ['id', 'qty', 'data' => product_row]
 */
function get_cart_items(): array
{
    $cart = $_SESSION[CART_SESSION_KEY] ?? [];
    if (empty($cart)) {
        return [];
    }

    $items = [];
    $product_ids = array_keys($cart);
    
    // Create placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    
    $sql = "SELECT * FROM hri_product WHERE product_id IN ($placeholders) AND product_is_active = 1";
    $products = fetch_all($sql, $product_ids);

    foreach ($products as $product) {
        $id = $product['product_id'];
        $items[] = [
            'id'   => $id,
            'qty'  => $cart[$id],
            'data' => $product
        ];
    }

    return $items;
}

/**
 * Calculate the cart subtotal.
 *
 * @param  array $cart_items  Result from get_cart_items()
 * @return float
 */
function calculate_cart_subtotal(array $cart_items): float
{
    $subtotal = 0;
    foreach ($cart_items as $item) {
        $price = get_product_effective_price($item['data']);
        $subtotal += $price * $item['qty'];
    }
    return (float) $subtotal;
}

/**
 * Calculate the delivery fee.
 *
 * @param  float $subtotal
 * @return float
 */
function calculate_delivery_fee(float $subtotal): float
{
    if ($subtotal >= FREE_DELIVERY_THRESHOLD || $subtotal <= 0) {
        return 0.00;
    }
    return (float) DELIVERY_FEE;
}

/**
 * Calculate the grand total.
 *
 * @param  float $subtotal
 * @param  float $delivery_fee
 * @return float
 */
function calculate_cart_total(float $subtotal, float $delivery_fee): float
{
    return (float) ($subtotal + $delivery_fee);
}

/**
 * Get the total count of items in the cart.
 *
 * @return int
 */
function get_cart_item_count(): int
{
    $cart = $_SESSION[CART_SESSION_KEY] ?? [];
    return (int) array_sum($cart);
}
?>
