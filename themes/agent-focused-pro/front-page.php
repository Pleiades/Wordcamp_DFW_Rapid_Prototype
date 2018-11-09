<?php
/**
 * Front Page template for Agent Focused Pro.
 *
 * @package Agent Focused Pro
 * @author Marcy Diaz for Winning Agent
 * @subpackage Customizations
 */

// Enqueue scripts for background image.
add_action( 'wp_enqueue_scripts', 'agentfocused_front_page_enqueue_scripts' );
function agentfocused_front_page_enqueue_scripts() {

	wp_enqueue_style( 'agentfocused-front-page-styles', get_stylesheet_directory_uri() . '/style-front.css', array(), CHILD_THEME_VERSION );

	$image = get_option( 'agentfocused-front-image', sprintf( '%s/images/front-page-image.jpg', get_stylesheet_directory_uri() ) );

	// Load scripts only if custom background is being used.
	if ( ! empty( $image ) && is_active_sidebar( 'front-page-1' ) ) {

		// Enqueue Backstretch scripts.
		wp_enqueue_script( 'agentfocused-backstretch', get_stylesheet_directory_uri() . '/js/backstretch.js', array( 'jquery' ), '1.0.0' );

		wp_enqueue_script( 'agentfocused-backstretch-set', get_stylesheet_directory_uri() . '/js/backstretch-set.js' , array( 'jquery', 'agentfocused-backstretch' ), '1.0.0' );

		wp_localize_script( 'agentfocused-backstretch-set', 'BackStretchImg', array( 'src' => str_replace( 'http:', '', $image ) ) );

	}

	// Enqueue Modernizr for object-fit.
	wp_enqueue_script( 'agentfocused-modernizr-object-fit', get_stylesheet_directory_uri() . '/js/modernizr-custom.js', array( 'jquery' ), '1.0.0' );
}

// Add widget areas to front page. Display the blog posts, if no active widgets.
add_action( 'genesis_meta', 'agentfocused_front_page_genesis_meta' );
function agentfocused_front_page_genesis_meta() {

	if ( is_active_sidebar( 'front-page-1' ) || is_active_sidebar( 'search-bar' ) || is_active_sidebar( 'front-page-2-left' )|| is_active_sidebar( 'front-page-2-right' ) || is_active_sidebar( 'front-page-3' ) || is_active_sidebar( 'front-page-4' ) || is_active_sidebar( 'front-page-5' ) || is_active_sidebar( 'front-page-6' ) ) {

		// Add front-page body class.
		add_filter( 'body_class', 'agentfocused_front_page_body_class' );

		// Force full width content layout.
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

		// Remove breadcrumbs.
		remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

		// Remove the default Genesis loop.
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		// Add the front page widget areas.
		add_action( 'genesis_loop', 'agentfocused_front_page_widgets' );

	}

	if ( is_active_sidebar( 'front-page-1' ) ) {

		// Add front-page-1 widget area.
		add_action( 'genesis_after_header', 'agentfocused_front_page_image_widget' );

	}

}

function agentfocused_front_page_body_class( $classes ) {

		$classes[] = 'front-page';
		return $classes;

}

function agentfocused_front_page_image_widget() {

	genesis_widget_area( 'front-page-1', array(
		'before' => '<div id="front-page-1" class="front-page-1"><div class="wrap"><div class="flexible-widgets widget-area' . agentfocused_widget_area_class( 'front-page-1' ) . '">',
		'after'  => '</div></div></div>',
	) );

}

function agentfocused_front_page_widgets() {

	genesis_widget_area( 'search-bar', array(
		'before' => '<div id="search-bar" class="search-bar"><div class="wrap"><div class="widget-area">',
		'after'  => '</div></div></div>',
	) );

	if ( is_active_sidebar( 'front-page-2-left' ) || is_active_sidebar( 'front-page-2-right' ) ) {

		echo '<div id="front-page-2" class="front-page-2"><div class="wrap">';

			genesis_widget_area( 'front-page-2-left', array(
				'before' => '<div class="front-page-2-left widget-area">',
				'after'  => '</div>',
			) );
			genesis_widget_area( 'front-page-2-right', array(
				'before' => '<div class="front-page-2-right widget-area">',
				'after'  => '</div>',
			) );

			echo '</div></div>';

	}

	genesis_widget_area( 'front-page-3', array(
		'before' => '<div id="front-page-3" class="front-page-3"><div class="wrap"><div class="widget-area">',
		'after'  => '</div></div></div>',
	) );

	genesis_widget_area( 'front-page-4', array(
		'before' => '<div id="front-page-4" class="front-page-4"><div class="wrap"><div class="flexible-widgets widget-area' . agentfocused_widget_area_class( 'front-page-4' ) . '">',
		'after'  => '</div></div></div>',
	) );

	genesis_widget_area( 'front-page-5', array(
		'before' => '<div id="front-page-5" class="front-page-5"><div class="wrap"><div class="widget-area">',
		'after'  => '</div></div></div>',
	) );

	genesis_widget_area( 'front-page-6', array(
		'before' => '<div id="front-page-6" class="front-page-6"><div class="wrap"><div class="flexible-widgets widget-area' . agentfocused_widget_area_class( 'front-page-6' ) . '">',
		'after'  => '</div></div></div>',
	) );

}

// Run Genesis.
genesis();
