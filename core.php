<?php
/*
 * Plugin Name:       Add To Card
 * Plugin URI:        https://example.com/plugins/mehdi-diaromidi-add-to-card
 * Description:       Add to card plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            M.Mehdi Diaromidi
 * Author URI:        https://mehdidiaromidi.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       add-to-card
 * Domain Path:       /languages
 */


defined('ABSPATH') || exit;

require_once  'vendor/autoload.php';

// function register_product_title_widget($widgets_manager)
// {

// 	require_once(__DIR__ . '/product-title-widget.php');

// 	$widgets_manager->register(new \Product_Title_Widget());
// }
// add_action('elementor/widgets/register', 'register_product_title_widget');

// function register_custom_loop_grid_widget($widgets_manager)
// {
// 	require_once(__DIR__ . '/custom-loop-grid-widget.php');
// 	$widgets_manager->register(new \Custom_Loop_Grid_Widget());
// }
// add_action('elementor/widgets/register', 'register_custom_loop_grid_widget');




use AddToCart\Plugin;

if (class_exists('AddToCart\Plugin')) {
	$the_plugin = new Plugin();
}

require_once  'view\front\add-to-cart-layout.php';
// include_once  '_inc\like\add.php';
include_once  '_inc\send_order.php';
include_once  '_inc\create_post_type.php';
// include(plugin_dir_path(__FILE__) . 'view\front\order-management-panel.php');
function register_order_info_widgets($widgets_manager)
{
	require_once('order-info-widget.php');
	$widgets_manager->register(new \Order_Info_Widget());
}
add_action('elementor/widgets/register', 'register_order_info_widgets');
function register_order_products_widgets($widgets_manager)
{
	require_once('order-products-widget.php');
	$widgets_manager->register(new \Order_Products_Widget());
}

add_action('elementor/widgets/register', 'register_order_products_widgets');



register_activation_hook(__FILE__, [$the_plugin, 'activate']);
register_deactivation_hook(__FILE__, [$the_plugin, 'deactivate']);
