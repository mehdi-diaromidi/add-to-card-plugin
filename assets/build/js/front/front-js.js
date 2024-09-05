jQuery(document).ready(function ($) {
    // بارگذاری داده‌ها از localStorage در شروع
    let cartData = JSON.parse(localStorage.getItem('cart_data')) || [];

    // تنظیم مقادیر اولیه موجودی‌ها در DOM
    function updateProductQuantity(productId, quantity) {
        let $quantityField = $(`.atc-quantity-roller-quantity[data-atc-product-id="${productId}"]`);
        let $decreaseButton = $(`.atc-quantity-roller-button-decrease[data-atc-product-id="${productId}"]`);

        // به‌روزرسانی مقدار موجودی
        $quantityField.text(quantity);

        // استفاده از انیمیشن‌های jQuery برای محو و نمایش عناصر
        if (quantity > 0) {
            $quantityField.stop(true).fadeIn(300);
            $decreaseButton.stop(true).fadeIn(300);
        } else {
            $quantityField.stop(true).fadeOut(300);
            $decreaseButton.stop(true).fadeOut(300);
        }
    }

    function updateItemCount(count) {
        localStorage.setItem('cart_item_count', count);
        $('.atc-item-count').text(count);
    }

    // فراخوانی به‌روزرسانی تعداد آیتم‌ها در ابتدای بارگذاری
    updateItemCount(cartData.reduce((acc, item) => acc + item.currentQuantity, 0));

    // تنظیم مقادیر اولیه موجودی‌ها در DOM
    $('.atc-quantity-roller-quantity').each(function () {
        let productId = $(this).data('atc-product-id');
        let productData = cartData.find(item => item.product_id === productId);
        let quantity = productData ? productData.currentQuantity : 0;
        updateProductQuantity(productId, quantity);
    });

    // مدیریت کلیک روی دکمه‌ها
    $(document).on('click', '.atc-quantity-roller-button', function () {
        let $button = $(this);
        let productId = $button.data('atc-product-id');
        let action = $button.data('type');

        // به‌روزرسانی مقدار موجودی بر اساس عمل مورد نظر (افزایش/کاهش)
        updateQuantity(productId, action);
    });

    function updateQuantity(productId, action) {
        let $quantityField = $(`.atc-quantity-roller-quantity[data-atc-product-id="${productId}"]`);
        let currentQuantity = parseInt($quantityField.text(), 10); // تبدیل به عدد صحیح

        if (isNaN(currentQuantity)) {
            currentQuantity = 0; // اطمینان از اینکه currentQuantity عددی معتبر است
        }

        if (action === 'increase') {
            currentQuantity += 1;
        } else if (action === 'decrease' && currentQuantity > 0) {
            currentQuantity -= 1;
        }

        // به‌روزرسانی مقدار در DOM
        updateProductQuantity(productId, currentQuantity);

        // به‌روزرسانی داده‌ها در localStorage
        let existingProductIndex = cartData.findIndex(item => item.product_id === productId);
        if (existingProductIndex !== -1) {
            if (currentQuantity === 0) {
                // حذف محصول از cartData اگر مقدار موجودی آن صفر است
                cartData.splice(existingProductIndex, 1);
            } else {
                cartData[existingProductIndex].currentQuantity = currentQuantity;
            }
        } else if (currentQuantity > 0) {
            cartData.push({
                product_id: productId,
                currentQuantity: currentQuantity // تغییر به currentQuantity برای سازگاری با داده‌های ذخیره‌شده
            });
        }

        // ذخیره‌سازی در localStorage
        localStorage.setItem('cart_data', JSON.stringify(cartData));

        // به‌روزرسانی تعداد آیتم‌ها
        updateItemCount(cartData.reduce((acc, item) => acc + item.currentQuantity, 0));
    }
});