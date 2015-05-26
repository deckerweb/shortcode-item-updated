# Shortcode Item Updated

Shortcode for showing the last updated date (and/or time) of an item of a post type.

### Usage - Examples:

**Example - default:**

```
[siu-item-updated]
```

Will show only date (as set in WordPress Settings > General) for the current displayed post (ID pulled via "get_the_ID()") - has to be used within the Loop in this default state.

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

### Shortcode Parameters:

- post_id = ID of the post of any (public) post type (defaults to ID of the current displayed post -- in this case, Shortcode has to be used within the Loop!)
- date_format = PHP date format (defaults to setting as in WordPress Settings > General)
- time_format = PHP time format (defaults to setting as in WordPress Settings > General)
- show_time = "yes" will display time also (defaults to "no")
- show_sep = "yes" will display separator string (defaults to "no")
- sep = optional separator string, defaults to "&#x00A0;@" (that is a space plus @-symbol, like so: " @") -- is translateable!
- show_label = "yes" will display a label string before date & time values (defaults to "no")
- label_before = label string before date & time values, defaults to "Last updated:" (by default not shown) -- is translateable!
- label_after = label string after time value, defaults to "" (not displayed) - useful for languages like German to get time values like "9.40 Uhr" (see the "Uhr" string)
- class = additional custom class for the wrapper
- wrapper = defaults to "span", but you may also use "div" or even "p" or any other HTML5 wrapper...

### Widget Usage:

* Shortcode could be used with "Text" widget -- if you have shortcodes for Widgets activated (possible via this filter: `add_filter( 'widget_text', 'do_shortcode' );` )
* If using extended/ advanced text widget plugins, the Shortcode usage then is already enabled automatically... :-)
* NOTE: You have to provide a unique post ID if using this outside of the Loop!

### Template Usage (Developers):

* Use WordPress' global "do_shortcode()" function as a template function, like so:
* do_shortcode( '[siu-item-updated post_id="123" show_label="yes"]' ); --> parameters apply like for regular Shortcode usage!
* NOTE: You have to provide a unique post ID if using this outside of the Loop!

### Plugin Filters (Developers):

* `siu_filter_shortcode_defaults` --> filter default values of Shortcode parameters
* `siu_filter_shortcode_item_updated` --> filter Shortcode output
* `shortcode_atts_siu-item-updated` --> lets you add new Shortcode parameters for example (= WordPress' default Shortcode filter `shortcode_atts_{$shortcode}` )

### Translations:

* textdomain: `shortcode-item-updated`
* default `.pot` file included
* German translations included (`de_DE`)
* translateable are the plugin title, plugin description, label before string and the separator string
* plugin path for translations: `wp-content/plugins/shortcode-item-updated/languages/shortcode-item-updated-de_DE.mo`
* global WordPress lang dir path for translations: `wp-content/languages/plugins/shortcode-item-updated-de_DE.mo` ---> NOTE: if this file/path exists it will be loaded at higher priority than the plugin path! This is the recommended path & way to store your translations!

Copyright (c) 2015 David Decker - DECKERWEB.de