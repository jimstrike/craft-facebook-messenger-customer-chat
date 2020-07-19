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

/**
 * Translation: en 
 */
return [

    // PLUGIN
    // =========================================================================

    'plugin.name' => 'Customer Chat',

    // GLOBAL
    // =========================================================================

    'global.show' => 'Show',
    'global.hide' => 'Hide',
    'global.fade' => 'Fade',
    'global.preview' => 'Preview',
    'global.settings.saved' => 'Settings saved.',
    'global.save' => 'Save',

    // SUBNAV
    // =========================================================================

    'subnav.getting_started.heading' => 'Documentation',
    'subnav.settings.heading' => 'Site settings',
    'subnav.preview.heading' => 'Preview',

    'subnav.getting_started.label' => 'Getting started',
    'subnav.preview.label' => 'Preview',
    'subnav.settings.label' => 'Settings',

    // SETTINGS
    // =========================================================================

    // Settings label
    'settings.page_id.label' => 'Facebook Page ID',
    'settings.enabled.label' => 'Enable {plugin.name} on all pages?',
    'settings.sdk_locale.label' => 'Chat dialog locale',
    'settings.theme_color.label' => 'Theme color',
    'settings.logged_in_greeting.label' => 'Logged in greeting',
    'settings.logged_out_greeting.label' => 'Logged out greeting',
    'settings.greeting_dialog_display.label' => 'Greeting dialog display',
    'settings.greeting_dialog_delay.label' => 'Greeting dialog delay',
    'settings.hours.label' => 'Opening hours',
    'settings.hours.option.opening_time.label' => 'Opening time',
    'settings.hours.option.closing_time.label' => 'Closing time',
    'settings.hours.option.closed.label' => 'Closed',
    'settings.sections.label' => 'Sections',

    // Settings instructions
    'settings.page_id.instructions' => 'The ID of your Facebook Page.',
    'settings.enabled.instructions' => 'Determines if the plugin should be enabled on your site. You can preview the plugin before enabling it.',
    'settings.sdk_locale.instructions' => 'Determines the language of the chat dialog which will fallback to English (US) if chosen locale is not supported by Facebook.',
    'settings.theme_color.instructions' => 'The color to use as a theme for the plugin, including the background color of the customer chat plugin icon and the background color of any messages sent by users. Supports any hexadecimal color code with a leading number sign (e.g. #0084FF), except white. Facebook highly recommends you choose a color that has a high contrast to white.',
    'settings.logged_in_greeting.instructions' => 'The greeting text that will be displayed if the user is currently logged in to Facebook. Maximum 80 characters.',
    'settings.logged_out_greeting.instructions' => 'The greeting text that will be displayed if the user is currently not logged in to Facebook. Maximum 80 characters.',
    'settings.greeting_dialog_display.instructions' => 'Sets how the greeting dialog will be displayed.',
    'settings.greeting_dialog_delay.instructions' => 'Sets the number of seconds of delay before the greeting dialog is shown after the plugin is loaded. This can be used to customize when you want the greeting dialog to appear.',
    'settings.hours.instructions' => 'Determines when the `{plugin.name}` must be displayed (if the criteria are met) by combining the opening hours below taking precedence over the `Enabled` setting when it is turned on.',
    'settings.sections.instructions' => 'Determines whether `{plugin.name}` must be displayed only on selected sections or all sections if none selected.',

    // Settings default values
    'settings.logged_in_greeting.default.value' => 'Hi! How can we help you?',
    'settings.logged_out_greeting.default.value' => 'Hi! How can we help you?',

    // Settings errors
    'settings.page_id.errors.blank' => 'Invalid Facebook Page ID.',

    // Settings warnings
    'settings.config.warning' => 'This is being overridden by the `{setting}` setting in the `{file}` file.',

    // PREVIEW
    // =========================================================================

    'preview.with.site' => 'Preview with site',
    'preview.with.locale' => 'Preview with locale',
    'preview.not.supported.locale.default' => 'Not supported locales will default to {locale}.',
    'preview.invalid.facebook.page.id' => 'Invalid Facebook Page ID.',

    // PAGE
    // =========================================================================

    'page.getting_started.title' => 'Getting started',
    'page.advanced_config.title' => 'Advanced config',
    'page.settings.title' => 'Settings',

    // TEXT
    // =========================================================================

    // EXCEPTION
    // =========================================================================

    'exception.forbidden.access' => 'You are not allowed to access this page.',
    'exception.forbidden.action' => 'You are not allowed to perform this action.',

];