<?php
/**
 * Shared shortcode engine for plugin and standalone snippets.
 * Copyright © 2015–2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'DDW_Shortcode_Item_Updated', false ) ) {
	/** Resolves explicit/current posts and renders their latest eligible modification date. */
	class DDW_Shortcode_Item_Updated {
		const VERSION = '2.3.0';

		/**
		 * Register the existing shortcode without persistent data or frontend assets.
		 * @return void No return value.
		 */
		public function __construct() {
			add_shortcode( 'siu-item-updated', array( $this, 'item_updated' ) );
		}

		/**
		 * Normalize historical boolean attributes, including German yes.
		 * @param mixed $param Attribute value.
		 * @return string Either yes or no.
		 */
		public function yes_no( $param = '' ) {
			return is_scalar( $param ) && in_array( strtolower( trim( (string) $param ) ), array( 'yes', 'ja' ), true ) ? 'yes' : 'no';
		}

		/**
		 * Select the page language before considering the site locale.
		 * @return bool Whether the rendered page is German.
		 */
		private function is_german() {
			// Respect explicit locale switching, including previews and rendering jobs.
			if ( function_exists( 'is_locale_switched' ) && is_locale_switched() ) {
				return 0 === strpos( get_locale(), 'de' );
			}
			$lang = apply_filters( 'wpml_current_language', null );
			if ( is_string( $lang ) && '' !== $lang ) {
				return in_array( strtolower( $lang ), array( 'de', 'at' ), true );
			}
			if ( function_exists( 'pll_current_language' ) ) {
				$lang = pll_current_language( 'slug' );
				if ( is_string( $lang ) && '' !== $lang ) {
					return in_array( strtolower( $lang ), array( 'de', 'at' ), true );
				}
			}
			return 0 === strpos( get_locale(), 'de' );
		}

		/**
		 * Provide the original defaults with the existing German snippet fallback.
		 * @param string $type Either sep or label_before.
		 * @return string Localized plain text, or empty for an unknown key.
		 */
		private function strings( $type ) {
			if ( 'sep' === $type ) {
				return $this->is_german() ? ', um' : _x( "\xc2\xa0@", 'Separator between date and time', 'shortcode-item-updated' );
			}
			if ( 'label_before' === $type ) {
				return $this->is_german() ? 'Zuletzt aktualisiert:' : _x( 'Last updated:', 'Label before date and time', 'shortcode-item-updated' );
			}
			return '';
		}

		/**
		 * Parse a positive decimal post ID without converting malformed strings.
		 * @param mixed $value Supplied ID.
		 * @return int Positive ID, or zero on failure.
		 */
		private function post_id( $value ) {
			if ( ! is_scalar( $value ) || ! preg_match( '/^[1-9][0-9]*$/D', trim( (string) $value ) ) ) {
				return 0;
			}
			$id = filter_var( trim( (string) $value ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
			return false === $id ? 0 : $id;
		}

		/**
		 * Resolve a fixed list; explicit post_ids takes precedence over post_id.
		 * @param array $atts Normalized shortcode attributes.
		 * @return int[] Unique IDs, capped at 100; malformed/oversized lists fail closed.
		 */
		private function source_ids( array $atts ) {
			if ( '' === trim( $atts['post_ids'] ) ) {
				$id = $this->post_id( $atts['post_id'] );
				return $id ? array( $id ) : array();
			}
			$tokens = explode( ',', $atts['post_ids'] );
			if ( count( $tokens ) > 100 ) { return array(); }
			$ids = array();
			foreach ( $tokens as $token ) {
				$id = $this->post_id( $token );
				if ( ! $id ) { return array(); }
				$ids[] = $id;
			}
			return array_values( array_unique( $ids ) );
		}

		/**
		 * Read a valid site-local database datetime without silently normalizing invalid dates.
		 * @param WP_Post $post Resolved post object.
		 * @param string $field Either modified or date.
		 * @return DateTimeImmutable|false Valid site-timezone datetime or false.
		 */
		private function datetime( $post, $field ) {
			$raw = 'modified' === $field ? $post->post_modified : $post->post_date;
			if ( ! preg_match( '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/D', $raw ) || '0000-00-00 00:00:00' === $raw ) {
				return false;
			}
			$date = get_post_datetime( $post, $field );
			return $date && $date->format( 'Y-m-d H:i:s' ) === $raw ? $date : false;
		}

		/**
		 * Choose the most recently modified public, unprotected, eligible source.
		 * @param array $atts Normalized shortcode attributes.
		 * @return DateTimeImmutable|false Latest eligible date; no permission-dependent output.
		 */
		private function latest( array $atts ) {
			$latest = false;
			$only = 'yes' === $this->yes_no( $atts['only_if_updated'] );
			$gap = preg_match( '/^[0-9]+$/D', $atts['min_update_gap'] ) ? filter_var( $atts['min_update_gap'], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0 ) ) ) : false;
			if ( false === $gap ) { return false; }
			foreach ( $this->source_ids( $atts ) as $id ) {
				$post = get_post( $id );
				// Identical visibility for logged-in users and visitors avoids leaking dates through page caches.
				if ( ! $post || ! is_post_publicly_viewable( $post ) || '' !== $post->post_password ) { continue; }
				$modified = $this->datetime( $post, 'modified' );
				if ( ! $modified ) { continue; }
				if ( $only ) {
					$published = $this->datetime( $post, 'date' );
					if ( ! $published ) { continue; }
					$delta = $modified->getTimestamp() - $published->getTimestamp();
					if ( $delta <= 0 || $delta < $gap ) { continue; }
				}
				if ( ! $latest || $modified->getTimestamp() > $latest->getTimestamp() ) { $latest = $modified; }
			}
			return $latest;
		}

		/**
		 * Render shortcode output using the site's timezone and locale.
		 * @param array|string $atts Shortcode attributes; WordPress also passes an empty string.
		 * @return string Escaped markup/plain text, or empty for an unavailable source/date.
		 */
		public function item_updated( $atts ) {
			$base = array(
				'post_id' => get_the_ID(), 'post_ids' => '',
				'date_format' => get_option( 'date_format' ), 'time_format' => get_option( 'time_format' ),
				'show_date' => 'yes', 'show_time' => 'no', 'show_sep' => 'no', 'sep' => $this->strings( 'sep' ),
				'show_label' => 'no', 'label_before' => $this->strings( 'label_before' ), 'label_after' => '',
				'class' => '', 'wrapper' => 'span', 'only_if_updated' => 'no', 'min_update_gap' => '0',
				'semantic' => 'no', 'output' => 'html', 'display' => 'absolute',
			);
			/**
			 * Filter default attributes; return an array of shortcode defaults.
			 * @since 1.0.0
			 * @param array $defaults Default attribute values.
			 */
			$defaults = apply_filters( 'siu_filter_shortcode_defaults', $base );
			$defaults = is_array( $defaults ) ? array_merge( $base, $defaults ) : $base;
			$atts = shortcode_atts( $defaults, is_array( $atts ) ? $atts : array(), 'siu-item-updated' );
			foreach ( $base as $key => $fallback ) {
				$atts[ $key ] = isset( $atts[ $key ] ) && is_scalar( $atts[ $key ] ) ? (string) $atts[ $key ] : (string) $fallback;
			}
			$date = $this->latest( $atts );
			if ( ! $date ) { return ''; }
			$show_date = 'yes' === $this->yes_no( $atts['show_date'] );
			$show_time = 'yes' === $this->yes_no( $atts['show_time'] );
			if ( ! $show_date && ! $show_time ) { return ''; }
			$text = '';
			if ( 'relative' === strtolower( $atts['display'] ) ) {
				$stamp = $date->getTimestamp();
				$now = time();
				$diff = human_time_diff( $stamp, $now );
				if ( $this->is_german() ) {
					$text = sprintf( $stamp > $now ? 'in %s' : 'vor %s', $diff );
				} else {
					/* translators: %s: localized duration, such as 3 days. */
					$text = sprintf( $stamp > $now ? __( 'in %s', 'shortcode-item-updated' ) : __( '%s ago', 'shortcode-item-updated' ), $diff );
				}
			} else {
				$format = $atts['date_format'];
				$shortcuts = array( 'de' => 'd.m.Y', 'us' => 'Y-m-d', 'iso' => 'Y-m-d' );
				if ( isset( $shortcuts[ strtolower( $format ) ] ) ) { $format = $shortcuts[ strtolower( $format ) ]; }
				if ( $show_date ) { $text = (string) wp_date( $format, $date->getTimestamp(), $date->getTimezone() ); }
				if ( $show_time ) {
					if ( $show_date && 'yes' === $this->yes_no( $atts['show_sep'] ) ) {
						$text .= html_entity_decode( $atts['sep'], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
					}
					$text .= ( $show_date ? ' ' : '' ) . wp_date( $atts['time_format'], $date->getTimestamp(), $date->getTimezone() );
					if ( '' !== $atts['label_after'] ) { $text .= ' ' . $atts['label_after']; }
				}
			}
			$label = 'yes' === $this->yes_no( $atts['show_label'] ) && '' !== $atts['label_before'] ? $atts['label_before'] . ' ' : '';
			if ( 'text' === strtolower( $atts['output'] ) ) {
				$output = wp_strip_all_tags( $label . $text );
			} else {
				$inner = esc_html( $text );
				if ( 'yes' === $this->yes_no( $atts['semantic'] ) ) {
					$inner = '<time datetime="' . esc_attr( $date->format( DATE_W3C ) ) . '">' . $inner . '</time>';
				}
				$wrapper = strtolower( $atts['wrapper'] );
				if ( ! in_array( $wrapper, array( 'span', 'div', 'p', 'small', 'strong', 'em', 'time', 'li', 'dt', 'dd', 'figcaption', 'section', 'article', 'aside', 'footer', 'header', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) { $wrapper = 'span'; }
				// A time wrapper itself carries datetime; never produce nested time elements.
				if ( 'time' === $wrapper ) { $inner = esc_html( $text ); }
				$classes = array( 'item-last-updated' );
				foreach ( preg_split( '/\s+/', trim( $atts['class'] ) ) as $class ) {
					$class = sanitize_html_class( $class );
					if ( '' !== $class ) { $classes[] = $class; }
				}
				$output = '<' . $wrapper . ' class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '"' . ( 'time' === $wrapper ? ' datetime="' . esc_attr( $date->format( DATE_W3C ) ) . '"' : '' ) . '>' . esc_html( $label ) . $inner . '</' . $wrapper . '>';
			}
			/**
			 * Filter final output; return HTML or plain text appropriate to output mode.
			 * @since 1.0.0
			 * @param string $output Rendered output. Trusted callbacks must escape their additions.
			 * @param array $atts Resolved attributes, including custom attributes supplied by filters.
			 */
			return apply_filters( 'siu_filter_shortcode_item_updated', $output, $atts );
		}
	}
}
