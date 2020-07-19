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

namespace jimstrike\fbmcc;

use Craft;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\UrlManager;
use craft\web\View;
use craft\web\twig\variables\Cp;
use craft\web\twig\variables\CraftVariable;
use craft\helpers\UrlHelper;

use yii\base\Event;

use jimstrike\fbmcc\models\Settings;
use jimstrike\fbmcc\services\FbmccService;
use jimstrike\fbmcc\twigextensions\FbmccTwigExtension;
use jimstrike\fbmcc\variables\FbmccVariable;
use jimstrike\fbmcc\helpers as Helpers;

/**
 * @author  Dhimiter Karalliu
 * @package Messenger Customer Chat
 * @since   1.0.0
 */
class Plugin extends \craft\base\Plugin
{
    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Plugin::$plugin
     *
     * @var Plugin
     */
    public static $plugin;

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @inheritdoc
     * @var string
     */
    public $schemaVersion = '1.0.1';

    /**
     * @inheritdoc
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @inheritdoc
     * @var bool
     */
    public $hasCpSection = true;

    /**
     * @var string
     */
    const ASSETS_NS_PREFIX = '@jimstrike/fbmcc/assets/dist';

    /**
     * @inheritdoc
     * 
     * @return void
     */
    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        if (!Craft::$app->getConfig()->getGeneral()->allowAdminChanges) {
            $this->hasCpSection = false;
        }

        // Custom initialization code goes here...

        // Register Components
        $this->setComponents([
            'fbmccService' => FbmccService::class,
        ]);

        // Register Twig extensions
        Craft::$app->view->registerTwigExtension(new FbmccTwigExtension());

        // Register variables
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set($this->handle, FbmccVariable::class);
            }
        );

        // Register our CP routes
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
            $event->rules['fbmcc'] = 'fbmcc/default/index';
            $event->rules['fbmcc/index'] = 'fbmcc/default/index';
            $event->rules['fbmcc/getting-started'] = 'fbmcc/default/getting-started';
            $event->rules['fbmcc/settings'] = 'fbmcc/default/settings';
            $event->rules['fbmcc/preview'] = 'fbmcc/default/preview';
        });

        // Do something after we're installed (nothing to do yet)
        /*Event::on(Plugins::class, Plugins::EVENT_AFTER_INSTALL_PLUGIN, function (PluginEvent $event) {
            if ($event->plugin === $this) {
                // We were just installed
            }
        });*/

        // Insert SDK snippet (frontend) right after the opening of <body> tag
        Event::on(View::class, View::EVENT_BEGIN_BODY, function(Event $event) {
            $this->_sdkSnippet();
        });

        // Logging - We're loaded
        Craft::info(self::t('{name} plugin loaded', ['name' => $this->name]), __METHOD__);
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): array
    {
        $item = parent::getCpNavItem();

        $item['label'] = self::t('plugin.name');

        /*$item['subnav']['getting_started'] = [
            'label' => self::t('subnav.getting_started.label'),
            'url' => 'fbmcc/getting-started'
        ];

        if (Craft::$app->getUser()->getIsAdmin() && Craft::$app->getConfig()->getGeneral()->allowAdminChanges) {
            $item['subnav']['settings'] = [
                'label' => self::t('subnav.settings.label'),
                'url' => 'fbmcc/settings'
            ];
        }*/

        return $item;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     * 
     * @return Settings|null
     */
    protected function createSettingsModel()
    {
        $settings = new Settings();
        $settings->setRef(self::baseRequestUrlAndFullPath());
        
        return $settings;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsResponse()
    {
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('fbmcc/settings'));
    }

    // Helpers
	// =========================================================================

    /**
     * Plugin's t() method
     * Plugin::t('message to be translated')
     * 
     * @var string $message
     * @var array $params
     * 
     * @return string
     */
	public static function t(string $message, array $params = [], string $language = null): string
    {
        return Craft::t(self::$plugin->handle, $message, $params, $language);
    }

    /**
     * Base request  URL and full path
     * 
     * @return string
     */
    public static function baseRequestUrlAndFullPath(): string
    {
        //return \yii\helpers\Url::base(true) . Craft::$app->getRequest()->getUrl();
        return \rtrim(UrlHelper::baseRequestUrl(), '/') . '/' . \ltrim(Craft::$app->getRequest()->getFullPath(), '/');
    }

    /**
     * Assets ns prefix
     * 
     * @return string
     */
    public static function assetsNsPrefix(): string
    {
        return \rtrim(self::ASSETS_NS_PREFIX, '/');
    }

    /**
     * Asset base url
     * 
     * @return string
     */
    public static function assetsBaseUrl(): string
    {
        $nsPrefix = self::assetsNsPrefix();

        return Craft::$app->assetManager->getPublishedUrl($nsPrefix, false);
    }

    // Private methods
    // =========================================================================
    
    /**
     * Echo sdk snippet
     * 
     * @return void
     */
    private function _sdkSnippet(): void
    {
        $request = Craft::$app->getRequest();

        if ($request->getIsSiteRequest() && !$request->getIsConsoleRequest()) {
            $currentSite = Craft::$app->getSites()->getCurrentSite();

            if ($this->getSettings()->isEnabled($currentSite->id)) {
                echo $this->fbmccService->sdkSnippet($currentSite->id);
            }
        }
    }
}