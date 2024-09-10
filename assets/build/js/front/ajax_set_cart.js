jQuery(document).ready(function ($) {
    // Retrieve cart data from localStorage or initialize as an empty array
    let cartData = JSON.parse(localStorage.getItem('cart_data')) || [];

    // Check if cart data is not empty
    if (cartData != []) {
        $.ajax({
            // Send AJAX request to get products based on cart data
            url: atc_plugin_ajax_set_cart.atc_ajaxurl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'wp_atc_get_products', // Specify action for AJAX request
                _nonce: atc_plugin_ajax_set_cart._atc_nonce, // Send security nonce
                cart_data: JSON.stringify(cartData) // Pass cart data as JSON
            },
            beforeSend: function () {
                pulse();
            },
            success: function (response) {
                // If the request is successful, insert the received HTML into the target element
                if (response.success) {
                    // Insert the received HTML into the '#atc-product-list' element
                    $('#atc-product-list').html(response.data.html);
                } else {
                    // Show error message using SweetAlert
                    Swal.fire({
                        icon: "error",
                        title: response.message, // Show error message from response
                        showCloseButton: true,
                        showConfirmButton: false,
                    });
                }
                $('#loading-message').hide();
            }

        });
    } else {
        $('#atc-product-list').html('<p class="alert-empty-cart">کاربر گرامی سبد خرید شما خالی است!</p>');

    }

    function pulse() {
        $('#loading-message').animate({
            opacity: 0.5
        }, 300, function () {
            $(this).animate({
                opacity: 1
            }, 300, pulse);
        });
    }

})