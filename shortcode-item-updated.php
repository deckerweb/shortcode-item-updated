<?php # -*- coding: utf-8 -*-
/**
 * Main plugin file.
 * @package           Shortcode Item Updated
 * @author            David Decker
 * @copyright         Copyright (c) 2015, David Decker - DECKERWEB
 * @license           GPL-2.0+
 * @link              http://deckerweb.de/twitter
 *
 * @wordpress-plugin
 * Plugin Name:       Shortcode Item Updated
 * Plugin URI:        https://github.com/deckerweb/shortcode-item-updated
 * Description:       Shortcode for showing the last updated date (and/or time) of an item of a post type.
 * Version:           2015.05.26
 * Author:            David Decker - DECKERWEB
 * Author URI:        http://deckerweb.de/
 * License:           GPL-2.0+
 * License URI:       http://www.opensource.org/licenses/gpl-license.php
 * Text Domain:       shortcode-item-updated
 * Domain Path:       /languages/
 * GitHub Plugin URI: https://github.com/deckerweb/shortcode-item-updated
 * GitHub Branch:     master
 *
 * Copyright (c) 2015 David Decker - DECKERWEB
 */

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}


add_action( 'init', 'ddw_siu_load_translations', 1 );
/**
 * Load the text domain for translation of the plugin.
 * 
 * @since 2015.05.26
 *
 * @uses  load_textdomain()	To load translations first from WP_LANG_DIR sub folder.
 * @uses  load_plugin_textdomain() To additionally load default translations from plugin folder (default).
 */
function ddw_siu_load_translations() {

	/** Set unique textdomain string */
	$siu_textdomain = 'shortcode-item-updated';

	/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
	$locale = apply_filters( 'plugin_locale', get_locale(), $siu_textdomain );

	/** Set filter for WordPress languages directory */
	$gwnf_wp_lang_dir = apply_filters(
		'siu_filter_wp_lang_dir',
		trailingslashit( WP_LANG_DIR ) . 'plugins/' . $siu_textdomain . '-' . $locale . '.mo'
	);

	/** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
	load_textdomain(
		$siu_textdomain,
		$siu_wp_lang_dir
	);

	/** Translations: Secondly, look in plugin's "languages" folder = default */
	load_plugin_textdomain(
		$siu_textdomain,
		FALSE,
		trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages'
	);

}  // end function


add_shortcode( 'siu-item-updated', 'ddw_siu_item_updated' );
/**
 * Shortcode for showing the last updated date (and/or time) of an item of a post type.
 * NOTE: Requires the ID of the post type item.
 *
 * @since  2015.05.25
 *
 * @uses   shortcode_atts()
 *
 * @param  array $atts
 *
 * @return string HTML String of last updated date.
 */
function ddw_siu_item_updated( $atts ) {

	/** Set default shortcode attributes */
	$defaults = apply_filters(
		'siu_filter_shortcode_defaults',
		array(
			'post_id'      => get_the_ID(),
			'date_format'  => get_option( 'date_format' ),
			'time_format'  => get_option( 'time_format' ),
			'show_date'    => 'yes',
			'show_time'    => 'no',
			'show_sep'     => 'no',
			/* translators: separator string between date and time values (a space plus @ symbol) */
			'sep'          => _x(
				'&#x00A0;@',
				'Translators: separator string between date and time values (a space plus @ symbol)',
				'shortcode-item-updated'
			),
			'show_label'   => 'no',
			/* translators: Text before date/ time */
			'label_before' => _x(
				'Last updated:',
				'Translators: Text before date/ time',
				'shortcode-item-updated'
			),
			'label_after'  => '',
			'class'        => '',
			'wrapper'      => 'span',
		)
	);

	/** Default shortcode attributes */
	$atts = shortcode_atts( $defaults, $atts, 'siu-item-updated' );

	/** Get date & time */
	$date_db        = get_post_field( 'post_modified', absint( $atts[ 'post_id' ] ), 'raw' );
	$date_converted = strtotime( $date_db );
	$date_updated   = date_i18n( esc_attr( $atts[ 'date_format' ] ), $date_converted );
	$time_updated   = date_i18n( esc_attr( $atts[ 'time_format' ] ), $date_converted );

	/** Prepare time display */
	$time_display = sprintf(
		'%1$s%2$s%3$s',
		( 'yes' == esc_attr( $atts[ 'show_sep' ] ) ) ? esc_attr( $atts[ 'sep' ] ) : '',
		( 'yes' == esc_attr( $atts[ 'show_time' ] ) ) ? ' ' . $time_updated : '',
		! empty( $atts[ 'label_after' ] ) ? ' ' . esc_html__( $atts[ 'label_after' ] ) : ''
	);

	/** Prepare output */
	$output = sprintf(
		'<%1$s class="item-last-updated%2$s">%3$s%4$s%5$s</%1$s>',
		esc_attr( $atts[ 'wrapper' ] ),
		! empty( $atts[ 'class' ] ) ? ' ' . esc_attr( $atts[ 'class' ] ) : '',
		( 'yes' == esc_attr( $atts[ 'show_label' ] ) ) ? esc_html__( $atts[ 'label_before' ] ) . ' ' : '',
		( 'yes' == esc_attr( $atts[ 'show_date' ] ) ) ? $date_updated : '',
		$time_display
	);

	/** Return the output - filterable */
	return apply_filters( 'siu_filter_shortcode_item_updated', $output, $atts );

}  // end function