<?php
// Register Custom Post Type for Orders
function create_atc_menu_orders_post_type()
{
    $labels = array(
        'name'                  => _x('سفارشات', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('سفارش', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('سفارشات', 'text_domain'),
        'name_admin_bar'        => __('سفارشات', 'text_domain'),
        'archives'              => __('آرشیو سفارشات', 'text_domain'),
        'attributes'            => __('ویژگی‌های سفارشات', 'text_domain'),
        'parent_item_colon'     => __('آیتم والد:', 'text_domain'),
        'all_items'             => __('تمام سفارشات', 'text_domain'),
        'add_new_item'          => __('افزودن سفارش جدید', 'text_domain'),
        'add_new'               => __('افزودن جدید', 'text_domain'),
        'new_item'              => __('سفارش جدید', 'text_domain'),
        'edit_item'             => __('ویرایش سفارش', 'text_domain'),
        'update_item'           => __('به‌روزرسانی سفارش', 'text_domain'),
        'view_item'             => __('مشاهده سفارش', 'text_domain'),
        'view_items'            => __('مشاهده سفارشات', 'text_domain'),
        'search_items'          => __('جستجوی سفارشات', 'text_domain'),
        'not_found'             => __('سفارشی پیدا نشد', 'text_domain'),
        'not_found_in_trash'    => __('هیچ سفارشی در زباله‌دان پیدا نشد', 'text_domain'),
        'featured_image'        => __('تصویر شاخص', 'text_domain'),
        'set_featured_image'    => __('انتخاب تصویر شاخص', 'text_domain'),
        'remove_featured_image' => __('حذف تصویر شاخص', 'text_domain'),
        'use_featured_image'    => __('استفاده از تصویر شاخص', 'text_domain'),
        'insert_into_item'      => __('قرار دادن در سفارش', 'text_domain'),
        'uploaded_to_this_item' => __('بارگذاری شده به این سفارش', 'text_domain'),
        'items_list'            => __('لیست سفارشات', 'text_domain'),
        'items_list_navigation' => __('ناوبری لیست سفارشات', 'text_domain'),
        'filter_items_list'     => __('فیلتر لیست سفارشات', 'text_domain'),
    );
    $args = array(
        'label'                 => __('سفارشات', 'text_domain'),
        'description'           => __('پست تایپ برای مدیریت سفارشات', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => false,
        'show_in_menu'          => false,
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type('atc_menu_orders', $args);
}
add_action('init', 'create_atc_menu_orders_post_type', 0);

// Register Meta Boxes
function atc_add_custom_meta_boxes()
{
    add_meta_box(
        'atc_order_info',
        'اطلاعات سفارش',
        'atc_render_order_info_meta_box',
        'atc_menu_orders',
        'normal',
        'high'
    );

    add_meta_box(
        'atc_order_items',
        'آیتم‌های سفارش',
        'atc_render_order_items_meta_box',
        'atc_menu_orders',
        'normal',
        'high'
    );

    add_meta_box(
        'order_status',
        'وضعیت سفارش',
        'atc_render_order_status_meta_box',
        'atc_menu_orders',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'atc_add_custom_meta_boxes');

// Render Order Info Meta Box
function atc_render_order_info_meta_box($post)
{
    $order_info = get_post_meta($post->ID, 'atc_order_info', true);
?>
    <p>
        <label for="customer_name">نام مشتری:</label>
        <input type="text" name="customer_name" id="customer_name" value="<?php echo esc_attr(isset($order_info['customer_name']) ? $order_info['customer_name'] : ''); ?>" class="widefat" />
    </p>
    <p>
        <label for="customer_phone">شماره تماس مشتری:</label>
        <input type="text" name="customer_phone" id="customer_phone" value="<?php echo esc_attr(isset($order_info['customer_phone']) ? $order_info['customer_phone'] : ''); ?>" class="widefat" />
    </p>
    <p>
        <label for="table_number">شماره میز:</label>
        <input type="text" name="table_number" id="table_number" value="<?php echo esc_attr(isset($order_info['table_number']) ? $order_info['table_number'] : ''); ?>" class="widefat" />
    </p>
    <p>
        <label for="items_count">تعداد آیتم‌ها:</label>
        <input type="number" name="items_count" id="items_count" value="<?php echo esc_attr(isset($order_info['items_count']) ? $order_info['items_count'] : ''); ?>" class="widefat" />
    </p>
    <p>
        <label for="total_price">قیمت نهایی:</label>
        <input type="number" name="total_price" id="total_price" value="<?php echo esc_attr(isset($order_info['total_price']) ? $order_info['total_price'] : ''); ?>" class="widefat" />
    </p>
<?php
}

// Render Order Items Meta Box
function atc_render_order_items_meta_box($post)
{
    $order_items = get_post_meta($post->ID, 'atc_order_items', true);
?>
    <p>
        <label for="product_name">نام محصول:</label>
        <input type="text" name="product_name" id="product_name" value="<?php echo esc_attr(isset($order_items['product_name']) ? $order_items['product_name'] : ''); ?>" class="widefat" />
    </p>
    <p>
        <label for="product_category">دسته بندی محصول:</label>
        <input type="text" name="product_category" id="product_category" value="<?php echo esc_attr(isset($order_items['product_category']) ? $order_items['product_category'] : ''); ?>" class="widefat" />
    </p>
    <p>
        <label for="products_count_card">تعداد محصول در سبد خرید:</label>
        <input type="number" name="products_count_card" id="products_count_card" value="<?php echo esc_attr(isset($order_items['products_count_card']) ? $order_items['products_count_card'] : ''); ?>" class="widefat" />
    </p>
<?php
}

// Render Order Status Meta Box
function atc_render_order_status_meta_box($post)
{
    $order_status = get_post_meta($post->ID, 'order_status', true);
?>
    <select name="order_status" id="order_status" class="widefat">
        <option value="waiting" <?php selected($order_status, 'waiting'); ?>>در انتظار تایید</option>
        <option value="registered" <?php selected($order_status, 'registered'); ?>>ثبت شده</option>
        <option value="completed" <?php selected($order_status, 'completed'); ?>>تکمیل شده</option>
        <option value="canceled" <?php selected($order_status, 'canceled'); ?>>لغو شده</option>
    </select>
<?php
}

// Save Meta Box Data
function atc_save_meta_box_data($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['customer_name'])) {
        $order_info = array(
            'customer_name'  => sanitize_text_field($_POST['customer_name']),
            'customer_phone' => sanitize_text_field($_POST['customer_phone']),
            'table_number'   => intval($_POST['table_number']),
            'items_count'    => intval($_POST['items_count']),
            'total_price'    => intval($_POST['total_price']),
        );
        update_post_meta($post_id, 'atc_order_info', $order_info);
    }

    if (isset($_POST['product_name'])) {
        $order_items = array(
            'product_name'       => sanitize_text_field($_POST['product_name']),
            'product_category'   => sanitize_text_field($_POST['product_category']),
            'products_count_card' => intval($_POST['products_count_card']),
        );
        update_post_meta($post_id, 'atc_order_items', $order_items);
    }

    if (isset($_POST['order_status'])) {
        update_post_meta($post_id, 'order_status', sanitize_text_field($_POST['order_status']));
    }
}
add_action('save_post', 'atc_save_meta_box_data');
