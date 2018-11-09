<?php
/**
 * Customizer additions.
 *
 * @package Agent Focused Pro
 * @author  Marcy Diaz for Winning Agent
 * @subpackage Customizations
 * @license GPL2-0+
 */

/**
 * Get default accent colors for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 1.0.0
 *
 * @return string Hex color code for accent colors.
 */
 
function agentfocused_customizer_get_default_accent_color() {
	return '#67ddab'; // Green #67ddab.
}

function agentfocused_customizer_get_default_secondary_color() {
	return '#566473'; // Gray #566473.
}

function agentfocused_customizer_get_default_footer_color() {
	return '#2c333c'; // Footer gray #2c333c.
}

add_action( 'customize_register', 'agentfocused_customizer_register' );

/**
 * Register settings and controls with the Customizer.
 *
 * @since 1.0.0
 * 
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function agentfocused_customizer_register() {

	global $wp_customize;

	$wp_customize->add_section( 'agentfocused-image', array(
		'title'          => __( 'Front Page Image', 'agentfocused' ),
		'description'    => __( 'Use the default image or personalize your site by uploading your own image for the front page 1 widget background.<br /><br />The image for the demo is <strong>1600 x 1000 pixels</strong>.', 'agentfocused' ),
		'priority'       => 35,
	) );

	$wp_customize->add_setting( 'agentfocused-front-image', array(
		'default'  => sprintf( '%s/images/front-page-image.jpg', get_stylesheet_directory_uri() ),
		'type'     => 'option',
	) );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'front-background-image',
			array(
				'label'       => __( 'Front Image Upload', 'agentfocused' ),
				'section'     => 'agentfocused-image',
				'settings'    => 'agentfocused-front-image',
			)
		)
	);

	$wp_customize->add_setting(
		'agentfocused_accent_color',
		array(
			'default'           => agentfocused_customizer_get_default_accent_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'agentfocused_accent_color',
			array(
				'description' => __( 'Change the default accent color for links, buttons, and front page testimonial.', 'agentfocused' ),
			    'label'       => __( 'Accent Color', 'agentfocused' ),
			    'section'     => 'colors',
			    'settings'    => 'agentfocused_accent_color',
			)
		)
	);

	$wp_customize->add_setting(
		'agentfocused_secondary_color',
		array(
			'default'           => agentfocused_customizer_get_default_secondary_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'agentfocused_secondary_color',
			array(
				'description' => __( 'Change the default secondary color for header, search bars, featured listings, and footer-widgets.', 'agentfocused' ),
			    'label'       => __( 'Secondary Color', 'agentfocused' ),
			    'section'     => 'colors',
			    'settings'    => 'agentfocused_secondary_color',
			)
		)
	);

		$wp_customize->add_setting(
			'agentfocused_footer_color',
			array(
			'default'           => agentfocused_customizer_get_default_footer_color(),
			'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'agentfocused_footer_color',
				array(
					'description' => __( 'Change the default footer color.', 'agentfocused' ),
				    'label'       => __( 'Footer Color', 'agentfocused' ),
				    'section'     => 'colors',
				    'settings'    => 'agentfocused_footer_color',
				)
			)
		);

}
