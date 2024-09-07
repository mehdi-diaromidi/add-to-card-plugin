jQuery(document).ready(function ($) {
    // Retrieve cart data from localStorage or initialize as an empty array
    let cartData = JSON.parse(localStorage.getItem('cart_data')) || [];

    // Function to update the displayed quantity of a product
    function updateProductQuantity(productId, quantity) {
        let $quantityField = $(`.atc-quantity-display-quantity[data-atc-product-id="${productId}"]`);
        let $cartWrapper = $(`.atc-product-card[data-atc-product-id="${productId}"]`);

        // Update the quantity displayed
        $quantityField.text(quantity);

        // Hide the product card if the quantity is 0 or less
        if (quantity <= 0) {
            $cartWrapper.fadeOut(300);
            console.log($cartWrapper); // Log the product wrapper to the console for debugging
        }
    }

    // Function to update the total item count in the cart
    function updateItemCount(count) {
        // Save item count in localStorage
        localStorage.setItem('cart_item_count', count);
        // Update the item count in the UI (commented out)
        // $('.atc-item-count').text(count);
    }

    // Update the total item count on page load
    updateItemCount(cartData.reduce((acc, item) => acc + item.currentQuantity, 0));

    // Update the quantity display for each product
    $('.atc-quantity-display-quantity').each(function () {
        let productId = $(this).data('atc-product-id');
        let productData = cartData.find(item => item.product_id === productId);
        let quantity = productData ? productData.currentQuantity : 0;
        updateProductQuantity(productId, quantity);
    });

    // Handle click on increase/decrease buttons
    $(document).on('click', '.atc-quantity-updator-button', function () {
        let productId = $(this).data('atc-product-id');
        let action = $(this).data('type');
        // Update the quantity based on the clicked button
        updateQuantity(productId, action);
    });

    // Function to increase or decrease product quantity
    function updateQuantity(productId, action) {
        let $quantityField = $(`.atc-quantity-display-quantity[data-atc-product-id="${productId}"]`);
        let currentQuantity = parseInt($quantityField.text(), 10);

        // Ensure current quantity is a valid number
        if (isNaN(currentQuantity)) {
            currentQuantity = 0;
        }

        // Increase or decrease quantity based on action
        if (action === 'increase') {
            currentQuantity += 1;
        } else if (action === 'decrease' && currentQuantity > 0) {
            currentQuantity -= 1;
        }

        // Update product quantity display
        updateProductQuantity(productId, currentQuantity);

        // Update cart data in localStorage
        let existingProductIndex = cartData.findIndex(item => item.product_id === productId);
        if (existingProductIndex !== -1) {
            if (currentQuantity === 0) {
                // Remove product from cartData if quantity is zero
                cartData.splice(existingProductIndex, 1);
            } else {
                // Update the quantity if the product exists in the cart
                cartData[existingProductIndex].currentQuantity = currentQuantity;
            }
        } else if (currentQuantity > 0) {
            // Add the product to cartData if it doesn't already exist
            cartData.push({
                product_id: productId,
                currentQuantity: currentQuantity
            });
        }

        // Save updated cart data to localStorage
        localStorage.setItem('cart_data', JSON.stringify(cartData));

        // Update the total item count in the cart
        updateItemCount(cartData.reduce((acc, item) => acc + item.currentQuantity, 0));
    }
});