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

namespace jimstrike\fbmcc\services;

use Craft;
use craft\base\Component;
use craft\web\View;
use jimstrike\fbmcc\Plugin;
use jimstrike\fbmcc\helpers as Helpers;

/**
 * @author  Dhimiter Karalliu
 * @package Messenger Customer Chat
 * @since   1.0.0
 */
class FbmccService extends Component
{
    /**
     * FB jssdk version
     * @var string
     */
    const FB_GRAPH_API_VERSION = 'v8.0';

    /**
     * FB Customer Chat script src
     * @var string
     */
    const FB_JSSDK_SRC = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';

    // Public Methods
    // =========================================================================

    /**
     * Facebook Messenger Customer Chat jssdk
     * 
     * @param int|null $siteId default
     * @param string $locale default
     * 
     * @return string
     */
    public function fbJsSdk(int $siteId = null, string $locale = ''): string
    {
        $sdkLocale = Plugin::$plugin->getSettings()->getSdkLocale($siteId);

        if (!empty($locale)) {
            $sdkLocale = $locale;
        }

        $sdkLocale = Helpers\Sanitize::locale($sdkLocale, '-', '_');

        return "<script>
            window.fbAsyncInit = function() {
                FB.init({
                    xfbml: true,
                    version: '" . self::FB_GRAPH_API_VERSION . "'
                });
            };
        
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) { return };
                js = d.createElement(s); js.id = id;
                js.src = '" . \str_replace('en_US', $sdkLocale, self::FB_JSSDK_SRC) . "';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>";
    }

    /**
     * Facebook Messenger Customer Chat html div
     * 
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function fbCustomerChat(int $siteId = null): string
    {
        $attrs = $this->_htmlAttrs($siteId);

        $map = \array_map(function($key) use ($attrs) {
            if(\is_bool($attrs[$key])) {
                return $attrs[$key] ? $key : '';
            }

            return $key . '="' . $attrs[$key] . '"';
        }, \array_keys($attrs));

        return '<div class="fb-customerchat" ' . \implode(' ', $map) . '></div>';
    }

    /**
     * Facebook Messenger Customer Chat snippet
     * 
     * @param int|null $siteId default
     * @param string $locale default
     * 
     * @return string
     */
    public function sdkSnippet(int $siteId = null, string $locale = ''): string
    {
        $settings = Plugin::$plugin->getSettings();

        if ($settings->getUseCodeSnippet($siteId) && !empty($settings->getCodeSnippet($siteId))) {
            return "\n<!-- start Facebook Messenger Custom Chat (fbmcc) with code snippet -->\n"
                . $settings->getCodeSnippet($siteId)
                . "\n<!-- end Facebook Messenger Custom Chat (fbmcc) with code snippet -->\n"
            ;
        }

        $locale = Helpers\Sanitize::locale($locale, '-', '_');

        return "\n<!-- start Facebook Messenger Custom Chat (fbmcc) -->\n"
            . $this->fbJsSdk($siteId, $locale)
            . $this->fbCustomerChat($siteId)
            . "\n<!-- end Facebook Messenger Custom Chat (fbmcc) -->\n"
        ;
    }

    /**
     * Array of locale options based on Facebook locales
     * 
     * @return array
     */
    public function sdkLocaleOptions(): array
    {
        $options = [];
        
        $fbLocales = \array_keys($this->_fbLocales());

        foreach ($fbLocales as $id) {
            $locale = Craft::$app->getI18n()->getLocaleById($id);

            if (!$locale instanceof \craft\i18n\Locale) {
                continue;
            }

            $localeId = Helpers\Sanitize::locale($locale->id, '-', '_');

            $options[] = [
                'value' => $localeId,
                'label' => Craft::t('app', '{id} – {name}', [
                    'name' => $locale->getDisplayName(Craft::$app->language),
                    'id' => $localeId
                ])
            ];
        }

        return $options;
    }

    // Private Methods
    // =========================================================================

    /**
     * Html attrs array from plugin settings
     * 
     * @param int|null $siteId default
     * 
     * @return array
     */
    private function _htmlAttrs(int $siteId = null): array
    {
        $settings = Plugin::$plugin->getSettings();

        return [
            'attribution' => $settings->getAttribution(),
            'page_id' => $settings->getPageId($siteId),
            'theme_color' => $settings->getThemeColor($siteId),
            'logged_in_greeting' => $settings->getLoggedInGreeting($siteId),
            'logged_out_greeting' => $settings->getLoggedOutGreeting($siteId),
            'greeting_dialog_display' => $settings->getGreetingDialogDisplay($siteId),
            'greeting_dialog_delay' => $settings->getGreetingDialogDelay($siteId),
            'ref' => $settings->getRef(),
        ];
    }

    /**
     * Facebook known locales
     * 
     * @return array
     */
    private function _fbLocales(): array
    {
        return include Plugin::$plugin->getBasePath() . '/etc/locales/facebook.php';
    }
}
