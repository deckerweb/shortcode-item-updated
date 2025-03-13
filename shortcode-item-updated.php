<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name:  Shortcode Item Updated
 * Plugin URI:   https://github.com/deckerweb/shortcode-item-updated
 * Description:  Shortcode for showing the last updated date (and/or time) of an item of a post type.
 * Version:      2.0.0
 * Author:       David Decker – DECKERWEB
 * Author URI:   https://deckerweb.de/
 * License:      GPL-2.0+
 * License URI:  http://www.opensource.org/licenses/gpl-license.php
 * Text Domain:  shortcode-item-updated
 * Domain Path:  /languages/
 * Requires WP:  6.7
 * Requires PHP: 7.4
 *
 * Copyright (c) 2015-2025 David Decker – DECKERWEB
 */

/** Prevent direct access */
if ( ! defined( 'ABSPATH' ) ) {
	 exit;  // Exit if accessed directly.
}


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
 * @uses   shortcode_atts() To parse Shortcode attributes.
 * @uses   ddw_siu_yes_no() To validate Shortcode attributes/ parameters.
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
		( 'yes' === ddw_siu_yes_no( $atts[ 'show_label' ] ) ) ? esc_html__( $atts[ 'label_before' ] ) . ' ' : '',
		( 'yes' === ddw_siu_yes_no( $atts[ 'show_date' ] ) ) ? $date_updated : '',
		$time_display
	);

	/** Return the output - filterable */
	return apply_filters( 'siu_filter_shortcode_item_updated', $output, $atts );

}  // end function