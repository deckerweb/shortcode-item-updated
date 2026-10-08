<?php
/** Host-owned integration and package validation for the bundled updater. */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Registers the host updater and validates replacement identity and platform requirements. */
final class DDW_SIU_Components {
	const REPOSITORY = 'https://github.com/deckerweb/shortcode-item-updated';

	/** Initialize the pinned public updater after host translations are ready.
	 * @return void
	 */
	public static function updater(): void {
		if ( ! class_exists( '\Deckerweb\GitHubReleaseUpdater\V2\Updater' ) ) {
			require_once __DIR__ . '/deckerweb-github-release-updater-v2.php';
		}
		if ( ! defined( '\Deckerweb\GitHubReleaseUpdater\V2\Updater::SUPPORTS_HOST_TRANSLATIONS' ) || ! \Deckerweb\GitHubReleaseUpdater\V2\Updater::SUPPORTS_HOST_TRANSLATIONS || ! defined( \Deckerweb\GitHubReleaseUpdater\V2\Updater::class . '::IMPLEMENTATION_VERSION' ) || version_compare( \Deckerweb\GitHubReleaseUpdater\V2\Updater::IMPLEMENTATION_VERSION, '2.1.0', '<' ) ) {
			add_action( 'admin_notices', array( self::class, 'notice' ) ); add_action( 'network_admin_notices', array( self::class, 'notice' ) ); return;
		}
		try {
			$de = 0 === strpos( determine_locale(), 'de' );
			$assets = plugin_dir_url( DDW_SIU_FILE ) . 'assets/';
			$artwork = array( 'icons' => array( 'svg' => $assets . 'icon.svg', '1x' => $assets . 'icon-128x128.png', '2x' => $assets . 'icon-256x256.png' ), 'banners' => array( 'low' => $assets . 'banner-' . ( $de ? 'de' : 'en' ) . '.png', 'high' => $assets . 'banner-high-' . ( $de ? 'de' : 'en' ) . '.png' ) );
			$updater = new \Deckerweb\GitHubReleaseUpdater\V2\Updater( DDW_SIU_FILE, self::REPOSITORY, 'Shortcode Item Updated', __( 'Display the latest update of selected posts, pages or custom post type items.', 'shortcode-item-updated' ), $artwork, array( 'translate' => require __DIR__ . '/updater-translations.php' ) );
			$updater->register();
			add_filter( 'upgrader_source_selection', array( self::class, 'validate_package' ), 30, 4 );
		} catch ( \InvalidArgumentException $exception ) {
			add_action( 'admin_notices', array( self::class, 'notice' ) ); add_action( 'network_admin_notices', array( self::class, 'notice' ) );
		}
	}

	/** Show a safe configuration notice only to administrators.
	 * @return void
	 */
	public static function notice(): void {
		if ( current_user_can( 'update_plugins' ) ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'Shortcode Item Updated updates are unavailable. Check the shared updater integration.', 'shortcode-item-updated' ) . '</p></div>';
		}
	}

	/** Validate this host's actual replacement before Core removes the installed plugin.
	 * Uses WordPress filesystem APIs and rejects mismatched identity, version and requirements.
	 * @param string|\WP_Error $source Extracted normalized package directory.
	 * @param string $remote_source Temporary remote directory (unused).
	 * @param object $upgrader WordPress upgrader instance.
	 * @param array $extra Per-package upgrade context.
	 * @return string|\WP_Error Unchanged source or a safe package error.
	 */
	public static function validate_package( $source, string $remote_source, $upgrader, array $extra ) {
		$basename = plugin_basename( DDW_SIU_FILE );
		if ( is_wp_error( $source ) || ! is_string( $source ) || ( $extra['plugin'] ?? '' ) !== $basename ) { return $source; }
		$conflict = ( isset( $extra['type'] ) && 'plugin' !== $extra['type'] ) || ( isset( $extra['action'] ) && 'update' !== $extra['action'] );
		$complete = 'plugin' === ( $extra['type'] ?? '' ) && 'update' === ( $extra['action'] ?? '' );
		$bulk = $upgrader instanceof \Plugin_Upgrader && ! empty( $upgrader->bulk );
		if ( $conflict || ( ! $complete && ! $bulk ) ) { return $source; }
		global $wp_filesystem, $wp_version;
		$error = new \WP_Error( 'siu_update_package', __( 'The update package does not match this plugin or its requirements.', 'shortcode-item-updated' ) );
		$main = trailingslashit( $source ) . 'shortcode-item-updated.php';
		if ( ! $wp_filesystem || ! $wp_filesystem->is_file( $main ) || $wp_filesystem->size( $main ) > 65536 ) { return $error; }
		$text = $wp_filesystem->get_contents( $main );
		if ( ! is_string( $text ) ) { return $error; }
		$fields = array();
		foreach ( array( 'Plugin Name', 'Version', 'Requires at least', 'Requires PHP', 'Text Domain', 'Update URI' ) as $field ) {
			preg_match( '/^[ \t\/*#@]*' . preg_quote( $field, '/' ) . ':\s*(.+)$/mi', substr( $text, 0, 8192 ), $match );
			$fields[ $field ] = isset( $match[1] ) ? trim( preg_replace( '~\s*(?:\*/|\?>).*$~', '', $match[1] ) ) : '';
		}
		$updates = get_site_transient( 'update_plugins' );
		$offer = is_object( $updates ) ? ( $updates->response[ $basename ] ?? null ) : null;
		$offered = is_object( $offer ) ? (string) ( $offer->new_version ?? '' ) : '';
		if ( 'Shortcode Item Updated' !== $fields['Plugin Name'] || 'shortcode-item-updated' !== $fields['Text Domain'] || self::REPOSITORY !== $fields['Update URI'] || ! preg_match( '/^\d+\.\d+\.\d+$/D', $fields['Version'] ) || $offered !== $fields['Version'] || ! version_compare( $fields['Version'], DDW_Shortcode_Item_Updated::VERSION, '>' ) || ! preg_match( '/^\d+\.\d+(?:\.\d+)?$/D', $fields['Requires at least'] ) || ! preg_match( '/^\d+\.\d+(?:\.\d+)?$/D', $fields['Requires PHP'] ) || version_compare( $wp_version, $fields['Requires at least'], '<' ) || version_compare( PHP_VERSION, $fields['Requires PHP'], '<' ) ) { return $error; }
		return $source;
	}
}
