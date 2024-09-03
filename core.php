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

// function register_custom_elementor_widget($widgets_manager)
// {

// 	require_once(__DIR__ . '/add-to-cart-widget.php');

// 	$widgets_manager->register(new \Elementor_Add_To_Cart_Widget());
// }
// add_action('elementor/widgets/register', 'register_custom_elementor_widget');


use AddToCart\Plugin;

if (class_exists('AddToCart\Plugin')) {
	$the_plugin = new Plugin();
}

require_once  'view\front\add-to-card-layout.php';
// include_once  '_inc\like\add.php';
include_once  '_inc\send_order.php';

register_activation_hook(__FILE__, [$the_plugin, 'activate']);
register_deactivation_hook(__FILE__, [$the_plugin, 'deactivate']);
