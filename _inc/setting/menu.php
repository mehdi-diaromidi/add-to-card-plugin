<?php

function wp_ls_register_menu()
{
    add_options_page(
        'تنظیمات پلاگین لایک مطالب',
        'لایک مطالب',
        'manage_options',
        'like_post_setting',
        'wp_ls_like_post_admin_layout',
    );
}
// include_once LIKE_SYSTEM_PLUGIN_DIR . '_inc/setting/setting.php';
add_action('admin_menu', 'wp_ls_register_menu');
