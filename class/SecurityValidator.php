<?php

class SecurityValidator
{

    private static function wp_atc_empty_validator($field): bool
    {
        return !empty($field);
    }

    private static function wp_atc_intval($field): int
    {
        return intval($field);
    }

    private static function wp_atc_sanitize_text_field($field)
    {
        return sanitize_text_field($field);
    }

    public static function wp_atc_post_user_id_validator($field): int|bool
    {
        if (!self::wp_atc_empty_validator($field)) {
            return false;
        }
        return self::wp_atc_intval(self::wp_atc_sanitize_text_field($field));
    }

    public static function wp_atc_nonce_validator($nonce): bool
    {
        return isset($nonce) && wp_verify_nonce($nonce);
    }
}
