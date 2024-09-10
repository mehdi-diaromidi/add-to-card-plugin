<?php

class SecurityValidator
{

    // Convert a field to an integer
    private static function wp_atc_intval($field): int
    {
        return intval($field);
    }

    // Sanitize a text field
    private static function wp_atc_sanitize_text_field($field)
    {
        return sanitize_text_field($field);
    }

    // Sanitize a textarea field
    private static function wp_atc_sanitize_textarea_field($str)
    {
        return sanitize_textarea_field($str);
    }

    // Validate and sanitize an integer field
    public static function wp_atc_integer_validator($field)
    {
        return self::wp_atc_intval(self::wp_atc_sanitize_text_field($field));
    }

    // Validate and sanitize a string field
    public static function wp_atc_string_validator($field): string
    {
        return self::wp_atc_sanitize_text_field($field);
    }

    // Validate and sanitize a textarea field
    public static function wp_atc_textarea_validator($str): string
    {
        return self::wp_atc_sanitize_textarea_field($str);
    }

    // Validate and sanitize a phone number field
    public static function wp_atc_phonenumber_validator($field): int
    {
        return self::wp_atc_intval(self::wp_atc_sanitize_text_field($field));
    }

    // Validate a nonce value
    public static function wp_atc_nonce_validator($nonce): bool
    {
        return isset($nonce) && wp_verify_nonce($nonce);
    }
}
