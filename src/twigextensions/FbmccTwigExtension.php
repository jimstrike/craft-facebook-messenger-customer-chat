<?php
/**
 * Messenger Customer Chat plugin for Craft CMS 4.x
 *
 * Let people start a conversation on your website and continue in Messenger. 
 * Allows your customers to interact with your business anytime with the same personalized, 
 * rich-media experience they get in Messenger.
 *
 * @link      https://github.com/jimstrike
 * @copyright Copyright (c) 2020 Dhimiter Karalliu
 * @license   https://github.com/jimstrike/craft-facebook-messenger-customer-chat/blob/master/LICENSE.md
 */

namespace jimstrike\fbmcc\twigextensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use Craft;
use jimstrike\fbmcc\Plugin;

/**
 * @author  Dhimiter Karalliu
 * @package Messenger Customer Chat
 * @since   1.0.0
 */
class FbmccTwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     * 
     * @return string
     */
    public function getName(): string
    {
        return Plugin::$plugin->name;
    }

    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function getFilters(): array
    {
        return [
            //new TwigFilter(Plugin::$plugin->handle . '_sdk_snippet', [$this, 'sdkSnippetFunction']),
            new TwigFilter(Plugin::$plugin->handle . '_validate_date_str', [$this, 'validateDateStrFunction']),
            new TwigFilter(Plugin::$plugin->handle . '_is_numeric', function($value) { 
                return  \is_numeric($value); 
            }),
        ];
    }

    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(Plugin::$plugin->handle . '_sdk_snippet', [$this, 'sdkSnippetFunction']),
            new TwigFunction(Plugin::$plugin->handle . '_validate_date_str', [$this, 'validateDateStrFunction']),
            new TwigFunction(Plugin::$plugin->handle . '_is_enabled', [$this, 'isEnabledByOpeningHoursFunction']),
            new TwigFunction(Plugin::$plugin->handle . '_asset', [$this, 'assetFunction']),
        ];
    }

    /**
     * SDK snippet
     * 
     * Twig usage:
     * {{ fbmcc_sdk_snippet(currentSite.id, 'en_US')|raw }}
     * {{ currentSite.id|fbmcc_sdk_snippet('en_US')|raw }}
     * 
     * @param int|null $siteId default
     * @param string $locale default
     * 
     * @return string
     */
    public function sdkSnippetFunction(int $siteId = null, string $locale = ''): string
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
    public function isEnabledByOpeningHoursFunction(int $siteId = null): bool
    {
        return Plugin::$plugin->getSettings()->isEnabledByOpeningHours($siteId);
    }

    /**
     * Validate date string
     * 
     * @param $str
     * 
     * @return bool 
     */
    public function validateDateStrFunction($str): bool
    {
        return (bool)\strtotime($str);
    }

    /**
     * Asset Published Url
     * 
     * @param string $resourcePath
     * 
     * @return string 
     */
    public function assetFunction(string $resourcePath): string
    {
        return Plugin::assetsBaseUrl() . '/' . \ltrim($resourcePath, '/');
    }
}
