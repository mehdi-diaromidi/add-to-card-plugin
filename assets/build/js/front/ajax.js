jQuery(document).ready(function ($) {
    $('#customer_cart').submit(function (e) {
        e.preventDefault();

        let customerName = $('#form-field-customer_name').val();
        let customerPhone = $('#form-field-customer_phone').val();
        let tableNumber = $('#form-field-table_number').val();
        let orderCustomerDetails = $('#form-field-order_customer_details').val();

        let cartData = JSON.parse(localStorage.getItem('cart_data')) || [];
        let cartItemCount = JSON.parse(localStorage.getItem('cart_item_count')) || [];

        if (cartData != [] && cartItemCount != []) {
            $.ajax({
                url: atc_plugin_ajax.atc_ajaxurl,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'wp_atc_register_order',
                    _nonce: atc_plugin_ajax._atc_nonce,
                    cart_data: JSON.stringify(cartData),
                    cart_item_count: cartItemCount,
                    customer_name: customerName,
                    customer_phone: customerPhone,
                    table_number: tableNumber,
                    order_customer_details: orderCustomerDetails
                },
                success: function (response) {
                    if (response) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Swal.fire({
                            icon: "success",
                            title: response.message
                        });
                    }
                },

            });
        }
    })
})

// jQuery(document).ready(function ($) {
//     $(document).on('submit', 'form', function (e) {
//         e.preventDefault();

//         let $form = $(this);
//         let formData = $form.serialize();

//         $.ajax({
//             url: atc_plugin_ajax.atc_ajaxurl,
//             type: 'POST',
//             dataType: 'JSON',
//             data: {
//                 action: 'update_order_status',
//                 _nonce: atc_plugin_ajax._atc_nonce,
//                 'formData': formData
//             },
//             success: function (response) {
//                 if (response.success) {
//                     alert('Order status updated successfully!');
//                     location.reload(); // Reload the page to reflect changes
//                 } else {
//                     alert('Error updating order status.');
//                 }
//             },
//             error: function () {
//                 alert('An error occurred.');
//             }
//         });
//     });
// });