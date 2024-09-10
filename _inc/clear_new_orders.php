<?php
// Function to clear the 'new_orders' option from the WordPress database
function wp_atc_clear_new_orders()
{
    // Check if the 'clear_orders' parameter is set in the POST request
    if (isset($_POST['clear_orders'])) {
        // Delete the 'new_orders' option from the database
        delete_option('new_orders');

        // Send a successful JSON response
        wp_send_json_success();
    } else {
        // Send an error JSON response if 'clear_orders' is not set
        wp_send_json_error();
    }
}

// Hook the function to an AJAX action for authenticated users
add_action('wp_ajax_clear_new_orders', 'wp_atc_clear_new_orders');
