# User guide

[Deutsch](USER-GUIDE-de.md) · [README](../README.md) · [Parameter table](../README.md#shortcode-parameters) · [Snippet installation](SNIPPETS.md)

## Start with the source, then choose the display

The shortcode has two separate jobs: choose a WordPress entry and format its saved modification time. Place it in a shortcode-capable content area. It does not change publication dates, save new metadata, inspect files or insert a notice automatically.

Find an item's ID by opening its edit screen and looking for `post=123` in the address. Use that number as `post_id`. All numeric IDs in these examples are placeholders to replace. IDs belong to the current website in Multisite; the shortcode does not search another site.

## A landing page that references a download

Your landing page may be ID 42 while its Download custom post type entry is ID 123. Using the default shortcode on the landing page would show the landing page's own update. Reference the download instead:

```text
[siu-item-updated post_id="123" show_label="yes" label_before="Download updated:" date_format="F j, Y"]
```

If the download entry was modified on June 3, 2020, the output is **Download updated: June 3, 2020**. Changing the landing page does not change that source. Uploading a replacement file changes this date only if the corresponding WordPress entry's modification time is also updated.

## A collection of manuals

Suppose IDs 123, 456 and 789 belong to three manuals. You want one line saying when the collection last changed:

```text
[siu-item-updated post_ids="123,456,789" show_label="yes" label_before="Manuals updated:" date_format="F j, Y" semantic="yes"]
```

If their modification dates are June 3, June 4 and June 2, the result uses June 4. It does not render a list, the winning item's title or links. Order is irrelevant; duplicate IDs are removed. There is no taxonomy/query language.

`post_ids` overrides `post_id` when nonempty. Up to 100 comma-separated tokens are accepted. Each must be a positive decimal integer. `123, bad` and a list with an empty trailing token are invalid and return nothing. IDs with no available post are skipped; a valid list whose sources are all unavailable returns nothing. This distinction avoids silently interpreting typing mistakes as a different source.

## Suppress publication-time saves

```text
[siu-item-updated post_id="123" only_if_updated="yes" min_update_gap="86400" show_label="yes"]
```

The modification must be strictly after publication and at least 86,400 seconds later. Exactly one day qualifies. With a zero threshold, any later timestamp qualifies; equal timestamps never qualify. With `only_if_updated="no"`, the threshold does not suppress valid output. Thresholds must be nonnegative whole seconds.

For collections, the condition is applied to each source first:

```text
[siu-item-updated post_ids="123,456,789" only_if_updated="yes" min_update_gap="86400" show_label="yes" label_before="Manuals updated:"]
```

A newly published manual with no eligible later change is ignored; the newest remaining eligible update is displayed. If none qualifies, the entire output is empty, including labels and wrappers. A routine later save can still qualify: the plugin cannot judge editorial significance.

## Date and time recipes

Readable English date and time:

```text
[siu-item-updated post_id="123" date_format="F j, Y" show_time="yes" time_format="g:i a" show_sep="yes" sep=", at" show_label="yes"]
```

Example: **Last updated: June 3, 2020, at 12:34 pm**.

Numeric U.S. date, explicitly requested:

```text
[siu-item-updated post_id="123" date_format="m/d/Y"]
```

Example: **06/03/2020**. The older `us` shortcut means `Y-m-d`, retained for compatibility. Use `iso` for the clearer name of the same format.

Time only:

```text
[siu-item-updated post_id="123" show_date="no" show_time="yes" time_format="H:i"]
```

Example: **12:34**. A separator appears only when both date and time are present. `label_after` appears only after an absolute time; it is not a general suffix. If both visibility flags are `no`, nothing appears.

Date and time defaults come from **Settings → General**. Month names and durations follow WordPress localization; labels respect the page language with WPML/Polylang, with the site's locale as fallback. Your administrator profile language does not choose the public label. Use custom labels for deliberately different wording.

## Relative dates and caches

```text
[siu-item-updated post_id="123" display="relative" show_label="yes"]
```

Example: **Last updated: 3 days ago**. WordPress chooses approximate units; this is not a countdown or exact elapsed-seconds report. A future timestamp shows **in 3 days** rather than incorrectly claiming it is in the past. At least one of `show_date`/`show_time` must be enabled. `date_format`, `time_format`, `sep` and `label_after` do not alter relative wording.

Use semantic markup to keep the exact source timestamp alongside that readable duration:

```text
[siu-item-updated post_id="123" display="relative" semantic="yes" show_label="yes"]
```

The plugin calculates the duration when WordPress renders the page. A page cache can retain old wording. It also may not know that a change to a referenced post should invalidate the referencing page. Purge the referencing page, configure a suitable cache lifetime or use dependency/exclusion features in your cache. This plugin does not install a cache-specific purge integration. Absolute dates are simpler for heavily cached pages.

## HTML output and styling

```text
[siu-item-updated post_id="123" date_format="iso" semantic="yes" class="download-date muted" wrapper="div"]
```

Example output in a Europe/Berlin site:

```html
<div class="item-last-updated download-date muted"><time datetime="2020-06-03T12:34:00+02:00">2020-06-03</time></div>
```

`semantic` is optional and defaults to `no`. It adds no stylesheet, JSON-LD, metadata tag or search-ranking guarantee. The ISO value includes the site's timezone offset at that date, including daylight saving time.

Allowed wrappers: `span`, `div`, `p`, `small`, `strong`, `em`, `time`, `li`, `dt`, `dd`, `figcaption`, `section`, `article`, `aside`, `footer`, `header`, and `h1`–`h6`. Unsupported wrappers fall back to `span`. Choose a wrapper valid within the surrounding page markup; for example, `li` needs an appropriate list parent. A `time` wrapper gets `datetime` itself and never nests another `time` element. Labels, formats and separators are text, not a way to inject HTML.

For example, add your own CSS to the theme or builder:

```css
.download-date { font-size: .875rem; }
.download-date.muted { color: #515b66; }
```

The existing `.item-last-updated` class stays in HTML mode. Classes are individually sanitized and deduplicated. The plugin itself adds no frontend styling.

## Unwrapped text and PHP templates

```text
[siu-item-updated post_id="123" output="text" date_format="iso"]
```

Example: **2020-06-03**, without an HTML wrapper. In text mode, `wrapper`, `class` and `semantic` have no effect. Labels still work; markup is stripped from text output. It is still a date formatter, not a raw Unix-timestamp API.

To display the normal safe HTML output in a PHP template:

```php
echo do_shortcode( '[siu-item-updated post_id="123" show_label="yes" semantic="yes"]' );
```

`do_shortcode()` returns a string, so `echo` is required for display. Escape plain text according to its final context:

```php
$updated = do_shortcode( '[siu-item-updated post_id="123" output="text" date_format="iso"]' );
echo esc_html( $updated ); // Visible text.
// Use esc_attr( $updated ) if inserting the value in an HTML attribute.
```

## Builders, loops and shared templates

Use a builder's shortcode element, not a literal text/code display. Inside a Query Loop, the default `post_id` comes from `get_the_ID()`. This also works with a builder loop that establishes WordPress's current post correctly. An archive heading, footer or unrelated template can have no current post or an unexpected one; specify `post_id` there. No fallback guesses an archive, template or remote site as the source.

In the WordPress editor, use the existing Shortcode block. This plugin registers no Gutenberg block. A builder field requesting text may need `output="text"`; it must still execute the shortcode. Native dynamic-field integration is not installed.

## Existing filters

Set a site-wide default, while explicit shortcode attributes retain precedence:

```php
add_filter( 'siu_filter_shortcode_defaults', function ( $defaults ) {
    $defaults['semantic'] = 'yes';
    $defaults['date_format'] = 'iso';
    return $defaults;
} );
```

Alter rendered output:

```php
add_filter( 'siu_filter_shortcode_item_updated', function ( $output, $atts ) {
    if ( 'text' === strtolower( $atts['output'] ) ) {
        return $output;
    }
    return '<div class="update-note">' . $output . '</div>';
}, 10, 2 );
```

Trusted filter callbacks must escape any additions appropriately. WordPress's `shortcode_atts_siu-item-updated` filter remains available. Added custom attributes survive through the output filter. The plugin introduces no new custom hook. An unavailable source returns an empty string before final output filtering; the filter does not override source visibility rules.

## Migration from 2.2.0

- Plugin minimum PHP becomes 8.0; the standalone snippet remains at 7.4. WordPress minimum stays 6.7.
- Shortcode name, original attributes, CSS class and existing filters remain.
- `de`/`us` now correctly use the source timestamp instead of today's date.
- The default label appears correctly when `show_label="yes"`.
- Invalid sources produce no misleading current/epoch date.
- Dates of private, draft, password-protected or nonpublic content are not exposed, including to logged-in administrators. This also makes cached public output independent of user privileges.
- Multiple classes work; unsupported wrappers fall back to `span`.
- Separators and time suffixes require the relevant visible time/date components.
- No settings migration, database schema change or content rewrite occurs.

## Multisite and lifecycle

Site activation and network activation are supported. Each site's IDs, locale, formats and timezone remain separate. A `post_ids` list never combines sites. The shortcode owns no persistent settings or scheduled tasks.

The installable plugin bundles shared Library data and a GitHub update cache. Deactivation preserves them. Uninstall clears this host's update cache and delegates Library cleanup: another physically installed host, even inactive, protects shared data. For the last host, temporary data is cleaned and Library settings are retained unless its explicit deletion option is enabled. Installed plugins and content are never removed by this cleanup. See [data inventory](DATA.md).
