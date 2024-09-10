jQuery(document).ready(function ($) {
    // Retrieve cart data from localStorage or initialize as an empty array
    let cartData = JSON.parse(localStorage.getItem('cart_data')) || [];

    // Function to update the displayed quantity of a product
    function updateProductQuantity(productId, quantity) {
        let $quantityField = $(`.atc-quantity-display-quantity[data-atc-product-id="${productId}"]`);
        let $addButton = $(`.atc-product-add-to-order[data-atc-product-id="${productId}"]`);
        let $quantityWrapper = $(`.atc-loop-increase-decrease[data-product-id="${productId}"]`);

        // Update the quantity displayed
        $quantityField.text(quantity);

        // Show/hide buttons based on the quantity
        if (quantity > 0) {
            $addButton.hide(); // Hide "Add to Order" button
            $quantityWrapper.fadeIn(300); // Show increase/decrease buttons
        } else {
            $quantityWrapper.hide(); // Hide increase/decrease buttons
            $addButton.fadeIn(300); // Show "Add to Order" button
        }
    }

    // Function to update the total item count in the cart
    function updateItemCount(count) {
        // Save item count in localStorage
        localStorage.setItem('cart_item_count', count);
        $('.count-item .elementor-button-text').text(count);
        $('.count-item').addClass('smooth-shake'); // Add shake effect

        // Remove shake class after animation
        setTimeout(function () {
            $('.count-item').removeClass('smooth-shake');
        }, 500); // Time should match the duration set in CSS

        // Show or hide the order registration container based on item count
        let $registerOrderContainer = $('.register_order_container');
        if (count > 0) {
            $registerOrderContainer.fadeIn(300); // Show if there are items
        } else {
            $registerOrderContainer.fadeOut(300); // Hide if no items
        }
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

    // Handle click on "Add to Order" button
    $(document).on('click', '.atc-product-add-to-order', function () {
        let productId = $(this).data('atc-product-id');
        // Increase the quantity when the "Add to Order" button is clicked
        updateQuantity(productId, 'increase');
    });

    // Handle click on increase/decrease buttons
    $(document).on('click', '.atc-quantity-updator-button', function () {
        let productId = $(this).data('atc-product-id');
        let action = $(this).data('type');
        // Update the quantity based on the clicked button
        updateQuantity(productId, action);
    });

function updateQuantity(productId, action) {
    let $quantityField = $(`.atc-quantity-display-quantity[data-atc-product-id="${productId}"]`).first();
    console.log($quantityField);

    let currentQuantity = parseInt($quantityField.text().replace(/\D/g, ''), 10); // Remove non-digit characters

    // Log the raw value and cleaned quantity
    console.log(`Raw value for product ${productId}:`, $quantityField.text());
    console.log(`Cleaned quantity for product ${productId}:`, currentQuantity);

    // Ensure current quantity is a valid number
    if (isNaN(currentQuantity)) {
        console.log(`Invalid quantity detected for product ${productId}. Setting to 0.`);
        currentQuantity = 0;
    }

    // Log the current quantity before modification
    console.log(`Current quantity before action for product ${productId}:`, currentQuantity);

    // Increase or decrease quantity based on action
    if (action === 'increase') {
        currentQuantity += 1;
    } else if (action === 'decrease' && currentQuantity > 0) {
        currentQuantity -= 1;
    }

    // Log the new quantity after modification
    console.log(`New quantity after action for product ${productId}:`, currentQuantity);

    // Update product quantity display
    updateProductQuantity(productId, currentQuantity);

    // Update cart data in localStorage
    let existingProductIndex = cartData.findIndex(item => item.product_id === productId);
    if (existingProductIndex !== -1) {
        if (currentQuantity === 0) {
            // Remove product from cartData if quantity is zero
            console.log(`Removing product ${productId} from cartData.`);
            cartData.splice(existingProductIndex, 1);
        } else {
            // Update the quantity if the product exists in the cart
            console.log(`Updating quantity for product ${productId} in cartData.`);
            cartData[existingProductIndex].currentQuantity = currentQuantity;
        }
    } else if (currentQuantity > 0) {
        // Add the product to cartData if it doesn't already exist
        console.log(`Adding product ${productId} to cartData.`);
        cartData.push({
            product_id: productId,
            currentQuantity: currentQuantity
        });
    }

    // Save updated cart data to localStorage
    localStorage.setItem('cart_data', JSON.stringify(cartData));

    // Update the total item count in the cart
    let totalItemCount = cartData.reduce((acc, item) => acc + item.currentQuantity, 0);
    console.log(`Total item count:`, totalItemCount);
    updateItemCount(totalItemCount);
}



});