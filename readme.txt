=== Shortcode Item Updated ===
Contributors: deckerweb
Tags: shortcode, last updated, modified date, custom post types
Requires at least: 6.7
Tested up to: 7.1.3
Requires PHP: 8.0
Stable tag: 2.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display the latest update of selected WordPress content with one flexible shortcode.

== Description ==

A lightweight shortcode for one item, the current loop item or the newest modification across a fixed ID list. Includes optional time-gap conditions, relative dates, semantic time markup and unwrapped text. No settings page or custom editor block.

This package is distributed on GitHub and bundles deckerweb Updater 2.1.0 and Library 0.8.1. It is not a WordPress.org distribution. The shortcode makes no external requests. GitHub update checks and deliberate package installations use GitHub; the Library's optional online catalog is initially off. No telemetry.

Full parameter reference and copyable examples: https://github.com/deckerweb/shortcode-item-updated/blob/master/README.md
German documentation: https://github.com/deckerweb/shortcode-item-updated/blob/master/README-de.md

== Installation ==

1. In Plugins > Add New > Upload Plugin, upload the supplied 2.3.0 installable ZIP.
2. Activate the plugin.
3. Add [siu-item-updated post_id="123" show_label="yes"] in a shortcode-capable content area; replace 123 with a public item's ID.
4. For the alternative standalone snippet, follow docs/SNIPPETS.md. Activate only one distribution.

== Frequently Asked Questions ==

= Which content can I use? =

Published, publicly viewable posts, pages and custom post type items without a password. Drafts, private content, revisions, deleted posts and nonpublic types are skipped for everyone, including administrators.

= Which date does it show? =

WordPress's saved modification time, formatted in the site's timezone. It does not track the modification of an uploaded PDF, inspect remote files or distinguish editorial changes from routine saves.

= How do I reference another item? =

Use `post_id="123"`, or `post_ids="123,456,789"` for the newest eligible update. Replace the example IDs with IDs from your site. Outside a post loop, always specify a source.

= Why is nothing displayed? =

Check the ID and public visibility, the modification timestamp, `only_if_updated` and its threshold. A malformed ID list or invalid threshold fails without output. Both date and time disabled also produce no output.

= Will it work in my builder? =

Use the builder's shortcode element or a field that actually executes WordPress shortcodes. A field that treats the shortcode as literal text will not render it. In the block editor use the existing Shortcode block; this plugin adds no Gutenberg block.

= Can I use the snippet instead? =

Yes. It has the same shortcode features and supports PHP 7.4. Use one installation method. The standalone snippet contains no file-based Library, plugin updater or translation catalogs; updates are manual.

= Why can relative dates or another item's date look stale? =

Page caches store rendered output. Relative wording ages, and updating a referenced item may not purge the page displaying it. Purge that page or use your cache's dependency/exclusion features. Absolute output remains the default.


Full FAQ: https://github.com/deckerweb/shortcode-item-updated/blob/master/docs/FAQ.md

== Changelog ==

= 2.3.0 — 2026-10-08 =

* New: Conditional update display with a minimum time gap.
* New: Latest update from multiple explicit post IDs.
* New: Semantic time markup, unwrapped text and relative dates.
* Improved: Documented recipes, complete parameter reference and standalone snippet distributions.
* Improved: Page-language labels, individual CSS classes and validated HTML wrappers.
* Fixed: The de/us shortcuts now show the source update date.
* Fixed: Restored the default label and suppressed invalid or unavailable sources.
* Fixed: Correct site-timezone formatting and separator/time suffix behavior.
* Misc: Bundled deckerweb Updater 2.1.0 and Library 0.8.1.
* Misc: The plugin requires PHP 8.0; the standalone snippet still supports PHP 7.4.

= 2.2.0 — 2025-03-28 =

* Improved: Class-based shortcode implementation.
* Misc: Plugin metadata links, snippet download and updated German translations.

= 2.1.0 — 2025-03-15 =

* New: German label defaults without separate translation files.
* Improved: Alternative use in PHP snippet managers.

= 2.0.0 — 2025-03-14 =

* Improved: Refreshed the lightweight shortcode plugin.
* Misc: Removed obsolete Shortcake integration; updated translations and version numbering.

= 2016-08-19 — 2016-08-19 =

* Improved: Documentation, translations and output handling.

= 2016-08-12 — 2016-08-12 =

* New: German and ISO-style date shortcuts; Shortcake integration.
* Improved: Translations and WordPress 4.6 compatibility.

= 2015-05-26 — 2015-05-26 =

* New: Optional labels, translated separators and current-loop post defaults.
* Fixed: Translation-loader variable.
* Improved: Shortcode parameters and installation documentation.

== Upgrade Notice ==

= 2.3.0 =
The plugin now requires PHP 8.0. The standalone snippet still supports PHP 7.4. Private/password-protected/nonpublic sources are no longer displayed. See migration notes in docs/USER-GUIDE.md.
