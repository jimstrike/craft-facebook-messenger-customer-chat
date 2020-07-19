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

namespace jimstrike\fbmcc\controllers;

use Craft;
//use craft\web\View;
use craft\helpers\UrlHelper;
use craft\helpers\DateTimeHelper;

use yii\web\Response;
use yii\web\ForbiddenHttpException;

use jimstrike\fbmcc\Plugin;

/**
 * @author  Dhimiter Karalliu
 * @package Messenger Customer Chat
 * @since   1.0.0
 */
class DefaultController extends BaseController
{

    // Protected Properties
    // =========================================================================

    /**
     * The actions must be in 'kebab-case'
     * @var bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = false;

    // Public Methods
    // =========================================================================

    public function init()
    {
        $this->requireAdmin();

        parent::init();
    }

    /**
     * Index action
     *
     * @return Response
     */
    public function actionIndex(): Response
    {
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('fbmcc/settings'));
    }

    /**
     * Getting started action
     *
     * @return Response
     */
    public function actionGettingStarted(): Response
    {
        $pluginVars = $this->_pluginVars();

        return $this->renderTemplate(Plugin::$plugin->handle . '/_default/getting-started', [
            'plugin' => $pluginVars,
        ]);
    }

    /**
     * Preview action
     *
     * @return Response
     */
    public function actionPreview(): Response
    {
        $sites = Craft::$app->getSites();
        
        $primarySite = $sites->getPrimarySite();
        $siteHandle = Craft::$app->getRequest()->getQueryParam('site') ?? $primarySite->handle;
        $currentSite = $sites->getSiteByHandle($siteHandle) ?? $primarySite;

        $sdkLocale = Craft::$app->getRequest()->getQueryParam('locale') ?? Plugin::$plugin->getSettings()->getSdkLocale($currentSite->id);
        $sdkSnippet = Plugin::$plugin->fbmccService->sdkSnippet($currentSite->id, $sdkLocale);
        $sdkLocaleOptions = Plugin::$plugin->fbmccService->sdkLocaleOptions();

        $pluginVars = $this->_pluginVars();
        
        return $this->renderTemplate(Plugin::$plugin->handle . '/_default/preview', [
            'plugin' => $pluginVars,
            'sdkLocale' => $sdkLocale,
            'sdkSnippet' => $sdkSnippet,
            'sdkLocaleOptions' => $sdkLocaleOptions,
            'currentSite' => $currentSite
        ]);
    }

    /**
     * Settings action
     *
     * @return Response
     */
    public function actionSettings(): Response
    {
        $sites = Craft::$app->getSites();
        
        $primarySite = $sites->getPrimarySite();
        $siteHandle = Craft::$app->getRequest()->getQueryParam('site') ?? $primarySite->handle;
        $currentSite = $sites->getSiteByHandle($siteHandle) ?? $primarySite;
        $sdkLocaleOptions = Plugin::$plugin->fbmccService->sdkLocaleOptions();

        $pluginVars = $this->_pluginVars();

        return $this->renderTemplate(Plugin::$plugin->handle . '/_default/settings', [
            'plugin' => $pluginVars,
            'currentSite' => $currentSite,
            'sdkLocaleOptions' => $sdkLocaleOptions
        ]);
    }

    /**
     * Save Settings action
     *
     * @return Response|null
     */
    public function actionSaveSettings()
    {
        $this->requirePostRequest();
        $this->requireAdmin();

        $params = Craft::$app->getRequest()->getBodyParams();
        
        $siteId = $params['siteId'];
        $data = $params['settings'];

        $settings = Plugin::getInstance()->getSettings();

        foreach ($data as $field => $value) {
            $settings->$field = $settings->makeValue($field, $value, $siteId);
        }

        $pluginSettingsSaved = Craft::$app->getPlugins()->savePluginSettings(Plugin::getInstance(), $settings->toArray());

        Craft::$app->getSession()->setNotice(Plugin::t('global.settings.saved'));

        return $this->redirectToPostedUrl();
    }

    /**
     * Plugin properties and methods 
     * we want to be accessible in templates
     *
     * @return array
     */
    private function _pluginVars(): array
    {
        return [
            'name' => Plugin::$plugin->name,
            'handle' => Plugin::$plugin->handle,
            'settings' => Plugin::$plugin->getSettings()
        ];
    }
}
