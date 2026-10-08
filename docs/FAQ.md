# FAQ by topic

[Deutsch](FAQ-de.md) · [User guide](USER-GUIDE.md)

## Getting started

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

## Everyday use

### Does it inspect a PDF or remote download?

No. It uses the chosen WordPress entry's modification time. Replacing a file is reflected only if that entry's timestamp changes.

### Does this affect SEO or publication dates?

No. Semantic output is a time element with datetime. There is no automatic content insertion, schema/JSON-LD generation or date modification.

## Display and integration

### Which settings choose the timezone and formats?

Settings → General on the current site. The date/time format can be overridden in a shortcode; the site's timezone still applies.

### Can I combine several sources with a threshold?

Yes. The threshold is applied per source before choosing the newest eligible update. A recently published, unchanged item does not hide an older eligible update.

### Is the historical us format month/day/year?

No: it is Y-m-d, retained for compatibility. Use iso for the same format, or m/d/Y for a numeric U.S. date.

### Can I insert markup in a label or separator?

No. These are text values. HTML output escapes them; text output removes tags. Use the trusted output filter for a custom structure and escape additions.

## Management

### Is there a settings page or a new editor block?

No. Shortcode attributes configure each output. The bundled Library has its own shared management controls; they do not configure this shortcode.

### What happens on Multisite or uninstall?

IDs and presentation settings belong to the current site. Network activation works without copying data to each site. The shortcode saves no data; shared-component cleanup is described in DATA.md.

### Does the standalone snippet update itself?

No. Replace its code manually. The installable plugin owns GitHub updates and the bundled Library. Manager storage belongs to the manager.
