<?php

return array(
    'public' => array(
        'js' => array(
            'sweetalert2' => array(
                'src'          => 'https://cdn.jsdelivr.net/npm/sweetalert2@11',
                'dependencies' => array('jquery'),
                'version'      => '11.0.0',
                'in_footer'    => true,
            ),
        ),
    ),
    'admin' => array(
        'js' => array(
            'admin-js' => array(
                'src'          => ADD_TO_CART_PLUGIN_BUILD_URL . 'js/admin/admin-js.js',
                'dependencies' => array('jquery'),
                'version'      => '1.0.0',
                'in_footer'    => false,
            ),
        ),
        'css' => array(
            'admin-style' => array(
                'src'     => ADD_TO_CART_PLUGIN_BUILD_URL . 'css/admin/admin-style.css',
                'version' => '1.0.0',
                'media'   => 'all',
            ),
        ),
    ),
    'front' => array(
        'js' => array(
            'front-menu-js' => array(
                'src'          => ADD_TO_CART_PLUGIN_BUILD_URL . 'js/front/front-menu-js.js',
                'dependencies' => array('jquery'),
                'version'      => '1.0.0',
                'in_footer'    => false,
                'page'         => 'menu'
            ),
            'front-cart-js' => array(
                'src'          => ADD_TO_CART_PLUGIN_BUILD_URL . 'js/front/front-cart-js.js',
                'dependencies' => array('jquery'),
                'version'      => '1.0.0',
                'in_footer'    => true,
                'page'         => 'customer-cart',
            ),
            'ajax_order' => array(
                'src'          => ADD_TO_CART_PLUGIN_BUILD_URL . 'js/front/ajax_order.js',
                'dependencies' => array('jquery'),
                'version'      => '1.0.0',
                'in_footer'    => true,
                'page'         => 'customer-cart',
                'localize_script' => array(
                    'atc_ajaxurl' => admin_url('admin-ajax.php'),
                    '_atc_nonce'   => wp_create_nonce()
                )
            ),
            'ajax_set_cart' => array(
                'src'          => ADD_TO_CART_PLUGIN_BUILD_URL . 'js/front/ajax_set_cart.js',
                'dependencies' => array('jquery'),
                'version'      => '1.0.0',
                'in_footer'    => false,
                'localize_script' => array(
                    'atc_ajaxurl' => admin_url('admin-ajax.php'),
                    '_atc_nonce'   => wp_create_nonce()
                ),
                'page'         => 'customer-cart'
            ),
        ),
        'css' => array(
            'front-style' => array(
                'src'     => ADD_TO_CART_PLUGIN_BUILD_URL . 'css/front/front-style.css',
                'version' => '1.0.0',
                'media'   => 'all',
            ),
        ),
    ),
);
