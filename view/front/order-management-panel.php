<?php

function display_orders_page()
{
    $orders = get_posts([
        'post_type' => 'atc_menu_orders',
        'post_status' => 'publish',
        'numberposts' => -1,
    ]);

    echo '<div style="background-color: #f3eadd; padding: 20px; color: #04256b;">';
    echo '<h1 style="color: #c6ad8b;">Order Management Panel</h1>';

    foreach ($orders as $order) {
        $order_id = $order->ID;
        $order_title = get_the_title($order_id);
        $order_meta = get_post_meta($order_id);

        // Extracting the order details
        $order_info = isset($order_meta['atc_order_info'][0]) ? maybe_unserialize($order_meta['atc_order_info'][0]) : [];
        $order_items = isset($order_meta['atc_order_items'][0]) ? maybe_unserialize($order_meta['atc_order_items'][0]) : [];
        $order_status = isset($order_meta['order_status'][0]) ? $order_meta['order_status'][0] : 'registered';

        echo '<div style="border: 1px solid #04256b; margin-bottom: 20px; padding: 10px; background-color: #ffffff;">';
        echo '<h2>' . esc_html($order_title) . '</h2>';

        echo '<p><strong>Order ID:</strong> ' . esc_html($order_id) . '</p>';

        echo '<p><strong>Customer Name:</strong> ' . esc_html($order_info['customer_name'] ?? 'N/A') . '</p>';
        echo '<p><strong>Customer Phone:</strong> ' . esc_html($order_info['customer_phone'] ?? 'N/A') . '</p>';
        echo '<p><strong>Table Number:</strong> ' . esc_html($order_info['table_number'] ?? 'N/A') . '</p>';
        echo '<p><strong>Items Count:</strong> ' . esc_html($order_info['items_count'] ?? 'N/A') . '</p>';
        echo '<p><strong>Total Price:</strong> ' . esc_html($order_info['total_price'] ?? 'N/A') . '</p>';

        echo '<h3>Order Items:</h3>';
        echo '<ul>';
        foreach ($order_items as $item) {
            echo '<li>';
            echo '<strong>Product Name:</strong> ' . esc_html($item['product_name']) . '<br>';
            echo '<strong>Product Category:</strong> ' . esc_html($item['product_category']) . '<br>';
            echo '<strong>Products Count:</strong> ' . esc_html($item['products_count_card']) . '<br>';
            echo '</li>';
        }
        echo '</ul>';

        // Status Change Form
        $statuses = ['waiting' => 'Waiting', 'registered' => 'Registered', 'completed' => 'Completed', 'canceled' => 'Canceled'];
        echo '<form method="post" style="margin-top: 10px;">';
        wp_nonce_field('update_order_status_nonce');
        echo '<input type="hidden" name="order_id" value="' . esc_attr($order_id) . '">';
        echo '<select name="order_status">';
        foreach ($statuses as $key => $label) {
            $selected = ($key === $order_status) ? 'selected' : '';
            echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<input type="submit" value="Update Status" style="background-color: #c6ad8b; color: #ffffff; border: none; padding: 5px 10px; cursor: pointer;">';
        echo '</form>';

        echo '</div>';
    }

    echo '</div>';
}

add_shortcode('order_management_panel', 'display_orders_page');
