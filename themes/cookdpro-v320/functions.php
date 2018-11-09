<?php
/**
 * Theme functions
 *
 * @package   Cookd
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'ABSPATH' ) || exit;

require_once untrailingslashit( get_template_directory() ) . '/lib/init.php';

define( 'CHILD_THEME_NAME', 'Cookd Pro Theme' );
define( 'CHILD_THEME_VERSION', '3.2.0' );
define( 'CHILD_THEME_URL', 'https://feastdesignco.com/product/cookd-pro/' );
define( 'CHILD_THEME_DEVELOPER', 'Feast Design Co.' );
define( 'COOKD_DIR', trailingslashit( get_stylesheet_directory() ) ); // deprecated
define( 'COOKD_URI', trailingslashit( get_stylesheet_directory_uri() ) ); // deprecated
define( 'FEAST_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'FEAST_URI', trailingslashit( get_stylesheet_directory_uri() ) );

add_theme_support( 'genesis-responsive-viewport' );

add_theme_support( 'html5' );

add_theme_support( 'custom-background' );

add_theme_support( 'genesis-accessibility', array(
	'headings',
	'search-form',
	'skip-links',
) );

add_theme_support( 'custom-header', array(
	'width'           => 640,
	'flex-width'	  => true,
	'height'          => 340,
	'flex-height'	  => true,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

add_theme_support( 'genesis-footer-widgets', 4 );

add_theme_support( 'genesis-after-entry-widget-area' );

add_theme_support( 'genesis-connect-woocommerce' );

genesis_unregister_layout( 'content-sidebar-sidebar' );

genesis_unregister_layout( 'sidebar-sidebar-content' );

genesis_unregister_layout( 'sidebar-content-sidebar' );

genesis_register_sidebar( array(
	'id'          => 'recipe-index',
	'name'        => __( 'Recipe Index Sidebar', 'cookd' ),
	'description' => __( 'This is the sidebar for the recipe index.', 'cookd' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Homepage: 3 Featured Top', 'cookd' ),
	'description' => __( 'This is the homepage top section, which is designed for the "Cookd Pro - Featured Posts" widget with one-third columns. These 3 posts are oversized and are designed to feature your most popular or more recent posts.', 'cookd' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Homepage: Accessory Top', 'cookd' ),
	'description' => __( 'This is the homepage middle section, which is designed for a call-to-action such as the "Genesis - eNews Extended" widget and the "Cookd Pro - Featured Posts" widget.', 'cookd' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-bottom',
	'name'        => __( 'Homepage: Bottom', 'cookd' ),
	'description' => __( 'This is the homepage section that appears next to the "Primary Sidebar", which is designed for the "Cookd Pro - Featured Posts" widget.', 'cookd' ),
) );

require_once COOKD_DIR . 'lib/customize/init.php';
require_once COOKD_DIR . 'lib/defaults.php';
require_once COOKD_DIR . 'lib/helpers.php';
require_once COOKD_DIR . 'lib/facetwp.php';

if ( is_admin() ) {
	require_once COOKD_DIR . 'lib/admin/functions.php';
}

add_action( 'after_setup_theme', 'cookd_content_width', 0 );
/**
 * Set the content width and allow it to be filtered directly.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cookd_content_width', 980 );
}

add_action( 'after_setup_theme', 'cookd_load_textdomain' );
/**
 * Load the child theme textdomain.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_load_textdomain() {
	load_child_theme_textdomain( 'cookd', COOKD_DIR . 'languages' );
}

add_action( 'init', 'cookd_register_image_sizes', 5 );
/**
 * Register custom image sizes for the theme.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_register_image_sizes() {
	add_image_size( 'cookd-large',     1170, 617, true );
	add_image_size( 'cookd-medium',     768, 405, true );
	add_image_size( 'cookd-small',      320, 169, true );
	add_image_size( 'cookd-grid',       580, 460, true );
	add_image_size( 'cookd-gridlarge', 1170, 800, true );
	add_image_size( 'cookd-vertical',  1000, 1477, true );
}

add_action( 'init', 'cookd_register_layouts' );
/**
 * Register additional theme layout options.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_register_layouts() {
	genesis_register_layout( 'full-width-slim', array(
		'label' => __( 'Full Width Slim', 'cookd' ),
		'img'   => COOKD_URI . 'images/layout-slim.gif',
	) );
	genesis_register_layout( 'alt-sidebar-content', array(
		'label' => __( 'Alt Sidebar/Content', 'cookd' ),
		'img'   => COOKD_URI . 'images/layout-alt-sidebar-content.gif',
	) );
}

add_action( 'widgets_init', 'cookd_register_widgets', 10 );
/**
 * Unregister the default Genesis Featured Posts widget and register all of
 * our custom Cookd Pro widgets.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_register_widgets() {
	require_once COOKD_DIR . 'lib/widgets/featured-posts/widget.php';

	unregister_widget( 'Genesis_Featured_Post' );
	register_widget( 'Cookd_Featured_Posts' );
}

add_action( 'wp_enqueue_scripts', 'cookd_enqueue_syles' );
/**
 * Load styles.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_enqueue_syles() {
	wp_enqueue_style( 'dashicons' );

	wp_enqueue_style(
		'font-awesome',
		'//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css'
	);

	wp_enqueue_style(
		'cookd-google-fonts',
		'//fonts.googleapis.com/css?family=IM+Fell+Double+Pica:400,400italic|Source+Sans+Pro:300,300italic,400,400italic,600,600italic',
		array(),
		CHILD_THEME_VERSION
	);
}

add_action( 'wp_enqueue_scripts', 'cookd_enqueue_js' );
/**
 * Load all required JavaScript for the Foodie theme.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_enqueue_js() {
	wp_enqueue_script(
		'cookd-general',
	 	COOKD_URI . 'js/general.js',
		array( 'jquery' ),
		CHILD_THEME_VERSION,
		true
	);
}

// Add post navigation.
add_action( 'genesis_after_entry_content', 'genesis_prev_next_post_nav', 5 );

// Move the main navigation before the header.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

// Move the post image into the entry header.
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 6 );

// Move the post info before the post title.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header',  'genesis_post_info', 8 );

remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
remove_action( 'wp_head', 'genesis_custom_header_style');

add_action( 'genesis_entry_footer' , 'cookd_remove_post_meta_pages', 0 );
/**
 * Remove the entry meta in the entry footer if not singular.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_remove_post_meta_pages() {
	if ( ! is_single() ) {
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	}
}

add_filter( 'genesis_post_meta', 'cookd_post_meta_filter' );
/**
 * Customize the entry meta in the entry.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function cookd_post_meta_filter() {
	return sprintf( '[post_categories before="%s: "] [post_tags before="%s: "]',
		esc_html_x( 'Categories', 'post category label', 'cookd' ),
		esc_html_x( 'Tags', 'post tags label', 'cookd' )
	);
}

add_filter( 'body_class', 'cookd_single_post_image_class' );
/**
 * Add a custom class when the post has a single featured image.
 *
 * @since  1.0.0
 * @access public
 * @param  array $classes The current post classes.
 * @return array $classes The modified post classes.
 */
function cookd_single_post_image_class( $classes ) {
	if ( cookd_has_single_post_image() ) {
		$classes[] = 'cookd-has-image';
	}

	return $classes;
}

add_action( 'genesis_before_content_sidebar_wrap', 'cookd_single_post_image', 8 );
/**
 * Display Featured Image on top of the post.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_single_post_image() {
	if ( cookd_has_single_post_image() ) {
		the_post_thumbnail( 'cookd-large' );
	}
}

add_filter( 'genesis_search_button_text', 'cookd_search_button_text' );
/**
 * Customize search form input button text.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function cookd_search_button_text() {
	return '&#xf002;';
}

add_filter( 'body_class', 'cookd_add_body_class', 10 );
/**
 * Add the theme name class to the body element.
 *
 * @since  1.0.0
 * @param  string $classes The existing body classes.
 * @return string $$classes Modified body classes.
 */
function cookd_add_body_class( $classes ) {
	$classes[] = 'cookd';
	return $classes;
}

add_action( 'genesis_before', 'cookd_before_header', 10 );
/**
 * Load an ad section before .site-inner.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_before_header() {
	genesis_widget_area( 'before-header', array(
		'before' => '<div id="before-header" class="before-header">',
		'after'  => '</div> <!-- end .before-header -->',
	) );
}

add_filter( 'genesis_post_info', 'cookd_post_info_filter', 10 );
/**
 * Modify the Genesis post info.
 *
 * @since  1.0.0
 * @access public
 * @return string Modified post info text.
 */
function cookd_post_info_filter() {
	$info = '[post_date] [post_categories before=""] [post_edit]';

	if ( function_exists( 'heart_this' ) ) {
		$info = "[heart_this] {$info}";
	}

	return $info;
}

/**
 * Return a "Read More" link wrapped in paragraph tags.
 *
 * @since  1.0.0
 * @access public
 * @return string Read more text.
 */
function cookd_get_read_more_link() {
	return sprintf( '<p><a class="more-link" href="%s">%s</a></p>',
		get_permalink(),
		apply_filters( 'cookd_read_more_text', __( 'Read More', 'cookd' ) )
	);
}

add_filter( 'excerpt_more', 'cookd_get_ellipsis' );
/**
 * Return an ellipsis to be used when truncating excerpts.
 *
 * @since  1.0.0
 * @access public
 * @return string an ellipsis.
 */
function cookd_get_ellipsis() {
	return '...';
}

add_filter( 'get_the_content_more_link', 'cookd_content_read_more_link' );
add_filter( 'the_content_more_link',     'cookd_content_read_more_link' );
/**
 * Modify the Genesis and WordPress content read more link.
 *
 * @since  1.0.0
 * @access public
 * @return string Modified read more text.
 */
function cookd_content_read_more_link() {
	return sprintf( '...</p>%s',
		cookd_get_read_more_link()
	);
}

add_filter( 'the_excerpt', 'cookd_excerpt_read_more_link' );
/**
 * Modify the WordPress excerpt by forcing a read more link to be appended.
 *
 * @since  1.0.0
 * @access public
 * @param  string $output the default excerpt output.
 * @return string $output Modified excerpt with a read more link added.
 */
function cookd_excerpt_read_more_link( $output ) {
	return $output . cookd_get_read_more_link();
}

add_action( 'genesis_after_loop', 'cookd_maybe_disable_sidebars', 10 );
/**
 * Disable the sidebars on custom layouts where they're not needed.
 *
 * @since  1.0.0
 * @access public
 * @uses   genesis_site_layout() Return the site layout for different contexts.
 * @return void
 */
function cookd_maybe_disable_sidebars() {
	$layout = genesis_site_layout();

	if ( in_array( $layout, array( 'full-width-slim', 'alt-sidebar-content' ), true ) ) {
		remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
	}

	if ( 'full-width-slim' === $layout ) {
		remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt' );
	}
}

add_filter( 'genesis_seo_title', 'feast_filter_genesis_seo_site_title', 10, 2 );
/**
 * Replace genesis_seo_title to display normal header image at whatever dimensions user uploaded at
 * See: https://feastdesignco.com/rethinking-the-header/
 *
 * @since  3.1.7
 * @access public
 * @param  string $creds Default credits.
 * @return string Modified Feast credits.
 */
function feast_filter_genesis_seo_site_title( $title, $inside ){
	$child_inside = sprintf( '<a href="%s" title="%s" ><img src="%s" title="%s" alt="%s logo"  nopin="nopin" /></a>', 
				trailingslashit( home_url() ), 
				esc_attr( get_bloginfo( 'name' ) ), 
				get_header_image(),
				esc_attr( get_bloginfo( 'name' ) ), 
				esc_attr( get_bloginfo( 'name' ) ) 
			);
	if( get_header_image() == '' ) { // overwrite $child_inside if no header image specified
		$child_inside = sprintf( '<a href="%s">%s</a>', 
					trailingslashit( home_url() ), 
					esc_attr( get_bloginfo( 'name' ) )
				);
	}
	$title = str_replace( $inside, $child_inside, $title );
	return $title;		
}


add_filter( 'genesis_footer_creds_text', 'feast_footer_creds_text', 10 );
/**
 * Customize the footer text
 * Edit the line that says get_bloginfo( 'name' ) with your custom site name if desired
 * Edit the empty quotes ('' // additional custom....) with additional text if desired
 *
 * @since  1.0.0
 * @access public
 * @param  string $creds Default credits.
 * @return string Modified Feast credits.
 */
function feast_footer_creds_text( $creds ) {
	return sprintf( 'Copyright &copy; %u %s on the <a href="%s" target="_blank" rel="noopener">%s</a><br/>%s',
		date( 'Y' ),
		get_bloginfo( 'name' ),
		CHILD_THEME_URL, 
		CHILD_THEME_NAME,
		'' // additional custom footer text here
	);
}
