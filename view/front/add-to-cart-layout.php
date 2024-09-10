<?php

function wp_atc_add_to_cart_layout()
{
    $product_id = get_the_ID();
?>
    <div class="atc-product-add-to-cart-buttons" data-product-id="<?php echo $product_id; ?>">
        <button class="atc-button atc-product-add-to-order" data-atc-product-id="<?php echo $product_id; ?>">
            <h2>
                افزودن به سبد
            </h2>
        </button>

        <div class="atc-loop-increase-decrease" style="display: none;" data-product-id="<?php echo $product_id; ?>">
            <span class="atc-quantity-updator-buttons">
                <button class="atc-quantity-updator-button atc-quantity-updator-button-increase" data-type="increase" data-atc-product-id="<?php echo $product_id; ?>">
                    <i class="fa fa-plus"></i>

                </button>
                <span class="atc-quantity-display-quantity" inputmode="numeric" data-atc-product-id="<?php echo $product_id; ?>">0</span>
                <button class="atc-quantity-updator-button atc-quantity-updator-button-decrease" data-type="decrease" data-atc-product-id="<?php echo $product_id; ?>">
                    <i class="fa fa-minus"></i>
                </button>
            </span>

        </div>
    </div>
<?php
}
add_shortcode('add-to-cart-layout', 'wp_atc_add_to_cart_layout');
