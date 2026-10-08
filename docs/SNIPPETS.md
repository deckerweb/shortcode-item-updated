# Standalone snippet installation

[Deutsch](SNIPPETS-de.md) · [User guide](USER-GUIDE.md)

The snippet has all version 2.3.0 shortcode features, requires WordPress 6.7 and PHP 7.4, and contains the same shortcode engine as the plugin. It is self-contained. No plugin paths, bundled Library, updater, plugin-row links or translation catalogs are required. The shortcode's German default labels work without a plugin catalog; localized months/durations use WordPress's language resources.

Use **one** installation method. Deactivate an older snippet or the installed plugin before enabling this snippet. In an accidental plugin/snippet overlap, the installed plugin takes precedence for the shortcode, but this is not a supported dual installation. An already loaded old class from a different snippet cannot be upgraded within that request.

## Code Snippets

1. Open **Snippets → Import** and select `shortcode-item-updated-2.3.0.code-snippets.json` from the separate snippet package.
2. Review the imported PHP snippet. Select **Run snippet everywhere**.
3. Save and activate. The supplied JSON leaves the snippet inactive for review.
4. Add `[siu-item-updated post_id="123" show_label="yes"]` to a shortcode-capable page, replacing 123 with a public item's ID.

Manual alternative: create a PHP snippet and paste the entire contents of `shortcode-item-updated-2.3.0-paste.txt`. That file has no opening PHP tag.

## Advanced Scripts (Premium)

Advanced Scripts 2.6.2 accepts the same Code Snippets import:

1. Under **Tools → Advanced Scripts → Import**, select `shortcode-item-updated-2.3.0.code-snippets.json`.
2. Leave it inactive for review. The importer configures **PHP**, **Everywhere**, and **plugins_loaded** at priority 1.
3. Review, save and activate.

Manual alternative: create a PHP script and paste the complete `shortcode-item-updated-2.3.0.snippet.php`, **including exactly one opening PHP tag**. Replace any existing `<?php` in the editor rather than duplicating it. Alternatively paste `-paste.txt` below the editor's existing tag. Select **Everywhere**, **plugins_loaded**, priority 1 or 10, with no restrictive conditions. Do not choose the manager's shortcode-only execution: this script registers its own `[siu-item-updated]`.

The shortcode registers on `init`, or immediately when loaded later. The script must run before content renders. The supplied premium package was used for testing; no premium code is part of this distribution.

## FluentSnippets

1. Create a new snippet of type **PHP**.
2. Paste the complete `shortcode-item-updated-2.3.0-paste.txt` body without an opening PHP tag.
3. Select **Run everywhere** and leave conditional execution off.
4. Save and enable/publish the snippet.

Use a functions/PHP snippet, not a mixed-content or on-demand snippet. FluentSnippets adds the opening PHP tag when saving its file. The separate native FluentSnippets JSON file, when included, can be imported using its Import control; it imports as inactive/draft for review.

## File and copy distinctions

- `.snippet.php`: complete PHP source with one opening tag; useful for a file include.
- `-paste.txt`: same implementation without that tag; use in Code Snippets/FluentSnippets editors, or below Advanced Scripts’ existing PHP tag.
- `.code-snippets.json`: native Code Snippets import, one inactive global PHP snippet.
- `.fluentsnippets.json`: native FluentSnippets import, one PHP snippet for everywhere execution.

Do not paste the installable plugin's main file: it loads files that a standalone snippet does not have. Copy the provided snippet body instead.

## Updates and removal

Updates are manual. Replace the old snippet code rather than adding a second active copy. Export your snippet first if you have customized it. Deactivate/remove the snippet in its manager; the shortcode creates no persistent data or scheduled tasks. Existing page content remains and contains its shortcode text until you remove or replace it.

The manager controls its own storage, exports and deletion; those records are outside this snippet's lifecycle.
