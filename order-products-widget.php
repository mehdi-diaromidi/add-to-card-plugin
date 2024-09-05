<?php

use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Order_Products_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'order_products_widget';
    }

    public function get_title()
    {
        return __('Order Products Info', 'plugin-name');
    }

    public function get_icon()
    {
        return 'eicon-products'; // Icon for widget
    }

    public function get_categories()
    {
        return ['general']; // Category to appear in Elementor panel
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __('Text Color', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
            ]
        );

        $this->add_control(
            'font_size',
            [
                'label' => __('Font Size', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        global $post;
        $order_id = $post->ID; // ID of the current post (Order)
        $order_items = get_post_meta($order_id, 'atc_order_items', true); // Get the serialized data

        if (!empty($order_items)) {
            $order_items = maybe_unserialize($order_items);

            echo '<div class="order-products-list" style="color: ' . esc_attr($this->get_settings('color')) . '; font-size: ' . esc_attr($this->get_settings('font_size')['size']) . 'px;">';

            foreach ($order_items as $item) {
                $product_name = $item['product_name'] ?? 'نامشخص';
                $product_category = $item['product_category'] ?? 'نامشخص';
                $product_count = $item['products_count'] ?? 0;

                echo '<div class="order-product">';
                echo '<h4>' . esc_html($product_name) . '</h4>';
                echo '<p>' . __('Category: ', 'plugin-name') . esc_html($product_category) . '</p>';
                echo '<p>' . __('Count: ', 'plugin-name') . esc_html($product_count) . '</p>';
                echo '</div>';
            }

            echo '</div>';
        } else {
            echo '<p>' . __('No products found in this order.', 'plugin-name') . '</p>';
        }
    }
}
