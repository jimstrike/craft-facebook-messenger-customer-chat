<?php
/**
 * Messenger Customer Chat plugin for Craft CMS 3.x
 *
 * Let people start a conversation on your website and continue in Messenger. 
 * Allows your customers to interact with your business anytime with the same personalized, 
 * rich-media experience they get in Messenger.
 *
 * @link      https://github.com/jimstrike
 * @copyright Copyright (c) 2020 Dhimiter Karalliu
 * @license   https://github.com/jimstrike/craft-facebook-messenger-customer-chat/blob/master/LICENSE.md
 */

namespace jimstrike\fbmcc\variables;

use Craft;
use jimstrike\fbmcc\Plugin;

/**
 * @author  Dhimiter Karalliu
 * @package Messenger Customer Chat
 * @since   1.0.0
 */
class FbmccVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @return string 
     */
    public function getName(): string
    {
        $name = Plugin::$plugin->name;

        return $name;
    }

    /**
     * SDK snippet
     * 
     * Twig usage:
     * {{ craft.fbmcc.sdkSnippet(currentSite.id, 'en_US')|raw }}
     * 
     * @param int|null $siteId default
     * @param string $locale default
     * 
     * @return string
     */
    public function getSdkSnippet(int $siteId = null, string $locale = ''): string
    {
        if (!Plugin::$plugin->getSettings()->isEnabled($siteId)) {
            return Plugin::$plugin->fbmccService->sdkSnippet($siteId, $locale);
        }
        
        return '<!-- Facebook Messenger Custom Chat (fbmcc) snippet already initiated -->';
    }

    /**
     * Plugin is enabled by opening hours
     * 
     * @param int|null $siteId default
     * 
     * @return bool
     */
    public function isEnabled(int $siteId = null): bool
    {
        return Plugin::$plugin->getSettings()->isEnabledByOpeningHours($siteId);
    }

    /**
     * Asset Published Url
     * 
     * @param string $resourcePath
     * 
     * @return string 
     */
    public function asset(string $resourcePath): string
    {
        return Plugin::assetsBaseUrl() . '/' . \ltrim($resourcePath, '/');
    }
}
