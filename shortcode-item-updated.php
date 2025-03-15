<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name:  Shortcode Item Updated
 * Plugin URI:   https://github.com/deckerweb/shortcode-item-updated
 * Description:  Shortcode for showing the last updated date (and/or time) of an item of a post type.
 * Project:      Code Snippet: DDW Shortcode Item Updated
 * Version:      2.1.0
 * Author:       David Decker – DECKERWEB
 * Author URI:   https://deckerweb.de/
 * License:      GPL-2.0+
 * License URI:  http://www.opensource.org/licenses/gpl-license.php
 * Text Domain:  shortcode-item-updated
 * Domain Path:  /languages/
 * Requires WP:  6.7
 * Requires PHP: 7.4
 *
 * Copyright: © 2015-2025, David Decker – DECKERWEB
 */

/** Prevent direct access */
if ( ! defined( 'ABSPATH' ) ) {
	 exit;  // Exit if accessed directly.
}


/**
 * Are we in German language context?
 *
 * @link https://wpml.org/faq/how-to-get-current-language-with-wpml/
 * @link https://polylang.pro/doc/function-reference/
 *
 * @since 2.1.0
 *
 * @uses get_user_locale() Returns current locale from user setting or if not set from get_locale().
 *
 * @return bool  TRUE if in German based context, FALSE otherwise.
 */
function ddw_siu_is_german() {
	
	$german_locales = [ 'de_DE', 'de_DE_formal', 'de_AT', 'de_CH', 'de_LU' ];
	$german_slugs   = [ 'de', 'at' ];
	$wp_locale      = get_user_locale();
	
	/** WPML plugin: current language */
	$wpml_current_lang = apply_filters( 'wpml_current_language', NULL );
	
	/** WordPress context – default */
	if ( in_array( $wp_locale, $german_locales ) ) return TRUE;
	
	/** for WPML plugin */
	elseif ( in_array( $wpml_current_lang, $german_slugs ) ) return TRUE;
	
	/** for Polylang plugin */
	elseif ( function_exists( 'pll_current_language' ) && ( in_array( pll_current_language( 'slug' ), $german_slugs ) ) ) return TRUE;
	
	return FALSE;
		
}  // end function


/**
 * Reusable and translateable strings.
 * Preset for German locales --> saves the use of a translation file ... :-)
 *
 * @since 2.1.0
 *
 * @param string  $type    Key of the string type to output.
 * @return string $string  Key of used language string.
 */
function ddw_siu_strings( $type ) {
	
	/* translators: separator string between date and time values (a space plus @ symbol) */
	$sep = ddw_siu_is_german() ? ', um' : _x(
			'&#x00A0;@',
			'Translators: separator string between date and time values (a space plus @ symbol)',
			'shortcode-item-updated'
		);
	
	/* translators: Text before date/ time */	
	$label_before = ddw_siu_is_german() ? 'Zuletzt aktualisiert:' : _x(
			'Last updated:',
			'Translators: Text before date/ time',
			'shortcode-item-updated'
		);
	
	/** Check string type */
	switch ( sanitize_key( $type ) ) {
	
		case 'sep':
			$string = $sep;
			break;
		case 'label_after':
			$string = $label_before;
			break;
		default:
			$type = '';
	
	}  // end switch
	
	return $string;
	
}  // end function


/**
 * Data validation function to only allow values "yes" and "no".
 *
 * @since  2016.08.16
 *
 * @param  string $param Shortcode param to validate.
 *
 * @return string Validated and escaped string.
 */
function ddw_siu_yes_no( $param = '' ) {

	$param = strtolower( esc_attr( $param ) );

	if ( in_array( $param, array( 'yes', 'ja' ) ) ) {

		return 'yes';

	} else {

		return 'no';

	}  // end if

}  // end function


add_shortcode( 'siu-item-updated', 'ddw_siu_item_updated' );
/**
 * Shortcode for showing the last updated date (and/or time) of an item of a post type.
 * NOTE: Requires the ID of the post type item.
 *
 * @since  2015.05.25
 *
 * @uses   shortcode_atts()  To parse Shortcode attributes.
 * @uses   ddw_siu_strings() To output translateable strings.
 * @uses   ddw_siu_yes_no()  To validate Shortcode attributes/ parameters.
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
			'sep'          => ddw_siu_strings( 'sep' ),
			'show_label'   => 'no',
			'label_before' => ddw_siu_strings( 'label_before' ),
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

	/** Bonus: Shortcuts for typical German and U.S. date formats */
	if ( 'us' === strtolower( esc_attr( $atts[ 'date_format' ] ) ) ) {

		$date_updated = date_i18n( 'Y-m-d' );

	} elseif ( 'de' === strtolower( esc_attr( $atts[ 'date_format' ] ) ) ) {

		$date_updated = date_i18n( 'd.m.Y' );

	}  // end if

	/** Prepare time display */
	$time_display = sprintf(
		'%1$s%2$s%3$s',
		( 'yes' === ddw_siu_yes_no( $atts[ 'show_sep' ] ) ) ? esc_attr( $atts[ 'sep' ] ) : '',
		( 'yes' === ddw_siu_yes_no( $atts[ 'show_time' ] ) ) ? ' ' . $time_updated : '',
		! empty( $atts[ 'label_after' ] ) ? ' ' . esc_html__( $atts[ 'label_after' ] ) : ''
	);

	/** Prepare output */
	$output = sprintf(
		'<%1$s class="item-last-updated%2$s">%3$s%4$s%5$s</%1$s>',
		strtolower( sanitize_html_class( $atts[ 'wrapper' ] ) ),
		! empty( $atts[ 'class' ] ) ? ' ' . sanitize_html_class( $atts[ 'class' ] ) : '',
		( 'yes' === ddw_siu_yes_no( $atts[ 'show_label' ] ) ) ? esc_html( $atts[ 'label_before' ] ) . ' ' : '',
		( 'yes' === ddw_siu_yes_no( $atts[ 'show_date' ] ) ) ? $date_updated : '',
		$time_display
	);

	/** Return the output - filterable */
	return apply_filters( 'siu_filter_shortcode_item_updated', $output, $atts );

}  // end function