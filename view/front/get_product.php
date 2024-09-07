<?php

include_once ADD_TO_CART_PLUGIN_DIR . '/class/Message.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/SecurityValidator.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/OrderHandler.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/PopupRenderer.php';

function wp_atc_get_products(): void
{
    // Validate nonce for security
    if (!SecurityValidator::wp_atc_nonce_validator($_POST['_nonce'])) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }

    // Retrieve cart data from POST request
    $cart_data = json_decode(stripslashes($_POST['cart_data']), true);

    // Check if cart data is empty
    if (empty($cart_data)) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
        return;
    }

    // Initialize order handler and variables for total price and HTML content
    $product_handler = new OrderHandler();
    $product_price_with_quantity = 0;
    $product_html = '';

    // Loop through cart items
    foreach ($cart_data as $item) {
        $product_id = $item['product_id'];
        $product_quantity = isset($item['currentQuantity']) && $item['currentQuantity'] !== '' ? $item['currentQuantity'] : 1;

        // Validate product ID and quantity
        if (!SecurityValidator::wp_atc_integer_validator($product_id) || !SecurityValidator::wp_atc_integer_validator($product_quantity)) {
            Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
            return;
        }

        // Get product details
        $product_details = $product_handler->get_product_details($product_id);
        $product_name = $product_details['product_name'];

        // Determine price (special price or normal price)
        $price = $product_details['product_price_special'] !== 'ندارد'
            ? $product_details['product_price_special']
            : $product_details['product_price_normal'];

        // Calculate total price based on quantity
        $product_price_with_quantity += $price * $product_quantity;

        // Get product image if available
        $product_src_picture = '';
        if ($product_details['product_thumbnail']) {
            $product_src_picture = wp_get_attachment_image_url($product_details['product_thumbnail'], 'full'); // 'full' defines the image size
        }

        // Build HTML for each product
        $product_html .= '
        <div class="atc-product-card" data-atc-product-id="' . esc_attr($product_id) . '">
            <div class="atc-product-column atc-product-image-col">
                <img class="atc-product-image" src="' . esc_url($product_src_picture) . '" alt="' . esc_attr($product_name) . '">
            </div>
            <div class="atc-product-column atc-product-info-col">
                <div class="atc-product-details">
                    <h2 class="atc-product-name">' . esc_html($product_name) . '</h2>
                </div>
                <div class="atc-product-price-info">
                    <p class="atc-product-price">' . esc_html($product_price_with_quantity) . '</p>
                </div>
            </div>
            <div class="atc-product-column atc-product-actions-col">
                <div class="atc-product-add-to-cart-buttons">
                    <div class="atc-loop-increase-decrease">
                        <span class="atc-quantity-updator-buttons">
                            <button class="atc-quantity-updator-button atc-quantity-updator-button-increase" data-type="increase" data-atc-product-id="' . esc_attr($product_id) . '">+</button>
                            <span class="atc-quantity-display-quantity" inputmode="numeric" data-atc-product-id="' . esc_attr($product_id) . '">' . esc_html($product_quantity) . '</span>
                            <button class="atc-quantity-updator-button atc-quantity-updator-button-decrease" data-type="decrease" data-atc-product-id="' . esc_attr($product_id) . '">-</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>';
    }

    // Send error response if no products are found
    if (empty($product_html)) {
        wp_send_json_error(['message' => 'هیچ محصولی یافت نشد!'], 404);
    } else {
        // Send success response with generated HTML
        wp_send_json_success(['html' => $product_html]);
    }
}

// Register AJAX actions for authenticated and non-authenticated users
add_action('wp_ajax_wp_atc_get_products', 'wp_atc_get_products');
add_action('wp_ajax_nopriv_wp_atc_get_products', 'wp_atc_get_products');
