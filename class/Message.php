<?php

class Message
{
    // Sends a JSON response with a message.
    public static function wp_atc_send_json_message(string $type, string $message, string $order_title, int $status_code = null, int $flag = 0): void
    {
        // Prepare the response array with the message details
        $response = array(
            'type' => $type,
            'message' => $message,
            'order_title' => $order_title
        );

        // Send the JSON response
        wp_send_json($response, $status_code, $flag);
    }
}
