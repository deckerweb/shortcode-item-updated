<?php
/**
 * Plugin Name: Shortcode Item Updated
 * Plugin URI: https://github.com/deckerweb/shortcode-item-updated
 * Description: Display the latest update of selected posts, pages or custom post type items with a flexible shortcode.
 * Version: 2.3.0
 * Requires at least: 6.7
 * Requires PHP: 8.0
 * Author: David Decker – DECKERWEB
 * Author URI: https://github.com/deckerweb
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: shortcode-item-updated
 * Domain Path: /languages/
 * Update URI: https://github.com/deckerweb/shortcode-item-updated
 * GitHub Plugin URI: https://github.com/deckerweb/shortcode-item-updated
 *
 * Copyright © 2015–2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */
defined( 'ABSPATH' ) || exit;

define( 'DDW_SIU_FILE', __FILE__ );
require_once __DIR__ . '/includes/class-shortcode.php';
require_once __DIR__ . '/includes/class-components.php';
require_once __DIR__ . '/includes/deckerweb-plugin-library/bootstrap.php';
deckerweb_library_register_v2( __FILE__, array(), __DIR__ . '/includes/deckerweb-plugin-library' );

/**
 * Register host translations and shortcode after WordPress initializes its locale.
 * @return void No return value.
 */
function ddw_siu_initialize() {
	load_plugin_textdomain( 'shortcode-item-updated', false, dirname( plugin_basename( DDW_SIU_FILE ) ) . '/languages' );
	new DDW_Shortcode_Item_Updated();
	if ( is_admin() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		DDW_SIU_Components::updater();
	}
}
add_action( 'init', 'ddw_siu_initialize', 5 );

/**
 * Add contextual documentation and Library access in the existing plugin row.
 * @param array $links Existing metadata links.
 * @param string $file Plugin basename for this row.
 * @return array Metadata links; no user details are placed in external URLs.
 */
function ddw_siu_pluginrow_meta( $links, $file ) {
	if ( plugin_basename( DDW_SIU_FILE ) !== $file || ! current_user_can( 'activate_plugins' ) ) { return $links; }
	$de = 0 === strpos( determine_locale(), 'de' );
	$links[] = '<a href="' . esc_url( 'https://github.com/deckerweb/shortcode-item-updated/blob/master/' . ( $de ? 'README-de.md' : 'README.md' ) ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Documentation', 'shortcode-item-updated' ) . '</a>';
	$links[] = '<a href="https://ko-fi.com/deckerweb" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support this project', 'shortcode-item-updated' ) . '</a>';
	return $links;
}
add_filter( 'plugin_row_meta', 'ddw_siu_pluginrow_meta', 10, 2 );
