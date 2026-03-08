<?php
/**
 * FILE: includes/order_functions.php
 * PURPOSE: Order creation, number generation, and WhatsApp message builder.
 * CRITICAL: The build_whatsapp_message() function formats the order
 *           for the store owner's WhatsApp.
 */

/**
 * Generate a unique order number.
 * Format: HRI-YYYYMMDD-XXX (e.g., HRI-20250101-001)
 *
 * @return string  Unique order number
 */
function generate_order_number(): string
{
    $date_part = date('Ymd');
    $prefix = ORDER_NUMBER_PREFIX . '-' . $date_part . '-';

    // Count today's orders to generate sequential number
    $today_start = date('Y-m-d 00:00:00');
    $today_end   = date('Y-m-d 23:59:59');
    $count = count_rows(
        "SELECT COUNT(*) FROM hri_order
         WHERE order_created_at BETWEEN :start AND :end",
        [':start' => $today_start, ':end' => $today_end]
    );

    $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    return $prefix . $sequence;
}

/**
 * Create a new order in the database.
 *
 * @param  array $order_data   Order header data
 * @param  array $order_items  Array of order line items
 * @return int                 The new order_id
 */
function create_order(array $order_data, array $order_items): int
{
    global $db_connection;

    try {
        $db_connection->beginTransaction();

        // Insert order header
        $sql = "INSERT INTO hri_order
                (order_number, order_customer_id, order_customer_name,
                 order_customer_phone, order_customer_address,
                 order_customer_neighborhood, order_customer_city,
                 order_notes, order_subtotal, order_delivery_fee,
                 order_total, order_item_count, order_status,
                 order_payment_method, order_language, order_ip_address)
                VALUES
                (:number, :customer_id, :name, :phone, :address,
                 :neighborhood, :city, :notes, :subtotal, :delivery_fee,
                 :total, :item_count, :status, :payment, :lang, :ip)";

        execute_query($sql, [
            ':number'       => $order_data['order_number'],
            ':customer_id'  => $order_data['order_customer_id'],
            ':name'         => $order_data['order_customer_name'],
            ':phone'        => $order_data['order_customer_phone'],
            ':address'      => $order_data['order_customer_address'],
            ':neighborhood' => $order_data['order_customer_neighborhood'] ?? null,
            ':city'         => $order_data['order_customer_city'] ?? DEFAULT_CITY,
            ':notes'        => $order_data['order_notes'] ?? null,
            ':subtotal'     => $order_data['order_subtotal'],
            ':delivery_fee' => $order_data['order_delivery_fee'],
            ':total'        => $order_data['order_total'],
            ':item_count'   => $order_data['order_item_count'],
            ':status'       => ORDER_STATUS_PENDING,
            ':payment'      => 'cod',
            ':lang'         => $order_data['order_language'] ?? DEFAULT_LANGUAGE,
            ':ip'           => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $order_id = (int) get_last_insert_id();

        // Insert each order item
        $item_sql = "INSERT INTO hri_order_item
                     (order_item_order_id, order_item_product_id,
                      order_item_name_fr, order_item_name_ar,
                      order_item_unit_price, order_item_quantity,
                      order_item_unit, order_item_subtotal)
                     VALUES
                     (:order_id, :product_id, :name_fr, :name_ar,
                      :unit_price, :quantity, :unit, :subtotal)";

        foreach ($order_items as $item) {
            execute_query($item_sql, [
                ':order_id'   => $order_id,
                ':product_id' => $item['product_id'],
                ':name_fr'    => $item['product_name_fr'],
                ':name_ar'    => $item['product_name_ar'],
                ':unit_price' => $item['unit_price'],
                ':quantity'   => $item['quantity'],
                ':unit'       => $item['unit'],
                ':subtotal'   => $item['subtotal'],
            ]);
        }

        $db_connection->commit();
        return $order_id;

    } catch (Exception $e) {
        $db_connection->rollBack();
        error_log('[HRI_ORDER_ERROR] ' . $e->getMessage());
        return 0;
    }
}

/**
 * Build the formatted WhatsApp message for an order.
 *
 * @param  array  $order_data   Order header info
 * @param  array  $order_items  Order line items
 * @return string               Formatted WhatsApp message text
 */
function build_whatsapp_message(array $order_data, array $order_items): string
{
    $msg  = "🛒 *طلب جديد — Nouvelle Commande*\n";
    $msg .= "━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "📋 *رقم الطلب — N° Commande:* #" . $order_data['order_number'] . "\n\n";
    $msg .= "👤 *الاسم — Nom:* " . $order_data['order_customer_name'] . "\n";
    $msg .= "📞 *الهاتف — Tél:* " . $order_data['order_customer_phone'] . "\n";
    $msg .= "📍 *العنوان — Adresse:* " . $order_data['order_customer_address'] . "\n";

    if (!empty($order_data['order_customer_neighborhood'])) {
        $msg .= "🏘️ *الحي — Quartier:* " . $order_data['order_customer_neighborhood'] . "\n";
    }

    $msg .= "🏙️ *المدينة — Ville:* " . ($order_data['order_customer_city'] ?? DEFAULT_CITY) . "\n";
    $msg .= "\n━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "📦 *المنتجات — Produits:*\n\n";

    // List each item with number emoji
    $number_emojis = ['1️⃣','2️⃣','3️⃣','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟'];
    $index = 0;
    foreach ($order_items as $item) {
        $emoji = $number_emojis[$index] ?? '▪️';
        $msg .= $emoji . " " . $item['product_name_fr'] . " / " . $item['product_name_ar'] . "\n";
        $msg .= "   🔢 Qté: " . $item['quantity'] . " " . $item['unit'] . "\n";
        $msg .= "   💰 Prix: " . format_price($item['subtotal']) . "\n\n";
        $index++;
    }

    $msg .= "━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "💰 *الإجمالي — Sous-total:* " . format_price($order_data['order_subtotal']) . "\n";
    $msg .= "🚚 *التوصيل — Livraison:* " . format_price($order_data['order_delivery_fee']) . "\n";
    $msg .= "💵 *المجموع — Total:* " . format_price($order_data['order_total']) . "\n";

    if (!empty($order_data['order_notes'])) {
        $msg .= "\n📝 *ملاحظات — Notes:* " . $order_data['order_notes'] . "\n";
    }

    $msg .= "\n━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "✅ الدفع عند الاستلام — Paiement à la livraison\n";
    $msg .= "🕐 وقت الطلب — Heure: " . date('Y-m-d H:i') . "\n";

    return $msg;
}

/**
 * Build the full WhatsApp URL with encoded message.
 *
 * @param  string $phone_number  Store WhatsApp number (international)
 * @param  string $message       Message text
 * @return string                Full wa.me URL
 */
function build_whatsapp_url(string $phone_number, string $message): string
{
    return WHATSAPP_API_URL . $phone_number . '?text=' . rawurlencode($message);
}

/**
 * Get order by order number.
 *
 * @param  string     $order_number
 * @return array|null
 */
function get_order_by_number(string $order_number): ?array
{
    return fetch_one(
        "SELECT * FROM hri_order WHERE order_number = :num",
        [':num' => $order_number]
    );
}

/**
 * Get all items for a given order.
 *
 * @param  int   $order_id
 * @return array
 */
function get_order_items(int $order_id): array
{
    return fetch_all(
        "SELECT * FROM hri_order_item WHERE order_item_order_id = :oid ORDER BY order_item_id ASC",
        [':oid' => $order_id]
    );
}

/**
 * Get orders for a specific customer.
 *
 * @param  int   $customer_id
 * @return array
 */
function get_orders_by_customer(int $customer_id): array
{
    return fetch_all(
        "SELECT * FROM hri_order WHERE order_customer_id = :cid ORDER BY order_created_at DESC",
        [':cid' => $customer_id]
    );
}

/**
 * Update order status.
 *
 * @param  int    $order_id    Order to update
 * @param  string $new_status  New status value
 * @return void
 */
function update_order_status(int $order_id, string $new_status): void
{
    $sql = "UPDATE hri_order SET order_status = :status";
    $params = [':status' => $new_status, ':id' => $order_id];

    // If marking as delivered, set timestamp
    if ($new_status === ORDER_STATUS_DELIVERED) {
        $sql .= ", order_delivered_at = NOW()";
    }

    $sql .= " WHERE order_id = :id";
    execute_query($sql, $params);
}
