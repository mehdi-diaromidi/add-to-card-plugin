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

    $cart_data = json_decode(stripslashes($_POST['cart_data']), true);
    $cart_item_count = (int) $_POST['cart_item_count'];

    if (empty($cart_data) || $cart_item_count <= 0) {
        Message::wp_atc_send_json_message('error', 'سبد خرید خالی است!', 400);
        return;
    }

    // ساخت عنوان سفارش
    $order_number = wp_count_posts('menu_orders')->publish + 1;
    $order_title = "سفارش شماره $order_number";

    // ساخت پست سفارش
    $order_handler = new OrderHandler();
    $order_id = $order_handler->create_order_post($order_title);

    $order_total_price = 0;
    $order_items = [];

    foreach ($cart_data as $index => $item) {
        $product_id = $item['product_id'];
        $product_quantity = isset($item['currentQuantity']) && $item['currentQuantity'] !== '' ? (int)$item['currentQuantity'] : 1;

        $product_details = $order_handler->get_product_details($product_id);

        if ($product_details['product_in_stock'] === 'ناموجود') {
            Message::wp_atc_send_json_message('error', 'محصول ناموجود است!', 400);
            return;
        }

        $price = $product_details['product_price_special'] !== 'ندارد'
            ? $product_details['product_price_special']
            : $product_details['product_price_normal'];

        $order_total_price += $price * $product_quantity;

        // اضافه کردن جزئیات محصول به آرایه order_items
        $order_items["item-$index"] = [
            'product' => (string)$product_id,
            'product_quantity' => (string)$product_quantity,
        ];
    }

    // سریالایز کردن و ذخیره اطلاعات مربوط به محصولات در متا‌دیتاها
    $order_handler->update_order_meta('order_details_items', $order_items);

    // ذخیره اطلاعات مربوط به تعداد کل آیتم‌ها و قیمت نهایی سفارش
    $order_handler->update_order_meta('order_details_quantity', $cart_item_count);
    $order_handler->update_order_meta('order_total_price', $order_total_price);

    // ارسال پاسخ به ایجکس
    Message::wp_atc_send_json_message('success', 'سفارش با موفقیت ثبت شد!', 200);
}

add_action('wp_ajax_wp_atc_register_order', 'wp_atc_register_order');
add_action('wp_ajax_nopriv_wp_atc_register_order', 'wp_atc_register_order');
