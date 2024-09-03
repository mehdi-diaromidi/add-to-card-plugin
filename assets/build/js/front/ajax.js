jQuery(document).ready(function ($) {
    $('.register-order').click(function (e) {
        e.preventDefault();
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
                    cart_item_count: cartItemCount
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
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                    }
                },

            });
        }
    })
})