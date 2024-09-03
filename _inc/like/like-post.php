<?php

include_once ADD_TO_CART_PLUGIN_DIR . '/class/Message.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/SecurityValidator.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/DatabasehandlerUser.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/class/DatabasehandlerPost.php';
include_once ADD_TO_CART_PLUGIN_DIR . '/PopupRenderer.php';


function wp_atc_like_post(): void
{
    if (!SecurityValidator::wp_atc_nonce_validator($_POST['_nonce'])) {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
    }

    if (!SecurityValidator::wp_atc_user_login_validator()) {
        Message::wp_atc_send_json_message('error', 'برای لایک این مطلب ابتدا در سایت لاگین نمایید.', 403);
    }

    $post_id = SecurityValidator::wp_atc_post_user_id_validator($_POST['post_id']);
    $user_id = SecurityValidator::wp_atc_post_user_id_validator($_POST['user_id']);



    if ($post_id && $user_id) {

        $user = new DatabasehandlerUser($user_id, $post_id);

        if (!$user->wp_atc_user_metadata_exists()) {
            $user->wp_atc_add_user_meta();
            $post_like_counter = wp_atc_update_like_counter($post_id, 'like');
            Message::wp_atc_send_json_message('success', 'لایک شما با موفقیت حذف شد!', $post_like_counter, 200);
        } elseif ($user->wp_atc_user_metadata_exists()) {
            if (is_user_liked_post($post_id, $user->user_metadata)) {
                $user->wp_atc_update_user_meta('dislike');
                $post_like_counter = wp_atc_update_like_counter($post_id, 'dislike');
                Message::wp_atc_send_json_message('success', 'لایک شما با موفقیت حذف شد!', $post_like_counter, 200);
            } elseif (!is_user_liked_post($post_id, $user->user_metadata)) {
                $user->wp_atc_update_user_meta('like');
                $post_like_counter = wp_atc_update_like_counter($post_id, 'like');
                Message::wp_atc_send_json_message('success', 'لایک شما با موفقیت ثبت شد!', $post_like_counter, 200);
            }
        } else {
            Message::wp_atc_send_json_message('error', 'خطایی رخ داد!', 403);
        }
    } else {
        Message::wp_atc_send_json_message('error', 'درخواست نامعتبر!', 403);
    }
}

function is_user_liked_post($post_id, $user_liked_posts): bool
{
    return in_array($post_id, $user_liked_posts);
}

function wp_atc_update_like_counter($post_id, $like_status): int
{
    $post = new DatabasehandlerPost($post_id);

    if (!$post->wp_atc_post_metadata_exists()) {
        $post->wp_atc_add_post_meta();
        return 1;
    } elseif ($post->wp_atc_post_metadata_exists()) {
        $post->wp_atc_update_post_meta($like_status);
        return $post->post_metadata;
    } else {
        Message::wp_atc_send_json_message('error', 'خطایی رخ داد!', 403);
    }
}


add_action('wp_ajax_wp_atc_like_post', 'wp_atc_like_post');
add_action('wp_ajax_nopriv_wp_atc_like_post', 'wp_atc_like_post');
