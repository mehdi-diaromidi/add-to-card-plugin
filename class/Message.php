<?php

class Message
{
    public static function wp_atc_send_json_message(string $type, string $message, int $status_code = null, int $flag = 0): void
    {
        // $redirect_link = self::wp_ls_create_redirect_tag($redirect_link);
        $response = array(
            $type => true,
            'message' => $message
        );
        wp_send_json($response, $status_code, $flag);
    }
}