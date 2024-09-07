jQuery(document).ready(function ($) {
    function checkNewOrders() {
        $.ajax({
            url: ajaxurl, // این متغیر توسط وردپرس به طور پیشفرض در سمت ادمین تعریف شده است
            type: 'POST',
            data: {
                action: 'check_new_orders' // اکشن مربوط به تابع PHP
            },
            success: function (response) {
                if (response.success && response.data.new_orders) {
                    let orderTitles = response.data.order_titles; // دریافت آرایه عنوان‌ها
                    let orderIds = response.data.order_ids; // دریافت آرایه آیدی‌ها
                    // console.log(orderTitles);
                    // console.log(orderIds);

                    let buttonText, buttonUrl;

                    // بررسی تعداد سفارشات و تنظیم متن دکمه و URL
                    // if (orderTitles.length === 1) {
                    buttonText = 'مشاهده سفارش';
                    buttonUrl = `https://localhost/digitalMenu/wp-admin/post.php?post=${orderIds[0]}&action=edit`;
                    // } else {
                    //     buttonText = 'مشاهده سفارشات';
                    //     buttonUrl = 'https://localhost/digitalMenu/wp-admin/edit.php?post_type=menu_orders';
                    // }

                    // ترکیب عنوان‌های سفارشات به صورت یک رشته
                    // let ordersText = orderTitles.join(' و ');

                    Swal.fire({
                        icon: 'success',
                        title: 'شما سفارش جدید دارید!',
                        text: 'شماره سفارش: ' + orderTitles,
                        confirmButtonText: buttonText,
                        footer: `<a href="${buttonUrl}">${buttonText}</a>`
                    });
                }
            }

        });
    }

    // تنظیم یک بازه زمانی برای ارسال درخواست به صورت دوره‌ای
    setInterval(checkNewOrders, 10000); // هر 10 ثانیه یک بار درخواست ارسال می‌شود
});