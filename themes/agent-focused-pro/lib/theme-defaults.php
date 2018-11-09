<?php

// Agent Focused Theme Setting Defaults.
add_filter( 'genesis_theme_settings_defaults', 'agentfocused_theme_defaults' );
function agentfocused_theme_defaults( $defaults ) {

	$defaults['blog_cat_num']              = 5;
	$defaults['content_archive']           = 'full';
	$defaults['content_archive_limit']     = 300;
	$defaults['content_archive_thumbnail'] = 1;
	$defaults['image_alignment']           = '';
	$defaults['image_size']                = 'af-featured-community';
	$defaults['posts_nav']                 = 'numeric';
	$defaults['site_layout']               = 'content-sidebar';

	return $defaults;

}

// Agent Focused Theme Setup.
add_action( 'after_switch_theme', 'agentfocused_theme_setting_defaults' );
function agentfocused_theme_setting_defaults() {

	if ( function_exists( 'genesis_update_settings' ) ) {

		genesis_update_settings( array(
			'blog_cat_num'              => 5,
			'content_archive'           => 'full',
			'content_archive_limit'     => 300,
			'content_archive_thumbnail' => 1,
			'image_alignment'           => '',
			'image_size'                => 'af-featured-community',
			'posts_nav'                 => 'numeric',
			'site_layout'               => 'content-sidebar',
		) );

	}

	update_option( 'posts_per_page', 5 );

}

// Simple Social Icon Defaults.
add_filter( 'simple_social_default_styles', 'agentfocused_social_default_styles' );
function agentfocused_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'alignleft',
		'background_color'       => '#ffffff',
		'background_color_hover' => '#666666',
		'border_color'           => '#ffffff',
		'border_color_hover'     => '#666666',
		'border_radius'          => 0,
		'border_width'           => 1,
		'icon_color'             => '#666666',
		'icon_color_hover'       => '#ffffff',
		'size'                   => 48,
		);

	$args = wp_parse_args( $args, $defaults );

	return $args;

}
