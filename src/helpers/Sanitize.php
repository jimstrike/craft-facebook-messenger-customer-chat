<?php
/**
 * Messenger Customer Chat plugin for Craft CMS 4.x
 *
 * Let people start a conversation on your website and continue in Messenger. 
 * Allows your customers to interact with your business anytime with the same personalized, 
 * rich-media experience they get in Messenger.
 *
 * @link      https://github.com/jimstrike
 * @copyright Copyright (c) Dhimiter Karalliu
 * @license   https://github.com/jimstrike/craft-facebook-messenger-customer-chat/blob/master/LICENSE.md
 */

namespace jimstrike\fbmcc\helpers;

/**
 * @author  Dhimiter Karalliu
 * @package Messenger Customer Chat
 * @since   1.0.0
 */
class Sanitize {
    /**
     * Sanitize a hex color value
     * 
     * @params string $color
     * @params bool $hash default
     * 
     * @return string
     */
    public static function color(string $color, bool $hash = true): string
    {
        $color = \str_replace('#', '', trim($color));
    
        // If the string is 6 characters long then use it in pairs.
        if (3 == \strlen($color)) {
            $color = \substr($color, 0, 1) . \substr($color, 0, 1) . \substr($color, 1, 1) . \substr($color, 1, 1) . \substr($color, 2, 1) . \substr($color, 2, 1);
        }
    
        $substr = [];
        
        for ($i = 0; $i <= 5; $i++) {
            $default = 0 == $i ? 'F' : $substr[$i-1];
            $substr[$i] = \substr($color, $i, 1);
            $substr[$i] = false === $substr[$i] || !\ctype_xdigit($substr[$i]) ? $default : $substr[$i];
        }

        $hex = \implode('', $substr);
    
        return !$hash ? $hex : '#' . $hex;
    }

    /**
     * Sanitize locale
     * 
     * @params string $locale
     * @params string $from default
     * @params string $to default
     * 
     * @return string
     */
    public static function locale(string $locale, string $from = '-', string $to = '_'): string
    {
        if (!\in_array($from, ['-', '_'])) {
            $from = '-';
        }

        if (!\in_array($to, ['-', '_'])) {
            $to = '_';
        }

        return \str_replace($from, $to, $locale);
    }
}