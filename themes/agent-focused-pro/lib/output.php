<?php
/**
 * Adds the customizer CSS to the front end.
 *
 * @package Agent Focused Pro
 * @author  Marcy Diaz for Winning Agent
 * @subpackage Customizations
 */

add_action( 'wp_enqueue_scripts', 'agentfocused_css' );
/**
 * Checks the settings for the accent color, secondary color, and footer color.
 * If any of these values are set the appropriate CSS is output.
 *
 * @since 1.0.0
 */
function agentfocused_css() {

	$handle  = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';

	$color_accent = get_theme_mod( 'agentfocused_accent_color', agentfocused_customizer_get_default_accent_color() ); // Green #67ddab.

	$color_secondary = get_theme_mod( 'agentfocused_secondary_color', agentfocused_customizer_get_default_secondary_color() ); // Gray #566473.

	$color_footer = get_theme_mod( 'agentfocused_footer_color', agentfocused_customizer_get_default_footer_color() ); // Footer gray #2c333c.

	$css = '';

	$css .= ( agentfocused_customizer_get_default_accent_color() !== $color_accent ) ? sprintf( '
		a,
		.add-color,
		.entry-title a:hover,
		.entry-title a:focus,
		.user-profile .agent a:hover.larger,
		.user-profile .agent a:focus.larger,
		.comment-header a:hover,
		.comment-header a:focus,
		.comment-reply a::before,
		.footer-widgets a:hover,
		.footer-widgets a:focus,
		.front-page .contact .icon,
		.menu-toggle:hover,
    	.menu-toggle:focus,
    	.menu-toggle.activated,
    	.menu-toggle:hover::before,
    	.menu-toggle:focus::before,
    	.menu-toggle.activated::before  {
			color: %1$s;
		}

		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.button,
		.search-form input[type="submit"],
		.property-search input[type="submit"],
		div.gform_wrapper .gform_footer input[type="submit"],
		.enews-widget input[type="submit"],
		.archive-pagination li a:hover,
		.archive-pagination li a:focus,
		.archive-pagination .active a,
		.featured-content .entry-title::after,
		.front-page-6 {
			background-color: %1$s;
		}

		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.button,
		.search-form input[type="submit"],
		.property-search input[type="submit"],
		div.gform_wrapper .gform_footer input[type="submit"],
		.enews-widget input[type="submit"],
		.genesis-nav-menu > .current-menu-item > a,
		.genesis-nav-menu > li > a:focus,
		.genesis-nav-menu > li > a:hover,
		.genesis-nav-menu .sub-menu,
		.nav-secondary .genesis-nav-menu > li.menu-item-has-children > a:focus,
		.nav-secondary .genesis-nav-menu > li.menu-item-has-children > a:hover {
				border-color: %1$s;
		}', $color_accent ) : '';

	$css .= ( agentfocused_customizer_get_default_secondary_color() !== $color_secondary ) ? sprintf( '
		.widget.property-search,
		.widget.IDX_Omnibar_Widget,
		.widget.IDX_Omnibar_Widget_Extra,
		.site-header,
		.archive-pagination li a,
		.footer-widgets,
		.front-page-3::before {
				background-color: %1$s;
		}

		.featured-listings .entry {
				color: %1$s;
		}', $color_secondary ) : '';

	$css .= ( agentfocused_customizer_get_default_footer_color() !== $color_footer ) ? sprintf( '
		.site-footer,
		.footer-bottom {
				background-color: %1$s;
		}', $color_footer ) : '';

	if ( $css ) {
		wp_add_inline_style( $handle, $css );
	}

}
