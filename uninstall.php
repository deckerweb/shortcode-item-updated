<?php
/** Clean this host updater cache and delegate shared Library cleanup.
 * The shortcode owns no settings, content or scheduled tasks.
 * Other installed Library hosts protect shared data, including inactive copies.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

require_once __DIR__ . '/includes/deckerweb-plugin-library/lifecycle.php';
deckerweb_library_uninstall_v3( __DIR__ . '/shortcode-item-updated.php' );
delete_site_transient( 'ddw_ghru_' . substr( md5( 'https://github.com/deckerweb/shortcode-item-updated' ), 0, 24 ) );
// The package is physically shared across networks; remove only its known updater cache.
if ( is_multisite() ) {
	$siu_offset = 0;
	$siu_cache = 'ddw_ghru_' . substr( md5( 'https://github.com/deckerweb/shortcode-item-updated' ), 0, 24 );
	do {
		$siu_networks = get_networks( array( 'fields' => 'ids', 'number' => 100, 'offset' => $siu_offset ) );
		foreach ( $siu_networks as $siu_network ) {
			delete_network_option( $siu_network, '_site_transient_' . $siu_cache );
			delete_network_option( $siu_network, '_site_transient_timeout_' . $siu_cache );
		}
		$siu_offset += count( $siu_networks );
	} while ( count( $siu_networks ) === 100 );
}
