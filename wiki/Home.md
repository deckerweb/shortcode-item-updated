# Shortcode Item Updated

![Shortcode Item Updated](https://raw.githubusercontent.com/deckerweb/shortcode-item-updated/master/assets/banner-github-en.png)

[Deutsch](Home-de.md) · [User guide](USER-GUIDE.md) · [FAQ by topic](FAQ.md)

Display the update date of a selected post, page or custom post type item wherever a WordPress shortcode is rendered. A download page can show the date of its separate download entry; a document overview can show the newest update across several items.

**Version 2.3.0** · WordPress **6.7+** · Plugin: PHP **8.0+** · Standalone snippet: PHP **7.4+**

[At a glance](#at-a-glance) · [Installation](#installation) · [Examples](#examples) · [Parameters](#shortcode-parameters) · [FAQ](#faq) · [Changelog](#changelog)

## At a glance

- One shortcode: `[siu-item-updated]`.
- One explicit item, the current loop item or the latest update from a fixed list of IDs.
- Optional update threshold, relative dates, semantic time markup and unwrapped text.
- Site date/time defaults, custom labels and reusable CSS classes.
- No settings page, automatic content insertion or frontend scripts.
- Installable plugin with deckerweb Updater 2.1.0 and Library 0.8.1; a standalone snippet is also available.

## Installation

Download the installable ZIP supplied with version 2.3.0. In **Plugins → Add New → Upload Plugin**, upload it and activate. There are no shortcode settings to configure.

For an update from 2.2.0, back up your site and install the ZIP as a replacement. PHP 8.0 is now required for the plugin. Existing shortcode names, attributes and filters remain; see the [migration notes](USER-GUIDE.md#migration-from-220).

For the snippet alternative, use the [manager-specific instructions](SNIPPETS.md). Run the PHP snippet everywhere. Do not activate both distributions.

## Examples

IDs below are examples. Replace them with IDs from your own site. Sample output assumes a stored modification of June 3, 2020 at 12:34 in the site's timezone.

### Current article

Inside a reliable post loop, use:

```text
[siu-item-updated show_label="yes"]
```

The site's date format and page-language label are used automatically. With an English date format of `F j, Y`, the output is **Last updated: June 3, 2020**.

### A download's date on its landing page

The landing page and download entry have different IDs. Reference the download entry explicitly:

```text
[siu-item-updated post_id="123" date_format="F j, Y" show_label="yes" label_before="Download updated:"]
```

Output: **Download updated: June 3, 2020**. This is the WordPress entry's update date, not the filesystem timestamp of its uploaded file.

### A document collection's newest update

```text
[siu-item-updated post_ids="123,456,789" date_format="F j, Y" show_label="yes" label_before="Documents updated:"]
```

One date is shown: the newest valid, publicly viewable source's modification time. Missing/unavailable items are skipped. A malformed list produces no output.

### Show only updates at least a day after publication

```text
[siu-item-updated post_id="123" only_if_updated="yes" min_update_gap="86400" show_label="yes"]
```

This hides the date when the source was only saved at publication or within the first day. It compares timestamps; it does not assess the significance of an edit.

### Date and 12-hour time

```text
[siu-item-updated post_id="123" date_format="F j, Y" show_time="yes" time_format="g:i a" show_sep="yes" sep=", at" show_label="yes"]
```

Output: **Last updated: June 3, 2020, at 12:34 pm**.

### Relative wording

```text
[siu-item-updated post_id="123" display="relative" show_label="yes"]
```

Example: **Last updated: 3 days ago**. Relative output changes when the page is rendered again; it has no live browser timer.

### Semantic markup or plain text

```text
[siu-item-updated post_id="123" semantic="yes" class="download-date muted"]
[siu-item-updated post_id="123" output="text" date_format="iso"]
```

The first produces a `<time datetime="…">` inside the normal wrapper. The second returns an unwrapped date such as **2020-06-03**. They are two separate examples. Semantic markup does not inject JSON-LD or promise a search-ranking effect.

## Shortcode parameters

Boolean parameters accept `yes` or `no`; the historical German `ja` also enables them. Unknown boolean values act as `no`. Defaults preserve the simple absolute date output.

| Parameter | Default | Meaning |
| --- | --- | --- |
| `post_id` | Current loop item | Positive ID of one public post, page or custom post type item. Provide it outside a reliable post loop. |
| `post_ids` | Empty | Comma-separated list of at most 100 positive IDs. Overrides post_id. The newest eligible modification wins. Duplicate IDs are removed. |
| `date_format` | Site date format | PHP date format. Shortcuts: de = d.m.Y; us and iso = Y-m-d. The historical us shortcut is an ISO-style format, not month/day/year. |
| `time_format` | Site time format | PHP time format, such as H:i or g:i a. Used only for absolute time output. |
| `show_date` | yes | Show the absolute date. With relative mode, either show_date or show_time must remain enabled. |
| `show_time` | no | Show the absolute time. Relative mode replaces the date/time combination with one duration. |
| `show_sep` | no | Insert sep only when both absolute date and time are visible. |
| `sep` | Nonbreaking space + @; German: , um | Plain-text separator. HTML entities are decoded and safely escaped for HTML output. |
| `show_label` | no | Show label_before before the date/time or relative duration. |
| `label_before` | Last updated: | Plain-text label in the page language. An explicit value overrides the default. |
| `label_after` | Empty | Plain text after the absolute time, such as Uhr. Ignored without show_time and in relative mode. |
| `class` | Empty | Additional CSS classes, separated by spaces. The original item-last-updated class remains. HTML mode only. |
| `wrapper` | span | Allowed HTML wrapper. Unsupported values fall back to span. A time wrapper automatically receives datetime. HTML mode only. |
| `only_if_updated` | no | Require modification strictly after publication. Applies separately to each source before selecting the newest. |
| `min_update_gap` | 0 | Minimum publication-to-modification gap in whole seconds; 86400 = one day. The exact threshold qualifies. Used with only_if_updated=yes. |
| `semantic` | no | Wrap the visible date/duration in time with an ISO 8601 datetime including the site timezone offset. HTML mode only. |
| `output` | html | html returns escaped markup; text returns unwrapped text. Template callers must escape text for its final context. |
| `display` | absolute | absolute uses the source date/time; relative shows a duration such as 3 days ago. Future timestamps use in 3 days. |

Full combinations, permitted wrappers, cache behavior and PHP/filter examples are in the [user guide](USER-GUIDE.md).

## FAQ

### Which content can I use?

Published, publicly viewable posts, pages and custom post type items without a password. Drafts, private content, revisions, deleted posts and nonpublic types are skipped for everyone, including administrators.

### Which date does it show?

WordPress's saved modification time, formatted in the site's timezone. It does not track the modification of an uploaded PDF, inspect remote files or distinguish editorial changes from routine saves.

### How do I reference another item?

Use `post_id="123"`, or `post_ids="123,456,789"` for the newest eligible update. Replace the example IDs with IDs from your site. Outside a post loop, always specify a source.

### Why is nothing displayed?

Check the ID and public visibility, the modification timestamp, `only_if_updated` and its threshold. A malformed ID list or invalid threshold fails without output. Both date and time disabled also produce no output.

### Will it work in my builder?

Use the builder's shortcode element or a field that actually executes WordPress shortcodes. A field that treats the shortcode as literal text will not render it. In the block editor use the existing Shortcode block; this plugin adds no Gutenberg block.

### Can I use the snippet instead?

Yes. It has the same shortcode features and supports PHP 7.4. Use one installation method. The standalone snippet contains no file-based Library, plugin updater or translation catalogs; updates are manual.

### Why can relative dates or another item's date look stale?

Page caches store rendered output. Relative wording ages, and updating a referenced item may not purge the page displaying it. Purge that page or use your cache's dependency/exclusion features. Absolute output remains the default.

[Full FAQ by topic](FAQ.md).


## Changelog

### 2.3.0 — 2026-10-08

- **New:** Conditional update display with a minimum time gap.
- **New:** Latest update from multiple explicit post IDs.
- **New:** Semantic time markup, unwrapped text and relative dates.
- **Improved:** Documented recipes, complete parameter reference and standalone snippet distributions.
- **Improved:** Page-language labels, individual CSS classes and validated HTML wrappers.
- **Fixed:** The de/us shortcuts now show the source update date.
- **Fixed:** Restored the default label and suppressed invalid or unavailable sources.
- **Fixed:** Correct site-timezone formatting and separator/time suffix behavior.
- **Misc:** Bundled deckerweb Updater 2.1.0 and Library 0.8.1.
- **Misc:** The plugin requires PHP 8.0; the standalone snippet still supports PHP 7.4.

### 2.2.0 — 2025-03-28

- **Improved:** Class-based shortcode implementation.
- **Misc:** Plugin metadata links, snippet download and updated German translations.

### 2.1.0 — 2025-03-15

- **New:** German label defaults without separate translation files.
- **Improved:** Alternative use in PHP snippet managers.

### 2.0.0 — 2025-03-14

- **Improved:** Refreshed the lightweight shortcode plugin.
- **Misc:** Removed obsolete Shortcake integration; updated translations and version numbering.

### 2016-08-19 — 2016-08-19

- **Improved:** Documentation, translations and output handling.

### 2016-08-12 — 2016-08-12

- **New:** German and ISO-style date shortcuts; Shortcake integration.
- **Improved:** Translations and WordPress 4.6 compatibility.

### 2015-05-26 — 2015-05-26

- **New:** Optional labels, translated separators and current-loop post defaults.
- **Fixed:** Translation-loader variable.
- **Improved:** Shortcode parameters and installation documentation.

[Complete release history](CHANGELOG).

## About the project

Created in 2015 by David Decker to display a download item's update time on a separate content page. The plugin remains a small presentation tool. Minor releases add compatible optional features; patch releases correct behavior. Breaking changes are identified explicitly.

## Issues, security and support

Use [Issues](https://github.com/deckerweb/shortcode-item-updated/issues) for ordinary problems. Report security details privately through the repository's **Security → Report a vulnerability** route; see [security policy](SECURITY.md).

Support continued maintenance: [Ko-fi](https://ko-fi.com/deckerweb), [Buy Me a Coffee](https://buymeacoffee.com/daveshine), [PayPal](https://paypal.me/deckerweb).

## License and components

Copyright © 2015–2026 David Decker – DECKERWEB. GPL-2.0-or-later. Bundled deckerweb Updater 2.1.0 and Library 0.8.1 are by David Decker and use GPL-2.0-or-later. Component sources: [Updater](https://github.com/deckerweb/deckerweb-updater), [Library](https://github.com/deckerweb/deckerweb-plugin-library). The runtime includes local component assets and translation resources. No premium plugin code is included.

The shortcode makes no external requests. The updater contacts GitHub for update metadata and package downloads. The Library's online catalog is optional and initially off; installations and activations require deliberate user actions. There is no telemetry. The snippet makes no external requests. See [data and lifecycle](DATA.md).

Artwork uses the approved native vector design. WordPress banners use the same elements as the GitHub banner, cropping only the top and bottom. SVGs contain native shapes, outlined lettering and gradients, with no raster images, filters or external resources. PNGs are rendered directly from the same SVG sources. Montserrat is licensed under the SIL Open Font License 1.1; see the [font license](https://raw.githubusercontent.com/deckerweb/shortcode-item-updated/master/assets/FONT-LICENSE.txt).
