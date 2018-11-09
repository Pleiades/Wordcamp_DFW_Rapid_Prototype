<?php
/**
 * This file adds the Theme Defaults to the Divine Theme.
 *
 * @package      Divine
 * @subpackage   Customizations
 * @link         http://restored316designs.com/themes
 * @author       Lauren Gaige // Restored 316 LLC
 * @copyright    Copyright (c) 2015, Restored 316 LLC, Released 3/11/2015
 * @license      GPL-2.0+
 */

//* Divine Theme Setting Defaults
add_filter( 'genesis_theme_settings_defaults', 'divine_theme_defaults' );
function divine_theme_defaults( $defaults ) {

	$defaults['blog_cat_num']              = 5;
	$defaults['content_archive']           = 'full';
	$defaults['content_archive_limit']     = 500;
	$defaults['content_archive_thumbnail'] = 1;
	$defaults['image_size']                = 'large-featured';
	$defaults['image_alignment']           = 'alignnone';
	$defaults['posts_nav']                 = 'numeric';
	$defaults['site_layout']               = 'content-sidebar';

	return $defaults;

}

//* Divine Theme Setup
add_action( 'after_switch_theme', 'divine_theme_setting_defaults' );
function divine_theme_setting_defaults() {

	if( function_exists( 'genesis_update_settings' ) ) {

		genesis_update_settings( array(
			'blog_cat_num'              => 5,	
			'content_archive'           => 'full',
			'content_archive_limit'     => 500,
			'content_archive_thumbnail' => 1,
			'image_size'                => 'large-featured',
			'image_alignment'           => 'alignnone',
			'posts_nav'                 => 'numeric',
			'site_layout'               => 'content-sidebar',
		) );
	
	} 

	update_option( 'posts_per_page', 5 );

}

//* Divine Simple Social Icon Defaults
add_filter( 'simple_social_default_styles', 'divine_social_default_styles' );
function divine_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'aligncenter',
		'background_color'       => '#FFFFFF',
		'background_color_hover' => '#FFFFFF',
		'border_radius'          => 0,
		'border_color'           => '#FFFFFF',
		'border_color_hover'     => '#FFFFFF',
		'border_width'           => 0,
		'icon_color'             => '#596922',
		'icon_color_hover'       => '#333333',
		'size'                   => 30,
		'new_window'             => 1,
		);
		
	$args = wp_parse_args( $args, $defaults );
	
	return $args;
	
}

//* Set Genesis Responsive Slider defaults
add_filter( 'genesis_responsive_slider_settings_defaults', 'divine_responsive_slider_defaults' );
function divine_responsive_slider_defaults( $defaults ) {

	$args = array(
		'location_horizontal'             => 'Left',
		'location_vertical'               => 'Top',
		'posts_num'                       => '5',
		'slideshow_excerpt_content_limit' => '100',
		'slideshow_excerpt_content'       => 'full',
		'slideshow_excerpt_width'         => '30',
		'slideshow_height'                => '500',
		'slideshow_more_text'             => __( 'Read More', 'divine' ),
		'slideshow_title_show'            => 1,
		'slideshow_width'                 => '750',
	);

	$args = wp_parse_args( $args, $defaults );
	
	return $args;
}

//* Set option to show posts on front page after switching themes
add_action( 'after_switch_theme', 'divine_theme_reading_defaults' );
function divine_theme_reading_defaults() {
	if ( 'posts' != get_option( 'show_on_front' ) ) {
	
		update_option( 'show_on_front', 'posts' );
	
	}
}
