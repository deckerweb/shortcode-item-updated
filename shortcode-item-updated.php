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


add_action( 'init', 'ddw_siu_load_translations', 1 );
/**
 * Load the text domain for translation of the plugin.
 *
 * @since 2015.05.26
 *
 * @uses  load_textdomain()	       To load translations first from WP_LANG_DIR sub folder.
 * @uses  load_plugin_textdomain() To additionally load default translations from plugin folder (default).
 */
function ddw_siu_load_translations() {

	/** Set unique textdomain string */
	$siu_textdomain = 'shortcode-item-updated';

	/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
	$locale = apply_filters( 'plugin_locale', get_locale(), $siu_textdomain );

	/** Set filter for WordPress languages directory */
	$siu_wp_lang_dir = apply_filters(
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


add_action( 'init', 'ddw_siu_prepare_shortcode_ui' );
/**
 * If plugin Shortcake is active, load our support for it.
 *
 * @since  2016.08.12
 *
 * @return add_action() Run action in Shortcake plugin.
 */
function ddw_siu_prepare_shortcode_ui() {

	/** Check if Shortcake exists */
    if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {

        return;

    }  // end if

    /** Only create the UI when in the admin */
    if ( ! is_admin() ) {

        return;

    }  // end if

    /** Load Shortcode UI registering */
	add_action( 'register_shortcode_ui', 'ddw_siu_register_shortcode_for_ui' );

}  // end function


/**
 * Shortcode UI setup for Shortcake plugin (Shortcode UI).
 * @link   https://wordpress.org/plugins/shortcode-ui/
 *
 * @since  2016.08.12
 *
 * @uses   shortcode_ui_register_for_shortcode() To register Shortcode attributes for UI.
 *
 * @return array Array with Shortcode UI arguments.
 */
function ddw_siu_register_shortcode_for_ui() {

	$shortcode_ui_args = array(
		/** General setup for the Shortcode UI */
		'label'         => esc_html__( 'Item last updated', 'shortcode-item-updated' ),
		'listItemImage' => 'dashicons-backup',
		/** Arguments for the Shortcode attributes */
		'attrs'         => array(
			array(
				'label'       => esc_html__( 'Date format', 'shortcode-item-updated' ),
				'attr'        => 'date_format',
				'type'        => 'text',
				'meta'        => array(
					'placeholder' => get_option( 'date_format' ),
				),
				'description' => esc_html__( 'Uses the PHP date format schema', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'Time format', 'shortcode-item-updated' ),
				'attr'        => 'time_format',
				'type'        => 'text',
				'meta'        => array(
					'placeholder' => get_option( 'time_format' ),
				),
				'description' => esc_html__( 'Uses the PHP time format schema', 'shortcode-item-updated' ),
			),
			array(
				'label'   => esc_html__( 'Show date?', 'shortcode-item-updated' ),
				'attr'    => 'show_date',
				'type'    => 'select',
				'options' => array(
					'yes' => esc_html__( 'Yes', 'shortcode-item-updated' ),
					'no'  => esc_html__( 'No', 'shortcode-item-updated' ),
				),
			),
			array(
				'label'       => esc_html__( 'Show time?', 'shortcode-item-updated' ),
				'attr'        => 'show_time',
				'type'        => 'select',
				'options'     => array(
					'no'  => esc_html__( 'No', 'shortcode-item-updated' ),
					'yes' => esc_html__( 'Yes', 'shortcode-item-updated' ),
				),
				'description' => esc_html__( 'Optional time of last update', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'Show separator?', 'shortcode-item-updated' ),
				'attr'        => 'show_sep',
				'type'        => 'select',
				'options'     => array(
					'no'  => esc_html__( 'No', 'shortcode-item-updated' ),
					'yes' => esc_html__( 'Yes', 'shortcode-item-updated' ),
				),
				'description' => esc_html__( 'Whether to show some string between date and time values', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'Separator string/ character', 'shortcode-item-updated' ),
				'attr'        => 'sep',
				'type'        => 'text',
				/* translators: separator string between date and time values (a space plus @ symbol) */
				'placeholder' => _x(
					'&#x00A0;@',
					'Translators: separator string between date and time values (a space plus @ symbol)',
					'shortcode-item-updated'
				),
				'description' => esc_html__( 'What is output between date and time strings', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'Show label?', 'shortcode-item-updated' ),
				'attr'        => 'show_label',
				'type'        => 'select',
				'options'     => array(
					'no'  => esc_html__( 'No', 'shortcode-item-updated' ),
					'yes' => esc_html__( 'Yes', 'shortcode-item-updated' ),
				),
				'description' => esc_html__( 'Whether to show label before date', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'Label before', 'shortcode-item-updated' ),
				'attr'        => 'label_before',
				'type'        => 'text',
				'meta'        => array(
					/* translators: HTML placeholder */
					'placeholder' => esc_html( _x(
						'Last updated:',
						'Translators: HTML placeholder',
						'shortcode-item-updated'
					) ),
				),
				'description' => esc_html__( 'Optional label output at the beginning', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'Label after', 'shortcode-item-updated' ),
				'attr'        => 'label_after',
				'type'        => 'text',
				'meta'        => array(
					'placeholder' => '',
				),
				'description' => esc_html__( 'Optional label output at the end', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'CSS class?', 'shortcode-item-updated' ),
				'attr'        => 'class',
				'type'        => 'text',
				'placeholder' => '',
				'description' => esc_html__( 'Additional custom CSS class for the whole wrapper', 'shortcode-item-updated' ),
			),
			array(
				'label'       => esc_html__( 'Wrapper tag?', 'shortcode-item-updated' ),
				'attr'        => 'wrapper',
				'type'        => 'text',
				'placeholder' => 'span',
				'description' => esc_html__( 'HTML wrapper tag that is used', 'shortcode-item-updated' ),
			),
		),
	);  // end array

	/** Pass our Shortcode and UI args to Shortcake plugin - filterable */
	shortcode_ui_register_for_shortcode(
		'siu-item-updated',
		apply_filters( 'siu_filter_shortcode_ui_args', $shortcode_ui_args )
	);

}  // end function
