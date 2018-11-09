<?php
/**
 * Cafe Pro.
 *
 * This file adds the landing page template to the Cafe Pro Theme.
 *
 * Template Name: Landing
 *
 * @package Cafe
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/cafe/
 */

add_filter( 'body_class', 'cafe_add_body_class' );
/**
 * Adds the landing page body class to the head.
 *
 * @since 1.0.0
 *
 * @param array $classes Current list of classes.
 * @return array New classes.
 */
function cafe_add_body_class( $classes ) {

	$classes[] = 'cafe-landing';

	return $classes;

}

// Removes Skip Links.
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

add_action( 'wp_enqueue_scripts', 'cafe_dequeue_skip_links' );
/**
 * Dequeues the skip links script.
 *
 * @since 1.0.0
 */
function cafe_dequeue_skip_links() {

	wp_dequeue_script( 'skip-links' );

}

// Forces full width content layout.
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

// Removes Before Header widget area.
remove_action( 'genesis_before_header', 'cafe_before_header' );

// Removes site header elements.
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

// Removes navigation.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_before_header', 'genesis_do_subnav', 11 );
remove_action( 'genesis_footer', 'rainmaker_footer_menu', 7 );

// Removes breadcrumbs.
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

// Removes site footer widgets.
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

// Removes site footer elements.
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

// Runs the Genesis loop.
genesis();
