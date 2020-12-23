# Messenger Customer Chat plugin for Craft CMS 3.x

Let people start a conversation on your website and continue in Messenger. Allows your customers to interact with your business anytime with the same personalized, rich-media experience they get in Messenger.

![Screenshot](resources/images/icon-192.png)

## Overview

This plugin allows you to interact with your customers using Messenger by integrating it on your Craft website.

To see and reply to those messages, simply use the same messaging tools you use for your Facebook messaging, on desktop at [facebook.com](https://www.facebook.com), Facebook Page Manager App (available on iOS and Android), or by adding your page account to Messenger.

You can learn more about Messenger Customer Chat by clicking [here](https://developers.facebook.com/docs/messenger-platform/discovery/customer-chat-plugin).

## Key features

- No disconnection: Using the plugin creates a long-lived thread between you and your customers in Messenger. Customers can start a chat on your website and carry on in their mobile device with the Messenger app.
- Chat transcripts are automatically created in the customer’s Messenger account.
- The familiar, modern interface of Messenger builds trust and encourages your customers to engage with you.
- You can use the same Inboxes on desktop and mobile that you use to manage your Facebook page messaging.
- You can set up hours of availability, auto replies and FAQ to serve customers when you’re not available.

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

In order to use the Messenger Customer Chat plugin, you will need to have a published Facebook Page. You can find a list of your Facebook Pages by following this [link](https://www.facebook.com/bookmarks/pages). If you do not have a Facebook Page, you can create one for free [here](https://www.facebook.com/pages/creation/?ref_type=pages_you_admin).

## Installation

### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for "Messenger Customer Chat". Then click on the "Install" button.

### With Composer

```bash
# Go to project directory
cd /path/to/my/craft-project

# Tell Composer to load the plugin
composer require jimstrike/craft-facebook-messenger-customer-chat

# Tell Craft to install the plugin
./craft plugin/install fbmcc
```

## Configuring Messenger Customer Chat plugin

After installling the plugin go to its settings in Control Panel → Customer Chat or Control Panel → Settings → Messenger Customer Chat. Read "Getting started" section before customizing and enabling the plugin on your site.

### Whitelisting your domain

Before enabling and using "Messenger Customer Chat" you must have a Facebook Page and whitelist your domain on your Facebook Page settings.

**Page admins do the following:**

- Login to Facebook (if your are not already logged in).
- Go to [facebook.com/bookmarks/pages](https://www.facebook.com/bookmarks/pages) to list your pages.
- Go to your page.
- Click **Settings** on the left under "Manage Page".
- Click **Advanced Messaging** on the left under "Page Settings".
- Add your domain under **Whitelisted Domains** section and click Save.

### Find your Facebook Page ID

**Page admins do the following:**

- Login to Facebook (if your are not already logged in).
- Go to [facebook.com/bookmarks/pages](https://www.facebook.com/bookmarks/pages) to list your pages.
- Go to your page.
- Click **Settings** on the left under "Manage Page".
- Click **Messaging** on the left under "Page Settings".
- Scroll down to **Your Messenger URL** section.
- You will find your Messenger link that looks something like this:
`m.me/543210123456789`. The numeric part of the URL after `m.me/` is your Facebook Page ID.

**If the part of the the URL after `m.me/` is not numeric then try this:**

- Go to frontpage of your Facebook Page.
- Right click anywhere on your screen and then choose View Page Source.
- On page source search for (Ctrl/Cmd + F) `page_id`.
- The numeric part after `page_id=` is your Facebook Page ID.

### Let people chat as a Guest

**Page admins do the following:**

- Login to Facebook (if your are not already logged in).
- Go to [facebook.com/bookmarks/pages](https://www.facebook.com/bookmarks/pages) to list your pages.
- Go to your page.
- Click **Settings** on the left under "Manage Page".
- Click **Messaging** on the left under "Page Settings".
- Scroll down to **Add Messenger to your website** section. and then click **Get Started**.
- A modal window will pop up.
- Toggle **Guest Chat** on/off under **Guest Chat**.
- Click **Next** until you finish and close the modal window.

### Using "Messenger Customer Chat" on your site

Go to "Messenger Customer Chat" site settings, provide your Facebook Page ID and/or code snippet copied from "Facebook page → Settings → Messaging → Add Messenger to your website" and then save. Preview it and adjust its settings until your are satisfied. Enable it.

## Advanced configuration

> for Craft developers

### Override settings

Copy plugin's `src/config.php` to your project's `config` folder as `fbmcc.php` and make your changes there to override default settings.

### Print SDK snippet in twig templates

```twig
{##
 # @param int|null siteId (defaults to current site ID)
 # @param string locale (defaults to plugin´s chat dialog locale setting)
 #
 # @return string (Facebook Messenger Customer Chat html snippet)
 #}
fbmcc_sdk_snippet(int siteId = null, string locale = '')
craft.fbmcc.sdkSnippet(int siteId = null, string locale = '')
```

> **Note:**
Snippet will only print if plugin is disabled in Customer Chat → Site Settings or `config/fbmcc.php` file.

### Check if enabled in twig templates

```twig
{##
 # @param int|null siteId (defaults to current site ID)
 #
 # @return bool
 #}
fbmcc_is_enabled(int siteId = null)
craft.fbmcc.isEnabled(int siteId = null)
```

> **Note:**
It will only check against "Opening hours". Checks against "Enabled" or "Sections" won't be made as you are in control of your twig templates.

### Twig examples

```twig
{{ fbmcc_sdk_snippet()|raw }}
{{ fbmcc_sdk_snippet(currentSite.id, 'en_GB')|raw }}

{# or #}

{{ craft.fbmcc.sdkSnippet()|raw }}
{{ craft.fbmcc.sdkSnippet(currentSite.id, 'en_GB')|raw }}

{# check if enabled first #}

{% if fbmcc_is_enabled() %}
    {{ fbmcc_sdk_snippet()|raw }}
{% endif %}

{# or #}

{% if craft.fbmcc.isEnabled(currentSite.id) %}
    {{ craft.fbmcc.sdkSnippet(currentSite.id, 'de_DE')|raw }}
{% endif %}
```

> **Note:**
Snippet in the above examples will only print if plugin is disabled in Customer Chat → Site Settings or `config/fbmcc.php` file. Otherwise a html comment saying that SDK snippet is already initiated will be printed on page source.

## Frequently Asked Questions

### Where can I find more information on Messenger Customer Chat?

You can find more information on the [Messenger Customer Chat page](https://developers.facebook.com/docs/messenger-platform/discovery/customer-chat-plugin).

### What do I need before setting up Messenger Customer Chat on my website?

You will need to have a published Facebook Page, its Page ID and be logged into Facebook on your computer or device.

### How does the plugin work?

The plugin is a snippet of JavaScript code that runs on your Craft website. There will be a small Messenger chat bubble that loads with your website in the lower right corner. Your customers can click on it at anytime and message you. It works on both mobile and desktop. You can find more information in Facebook Developer Docs on the [Messenger Customer Chat page](https://developers.facebook.com/docs/messenger-platform/discovery/customer-chat-plugin).

### Where can I see all my messages?

On the desktop, you can see all messages in your Page Inbox. Navigate to your Facebook Page on Facebook and click on 'Inbox' at the top. On mobile, you can download the Facebook Pages Manager app and navigate to Inbox. You can also link your Page account to your Messenger app and access all your messages there.

### What permissions do I need on a Page to enable the Messenger customer chat feature?

You need be an administrator of the Page.

### Can I see messaging analytics for my Facebook page?

On the desktop, go to your Facebook page, and click on Insights on the Navigation bar. On the left hand side, click on Messaging to see your messaging analytics.

### Why are some of my users seeing an additional confirmation window after clicking "Chat Now"?

A "Continue As" popup is shown infrequently for security purposes. Most signed-in users will only have to click the plugin itself to opt in, without having to re-confirm. Additionally, as more users message you via the plugin, the "Continue As" popup will be shown less often.

## Troubleshooting tips

If you're having trouble getting the plugin to render properly, try the tips below:

- If you see a console error like "Refused to display *in a frame because an ancestor violates the following Content Security Policy directive:*", check that the domain of the page the plugin is being rendered on has been whitelisted and that your Facebook Page ID is being entered correctly. Also make sure you didn't set the Referrer-Policy header to no-referrer.

- The Firefox Facebook Container Add-On prevents the plugin from showing up. Remove the add on if you want the plugin to render.

- Firefox desktop private browsers (version 63 and above) and Firefox mobile browsers block content tracking by default which will prevent the plugin from rendering. Turn off content blocking (click the gray shield in the search bar) to see the plugin render.