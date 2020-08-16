<?php
/**
 * Messenger Customer Chat plugin for Craft CMS 3.x
 *
 * Let people start a conversation on your website and continue in Messenger. 
 * Allows your customers to interact with your business anytime with the same personalized, 
 * rich-media experience they get in Messenger.
 *
 * @link        https://github.com/jimstrike
 * @copyright   Copyright (c) 2020 Dhimiter Karalliu
 * @license     https://github.com/jimstrike/craft-facebook-messenger-customer-chat/blob/master/LICENSE.md
 */

/**
 * Messenger Customer Chat config.php
 *
 * This file exists only as a template for the Messenger Customer Chat settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to '<craft_project>/config' as 'fbmcc.php'
 * and make your changes there to override default settings.
 *
 * Once copied to '<craft_project>/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

 // Same settings on all sites
return [
    /**
     * Your Facebook Page ID
     * 
     * @var string 
     */
    'pageId' => 'FACEBOOK_PAGE_ID',

    /**
     * Enable plugin in all pages.
     * If your are planning to display the plugin by using the provided 
     * twig functions/variables, then this must be set to false.
     * 
     * @var bool
     */ 
    'enabled' => false,

    /**
     * Locale/language used in Facebook Messanger Customer Chat.
     * Locales not supported by Facebook will default to 'en_US'.
     * 
     * @var string
     */
    'sdkLocale' => 'en_US',

    /**
     * Theme color. 
     * E.g.: '#0084ff' (not white).
     * 
     * @var string
     */
    'themeColor' => '#0084ff',

    /**
     * Greeting displayed when logged in. 
     * Max 80 chars.
     * 
     * @var string 
     */
    'loggedInGreeting' => 'Hi! How can we help you?',

    /**
     * Greeting displayed when logged out. 
     * Max 80 chars.
     * 
     * @var string 
     */
    'loggedOutGreeting' => 'Hi! How can we help you?',

    /**
     * Greeting dialog display. 
     * Use one of the following:
     * 'hide', 'show' or 'fade'
     * 
     * @var string 
     */
    'greetingDialogDisplay' => 'hide',

    /**
     * Delay (in seconds) greeting dialog before appearing.
     * Use seconds.
     * 
     * @var int 
     */
    'greetingDialogDelay' => 3,

    /**
     * Whether to use code snippet copied directly 
     * from Facebook page settings instead of customizing it.
     * 
     * @var bool 
     */
    'useCodeSnippet' => false,

    /**
     * Code snippet copied directly from:
     * Facebook page → Settings → Messaging → Add Messenger to your website.
     * This code will be inserted if 'useCodeSnippet' is true and 'codeSnippet' is not empty.
     * 
     * @var string
     */
    'codeSnippet' => (string)\file_get_contents(CRAFT_BASE_PATH . '/templates/path/to/code_snippet'),
    
    /**
     * Display plugin by opening hours.
     * 
     * @var array|null 
     */
    'hours' => [
        // Sunday
        (0) => [
            // ...
            'closed' => true
        ],
        // Monday
        (1) => [
            'from' => [
                'time' => '08:00 AM',
                'timezone' => 'Europe/Berlin'
            ],
            'to' => [
                'time' => '04:00 PM',
                'timezone' => 'Europe/Berlin'
            ],
            'closed' => false
        ],
        // Other days ...
    ],

    /**
     * Display plugin only on the these craft sections.
     * 
     * @var array|null 
     */
    'sections' => [
        (1) => true,  // section with ID 1 (show)
        (2) => false, // section with ID 2 (don't show)
    ],
];

/*
// Multi-site settings
// return [
//     // Site with ID: 1
//     (1) => [
//         'pageId' => 'FACEBOOK_PAGE_ID',
//         // ...
//     ],

//     // Site with ID: 2
//     (2) => [
//         'pageId' => 'FACEBOOK_PAGE_ID',
//         // ...
//     ]
// ];
*/