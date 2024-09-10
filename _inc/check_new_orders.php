<?php

// Function to check for new orders stored in the WordPress options table
function wp_atc_check_new_orders_function()
{
    // Retrieve the 'new_orders' option from the database, defaulting to an empty array if not set
    $new_orders = get_option('new_orders', []);

    // If there are new orders, send a JSON response with the order titles and IDs
    if (!empty($new_orders)) {
        $order_titles = array_column($new_orders, 'order_title'); // Extract order titles
        $order_ids = array_column($new_orders, 'order_id'); // Extract order IDs

        wp_send_json_success([
            'new_orders' => true,
            'order_titles' => $order_titles, // Include titles in the response
            'order_ids' => $order_ids, // Include IDs in the response
        ]);
    } else {
        // If no new orders, send a JSON response indicating there are no new orders
        wp_send_json_success([
            'new_orders' => false,
        ]);
    }
}

// Hook the function to an AJAX action for authenticated users
add_action('wp_ajax_check_new_orders', 'wp_atc_check_new_orders_function');
