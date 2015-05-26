# Shortcode Item Updated

Shortcode for showing the last updated date (and/or time) of an item of a post type.


## Plugin Installation:

**Manual Upload**
* download current .zip archive from master branch here, URL: [https://github.com/deckerweb/shortcode-item-updated/archive/master.zip](https://github.com/deckerweb/shortcode-item-updated/archive/master.zip)
* unzip the package, then **rename the folder to `shortcode-item-updated`**, then upload renamed folder via FTP to your WordPress plugin directory
* activate the plugin

**Via "GitHub Updater" Plugin** *(recommended!)*

* Install & activate the "GitHub Updater" plugin, get from here: [https://github.com/afragen/github-updater](https://github.com/afragen/github-updater)
* Recommended: set your API Token in the plugin's settings
* Go to "Settings > GitHub Updater > Install Plugin"
* Paste the GitHub URL `https://github.com/deckerweb/shortcode-item-updated`
* Install & activate the plugin

**Updates**
* Are done via the plugin "GitHub Updater" (see above) - leveraging the default WordPress update system!
* Setting your GitHub API Token is recommended! :)


## Usage - Examples:

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


## Shortcode Parameters:

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


## Widget Usage:

* Shortcode could be used with "Text" widget -- if you have shortcodes for Widgets activated (possible via this filter: `add_filter( 'widget_text', 'do_shortcode' );` )
* If using extended/ advanced text widget plugins, the Shortcode usage then is already enabled automatically... :-)
* NOTE: You have to provide a unique post ID if using this outside of the Loop!


## Template Usage (Developers):

* Use WordPress' global "do_shortcode()" function as a template function, like so:
* `<?php do_shortcode( '[siu-item-updated post_id="123" show_label="yes"]' ); ?>` --> parameters apply like for regular Shortcode usage (see above)!
* NOTE: You have to provide a unique post ID if using this outside of the Loop!


## Plugin Filters (Developers):

* `siu_filter_shortcode_defaults` --> filter default values of Shortcode parameters
* `siu_filter_shortcode_item_updated` --> filter Shortcode output
* `shortcode_atts_siu-item-updated` --> lets you add new Shortcode parameters for example (= WordPress' default Shortcode filter `shortcode_atts_{$shortcode}` )


## Translations:

* Used textdomain: `shortcode-item-updated`
* Default `.pot` file included
* German translations included (`de_DE`)
* Currently translateable are the plugin title, plugin description, label before string and the separator string
* Plugin's own path for translations: `wp-content/plugins/shortcode-item-updated/languages/shortcode-item-updated-de_DE.mo`
* *Recommended:* Global WordPress lang dir path for translations: `wp-content/languages/plugins/shortcode-item-updated-de_DE.mo` ---> *NOTE: if this file/path exists it will be loaded at higher priority than the plugin path! This is the recommended path & way to store your translations as it is update-safe and allows for custom translations!*
* Recommended translation tools: *Poedit Pro v1.8+* or *WordPress Plugin "Loco Translate"* or *your IDE/ Code Editor* or *old WordPress "Codestyling Localization"* (for the brave who know what they are doing :) )


## Changelog:

See plugin file [CHANGES.md here](https://github.com/deckerweb/shortcode-item-updated/blob/master/CHANGES.md)

Copyright (c) 2015 David Decker - DECKERWEB.de