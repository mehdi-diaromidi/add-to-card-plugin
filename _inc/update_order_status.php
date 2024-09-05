<?php

function update_order_status()
{
    if (
        !isset($_POST['order_id'], $_POST['order_status'], $_POST['_wpnonce']) ||
        !wp_verify_nonce($_POST['_wpnonce'], 'update_order_status_nonce')
    ) {
        wp_send_json_error(['message' => 'Invalid request.']);
    }

    $order_id = intval($_POST['order_id']);
    $status = sanitize_text_field($_POST['order_status']);

    if (in_array($status, ['waiting', 'registered', 'completed', 'canceled'])) {
        update_post_meta($order_id, 'order_status', $status);
        wp_send_json_success();
    } else {
        wp_send_json_error(['message' => 'Invalid status.']);
    }
}

add_action('wp_ajax_update_order_status', 'update_order_status');
add_action('wp_ajax_nopriv_update_order_status', 'update_order_status');
