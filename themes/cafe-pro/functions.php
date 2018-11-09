<?php
/**
 * Cafe Pro.
 *
 * This file adds functions to the Cafe Pro Theme.
 *
 * @package Cafe
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/cafe/
 */

// Starts the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Sets up Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

add_action( 'after_setup_theme', 'cafe_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function cafe_localization_setup() {

	load_child_theme_textdomain( 'cafe-pro', get_stylesheet_directory() . '/languages' );

}

// Adds Image Upload and Color Selection to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Includes Section Image and Color CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', __( 'Cafe Pro', 'cafe-pro' ) );
define( 'CHILD_THEME_URL', 'https://my.studiopress.com/themes/cafe/' );
define( 'CHILD_THEME_VERSION', '1.0.5' );

add_action( 'wp_enqueue_scripts', 'cafe_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function cafe_enqueue_scripts_styles() {

	wp_enqueue_script( 'global-script', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'scrollTo', get_stylesheet_directory_uri() . '/js/jquery.scrollTo.min.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'localScroll', get_stylesheet_directory_uri() . '/js/jquery.localScroll.min.js', array( 'scrollTo' ), '2.0.0', true );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Dosis:400,600|Crimson+Text:400,400italic,700', array(), CHILD_THEME_VERSION );

}

// Adds HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Adds Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Adds viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Adds support for custom header.
add_theme_support( 'custom-header', array(
	'flex-height'     => true,
	'flex-width'      => true,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 140,
	'width'           => 350,
) );

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Adds support for footer menu.
add_theme_support( 'genesis-menus' , array( 'secondary' => __( 'Before Header Menu', 'cafe-pro' ), 'primary' => __( 'After Header Menu', 'cafe-pro' ), 'footer' => __( 'Footer Menu', 'cafe-pro' ) ) );

// Unregisters layout settings.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Unregisters the header right widget area.
unregister_sidebar( 'header-right' );

// Unregisters secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav', 11 );

add_action( 'genesis_footer', 'cafe_footer_menu', 7 );
/**
 * Outputs the menu in the footer.
 *
 * @since 1.0.0
 */
function cafe_footer_menu() {

	genesis_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => false,
		'menu_class'     => 'genesis-nav-menu',
	) );

}

// Adds Attributes for Footer Navigation.
add_filter( 'genesis_attr_nav-footer', 'genesis_attributes_nav' ); 

add_action( 'genesis_before_header', 'cafe_before_header' );
/**
 * Adds the before header widget area.
 *
 * @since 1.0.0
 */
function cafe_before_header() {

	genesis_widget_area( 'before-header', array(
		'before' => '<div class="before-header widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

// Repositions the post info.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 5 );

add_filter( 'genesis_author_box_gravatar_size', 'cafe_author_box_gravatar' );
/**
 * Modifies the size of the Gravatar in the author box.
 *
 * @since 1.0.0
 *
 * @param int $size Current Gravatar size.
 * @return int New size.
 */
function cafe_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'cafe_comments_gravatar' );
/**
 * Modifies the size of the Gravatar in the entry comments.
 *
 * @since 1.0.0
 *
 * @param array $args The comment list arguments.
 * @return array Arguments with new avatar size.
 */
function cafe_comments_gravatar( $args ) {

	$args['avatar_size'] = 48;

	return $args;

}

add_filter( 'genesis_footer_output', 'cafe_custom_footer' );
/**
 * Modifies the site footer content and markup.
 *
 * @since 1.0.0
 *
 * @param string $output Current site footer content.
 * @return string New site footer content.
 */
function cafe_custom_footer( $output ) {

	$output = sprintf( '<p>%s<span class="dashicons dashicons-heart"></span>%s<a href="https://www.studiopress.com/">%s</a></p>',  __( 'Handcrafted with ', 'cafe-pro' ), __( ' on the', 'cafe-pro' ), __( ' Genesis Framework', 'cafe-pro' ) );

	return $output;

}

add_filter( 'genesis_nav_items', 'cafe_nav_site_title', 10, 2 );
add_filter( 'wp_nav_menu_items', 'cafe_nav_site_title', 10, 2 );
/**
 * Adds Site Title to Primary Nav.
 *
 * @since 1.0.0
 *
 * @param string $menu The menu markup.
 * @param string|array $args Menu arguments. 
 * @return string The new menu markup.
 */
function cafe_nav_site_title( $menu, $args ) {

	$args = (array)$args;

	if ( 'primary' !== $args['theme_location']  ) {
		return $menu;
	}

	$output = sprintf( '<li class="small-site-title"><a href="%s">%s</a></li>', trailingslashit( home_url() ), get_bloginfo( 'name' ) );

	return $output . $menu;

}

/**
 * Counts used widgets in given sidebar.
 *
 * @since 1.0.0
 *
 * @param string $id The sidebar ID.
 * @return int|void The number of widgets, or nothing.
 */
function cafe_count_widgets( $id ) {

	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

/**
 * Outputs class names based on widget count.
 *
 * @since 1.0.0
 *
 * @param string $id The widget ID.
 * @return string The class.
 */
function cafe_widget_area_class( $id ) {

	$count = cafe_count_widgets( $id );

	$class = '';

	if( $count == 1 || $count < 9 ) {

		$classes = array(
			'zero',
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
		);

		$class = $classes[ $count ] . '-widget';
		$class = $count == 1 ? $class : $class . 's';

		return $class;

	} else {

		$class = 'widget-thirds';
		
		return $class;

	}

}

// Registers widget areas.
genesis_register_sidebar( array(
	'id'          => 'before-header',
	'name'        => __( 'Before Header', 'cafe-pro' ),
	'description' => __( 'This is the section before the header.', 'cafe-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'cafe-pro' ),
	'description' => __( 'This is the Front Page 1 section.', 'cafe-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'cafe-pro' ),
	'description' => __( 'This is the Front Page 2 section.', 'cafe-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'cafe-pro' ),
	'description' => __( 'This is the Front Page 3 section.', 'cafe-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'cafe-pro' ),
	'description' => __( 'This is the Front Page 4 section.', 'cafe-pro' ),
) );
