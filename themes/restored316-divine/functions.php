<?php
/**
 * Divine.
 *
 * @package      Divine
 * @link         http://restored316designs.com/themes
 * @author       Lauren Gaige // Restored 316 LLC
 * @copyright    Copyright (c) 2015, Restored 316 LLC, Released 3/11/2015
 * @license      GPL-2.0+
 */

//* Start the engine
require_once( get_template_directory() . '/lib/init.php' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Add Color Selection to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Divine' );
define( 'CHILD_THEME_URL', 'http://restored316designs.com' );
define( 'CHILD_THEME_VERSION', '1.0.6' );

//* Install Plugins
require_once( get_stylesheet_directory() . '/lib/plugins/tgm-plugin-activation/register-plugins.php' );

//* Enqueue Google fonts, Responsive Menu, and Dashicons
add_action( 'wp_enqueue_scripts', 'divine_google_fonts' );
function divine_google_fonts() {
	wp_enqueue_script( 'divine-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=EB+Garamond|Open+Sans:400,300italic,300,400italic,600,600italic,700,700italic,800,800italic|Source+Serif+Pro', array() );
	wp_enqueue_style( 'dashicons' );
}

//* Add new image sizes
add_image_size( 'blog-square-featured', 400, 400, TRUE );
add_image_size( 'blog-vertical-featured', 400, 600, TRUE );
add_image_size( 'sidebar-featured', 125, 125, TRUE );
add_image_size( 'large-featured', 750, 500, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 660,
	'height'          => 272,
	'flex-width'      => false,
	'flex-height'     => false,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add support for 2-column footer widgets
add_theme_support( 'genesis-footer-widgets', 2 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Hooks widget area above header
add_action( 'genesis_before', 'divine_widget_above_header'  ); 
function divine_widget_above_header() {

    genesis_widget_area( 'widget-above-header', array(
		'before' => '<div class="widget-above-header widget-area"><div class="wrap">',
		'after'  => '</div></div>',
    ) );

}

//* Reposition the secondary navigation
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before', 'genesis_do_subnav' );

//* Add widget to secondary navigation
add_filter( 'genesis_nav_items', 'divine_social_icons', 10, 2 );
add_filter( 'wp_nav_menu_items', 'divine_social_icons', 10, 2 );

function divine_social_icons($menu, $args) {
	$args = (array)$args;
	if ( 'secondary' !== $args['theme_location'] )
		return $menu;
	ob_start();
	genesis_widget_area('nav-social-menu');
	$social = ob_get_clean();
	return $menu . $social;
}

//* Position post info above post title
remove_action( 'genesis_entry_header', 'genesis_post_info', 12);
add_action( 'genesis_entry_header', 'genesis_post_info', 9 ); 

//* Customize the Post Info Function
add_filter( 'genesis_post_info', 'divine_post_info_filter' );
function divine_post_info_filter( $post_info ) {

	$post_info = '[post_categories before="" sep=""]';
    return $post_info;

}

//* Customize the Post Meta function
add_filter( 'genesis_post_meta', 'divine_post_meta_filter' );
function divine_post_meta_filter( $post_meta ) {

    $post_meta = '<span class="dashicons dashicons-format-chat"></span><br>[post_comments]';
    return $post_meta;

}

//* Add post navigation (requires HTML5 support)
add_action( 'genesis_entry_footer', 'genesis_prev_next_post_nav', 15 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Hooks before-content widget area to single posts
add_action( 'genesis_before_content', 'divine_before_content'  ); 
function divine_before_content() {

    if ( ! is_home() )
    	return;

    genesis_widget_area( 'before-content', array(
		'before' => '<div class="before-content widget-area"><div class="wrap">',
		'after'  => '</div></div>',
    ) );

}

//* Add split sidebars underneath the primary sidebar
add_action( 'genesis_after_sidebar_widget_area', 'divine_split_sidebars' );
function divine_split_sidebars() {
	foreach ( array( 'sidebar-split-left', 'sidebar-split-right', 'sidebar-split-bottom' ) as $area ) {
		echo '<div class="' . $area . '">';
		dynamic_sidebar( $area );
		echo '</div><!-- end #' . $area . '-->';
	}
}

//* Modify the Genesis content limit read more link
add_filter( 'get_the_content_more_link', 'divine_read_more_link' );
function divine_read_more_link() {
	return '... <a class="more-link" href="' . get_permalink() . '">continue reading...</a>';
}

//* Hooks widget area before footer
add_action( 'genesis_after', 'divine_widget_before_footer'  ); 
function divine_widget_before_footer() {

    genesis_widget_area( 'widget-before-footer', array(
		'before' => '<div class="widget-before-footer widget-area"><div class="wrap">',
		'after'  => '</div></div>',
    ) );

}

//* Reposition the footer widgets
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_after', 'genesis_footer_widget_areas' );

//* Reposition the footer
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
add_action( 'genesis_after', 'genesis_footer_markup_open', 11 );
add_action( 'genesis_after', 'genesis_do_footer', 12 );
add_action( 'genesis_after', 'genesis_footer_markup_close', 13 ); 

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'divine_remove_comment_form_allowed_tags' );
function divine_remove_comment_form_allowed_tags( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults; 
}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'divine_comments_gravatar' );
function divine_comments_gravatar( $args ) {

	$args['avatar_size'] = 96;

	return $args;

}

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'divine_author_box_gravatar' );
function divine_author_box_gravatar( $size ) {

	return 125;

}

//* Customize the credits 
add_filter('genesis_footer_creds_text', 'divine_custom_footer_creds_text');
function divine_custom_footer_creds_text() {
    echo '<div class="creds"><p>';
    echo 'Copyright &copy; ';
    echo date('Y');
    echo ' &middot; <a target="_blank" href="http://restored316designs.com/themes">Divine theme</a> by <a target="_blank" href="http://www.restored316designs.com">Restored 316</a>';
    echo '</p></div>';

}

//* Add Theme Support for WooCommerce
add_theme_support( 'genesis-connect-woocommerce' );

//* Remove Related Products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//* Remove Add to Cart on Archives
add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );
function remove_add_to_cart_buttons() {

    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

}

//* Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

//* Display 12 products per page
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

//* Add WooCommerce Gallery Options
add_action( 'after_setup_theme', 'divine_woo_gallery' );
function divine_woo_gallery() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}


//* Register widget areas
genesis_register_sidebar( array(
	'id'			=> 'sidebar-split-left',
	'name'			=> __( 'Sidebar Split Left', 'divine' ),
	'description'	=> __( 'This is the left side of the split sidebar', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'sidebar-split-right',
	'name'			=> __( 'Sidebar Split Right', 'divine' ),
	'description'	=> __( 'This is the right side of the split sidebar', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'sidebar-split-bottom',
	'name'			=> __( 'Sidebar Split Bottom', 'divine' ),
	'description'	=> __( 'This is the bottom of the split sidebar', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'before-content',
	'name'        => __( 'Home - Before Content', 'divine' ),
	'description' => __( 'This is the slider section on the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-slider',
	'name'        => __( 'Home - Slider', 'divine' ),
	'description' => __( 'This is the slider section on the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'divine' ),
	'description' => __( 'This is the top section of the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-triple-bottom',
	'name'        => __( 'Home - Triple Bottom', 'divine' ),
	'description' => __( 'This is the bottom section of the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-double-bottom',
	'name'        => __( 'Home - Double Bottom', 'divine' ),
	'description' => __( 'This is the bottom section of the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'category-index',
	'name'        => __( 'Category Index', 'divine' ),
	'description' => __( 'This is the category index for the category index page template.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'widget-above-header',
	'name'			=> __( 'Widget Above Header', 'divine' ),
	'description'	=> __( 'This is the widget area above the header', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'widget-before-footer',
	'name'			=> __( 'Widget Before Footer', 'divine' ),
	'description'	=> __( 'This is the widget area above the header', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'nav-social-menu',
	'name'        => __( 'Nav Social Menu', 'divine' ),
	'description' => __( 'This is the nav social menu section.', 'divine' ),
) );
