<?php


/**
 * Class Plugin.
 */
class Plugin
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->init();
	}

	public function activate() {}
	public function deactivate() {}

	/**
	 * Initialize plugin
	 */
	private function init()
	{
		define('ADD_TO_CART_PLUGIN_DIR', plugin_dir_path(__DIR__));
		define('ADD_TO_CART_PLUGIN_URL', plugin_dir_url(__DIR__));
		define('ADD_TO_CART_PLUGIN_BUILD_PATH', ADD_TO_CART_PLUGIN_DIR . 'assets/build/');
		define('ADD_TO_CART_PLUGIN_BUILD_URL', ADD_TO_CART_PLUGIN_URL . 'assets/build/');
		define('ADD_TO_CART_PLUGIN_VERSION', '1.0.0');

		include_once ADD_TO_CART_PLUGIN_DIR . 'src/Assets.php';

		if (class_exists('Assets')) {
			new Assets();
		}
	}
}
