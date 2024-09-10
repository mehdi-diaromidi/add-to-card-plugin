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



include_once  'src/Plugin.php';

if (class_exists('Plugin')) {
	$the_plugin = new Plugin();
}

require_once  ADD_TO_CART_PLUGIN_DIR . 'view/front/add-to-cart-layout.php';
require_once  ADD_TO_CART_PLUGIN_DIR . 'view/front/cart-layout.php';
require_once  ADD_TO_CART_PLUGIN_DIR . 'view/front/get_product.php';
include_once  ADD_TO_CART_PLUGIN_DIR . '_inc/send_order.php';
include_once  ADD_TO_CART_PLUGIN_DIR . '_inc/check_new_orders.php';
include_once  ADD_TO_CART_PLUGIN_DIR . '_inc/clear_new_orders.php';