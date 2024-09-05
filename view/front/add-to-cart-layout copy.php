<?php

function wp_atc_cart_layout()
{
    $product_id = get_the_ID();
?>
    <div class="atc-product-cards">
        <div class="atc-product-card">
            <img src="https://via.placeholder.com/300x200" alt="Product Image" class="atc-product-image">
            <div class="atc-product-info">
                <h2 class="atc-product-name">Product Name</h2>
                <p class="atc-product-price">$29.99</p>
                <div class="atc-product-actions-button" data-product-id="<?php echo $product_id; ?>">
                    <div class="atc-loop-qty">
                        <span class="atc-quantity-roller-roller">
                            <button class="atc-quantity-roller-button atc-quantity-roller-button-increase" data-type="increase" data-atc-product-id="<?php echo $product_id; ?>">+</button>
                            <span class="atc-quantity-roller-quantity" contenteditable="true" inputmode="numeric" data-atc-product-id="<?php echo $product_id; ?>" style="display: none;">0</span>
                            <button class="atc-quantity-roller-button atc-quantity-roller-button-decrease" data-type="decrease" data-atc-product-id="<?php echo $product_id; ?>" style="display: none;">-</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- <button class="atc-button atc-product__add-to-order" data-atc-trigger="" data-atc-product-id="140" data-atc-product-type="simple" data-atc-variation-id="0" data-atc-variation-attributes="" style="display:unset">افزودن</button> -->
<?php
}
add_shortcode('add-to-cart', 'wp_atc_add_to_cart_layout');
