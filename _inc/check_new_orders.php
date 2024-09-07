<?php

function wp_atc_check_new_orders_function()
{
    $new_orders = get_transient('new_orders');

    if ($new_orders) {
        // $order_titles = $new_orders['order_title'];
        // $order_ids = $new_orders['order_id'];

        foreach ($new_orders as $order) {
            $order_titles = $order['order_title'];
            $order_ids = $order['order_id'];
        }

        wp_send_json_success([
            'new_orders' => true,
            'order_titles' => $order_titles,
            'order_ids' => $order_ids,
        ]);
    } else {
        wp_send_json_success([
            'new_orders' => false,
        ]);
    }
}
add_action('wp_ajax_check_new_orders', 'wp_atc_check_new_orders_function');


function wp_atc_delete_transient()
{
    delete_transient('new_orders');
}
add_action('wp_ajax_delete_transient', 'wp_atc_delete_transient');
