# Shortcode Item Updated

**Simple & lightweight:** Flexible Shortcode for showing the **last updated** date (and/or time) of an item of a post type.

### Tested Compatibility
- **WordPress**: 6.7.2
- **PHP**: 8.3+
- Requires at least: WP 6.7 / PHP 7.4

---

[Support Project](#support-the-project) | [Installation](#installation) | [Description](#description) | [Features](#features) | [Usage](#usage--examples) | [Shortcode Parameters](#shortcode-parameters) | [Bonus Shortcuts](#shortcode-parameters--bonus-shortcuts) | [Widgets](#widget-usage) | [Templates](#template-usage-developers) | [Filters](#plugin-filters--developers) | [Changelog](#changelog--version-history) | [Plugin Scope / Disclaimer](#plugin-scope--disclaimer)

---

## Support the Project

If you find this project helpful, consider showing your support by buying me a coffee! Your contribution helps me keep developing and improving this plugin.

Enjoying the plugin? Feel free to treat me to a cup of coffee ☕🙂 through the following options:

- [![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/W7W81BNTZE)
- [Buy me a coffee](https://buymeacoffee.com/daveshine)
- [PayPal donation](https://paypal.me/deckerweb)

---

## Installation

**Quick Install – as Plugin**
1. **Download ZIP:** [**shortcode-item-updated.zip**](https://github.com/deckerweb/shortcode-item-updated/releases/latest/download/shortcode-item-updated.zip)
2. Upload via WordPress Plugins > Add New > Upload Plugin
3. Once activated, you can use the shortcode – [see Usage below](#usage--examples)

**Alternative: Use as Code Snippet**
1. Below, download the appropriate snippet version
2. activate or deactivate in your snippets plugin

[**Download .json**](https://github.com/deckerweb/shortcode-item-updated/releases/latest/download/ddw-shortcode-item-updated.code-snippets.json) version for: _Code Snippets_ (free & Pro), _Advanced Scripts_ (Premium), _Scripts Organizer_ (Premium)
--> just use their elegant script import features
--> in _Scripts Organizer_ use the "Code Snippets Import"

For all other snippet manager plugins just use our plugin's main .php file [`shortcode-item-updated.php`](https://github.com/deckerweb/shortcode-item-updated/blob/master/shortcode-item-updated.php) and use its content as snippet (bevor saving your snippet: please check for your plugin if the opening php tag needs to be removed or not!).

---

## Description

Very useful to output the updated date of a custom post type item on a regular page, within a widget or anywhere else... :-)

*Backstory:* I needed something like that for a client project to display the last updated date/time of a download post type on a regular content page. Since I knew, I would need the same functionality for an other existing site and maybe in future too, I just build it into a "general plugin" rather than a simple code snippet...! There were no existing plugins/ solutions out there (at least I didn't found them yet...) that fitted my needs so I had to build it myself ...

---

## Features

* Can be used in post/ page content (also post types), text widgets and also for page builder plugins etc.
* Supports date/ time format from WordPress settings by default
* Genesis Framework: easily use this in various footer/ simple edit plugins (don't forget post ID) or with the awesome Blox free/pro plugin and similar - it's that easy!
* Developer friendly: customize or extend via filters, styles and styling-friendly CSS classes
* Fully internationalized and translateable! -- German translations already packaged!
* Developed with security in mind: proper WordPress coding standards and security functions - escape all the things! :)

---

## Usage - Examples

**Example - default:**

```
[siu-item-updated]
```

Will show only date (as set in WordPress Settings > General) for the current displayed post (ID pulled via `get_the_ID()`) - has to be used within the Loop in this default state.

**Example - custom 1:**

```
[siu-item-updated post_id="363"]
```

Will show only date (as set in WordPress Settings > General) for the item of a post type with post ID "363"

**Example - custom 2:**

```
[siu-item-updated post_id="363" show_time="yes" show_sep="yes"]
```

- Will also show the time
- Will show a "separator" string between date and time values (sep)

---

## Shortcode Parameters

| Parameter | Description |  Default | Translateable
|:----------:|:-------------|:------:|:-------------:|
| `post_id` | ID of the post of any (public) post type | `get_the_ID()` = ID of the current displayed post -- in this case, Shortcode has to be used within the Loop!) | -- |
| `date_format` | PHP date format | setting as in WordPress Settings > General | (automatically via `date_i18n()` ) |
| `time_format` | PHP time format | setting as in WordPress Settings > General | (automatically via `date_i18n()` ) |
| `show_date` | will display updated date, by default visible! | `yes` | -- |
| `show_time` | `yes` will display time also | `no` | -- |
| `show_sep` | `yes` will display separator string | `no` | -- |
| `sep` | optional separator string | `&#x00A0;@` (that is a space plus @-symbol, like so: " @") | yes! |
| `show_label` | `yes` will display a label string before date & time values | `no` | -- |
| `label_before` | label string before date & time values | `Last updated:` (by default not shown) | yes! |
| `label_after` | label string after time value -- useful for languages like German to get time values like "9.40 Uhr" (see the "Uhr" string) | "" (not displayed) | -- |
| `class` | additional custom class for the wrapper | "" (none) | -- |
| `wrapper` | HTML wrapper element - any HTML5 wrapper is possible | `span` | -- |

---

## Shortcode Parameters - Bonus Shortcuts

* Shortcut for German date format to get output `d.m.Y` like `12.08.2016` (Usage: `date_format="de"`)
* Shortcut for U.S. date format to get output `Y-m-d` like `2016-08-12` (Usage: `date_format="us"`)

---

## Widget Usage

NO LONGER recommended! Widgets are outdated, I do not recommend them (and _didn't_ use them myself for lots of years already!).

* Shortcode could be used with "Text" widget -- if you have shortcodes for Widgets activated (possible via this filter: `add_filter( 'widget_text', 'do_shortcode' );` )
* If using extended/ advanced text widget plugins, the Shortcode usage then is already enabled automatically... :-)
* NOTE: You have to provide a unique post ID if using this outside of the Loop!

---

## Template Usage (Developers)

* Use WordPress' global "do_shortcode()" function as a template function, like so:
* `<?php do_shortcode( '[siu-item-updated post_id="123" show_label="yes"]' ); ?>` --> parameters apply like for regular Shortcode usage (see above)!
* NOTE: You have to provide a unique post ID if using this outside of the Loop!

---

## Plugin Filters (Developers)

* `siu_filter_shortcode_defaults` --> filter default values of Shortcode parameters
* `siu_filter_shortcode_item_updated` --> filter Shortcode output
* `shortcode_atts_siu-item-updated` --> lets you add new Shortcode parameters for example (= WordPress' default Shortcode filter `shortcode_atts_{$shortcode}` )

---

## Changelog – Version History

#### 🎉 v2.1.0 (2024-03-15)

* Detection for German locales to run plugin without translation files for German sites – NOTE: you can use translations as before as well, nothing changes here. The two strings can also be modified in the Shortcode itself.
* This (single file) plugin can alternatively be used as "code snippet version". Works perfectly fine in _Code Snippets_ (free & Pro), _FluentSnippets_, _WPCode_, _Advanced Scripts_, _Scripts Organizer_ and _WPCodeBox_ – NOTE: Use that way at your own risk and you have to care for updates yourself. – For download see under [Installation](#installation)
* Few code improvements


#### 🎉 v2.0.0 (2025-03-14)

* Updated plugin after 9 years, yeah! – Brought back to its basic beauty. (How it should be!)
* Removed additional translation loading – no longer needed; WordPress does now all we need by itself (yeah!)
* Removed support for third-party plugin "Shortcode UI (Shortcake)" which is no longer maintained, and, to be honest, no longer needed
* Brought changelog to Readme file here, CHANGES.md file removed
* Changed plugins versioning from date-based to version number based, which makes more sense here
* Changed to version v2.0.0 just to express the fresh restart
* Updated .pot file, plus packaged German translations


#### 🎉 Version 2016-08-19

* Updated Readme file
* Updated .pot file plus German translations
* Improved security and polishing of plugin


#### 🎉 Version 2016-08-12

* Added Shortcut for German date format to get output `d.m.Y` like `12.08.2016` (Usage: `date_format="de"`)
* Added Shortcut for U.S. date format to get output `Y-m-d` like `2016-08-12` (Usage: `date_format="us"`)
* Added support for plugin "Shortcake" to give Shortcode an UI :-)
* Updated and corrected translations
* Approved compatibility with WordPress 4.6


#### 🎉 Version 2015-05-26

* Bugfix for variable name in translation loader
* Refined Shortcode parameters
* Added label "Last updated:" (defaults to not being shown!)
* Made separator string translateable (makes sense for a lot of languages to have a "sane default" then)
* Added translations to the plugin; plus default .pot file and German translations
* Added "get_the_ID()" as the default value of "post_id" parameter -- Note: in this case the Shortcode could be used as is [siu-item-updated] but within the Loop!
* Added CHANGES.md file for changelogs
* Added installations instructions to readme
* Improved readme overall
* Minor formatting stuff


#### 🎉 Version 2015-05-25

* Initial release on GitHub

---

Copyright © 2015-2025 David Decker – DECKERWEB.de
