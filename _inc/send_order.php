<?php

include_once ADD_TO_CART_PLUGIN_DIR . '/class/Message.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/SecurityValidator.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/OrderHandler.php';

function wp_atc_register_order(): void
{
    // Validate nonce for security
    if (!SecurityValidator::wp_atc_nonce_validator($_POST['_nonce'])) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }

    // Decode cart data and get item count from the request
    $cart_data = json_decode(stripslashes($_POST['cart_data']), true);
    $cart_item_count = $_POST['cart_item_count'];

    // If cart data is empty or item count is zero, return an error message
    if (empty($cart_data) || $cart_item_count <= 0) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }

    // Create the order title based on the current number of published orders
    $order_number = wp_count_posts('menu_orders')->publish + 1;
    $order_title = "سفارش شماره $order_number";

    // Create a new order post in 'menu_orders' post type
    $order_handler = new OrderHandler();
    $order_id = $order_handler->create_order_post($order_title);

    // If the order creation fails, return an error message
    if (!$order_id) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }

    $order_price = 0; // Initialize order price
    $order_items = []; // Initialize order items array

    // Iterate through each cart item
    foreach ($cart_data as $index => $item) {
        $product_id = $item['product_id'];
        $product_quantity = isset($item['currentQuantity']) && $item['currentQuantity'] !== '' ? $item['currentQuantity'] : 1;

        // Validate product ID and quantity
        if (!SecurityValidator::wp_atc_integer_validator($product_id) || !SecurityValidator::wp_atc_integer_validator($product_quantity)) {
            Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
            return;
        }

        // Get product details based on product ID
        $product_details = $order_handler->get_product_details($product_id);

        // If product is out of stock, return an error message
        if ($product_details['product_in_stock'] === 'ناموجود') {
            Message::wp_atc_send_json_message('error', 'محصول ' . $product_details['product_name'] . ' ناموجود است! ', 404);
            // return;
        }

        // Calculate price based on normal or special price
        $price = $product_details['product_price_special'] !== 'ندارد'
            ? $product_details['product_price_special']
            : $product_details['product_price_normal'];

        $order_price += $price * $product_quantity; // Update total order price

        // Add product details to the order items array
        $order_items["item-$index"] = [
            'product' => $product_id,
            'product_quantity' => $product_quantity,
        ];

        // Update the quantity of product materials based on the order
        foreach ($product_details['product_materials'] as $item_key => $item_data) {
            $product_material_id = $item_data['product_material_id'];
            $product_material_amount = $item_data['product_material_amount'] * $product_quantity;
            $update_product_material_amount_status = $order_handler->update_product_materials_amount($product_material_id, $product_material_amount);
            if (!$update_product_material_amount_status) {
                Message::wp_atc_send_json_message('error', 'محصول ' . $product_details['product_name'] . ' با تعداد مورد نظر شما ناموجود است ', 404);
            }
        }
    }

    // Validate and retrieve order details from POST request
    $cart_item_count = SecurityValidator::wp_atc_integer_validator($_POST['cart_item_count']);
    $order_customer_name = SecurityValidator::wp_atc_string_validator($_POST['order_customer_name']);
    $order_customer_phone = SecurityValidator::wp_atc_phonenumber_validator($_POST['order_customer_phone']);
    $order_table = SecurityValidator::wp_atc_string_validator($_POST['order_table']);
    $order_customer_details = SecurityValidator::wp_atc_textarea_validator($_POST['order_customer_details']);

    // Save the order meta data with the updated meta field slugs
    $order_handler->update_order_meta('order_customer_name', $order_customer_name);
    $order_handler->update_order_meta('order_customer_phone', $order_customer_phone);
    $order_handler->update_order_meta('order_customer_details', $order_customer_details);
    $order_handler->update_order_meta('order_table', $order_table);
    $order_handler->update_order_meta('order_cart_item_count', $cart_item_count);
    $order_handler->update_order_meta('order_details_items', $order_items);
    $order_handler->update_order_meta('order_price', $order_price);
    $order_handler->update_order_meta('order_status', 'در انتظار تایید'); // Default status

    // If order creation was successful, store the new order information in an option
    if ($order_id) {
        $new_orders = get_option('new_orders', []);
        $new_orders[] = [
            'order_id' => $order_id,
            'order_title' => $order_title
        ];
        update_option('new_orders', $new_orders);
    }


    // Send a success message back to the AJAX request
    Message::wp_atc_send_json_message('success', 'سفارش با موفقیت ثبت شد!', $order_title, 200);
}

// Add AJAX actions for both logged-in and non-logged-in users
add_action('wp_ajax_wp_atc_register_order', 'wp_atc_register_order');
add_action('wp_ajax_nopriv_wp_atc_register_order', 'wp_atc_register_order');
