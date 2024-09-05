<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Order_Info_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'order_customer_info';
    }

    public function get_title()
    {
        return __('Order Customer Info', 'plugin-name');
    }

    public function get_icon()
    {
        return 'eicon-person'; // Icon for widget
    }

    public function get_categories()
    {
        return ['general']; // Category to appear in Elementor panel
    }

    protected function register_controls()
    {

        // شروع بخش محتوایی برای انتخاب داده
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // گزینه برای انتخاب نوع داده
        $this->add_control(
            'info_type',
            [
                'label' => __('Select Order Info to Display', 'plugin-name'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'customer_name'  => __('Customer Name', 'plugin-name'),
                    'customer_phone' => __('Customer Phone', 'plugin-name'),
                    'table_number'   => __('Table Number', 'plugin-name'),
                    'items_count'    => __('Items Count', 'plugin-name'),
                    'total_price'    => __('Total Price', 'plugin-name'),
                ],
                'default' => 'customer_name',
            ]
        );

        // پایان بخش محتوایی
        $this->end_controls_section();

        // شروع بخش استایلی برای تنظیم رنگ و فونت
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'plugin-name'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // کنترل رنگ
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'plugin-name'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .order-info' => 'color: {{VALUE}};',
                ],
            ]
        );

        // کنترل فونت
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __('Typography', 'plugin-name'),
                'selector' => '{{WRAPPER}} .order-info',
            ]
        );

        // پایان بخش استایلی
        $this->end_controls_section();
    }

    protected function render()
    {
        // دریافت آیدی پست فعلی
        $post_id = get_the_ID();

        // دریافت اطلاعات سفارش از متاکلید atc_order_info
        $order_info = get_post_meta($post_id, 'atc_order_info', true);

        if (empty($order_info)) {
            echo '<div class="order-info">' . __('No order info available.', 'plugin-name') . '</div>';
            return;
        }

        // استخراج داده‌های انتخاب شده توسط کاربر
        $info_type = $this->get_settings_for_display('info_type');
        $output = '';

        switch ($info_type) {
            case 'customer_name':
                $output = !empty($order_info['customer_name']) ? $order_info['customer_name'] : __('No name provided', 'plugin-name');
                break;

            case 'customer_phone':
                $output = !empty($order_info['customer_phone']) ? $order_info['customer_phone'] : __('No phone number provided', 'plugin-name');
                break;

            case 'table_number':
                $output = isset($order_info['table_number']) ? $order_info['table_number'] : __('No table number provided', 'plugin-name');
                break;

            case 'items_count':
                $output = isset($order_info['items_count']) ? $order_info['items_count'] : __('No items count provided', 'plugin-name');
                break;

            case 'total_price':
                if (isset($order_info['total_price'])) {
                    // جدا کردن ارقام سه‌رقمی و اضافه کردن "تومان"
                    $output = number_format($order_info['total_price']) . ' تومان';
                } else {
                    $output = __('No total price provided', 'plugin-name');
                }
                break;
        }

        // خروجی نهایی با اعمال تنظیمات رنگ و فونت
        echo '<div class="order-info" style="color: ' . $this->get_settings_for_display('text_color') . ';">' . esc_html($output) . '</div>';
    }
}
