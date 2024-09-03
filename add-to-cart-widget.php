<?php

class Elementor_Add_To_Cart_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'add_to_cart';
    }

    public function get_title()
    {
        return __('Add to Cart', 'plugin-name');
    }

    public function get_icon()
    {
        return 'eicon-cart';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Add to Cart', 'plugin-name'),
                'placeholder' => __('Type your text here', 'plugin-name'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .atc-product__add-to-order' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
?>
        <div class="atc-product__actions-button">
            <button class="atc-button atc-product__add-to-order"><?php echo $settings['button_text']; ?></button>
        </div>
    <?php
    }

    protected function _content_template()
    {
    ?>
        <#
            var buttonText=settings.button_text;
            #>
            <?php
            $product_id = get_the_ID();
            ?>
            <div class="atc-product__actions-button" data-product-id="<?php echo $product_id; ?>">
                <div class="loop-qty">
                    <span class="atc-quantity-roller__roller">
                        <button class="atc-quantity-roller__button atc-quantity-roller__button--increase" data-type="increase" data-atc-product-id="<?php echo $product_id; ?>">+</button>
                        <span class="atc-quantity-roller__quantity" contenteditable="true" inputmode="numeric" data-atc-product-id="<?php echo $product_id; ?>">
                            0
                        </span>

                        <button class="atc-quantity-roller__button atc-quantity-roller__button--decrease" data-type="decrease" data-atc-product-id="<?php echo $product_id; ?>">-</button>
                    </span>
                </div>
            </div>
            <div class="test-add-to-cart">0</div>
            </div>
    <?php
    }
}
