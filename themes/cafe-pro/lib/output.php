<?php
/**
 * Cafe Pro.
 *
 * This file adds the required CSS to the front end to the Cafe Pro Theme.
 *
 * @package Cafe
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/cafe/
 */

add_action( 'wp_enqueue_scripts', 'cafe_css' );
/**
* Checks the settings for the images and background colors for each image.
* If any of these value are set the appropriate CSS is output.
*
* @since 1.0.0
*/
function cafe_css() {

	$handle = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';

	$color = get_theme_mod( 'cafe_accent_color', cafe_customizer_get_default_accent_color() );
	$opts  = apply_filters( 'cafe_images', array( 'header', '2', '4' ) );

	$settings = array();

	foreach( $opts as $opt ){
		$settings[$opt]['image'] = preg_replace( '/^https?:/', '', get_option( $opt .'-image', sprintf( '%s/images/bg-%s.jpg', get_stylesheet_directory_uri(), $opt ) ) );
	}

	$css = '';

	foreach ( $settings as $section => $value ) { 

		$background = $value['image'] ? sprintf( 'background-image: url(%s);', $value['image'] ) : '';

		$css .= ( ! empty( $section ) && ! empty( $background ) ) ? sprintf( '
		.front-page-%s {
			%s
		}
		', $section, $background ) : '';

	}

	$css .= ( cafe_customizer_get_default_accent_color() !== $color ) ? sprintf( '
		a,
		.front-page-3 .featured-content .entry-title a:hover,
		.entry-title a:hover,
		.footer-widgets a:hover,
		.genesis-nav-menu a:hover,
		.genesis-nav-menu a:hover,
		.nav-primary .genesis-nav-menu .sub-menu a:hover,
		.nav-primary .genesis-nav-menu .sub-menu a:hover,
		.site-footer .wrap a:hover {
			color: %1$s;
		}

		button:hover,
		input:hover[type="button"],
		input:hover[type="reset"],
		input:hover[type="submit"],
		.archive-pagination .active a,
		.archive-pagination li a:hover,
		.button:hover,
		.footer-widgets .button,
		.footer-widgets button,
		.footer-widgets input[type="button"],
		.footer-widgets input[type="reset"],
		.footer-widgets input[type="submit"],
		.front-page-3 {
			background-color: %1$s;
		}

		button:hover,
		input:hover[type="button"],
		input:hover[type="reset"],
		input:hover[type="submit"],
		.button:hover,
		.footer-widgets .button,
		.footer-widgets button,
		.footer-widgets input[type="button"],
		.footer-widgets input[type="reset"],
		.footer-widgets input[type="submit"] {
			box-shadow: 0px 0px 0px 10px %1$s;
			color: #fff;
		}
		', $color ) : '';

	if( $css ){
		wp_add_inline_style( $handle, $css );
	}

}
