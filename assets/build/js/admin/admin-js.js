jQuery(document).ready(function ($) {
    // Function to check for new orders via AJAX
    function checkNewOrders() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'check_new_orders'
            },
            success: function (response) {
                if (response.success && response.data.new_orders) {
                    let orderTitles = response.data.order_titles;
                    let orderIds = response.data.order_ids;

                    let buttonText, buttonUrl;

                    // Check if there is only one order or multiple orders
                    if (orderTitles.length === 1) {
                        buttonText = 'مشاهده سفارش'; // Text for single order
                        buttonUrl = `https://blue-menu.ir/wp-admin/post.php?post=${orderIds[0]}&action=edit`;
                    } else {
                        buttonText = 'مشاهده سفارشات'; // Text for multiple orders
                        buttonUrl = 'https://blue-menu.ir/wp-admin/edit.php?post_type=menu_orders';
                    }

                    let ordersText = orderTitles.join(' و ');

                    // Show SweetAlert notification with new order details
                    Swal.fire({
                        icon: 'warning',
                        title: 'شما سفارش جدید دارید!',
                        text: 'شماره سفارش: ' + ordersText,
                        footer: `<button class="swal2-confirm swal2-styled"><a style="text-decoration:none; color:white;" href="${buttonUrl}" id="wp-atc-view-orders-link">${buttonText}</a></button>`,
                        showConfirmButton: false,
                        backdrop: `
                            rgba(0,0,123,0.4)
                        `
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If the admin clicks the close button, nothing happens
                        }
                    });

                    // Add click event to the link in SweetAlert footer
                    $('#wp-atc-view-orders-link').on('click', function (e) {
                        e.preventDefault();
                        window.location.href = buttonUrl;

                        // After navigating to the link, clear new orders via AJAX
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'clear_new_orders',
                                clear_orders: true
                            },
                            success: function (clear_response) {
                                if (clear_response.success) {
                                    console.log('سفارشات جدید پاک شدند.'); // Log message after clearing orders
                                }
                            }
                        });
                    });
                }
            }
        });
    }

    // Send request every 10 seconds
    setInterval(checkNewOrders, 10000);
});