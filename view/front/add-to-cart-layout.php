<?php

function wp_atc_add_to_cart_layout()
{
    $product_id = get_the_ID();
?>
    <div class="atc-product__actions-button" data-product-id="<?php echo $product_id; ?>">
        <div class="loop-qty">
            <span class="atc-quantity-roller__roller">
                <button class="atc-quantity-roller__button atc-quantity-roller__button--increase" data-type="increase" data-atc-product-id="<?php echo $product_id; ?>">+</button>
                <span class="atc-quantity-roller__quantity" contenteditable="true" inputmode="numeric" data-atc-product-id="<?php echo $product_id; ?>">0</span>


                <button class="atc-quantity-roller__button atc-quantity-roller__button--decrease" data-type="decrease" data-atc-product-id="<?php echo $product_id; ?>">-</button>
            </span>
        </div>
    </div>
    <div class="atc-item-count">0</div>
    <!-- <button class="atc-button atc-product__add-to-order" data-atc-trigger="" data-atc-product-id="140" data-atc-product-type="simple" data-atc-variation-id="0" data-atc-variation-attributes="" style="display:unset">افزودن</button> -->
<?php
}
add_shortcode('add-to-cart', 'wp_atc_add_to_cart_layout');