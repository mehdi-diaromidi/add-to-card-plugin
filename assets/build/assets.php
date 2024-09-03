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
            'front-js' => array(
                'src'          => ADD_TO_CART_PLUGIN_BUILD_URL . 'js/front/front-js.js',
                'dependencies' => array('jquery'),
                'version'      => '1.0.0',
                'in_footer'    => false,
            ),
            'ajax' => array(
                'src'          => ADD_TO_CART_PLUGIN_BUILD_URL . 'js/front/ajax.js',
                'dependencies' => array('jquery'),
                'version'      => '1.0.0',
                'in_footer'    => false,
                'localize_script' => array(
                    'atc_ajaxurl' => admin_url('admin-ajax.php'),
                    '_atc_nonce'   => wp_create_nonce(),
                    'atc_user_id' => get_current_user_id()
                )
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
