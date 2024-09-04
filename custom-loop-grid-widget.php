<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Custom_Loop_Grid_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'custom_loop_grid_widget';
    }

    public function get_title()
    {
        return __('Custom Loop Grid Widget', 'add-ro-cart');
    }

    public function get_icon()
    {
        return 'eicon-posts-ticker'; // You can use any icon from Elementor's icon library
    }

    public function get_categories()
    {
        return ['general']; // Change this if you want it to be in a specific category
    }

    protected function _register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'add-ro-cart'),
            ]
        );

        // Category Control
        $this->add_control(
            'category',
            [
                'label' => __('Select Category', 'add-ro-cart'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_categories_options(),
                'default' => '',
            ]
        );

        // Template Control
        $this->add_control(
            'template',
            [
                'label' => __('Select Template', 'add-ro-cart'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_templates_options(),
                'default' => '',
            ]
        );

        $this->end_controls_section(); // End Content Section

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'add-ro-cart'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Text Color Control
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'add-ro-cart'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-loop-grid-widget-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Background Color Control
        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'add-ro-cart'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-loop-grid-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section(); // End Style Section
    }

    private function get_categories_options()
    {
        $options = [];
        $taxonomy = 'product_categories'; // Use correct taxonomy for WooCommerce product categories
        $post_type = 'menu-product'; // Your custom post type

        // Get terms for the specified taxonomy
        $categories = get_terms([
            'post_type' => $post_type,
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $options[$category->term_id] = $category->name;
            }
        }

        return $options;
    }


    private function get_templates_options()
    {
        $options = [];
        $templates = get_posts([
            'post_type' => 'elementor_library',
            'posts_per_page' => -1
        ]);

        foreach ($templates as $template) {
            $options[$template->ID] = $template->post_title;
        }

        return $options;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Start the container for loop grid
        echo '<div class="custom-loop-grid-widget" style="background-color: ' . esc_attr($settings['background_color']) . ';">';

        // Query products based on selected category
        if ($settings['category']) {
            $query = new \WP_Query([
                'post_type' => 'menu-product', // Adjusted to your custom post type
                'posts_per_page' => -1, // Adjust if needed
                'tax_query' => [
                    [
                        'taxonomy' => 'product_categories', // Adjust to match your taxonomy
                        'field' => 'term_id',
                        'terms' => $settings['category'],
                    ],
                ],
            ]);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $product_id = get_the_ID();

                    // Render each product with the selected template
                    if ($settings['template']) {
                        $template_id = $settings['template'];
                        $elementor_instance = \Elementor\Plugin::$instance;

                        // Temporarily set the global post to the current product
                        setup_postdata($GLOBALS['post'] = &get_post($product_id));

                        echo '<div class="custom-loop-grid-widget-item">';
                        echo '<div class="elementor-template">';
                        echo $elementor_instance->frontend->get_builder_content_for_display($template_id);
                        echo '</div>';
                        echo '</div>';

                        // Restore the global post data
                        wp_reset_postdata();
                    }
                }
            }
        }

        echo '</div>'; // End the container for loop grid
    }
}
