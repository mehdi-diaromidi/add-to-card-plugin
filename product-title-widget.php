<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Product_Title_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'product_title';
    }

    public function get_title()
    {
        return __('Product Title', 'add-to-cart');
    }

    public function get_icon()
    {
        return 'eicon-post-title';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Title', 'add-to-cart'),
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __('HTML Tag', 'add-to-cart'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => __('Alignment', 'add-to-cart'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'add-to-cart'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'add-to-cart'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'add-to-cart'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justify', 'add-to-cart'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .product-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'add-to-cart'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'add-to-cart'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .product-title',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $product_id = get_the_ID();
        $product_title = get_the_title($product_id);
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];

        echo sprintf('<%1$s class="product-title">%2$s</%1$s>', $tag, esc_html($product_title));
    }

    protected function _content_template()
    {
?>
        <#
            var productTitle='<?php echo get_the_title(); ?>' ;
            var tag=settings.html_tag;

            view.addInlineEditingAttributes( 'productTitle' , 'none' );
            #>
            <{{{ tag }}} class="product-title" {{{ view.getRenderAttributeString( 'productTitle' ) }}}>
                {{{ productTitle }}}
            </{{{ tag }}}>
    <?php
    }
}
