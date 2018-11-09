<?php

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme defaults.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
load_child_theme_textdomain( 'agentfocused', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'agentfocused' ) );

// Add Image upload and custom colors to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include WordPress Theme Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', __( 'Agent Focused Pro', 'agentfocused' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/agent-focused/' );
define( 'CHILD_THEME_VERSION', '1.0' );

// Include community custom post type, community widget, and dashboard widget.
include_once( get_stylesheet_directory() . '/lib/community-post-types.php' );
include_once( get_stylesheet_directory() . '/lib/widgets/agentfocused-widgets.php' );
include_once( get_stylesheet_directory() . '/lib/dashboard-widget/agentfocused-dashboard-widget.php' );

// Enqueue scripts and styles.
add_action( 'wp_enqueue_scripts', 'agentfocused_scripts_styles' );
function agentfocused_scripts_styles() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300italic,400,400italic,600,600italic,700', array(), CHILD_THEME_VERSION );

	wp_enqueue_style( 'ionicons', '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', array(), CHILD_THEME_VERSION );

	wp_enqueue_script( 'agentfocused-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	$output = array(
		'mainMenu' => __( 'Menu', 'agentfocused' ),
		'subMenu'  => __( 'Menu', 'agentfocused' ),
	);
	wp_localize_script( 'agentfocused-responsive-menu', 'AgentfocusedL10n', $output );

}

// Add Visual Editor stylesheet.
add_editor_style( 'editor-style.css' );

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add screen reader class to archive description.
add_filter( 'genesis_attr_author-archive-description', 'genesis_attributes_screen_reader_class' );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom retina logo.
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

// Add support for custom background.
add_theme_support( 'custom-background' );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Unregister layout settings.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Unregister the header right widget area.
unregister_sidebar( 'header-right' );

// Unregister secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Name Menus.
add_theme_support( 'genesis-menus', array(
	'primary' 	=> __( 'Header Navigation Menu', 'agentfocused' ),
	'secondary' => __( 'Footer Navigation Menu', 'agentfocused' ) ) );

// Remove output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

// Reposition primary navigation into header.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 11 );

// Reposition secondary navigation into footer.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 8 );

// Only one level depth for secondary footer navigation.
add_filter( 'wp_nav_menu_args', 'agentfocused_secondary_menu_args' );
function agentfocused_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;

}

// Add Footer Bottom widget area.
add_action( 'genesis_after_footer', 'agent_add_footer_bottom_widget_area', 12 );
function agent_add_footer_bottom_widget_area() {

	if ( is_active_sidebar( 'footer-bottom' ) ) {
		genesis_widget_area( 'footer-bottom', array(
			'before' => '<div class="footer-bottom"><div class="wrap"><div class="widget-area">',
			'after'  => '</div></div></div>',
		) );
	}

}

// Add support for structural wraps.
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'footer-widgets',
	'footer',
) );

// Add image sizes.
add_image_size( 'large-featured-image', 1350, 540, true ); // Large Featured on Single Pages/Posts.
add_image_size( 'properties', 460, 460, true ); // For AgentPress Listings widget.
add_image_size( 'af-featured-community', 900, 450, true ); // For blog and AFP Communities archive.

// Add large featured image outside of content.
add_action( 'genesis_after_header', 'agentfocused_large_featured_image' );
function agentfocused_large_featured_image() {

	global $post;

	$image = genesis_get_image( array(
		'format' => 'url',
		'size'   => 'large-featured-image',
		'num'    => 0,
		'fallback' => 'false'
	) );

	if ( ! is_singular() || ! $image ) {
		return;

	} else {

		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

		echo '<div class="large-featured"><div class="large-entry-image" style="background-image: url( '. esc_url( $image ) .' );">';
		echo '</div></div>';

	}
}

// Customize 'Read More' text.
add_filter( 'excerpt_more', 'agentfocused_read_more_link' );
add_filter( 'get_the_content_more_link', 'agentfocused_read_more_link' );
add_filter( 'the_content_more_link', 'agentfocused_read_more_link' );
function agentfocused_read_more_link( $more ) {

	$new_a11y_read_more_title = sprintf( '<span class="screen-reader-text">%s %s</span>', __( 'about ', 'agentfocused' ), get_the_title() );

	return sprintf( ' ... <a href="%s" class="more-link">%s %s</a>', get_permalink(), __( '&#187; Learn More', 'agentfocused' ), $new_a11y_read_more_title );

}

// Add Community Type to Community Post Meta.
add_filter( 'genesis_post_meta', 'agentfocused_community_post_meta' );
function agentfocused_community_post_meta( $post_meta ) {

	if ( 'wap-community' === get_post_type() ) {

		$post_meta = '[post_terms taxonomy="wap-community-type" before="Community Type: "]';
		return $post_meta;

	} else {

		return $post_meta;

	}
}

// Add archive description to AgentPress Listings.
add_action('init', 'agentfocused_custom_init');
function agentfocused_custom_init() {
	add_post_type_support( 'listing', 'genesis-cpt-archives-settings' );
}

// Keeps Gravity Form from inteferring with Genesis Accessibility Skip Links. HT Robin Cornett.
// add_filter( 'gform_tabindex', '__return_false' );
// add_filter( 'gform_confirmation_anchor', create_function( '','return 20;' ) );

// Change size of the user profile Gravatar.
add_filter( 'genesis_gravatar_sizes', 'agentfocused_user_profile_gravatar' );
function agentfocused_user_profile_gravatar( $sizes ) {

	$sizes['Agent Profile Image'] = 236;
	return $sizes;

}

// Change size of author box Gravatar.
add_filter( 'genesis_author_box_gravatar_size', 'agentfocused_author_box_gravatar' );
function agentfocused_author_box_gravatar( $size ) {

	return 140;

}

// Change size of entry comments Gravatar.
add_filter( 'genesis_comment_list_args', 'agentfocused_comments_gravatar' );
function agentfocused_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;
	return $args;

}

// Setup widget counts.
function agentfocused_count_widgets( $id ) {

	global $sidebars_widgets;

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

// From Digital Pro Flexible widget classes.
function agentfocused_widget_area_class( $id ) {

	$count = agentfocused_count_widgets( $id );

	$class = '';

	if( $count == 1 ) {
		$class .= ' widget-full';
	} elseif( $count % 3 == 1 ) {
		$class .= ' widget-thirds';
	} elseif( $count % 4 == 1 ) {
		$class .= ' widget-fourths';
	} elseif( $count % 2 == 0 ) {
		$class .= ' widget-halves uneven';
	} else {	
		$class .= ' widget-halves even';
	}
	return $class;

}

// Add support for 2-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 2 );

// Register widget areas.
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'agentfocused' ),
	'description' => __( 'This is the front page 1 section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'search-bar',
	'name'        => __( 'Search Bar', 'agentfocused' ),
	'description' => __( 'This is the search bar section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2-left',
	'name'        => __( 'Front Page 2 Left', 'agentfocused' ),
	'description' => __( 'This is the front page 2 left section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2-right',
	'name'        => __( 'Front Page 2 Right', 'agentfocused' ),
	'description' => __( 'This is the front page 2 right section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'agentfocused' ),
	'description' => __( 'This is the front page 3 section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'agentfocused' ),
	'description' => __( 'This is the front page 4 section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-5',
	'name'        => __( 'Front Page 5', 'agentfocused' ),
	'description' => __( 'This is the front page 5 section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-6',
	'name'        => __( 'Front Page 6', 'agentfocused' ),
	'description' => __( 'This is the front page 6 section.', 'agentfocused' ),
) );
genesis_register_sidebar( array(
	'id'          => 'footer-bottom',
	'name'        => __( 'Footer Bottom', 'agentfocused' ),
	'description' => __( 'This is the footer bottom section.', 'agentfocused' ),
) );
/*
spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
    if (file_exists($fileName)) {
        require $fileName;

        return true;
    }

    return false;
});

$faker = Faker\Factory::create();
$faker = \Faker\Factory::create();
*/

