<?php
/**
 * Adjusts default output.
 *
 * @package   Cookd
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

add_filter( 'simple_social_default_styles', 'cookd_social_default_styles' );
/**
 * Override the default settings in the Simple Social Icons plugin.
 *
 * @since  1.0.0
 * @access public
 * @param  array $defaults the default settings.
 * @return array $defaults the overridden default settings.
 */
function cookd_social_default_styles( $defaults ) {
	return wp_parse_args( array(
		'alignment'              => 'aligncenter',
		'background_color'       => '#ffffff',
		'background_color_hover' => '#f04848',
		'border_radius'          => 60,
		'icon_color'             => '#000000',
		'icon_color_hover'       => '#ffffff',
		'size'                   => 60,
	), $defaults );
}

add_filter( 'shortcode_atts_post_categories', 'cookd_limited_post_categories_atts', 10, 3 );
/**
 * Set a default attribute for the post categories limit.
 *
 * @since  1.0.0
 * @access public
 * @param  array $out The output array of shortcode attributes.
 * @param  array $pairs The supported attributes and their defaults.
 * @param  array $atts The user defined shortcode attributes.
 * @return array
 */
function cookd_limited_post_categories_atts( $out, $pairs, $atts ) {
	$out['limit'] = 1;

	if ( isset( $atts['limit'] ) ) {
		$out['limit'] = $atts['limit'];
	}

	return $out;
}

add_filter( 'genesis_post_categories_shortcode', 'cookd_limited_post_categories', 10, 2 );
/**
 * Filter the Genesis post categories shortcode to handle a limit attribute.
 *
 * @since  1.0.0
 * @access public
 * @param  string $output The default shortcode output.
 * @param  array  $atts The default shortcode attributes.
 * @return string
 */
function cookd_limited_post_categories( $output, $atts ) {
	if ( ! isset( $atts['limit'] ) ) {
		return $output;
	}

	$limit = absint( $atts['limit'] );
	$cats  = explode( $atts['sep'], $output );

	if ( $limit >= count( $cats ) ) {
		return $output;
	}

	$count = 0;

	foreach ( $cats as $key => $cat ) {
		$count++;
		if ( $limit < $count ) {
			unset( $cats[ $key ] );
		}
	}

	$output = implode( $atts['sep'], $cats );

	return apply_filters( 'cookd_limited_post_categories', $output, $atts );
}
