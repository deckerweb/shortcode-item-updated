# Functional checks

[Deutsch](README-de.md)

Run these checks only in an isolated, disposable WordPress installation. They create/delete fixture posts, change formats/locales, manipulate update offers and create snippet-manager records. Never run them on a production site. Use WP-CLI eval-file with the selected test installation.

shortcode.php checks the shared shortcode engine against real post records. components.php checks component election, translation resources and package identity/version/requirements. network-isolation.php requires an isolated Multisite installation. Manager import checks require separately installed, legitimately obtained managers; no manager code is bundled here. build-snippets.py produces the files they import. SIU_TEST_SNIPPET_DIR can point to a separate generated snippet directory.

The test results supplied with 2.3.0 identify actual environments and remaining limits.
