<?php

include_once ADD_TO_CART_PLUGIN_DIR . '/_inc/update_product_stock.php';

class OrderHandler
{
    private int $order_id;

    public function __construct(int $order_id = 0)
    {
        $this->order_id = $order_id;
    }

    public function create_order_post(string $order_title): int
    {
        $this->order_id = wp_insert_post([
            'post_title'  => $order_title,
            'post_type'   => 'menu_orders',
            'post_status' => 'publish',
        ]);

        return $this->order_id;
    }

    public function update_order_meta(string $meta_key, $meta_value): void
    {
        update_post_meta($this->order_id, $meta_key, $meta_value);
    }

    public function add_order_meta(string $meta_key, $meta_value): void
    {
        add_post_meta($this->order_id, $meta_key, $meta_value);
    }

    public function get_post_meta(int $post_id, string $meta_key)
    {
        return get_post_meta($post_id, $meta_key, true);
    }

    public function get_product_details(int $product_id): array
    {
        $product_name = get_the_title($product_id);
        $product_in_stock = $this->get_post_meta($product_id, 'product_in_stock');
        $product_price_special = $this->get_post_meta($product_id, 'product_price_special');
        $product_price_normal = $this->get_post_meta($product_id, 'product_price_normal');
        $product_category_terms = get_the_terms($product_id, 'product_categories');
        $product_category = !empty($product_category_terms) ? $product_category_terms[0]->name : '';
        $product_thumbnail = $this->get_post_meta($product_id, '_thumbnail_id', true);
        $product_materials = $this->get_post_meta($product_id, 'product_materials', true);


        return [
            'product_name' => $product_name,
            'product_in_stock' => $product_in_stock,
            'product_price_special' => $product_price_special,
            'product_price_normal' => $product_price_normal,
            'product_category' => $product_category,
            'product_thumbnail' => $product_thumbnail,
            'product_materials' => $product_materials,
        ];
    }
    public function update_material_meta($material_id, $material_updated_amount): void
    {
        update_post_meta($material_id, 'materials_amount', $material_updated_amount);
    }
    public function update_product_materials_amount(int $product_material_id, $product_material_amount): bool
    {
        $material_amount = $this->get_post_meta($product_material_id, 'materials_amount');
        $material_updated_amount = $material_amount - $product_material_amount;
        if ($material_updated_amount <= 0) {
            update_post_meta($product_material_id, 'materials_stock', 'ناموجود');
            update_all_products_stock_status($product_material_id);
            return false;
        } else {
            $this->update_material_meta($product_material_id, $material_updated_amount);
            return true;
        }
    }
}
