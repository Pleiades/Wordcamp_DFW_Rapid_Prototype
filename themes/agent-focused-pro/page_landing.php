<?php
/**
 * Landing Page template for Agent Focused Pro.
 *
 * @package Agent Focused Pro
 * @author Marcy Diaz for Winning Agent
 * @subpackage Customizations
 */

/*
Template Name: Landing Page
*/

// Add custom body class.
add_filter( 'body_class', 'agentfocused_add_body_class' );
function agentfocused_add_body_class( $classes ) {

	$classes[] = 'landing-page';
	return $classes;

}

// Force full width content layout.
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

// Remove site header and markup.
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

// Remove header menu.
remove_action( 'genesis_header', 'genesis_do_nav', 11 );

// Remove breadcrumbs.
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

// Remove site footer widgets.
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

// Remove site footer menu and markup.
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_subnav', 8 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

// Remove footer bottom widget.
remove_action( 'genesis_after_footer', 'agent_add_footer_bottom_widget_area', 12 );

// Run Genesis.
genesis();
