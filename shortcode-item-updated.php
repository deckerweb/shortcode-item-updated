<?php # -*- coding: utf-8 -*-
/**
 * Main plugin file.
 * @package     Shortcode Item Updated
 * @author      David Decker
 * @copyright   Copyright (c) 2015, David Decker - DECKERWEB
 * @license     GPL-2.0+
 * @link        http://deckerweb.de/twitter
 *
 * @wordpress-plugin
 * Plugin Name:       Shortcode Item Updated
 * Plugin URI:        https://github.com/deckerweb/shortcode-item-updated
 * Description:       Shortcode for showing the last updated date (and/or time) of an item of a post type.
 * Version:           2015.05.25
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

/** Not a WordPress context? Stop. */
! defined( 'ABSPATH' ) and exit;


add_shortcode( 'siu-item-updated', 'ddw_siu_item_updated' );
/**
 * Shortcode for showing the last updated date (and/or time) of an item of a post type.
 * NOTE: Requires the ID of the post type item.
 *
 * @since  1.0.0
 *
 * @uses   shortcode_atts()
 *
 * @param  array $atts
 *
 * @return string HTML String of last updated date.
 */
function ddw_siu_item_updated( $atts ) {

	/** Set default shortcode attributes */
	$defaults = array(
		'post_id'     => '',
		'date_format' => get_option( 'date_format' ),
		'time_format' => get_option( 'time_format' ),
		'show_time'   => 'no',
		'show_sep'    => 'no',
		'sep'         => '@',
		'class'       => '',
		'wrapper'     => 'span',
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
		'%1$s%2$s',
		( 'yes' == $atts[ 'show_sep' ] ) ? ' ' . esc_attr( $atts[ 'sep' ] ) : '',
		( 'yes' == $atts[ 'show_time' ] ) ? ' ' . $time_updated : ''
	);

	/** Prepare output */
	$output = sprintf(
		'<%1$s class="item-last-updated%2$s">%3$s%4$s</%1$s>',
		esc_attr( $atts[ 'wrapper' ] ),
		! empty( $atts[ 'class' ] ) ? esc_attr( $atts[ 'class' ] ) : '',
		$date_updated,
		$time_display
	);

	/** Return the output - filterable */
	return apply_filters( 'siu_filter_shortcode_item_updated', $output, $atts );

}  // end function