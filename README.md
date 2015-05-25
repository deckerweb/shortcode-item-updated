# Shortcode Item Updated

Shortcode for showing the last updated date (and/or time) of an item of a post type.

Example - default:
[siu-item-updated post_id="363"]
Will show only date (as set in WordPress' General Settings)

Example - custom:
[siu-item-updated post_id="363" show_time="yes" show_sep="yes"]
- Will also show the time
- Will show a "separator" string between date and time values (sep)

Parameters:
post_id = ID of the post of any (public) post type
date_format = PHP date format (defaults to "General Settings")
time_format = PHP time format (defaults to "General Settings")
show_time = "yes" will display time also (defaults to "no")
show_sep = "yes" will display separator string (defaults to "no")
sep = optional separator string, defaults to "@"
class = additional custom class for the wrapper
wrapper = defaults to "span", but you may also use "div" or even "p" or any other HTML5 wrapper...