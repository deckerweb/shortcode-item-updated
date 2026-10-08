<?php
/** Host-owned translation adapter for deckerweb Updater 2.1.0. */
defined( 'ABSPATH' ) || exit;
/**
 * Translate a component message through the host domain.
 * @param string $message English component message.
 * @return string Localized host message or unchanged English fallback.
 */
return static function ( string $message ): string {
    // Literal calls let the host's normal translation extractor collect every source string.
    switch ( $message ) {
        case 'Private mode must be boolean.':
            return __( 'Private mode must be boolean.', 'shortcode-item-updated' );
        case 'Invalid authentication provider.':
            return __( 'Invalid authentication provider.', 'shortcode-item-updated' );
        case 'The plugin must be installed in a stable slug directory.':
            return __( 'The plugin must be installed in a stable slug directory.', 'shortcode-item-updated' );
        case 'Invalid GitHub repository URL.':
            return __( 'Invalid GitHub repository URL.', 'shortcode-item-updated' );
        case 'The private update could not be authorized. Check the repository credentials and refresh updates.':
            return __( 'The private update could not be authorized. Check the repository credentials and refresh updates.', 'shortcode-item-updated' );
        case 'Could not create the update download file.':
            return __( 'Could not create the update download file.', 'shortcode-item-updated' );
        case 'The private update download failed. Check credentials and try again.':
            return __( 'The private update download failed. Check credentials and try again.', 'shortcode-item-updated' );
        case 'Could not access the update filesystem.':
            return __( 'Could not access the update filesystem.', 'shortcode-item-updated' );
        case 'GitHub release does not contain the plugin main file.':
            return __( 'GitHub release does not contain the plugin main file.', 'shortcode-item-updated' );
        case 'Could not prepare the GitHub release package.':
            return __( 'Could not prepare the GitHub release package.', 'shortcode-item-updated' );
        case 'See the release on GitHub.':
            return __( 'See the release on GitHub.', 'shortcode-item-updated' );
        default:
            return $message;
    }
};
