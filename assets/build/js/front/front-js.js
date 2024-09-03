jQuery(document).ready(function ($) {
    // بارگذاری داده‌ها از localStorage در شروع
    let cartData = JSON.parse(localStorage.getItem('cart_data')) || [];

    // تنظیم مقادیر اولیه موجودی‌ها در DOM
    function updateProductQuantity(productId, quantity) {
        $(`.atc-quantity-roller__quantity[data-atc-product-id="${productId}"]`).text(quantity);
    }

    function updateItemCount(count) {
        localStorage.setItem('cart_item_count', count);
        $('.atc-item-count').text(count);
    }

    function renderCart() {
        let cartContainer = $('.cart-container'); // فرض بر این است که یک div با کلاس cart-container برای نمایش سبد خرید وجود دارد
        cartContainer.empty();

        cartData.forEach(item => {
            let productElement = $(`.atc-quantity-roller__quantity[data-atc-product-id="${item.product_id}"]`);

            // استخراج اطلاعات از DOM
            let productTitle = productElement.closest('.elementor-widget-wrap').find('.product_title .elementor-widget-container').text().trim();
            let productPrice = productElement.closest('.elementor-widget-wrap').find('.product_special_price .elementor-widget-container').text().trim() || productElement.closest('.elementor-widget-wrap').find('.product_price_normal .elementor-widget-container').text().trim();
            // let productImageElement = productElement.closest('.elementor-widget-wrap').find('.product_picture img');
            // let productImageSrc = productImageElement.attr('src');

            // if (!productImageSrc) {
            //     console.error("تصویر محصول پیدا نشد!", item.product_id);
            //     productImageSrc = 'مسیر پیش‌فرض تصویر'; // مسیر پیش‌فرض تصویر را برای محصولات بدون تصویر قرار دهید
            // }

            let productHtml = `
                <div class="cart-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="flex: 30%;"><img src="" style="width: 100%; border-radius: 8px;"></div>
                    <div style="flex: 20%;">${productTitle}</div>
                    <div style="flex: 20%;">${productPrice}</div>
                    <div style="flex: 30%;" data-product-id="${item.product_id}">
                        <button class="atc-quantity-roller__button atc-quantity-roller__button--increase" data-type="increase" data-atc-product-id="${item.product_id}">+</button>
                        <span class="atc-quantity-roller__quantity" contenteditable="true" inputmode="numeric" data-atc-product-id="${item.product_id}">${item.currentQuantity}</span>
                        <button class="atc-quantity-roller__button atc-quantity-roller__button--decrease" data-type="decrease" data-atc-product-id="${item.product_id}">-</button>
                    </div>
                </div>`;

            cartContainer.append(productHtml);
        });
    }

    function updateQuantity(productId, action) {
        let $quantityField = $(`.atc-quantity-roller__quantity[data-atc-product-id="${productId}"]`);
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
                product_quantity: currentQuantity
            });
        }

        // ذخیره‌سازی در localStorage
        localStorage.setItem('cart_data', JSON.stringify(cartData));

        // به‌روزرسانی تعداد آیتم‌ها و رندر مجدد سبد خرید
        updateItemCount(cartData.length);
        renderCart();
    }

    // تنظیم مقادیر اولیه موجودی‌ها در DOM
    $('.atc-quantity-roller__quantity').each(function () {
        let productId = $(this).data('atc-product-id');
        let productData = cartData.find(item => item.product_id === productId);
        let quantity = productData ? productData.currentQuantity : 0;
        updateProductQuantity(productId, quantity);
    });

    // فراخوانی به‌روزرسانی تعداد آیتم‌ها و رندر سبد خرید در ابتدای بارگذاری
    updateItemCount(cartData.length);
    renderCart();

    // مدیریت کلیک روی دکمه‌ها
    $(document).on('click', '.atc-quantity-roller__button', function () {
        let $button = $(this);
        let productId = $button.data('atc-product-id');
        let action = $button.data('type');

        // به‌روزرسانی مقدار موجودی بر اساس عمل مورد نظر (افزایش/کاهش)
        updateQuantity(productId, action);
    });
});