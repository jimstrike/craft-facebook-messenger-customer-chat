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

namespace jimstrike\fbmcc\models;

use Craft;
use craft\base\Model;
use craft\helpers\DateTimeHelper;

use jimstrike\fbmcc\Plugin;
use jimstrike\fbmcc\helpers as Helpers;

/**
 * @author  Dhimiter Karalliu
 * @package Messenger Customer Chat
 * @since   1.0.0
 */
class Settings extends Model
{
    // Public
    public $pageId;
    public $enabled;
    public $sdkLocale;
    public $themeColor;
    public $loggedInGreeting;
    public $loggedOutGreeting;
    public $greetingDialogDisplay;
    public $greetingDialogDelay;
    public $useCodeSnippet;
    public $codeSnippet;
    public $hours;
    public $sections;

    // Private
    private $attribution = 'setup_tool';
    private $ref = '';

    // Getters and Setters
    // =========================================================================

    /**
     * @param int|null $siteId default
     * 
     * @return bool
     */
    public function getEnabled(int $siteId = null): bool
    {
        $setting = $this->_getSetting('enabled', $siteId);
        
        return (bool)$setting ?: false;
    }

    /**
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function getPageId(int $siteId = null): string
    {
        $setting = $this->_getSetting('pageId', $siteId);
        
        return $setting ?: 'FACEBOOK_PAGE_ID';
    }

    /**
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function getSdkLocale(int $siteId = null): string
    {
        $setting = $this->_getSetting('sdkLocale', $siteId);
        $locale = $setting ?: 'en_US';

        return Helpers\Sanitize::locale($locale, '-', '_');
    }

    /**
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function getThemeColor(int $siteId = null): string
    {
        $setting = $this->_getSetting('themeColor', $siteId);
        $color = $setting ?: '#0084ff';

        return Helpers\Sanitize::color($color);
    }

    /**
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function getLoggedInGreeting(int $siteId = null): string
    {
        $setting = $this->_getSetting('loggedInGreeting', $siteId);

        return $setting ?: Craft::t('fbmcc', 'settings.logged_in_greeting.default.value');
    }

    /**
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function getLoggedOutGreeting(int $siteId = null): string
    {
        $setting = $this->_getSetting('loggedOutGreeting', $siteId);

        return $setting ?: Craft::t('fbmcc', 'settings.logged_out_greeting.default.value');
    }

    /**
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function getGreetingDialogDisplay(int $siteId = null): string
    {
        $setting = $this->_getSetting('greetingDialogDisplay', $siteId);
        $display = strtolower($setting) ?: 'hide';
        
        if (!\in_array($display, ['hide', 'show', 'fade'])) {
            return 'hide';
        }

        return $display;
    }

    /**
     * @param int|null $siteId default
     * 
     * @return int
     */
    public function getGreetingDialogDelay(int $siteId = null): int
    {
        $setting = $this->_getSetting('greetingDialogDelay', $siteId);
        $delay = (int)$setting ?? 3;
        
        return abs((int)$delay);
    }

    /**
     * @param int|null $siteId default
     * 
     * @return string
     */
    public function getCodeSnippet(int $siteId = null): string
    {
        $setting = \trim($this->_getSetting('codeSnippet', $siteId));

        return $setting ?: '';
    }

    /**
     * @param int|null $siteId default
     * 
     * @return bool
     */
    public function getUseCodeSnippet(int $siteId = null): bool
    {
        $setting = $this->_getSetting('useCodeSnippet', $siteId);
        
        return (bool)$setting ?: false;
    }

    /**
     * @param int|null $siteId default
     * 
     * @return array
     */
    public function getHours(int $siteId = null): array
    {
        $setting = $this->_getSetting('hours', $siteId);
        $hours = $setting ?: [];
        
        return $hours;
    }

    /**
     * @param int|null $siteId default
     * 
     * @return array
     */
    public function getSections(int $siteId = null): array
    {
        $setting = $this->_getSetting('sections', $siteId);
        $sections = $setting ?: [];
        
        return $sections;
    }

    /**
     * @return string
     */
    public function getAttribution(): string
    {
        if (!in_array($this->attribution, ['setup_tool'])) {
            return 'setup_tool';
        }

        return $this->attribution;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        $request = Craft::$app->getRequest();

        if (!$request->getIsConsoleRequest()) {
            if (empty($this->ref)) {
                return Plugin::baseRequestUrlAndFullPath();
            }
        }

        return $this->ref;
    }

    /**
     * @param string $ref
     * 
     * @return Settings
     */
    public function setRef(string $ref): Settings
    {
        $this->ref = $ref;

        return $this;
    }

    // Helper set methods
    // =========================================================================

    /**
     * Set array value for property
     * 
     * @param string $field
     * @param mixed $value
     * @param int $siteId default
     * 
     * @return array
     * $this->pageId = [
     *     ['siteId' => 'value'],
     *     ['siteId' => 'value']
     * ]
     */
    public function makeValue(string $field, $value, int $siteId = 1): array
    {
        // Sanitize value
        $value = $this->_sanitizeValue($field, $value);

        $base = \is_array($this->$field) ? $this->$field : [];
        
        $replace = [($siteId) => (\is_string($value) ? \trim($value) : ($value ?? ''))];

        $a = \array_replace($base, $replace) ?? (array)$this->$field;
        
        \ksort($a);

        return $a;
    }

    // Misc helpers
    // =========================================================================

    /**
     * @param int|null $siteId default
     * 
     * @return bool
     */
    public function isEnabledByOpeningHours(int $siteId = null): bool
    {
        $hours = $this->getHours($siteId);

        $now = DateTimeHelper::toDateTime(new \DateTime('now'), true);

        $key = $now->format('w'); // 0 (for Sunday) through 6 (for Saturday)

        $closed = $hours[$key]['closed'] ?? false;

        if ($closed) {
            return false;
        }
        
        $timeFrom = $hours[$key]['from']['time'] ?? null;
        $timeTo = $hours[$key]['to']['time'] ?? null;

        $from = (bool)\strtotime($timeFrom) ? DateTimeHelper::toDateTime(new \DateTime($timeFrom), true) : null;
        $to = (bool)\strtotime($timeTo) ? DateTimeHelper::toDateTime(new \DateTime($timeTo), true) : null; 

        if ($from && $to) {
            if ($now < $from || $now > $to) {
                return false;
            }
        }

        if (!$to) {
            if ($from && $now < $from) {
                return false;
            }
        }

        if (!$from) {
            if ($to && $now > $to) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int|null $siteId default
     * 
     * @return bool
     */
    public function isEnabledBySections(int $siteId = null): bool
    {
        $sections = \array_keys(\array_filter((array)$this->getSections($siteId)));

        if (!$sections) {
            return true;
        }

        $request = Craft::$app->getRequest();

        $uri = ltrim(\implode('/', (array)$request->getSegments()), '/') ?: '__home__';
        $entry = \craft\elements\Entry::find()->uri($uri)->one();

        if ($entry instanceof \craft\elements\Entry) {
            if (\in_array(($entry->sectionId ?? null), $sections)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int|null $siteId default
     * 
     * @return bool
     */
    public function isEnabled(int $siteId = null): bool
    {
        return 
            $this->getEnabled($siteId) && 
            $this->isEnabledByOpeningHours($siteId) && 
            $this->isEnabledBySections($siteId)
        ;
    }

    // Private methods
    // =========================================================================

    /**
     * @param string $field
     * @param mixed $value
     * 
     * @return mixed
     */
    private function _sanitizeValue(string $field, $value)
    {
        // Property "$this->hours" expects a valid date string deep into the assoc array
        if ($field == 'hours') {
            foreach ($value as $day => $time) {
                foreach (['from', 'to'] as $key) {
                    if (!(bool)\strtotime($time[$key]['time'])) {
                        $value[$day][$key]['time'] = '';
                    }
                }
            }
        }

        // ...

        return $value;
    }

    /**
     * @param string $setting
     * @param int|null $siteId default
     * 
     * @return mixed
     */
    private function _getSetting(string $setting, int $siteId = null)
    {
        if (empty($siteId)) {
            $siteId = Craft::$app->getSites()->getCurrentSite()->id ?? null;
        }

        $configs = Craft::$app->getConfig()->getConfigFromFile('fbmcc');
        
        if (isset($configs[$siteId][$setting])) {
            return $configs[$siteId][$setting];
        }

        if (isset($configs[$setting])) {
            return $configs[$setting];
        }

        return $this->$setting[$siteId] ?? '';
    }
}