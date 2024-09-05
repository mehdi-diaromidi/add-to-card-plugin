<?php

namespace AddToCart;

/**
 * Class Assets.
 */
class Assets
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize.
     */
    private function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    /**
     * Enqueue styles.
     */
    private function enqueue_styles($styles, $prefix)
    {
        foreach ($styles as $handle => $data) {
            wp_enqueue_style(
                $prefix . '-' . $handle,
                $data['src'],
                $data['dependencies'] ?? [],
                $data['version'],
                $data['media'] ?? 'all'
            );
        }
    }

    /**
     * Enqueue scripts.
     */
    private function enqueue_scripts($scripts, $prefix)
    {
        foreach ($scripts as $handle => $data) {
            if ($data['page'] != '') {
                if (is_page($data['page'])) {
                    wp_enqueue_script(
                        $prefix . '-' . $handle,
                        $data['src'],
                        $data['dependencies'] ?? [],
                        $data['version'],
                        $data['in_footer'] ?? true
                    );

                    if (!empty($data['localize_script'])) {
                        wp_localize_script($prefix . '-' . $handle, 'atc_plugin_' . $handle, $data['localize_script']);
                    }
                }
            } else {
                wp_enqueue_script(
                    $prefix . '-' . $handle,
                    $data['src'],
                    $data['dependencies'] ?? [],
                    $data['version'],
                    $data['in_footer'] ?? true
                );

                if (!empty($data['localize_script'])) {
                    wp_localize_script($prefix . '-' . $handle, 'atc_plugin_' . $handle, $data['localize_script']);
                }
            }
        }
    }

    /**
     * Enqueue assets for the frontend.
     */
    public function enqueue_front_assets()
    {
        $asset_config_file = sprintf('%s/assets.php', ADD_TO_CART_PLUGIN_BUILD_PATH);

        if (file_exists($asset_config_file)) {
            $asset_config = include_once $asset_config_file;

            // Enqueue public assets
            $this->enqueue_styles($asset_config['public']['css'] ?? [], 'atc');
            $this->enqueue_scripts($asset_config['public']['js'] ?? [], 'atc');

            // Enqueue front-specific assets
            $this->enqueue_styles($asset_config['front']['css'] ?? [], 'atc');
            $this->enqueue_scripts($asset_config['front']['js'] ?? [], 'atc');
        }
    }

    /**
     * Enqueue assets for the admin panel.
     */
    public function enqueue_admin_assets()
    {
        $asset_config_file = sprintf('%s/assets.php', ADD_TO_CART_PLUGIN_BUILD_PATH);

        if (file_exists($asset_config_file)) {
            $asset_config = include_once $asset_config_file;

            // Enqueue public assets
            $this->enqueue_styles($asset_config['public']['css'] ?? [], 'atc');
            $this->enqueue_scripts($asset_config['public']['js'] ?? [], 'atc');

            // Enqueue admin-specific assets
            $this->enqueue_styles($asset_config['admin']['css'] ?? [], 'atc');
            $this->enqueue_scripts($asset_config['admin']['js'] ?? [], 'atc');
        }
    }
}
