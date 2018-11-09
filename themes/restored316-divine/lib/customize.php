<?php
/**
 * Customizer Additions.
 *
 * @package      Divine
 * @link         http://restored316designs.com/themes
 * @author       Lauren Gaige // Restored 316 LLC
 * @copyright    Copyright (c) 2015, Restored 316 LLC, Released 3/11/2015
 * @license      GPL-2.0+
 */
 
/**
 * Get default primary color for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 1.0.0
 *
 * @return string Hex color code for primary color.
 */
function divine_customizer_get_default_primary_color() {
	return '#596922';
}

/**
 * Get default accent color for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 1.0.0
 *
 * @return string Hex color code for accent color.
 */
function divine_customizer_get_default_accent_color() {
	return '#596922';
}
 
add_action( 'customize_register', 'divine_customizer_register' );
/**
 * Register settings and controls with the Customizer.
 *
 * @since 1.0.0
 * 
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function divine_customizer_register() {

	global $wp_customize;
	
	$wp_customize->add_setting(
		'divine_primary_color',
		array(
			'default' => divine_customizer_get_default_primary_color(),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'divine_primary_color',
			array(
				'description' => __( 'Change the default primary color for your post title, buttons, and links.', 'divine' ),
			    'label'    => __( 'Primary Color', 'divine' ),
			    'section'  => 'colors',
			    'settings' => 'divine_primary_color',
			)
		)
	);
	
	$wp_customize->add_setting(
		'divine_accent_color',
		array(
			'default' => divine_customizer_get_default_accent_color(),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'divine_accent_color',
			array(
				'description' => __( 'Change the default accent color for your links.', 'divine' ),
			    'label'    => __( 'Accent Color', 'divine' ),
			    'section'  => 'colors',
			    'settings' => 'divine_accent_color',
			)
		)
	);

}

add_action( 'wp_enqueue_scripts', 'divine_css' );
/**
* Checks the settings for the accent color, highlight color, and header
* If any of these value are set the appropriate CSS is output
*
* @since 1.0.0
*/
function divine_css() {

	$handle  = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';

	$color = get_theme_mod( 'divine_primary_color', divine_customizer_get_default_primary_color() );
	$color_accent = get_theme_mod( 'divine_accent_color', divine_customizer_get_default_accent_color() );

	$css = '';
		
	$css .= ( divine_customizer_get_default_primary_color() !== $color ) ? sprintf( '
		
		.title-area,
		button, input[type="button"], 
		input[type="reset"], 
		input[type="submit"], 
		.button, 
		.entry-content .button,
		.enews-widget input[type="submit"],
		.content .entry-header .entry-meta .entry-categories a:hover {
			background: %1$s;
		}
		
		.title-area {
			outline-color: %1$s;
		}
		
		a:hover,
		.entry-title a:hover, 
		.footer-widgets .entry-title a:hover {
			color: %1$s;
		}
		
		.woocommerce .woocommerce-message,
		.woocommerce .woocommerce-info {
			border-top-color: %1$s !important;
		}
		
		.woocommerce .woocommerce-message::before,
		.woocommerce .woocommerce-info::before,
		.woocommerce div.product p.price,
		.woocommerce div.product span.price,
		.woocommerce ul.products li.product .price,
		.woocommerce form .form-row .required {
			color: %1$s !important;
		}
		
		.woocommerce #respond input#submit, 
		.woocommerce a.button, 
		.woocommerce button.button, 
		.woocommerce input.button,
		.woocommerce span.onsale,
		.easyrecipe .ui-button-text-icon-primary .ui-button-text, 
		.easyrecipe .ui-button-text-icons .ui-button-text {
			background-color: %1$s !important;
		}
		
		', $color ) : '';

	$css .= ( divine_customizer_get_default_accent_color() !== $color_accent ) ? sprintf( '

		a,
		.genesis-nav-menu li:hover,
		.genesis-nav-menu a:hover,
		.site-header .genesis-nav-menu a:hover,
		.entry-title a,
		.sidebar .widget-title a {
			color: %1$s;
		}
		
		', $color_accent ) : '';
		
		
	if ( divine_customizer_get_default_primary_color() !== $color_accent || divine_customizer_get_default_accent_color() !== $color_highlight ) {
		$css .= '
		}
		';
	}

	if( $css ){
		wp_add_inline_style( $handle, $css );
	}

}
