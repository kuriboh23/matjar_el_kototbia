<?php
/**
 * FILE: includes/cart_functions.php
 * PURPOSE: Server-side cart management functions.
 *          Cart is stored in $_SESSION[CART_SESSION_KEY].
 *          Each item: ['product_id'=>int, 'quantity'=>float, 'product_data'=>array]
 * NOTE: Cart can also be managed client-side via localStorage (see cart.js).
 *       These functions handle the server-side persistence for logged-in users.
 */

/**
 * Get all cart items from session.
 *
 * @return array  Array of cart items
 */
function get_cart_items(): array
{
    return $_SESSION[CART_SESSION_KEY] ?? [];
}

/**
 * Add a product to the session cart.
 *
 * @param  int   $product_id  Product to add
 * @param  float $quantity    Quantity to add
 * @return bool               True on success
 */
function add_to_cart(int $product_id, float $quantity = 1): bool
{
    // Fetch product data from DB to verify it exists and is active
    $product_data = get_product_by_id($product_id);
    if (!$product_data || !$product_data['product_is_active']) {
        return false;
    }

    // Initialize cart if empty
    if (!isset($_SESSION[CART_SESSION_KEY])) {
        $_SESSION[CART_SESSION_KEY] = [];
    }

    // Check if product already in cart
    $found = false;
    foreach ($_SESSION[CART_SESSION_KEY] as &$cart_item) {
        if ($cart_item['product_id'] === $product_id) {
            $cart_item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    unset($cart_item); // Break reference

    // If not found, add as new item
    if (!$found) {
        $_SESSION[CART_SESSION_KEY][] = [
            'product_id'   => $product_id,
            'quantity'     => $quantity,
            'product_data' => $product_data,
        ];
    }

    return true;
}

/**
 * Update the quantity of a product in the cart.
 *
 * @param  int   $product_id    Product to update
 * @param  float $new_quantity  New quantity (0 removes it)
 * @return void
 */
function update_cart_quantity(int $product_id, float $new_quantity): void
{
    if ($new_quantity <= 0) {
        remove_from_cart($product_id);
        return;
    }

    if (isset($_SESSION[CART_SESSION_KEY])) {
        foreach ($_SESSION[CART_SESSION_KEY] as &$cart_item) {
            if ($cart_item['product_id'] === $product_id) {
                $cart_item['quantity'] = $new_quantity;
                break;
            }
        }
        unset($cart_item);
    }
}

/**
 * Remove a product from the cart.
 *
 * @param  int  $product_id  Product to remove
 * @return void
 */
function remove_from_cart(int $product_id): void
{
    if (isset($_SESSION[CART_SESSION_KEY])) {
        $_SESSION[CART_SESSION_KEY] = array_filter(
            $_SESSION[CART_SESSION_KEY],
            function ($cart_item) use ($product_id) {
                return $cart_item['product_id'] !== $product_id;
            }
        );
        // Re-index array
        $_SESSION[CART_SESSION_KEY] = array_values($_SESSION[CART_SESSION_KEY]);
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
 * Calculate the cart subtotal (before delivery fee).
 *
 * @param  array $cart_items  Array of cart items
 * @return float              Subtotal amount
 */
function calculate_cart_subtotal(array $cart_items): float
{
    $subtotal = 0;
    foreach ($cart_items as $cart_item) {
        $price = get_product_effective_price($cart_item['product_data']);
        $subtotal += $price * $cart_item['quantity'];
    }
    return round($subtotal, 2);
}

/**
 * Calculate delivery fee based on subtotal.
 *
 * @param  float $subtotal  Cart subtotal
 * @return float            Delivery fee (0 if above threshold)
 */
function calculate_delivery_fee(float $subtotal): float
{
    if ($subtotal >= FREE_DELIVERY_THRESHOLD) {
        return 0.00;
    }
    return DELIVERY_FEE;
}

/**
 * Calculate the grand total (subtotal + delivery).
 *
 * @param  float $subtotal     Cart subtotal
 * @param  float $delivery_fee Delivery fee
 * @return float               Grand total
 */
function calculate_cart_total(float $subtotal, float $delivery_fee): float
{
    return round($subtotal + $delivery_fee, 2);
}

/**
 * Get the total number of items in the cart.
 *
 * @return int  Item count
 */
function get_cart_item_count(): int
{
    $cart_items = get_cart_items();
    $count = 0;
    foreach ($cart_items as $cart_item) {
        $count += $cart_item['quantity'];
    }
    return (int) $count;
}
