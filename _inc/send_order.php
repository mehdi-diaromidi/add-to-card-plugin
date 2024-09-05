<?php

include_once ADD_TO_CART_PLUGIN_DIR . '/class/Message.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/SecurityValidator.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/OrderHandler.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/PopupRenderer.php';

function wp_atc_register_order(): void
{
    if (!SecurityValidator::wp_atc_nonce_validator($_POST['_nonce'])) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }
    SecurityValidator::wp_atc_integer_validator($_POST['table_number']);
    SecurityValidator::wp_atc_integer_validator($_POST['cart_item_count']);
    SecurityValidator::wp_atc_string_validator($_POST['customer_name']);
    SecurityValidator::wp_atc_phonenumber_validator($_POST['customer_phone']);
    SecurityValidator::wp_atc_textarea_validator($_POST['order_customer_details']);

    $cart_data = json_decode(stripslashes($_POST['cart_data']), true);
    $cart_item_count = $_POST['cart_item_count'];


    if (empty($cart_data) || $cart_item_count <= 0) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }

    // ساخت عنوان سفارش
    $order_number = wp_count_posts('atc_menu_orders')->publish + 1;
    $order_title = "سفارش شماره $order_number";

    // ساخت پست سفارش
    $order_handler = new OrderHandler();
    $order_id = $order_handler->create_order_post($order_title);

    if (!$order_id) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }

    $order_total_price = 0;
    $order_items = [];

    foreach ($cart_data as $item) {
        $product_id = $item['product_id'];
        $product_quantity = isset($item['currentQuantity']) && $item['currentQuantity'] !== '' ? $item['currentQuantity'] : 1;
        if (!SecurityValidator::wp_atc_integer_validator($product_id) || !SecurityValidator::wp_atc_integer_validator($product_quantity)) {
            Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
            return;
        }

        $product_details = $order_handler->get_product_details($product_id);

        if ($product_details['product_in_stock'] === 'ناموجود') {
            Message::wp_atc_send_json_message('error', 'محصول ناموجود است!', 403);
            return;
        }

        $price = $product_details['product_price_special'] !== 'ندارد'
            ? $product_details['product_price_special']
            : $product_details['product_price_normal'];

        $order_total_price += $price * $product_quantity;

        // اضافه کردن جزئیات محصول به آرایه order_items
        $order_items[] = [
            'product_name' => $product_details['product_name'],
            'product_category' => $product_details['product_category'],
            'products_count' => $product_quantity,
        ];
    }

    // ذخیره اطلاعات متا در پست سفارش
    $order_handler->update_order_meta('atc_order_info', [
        'customer_name' => sanitize_text_field($_POST['customer_name']),
        'customer_phone' => sanitize_text_field($_POST['customer_phone']),
        'table_number' => intval($_POST['table_number']),
        'items_count' => $cart_item_count,
        'total_price' => $order_total_price,
    ]);

    $order_handler->update_order_meta('atc_order_items', $order_items);
    $order_handler->update_order_meta('order_status', 'registered'); // مقدار پیش‌فرض

    // ارسال پاسخ به ایجکس
    Message::wp_atc_send_json_message('success', 'سفارش با موفقیت ثبت شد!', 200);
}

add_action('wp_ajax_wp_atc_register_order', 'wp_atc_register_order');
add_action('wp_ajax_nopriv_wp_atc_register_order', 'wp_atc_register_order');
