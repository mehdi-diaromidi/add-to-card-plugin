jQuery(document).ready(function ($) {
    // Get cart data from localStorage or initialize as an empty array
    // let cartData = JSON.parse(localStorage.getItem('cart_data')) || []; 
    $('#customer_cart').submit(function (e) {
        e.preventDefault();

        // Get form input values for customer details and table number
        let customerName = $('#form-field-customer_name').val();
        let customerPhone = $('#form-field-customer_phone').val();
        let tableNumber = $('#form-field-table_number').val();
        let orderCustomerDetails = $('#form-field-order_customer_details').val();

        // Retrieve cart data and item count from localStorage
        let cartData = JSON.parse(localStorage.getItem('cart_data')) || [];
        let cartItemCount = JSON.parse(localStorage.getItem('cart_item_count')) || [];

        // Check if cart data, item count, customer name, and table number are valid
        if (cartData != [] && cartItemCount != [] && customerName != '' && tableNumber != '') {
            $.ajax({
                // Send AJAX request to register the order
                url: atc_plugin_ajax_order.atc_ajaxurl,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'wp_atc_register_order', // Specify action for the AJAX request
                    _nonce: atc_plugin_ajax_order._atc_nonce, // Send security nonce
                    cart_data: JSON.stringify(cartData), // Pass cart data
                    cart_item_count: cartItemCount, // Pass cart item count
                    order_customer_name: customerName, // Pass customer name
                    order_customer_phone: customerPhone, // Pass customer phone
                    order_table: tableNumber, // Pass table number
                    order_customer_details: orderCustomerDetails // Pass additional customer details
                },
                success: function (response) {
                    // Handle the response and show success message with SweetAlert
                    if (response.type == 'success') {
                        Swal.fire({
                            icon: "success",
                            title: response.message, // Show response message
                            text: response.order_title, // Show order title
                            showCloseButton: true,
                            showConfirmButton: false,
                        });
                        localStorage.removeItem('cart_data');
                        localStorage.removeItem('cart_item_count');
                    } else {
                        $('.elementor-message elementor-message-success elementor-message-svg').hide();

                        Swal.fire({
                            icon: "error",
                            title: 'خطا در ثبت سفارش!',
                            text: response.message,
                            showCloseButton: true,
                            showConfirmButton: false,
                        });
                    }
                }
            });
        }
    })
})