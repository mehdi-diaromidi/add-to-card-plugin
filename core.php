<?php
/*
 * Plugin Name:       Add To Cart
 * Plugin URI:        https://example.com/plugins/mehdi-diaromidi-add-to-cart
 * Description:       Add to card plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            M.Mehdi Diaromidi
 * Author URI:        https://mehdidiaromidi.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       add-to-cart
 * Domain Path:       /languages
 */


defined('ABSPATH') || exit;

require_once  'vendor/autoload.php';


use AddToCart\Plugin;

if (class_exists('AddToCart\Plugin')) {
	$the_plugin = new Plugin();
}

require_once  'view\front\add-to-cart-layout.php';
require_once  'view\front\cart-layout.php';
require_once  'view\front\get_product.php';
include_once  '_inc\send_order.php';
include_once  '_inc\create_post_type.php';
include_once  '_inc\check_new_orders.php';


register_activation_hook(__FILE__, [$the_plugin, 'activate']);
register_deactivation_hook(__FILE__, [$the_plugin, 'deactivate']);
