<?php
/**
 * Cafe Pro.
 *
 * This file adds the default theme settings to the Cafe Pro Theme.
 *
 * @package Cafe
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/cafe/
 */

add_filter( 'genesis_theme_settings_defaults', 'cafe_theme_defaults' );
/**
 * Updates theme settings on reset.
 *
 * @since 1.0.0
 *
 * @param array $defaults Default theme settings.
 * @return array Modified defaults.
 */
function cafe_theme_defaults( $defaults ) {

	$defaults['blog_cat_num']              = 4;
	$defaults['content_archive']           = 'full';
	$defaults['content_archive_limit']     = 0;
	$defaults['content_archive_thumbnail'] = 0;
	$defaults['image_alignment']           = 'alignleft';
	$defaults['posts_nav']                 = 'numeric';
	$defaults['site_layout']               = 'content-sidebar';

	return $defaults;

}

add_action( 'after_switch_theme', 'cafe_theme_setting_defaults' );
/**
 * Updates theme settings on activation.
 *
 * @since 1.0.0
 */
function cafe_theme_setting_defaults() {

	if( function_exists( 'genesis_update_settings' ) ) {

		genesis_update_settings( array(
			'blog_cat_num'              => 4,
			'content_archive'           => 'full',
			'content_archive_limit'     => 0,
			'content_archive_thumbnail' => 0,
			'image_alignment'           => 'alignleft',
			'posts_nav'                 => 'numeric',
			'site_layout'               => 'content-sidebar',
		) );

	}

	update_option( 'posts_per_page', 4 );

}

add_filter( 'simple_social_default_styles', 'cafe_social_default_styles' );
/**
 * Updates Simple Social Icon settings on activation.
 *
 * @since 1.0.0
 */
function cafe_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'aligncenter',
		'background_color'       => '#a0ac48',
		'background_color_hover' => '#000000',
		'border_radius'          => 0,
		'icon_color'             => '#ffffff',
		'icon_color_hover'       => '#ffffff',
		'size'                   => 36,
		);

	$args = wp_parse_args( $args, $defaults );

	return $args;

}
