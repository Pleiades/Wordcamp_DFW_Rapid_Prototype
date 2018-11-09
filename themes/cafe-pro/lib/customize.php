<?php
/**
 * Cafe Pro.
 *
 * This file adds the Customizer additions to the Cafe Pro Theme.
 *
 * @package Cafe Pro
 * @author  StudioPress 
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/cafe/
 */
 
/**
 * Get default accent color for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 1.0.0
 *
 * @return string Hex color code for accent color.
 */
function cafe_customizer_get_default_accent_color() {

	return '#a0ac48';

}

add_action( 'customize_register', 'cafe_customizer' );
/**
 * Register settings and controls with the Customizer.
 *
 * @since 1.0.0
 * 
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function cafe_customizer(){

	global $wp_customize;

	// Front page images.
	$images = apply_filters( 'cafe_images', array( 'header', '2', '4' ) );
	
	$wp_customize->add_section( 'cafe-settings', array(
		'description' => __( 'Use the included default images or personalize your site by uploading your own images.<br /><br />The default images are <strong>2000 pixels wide and between 1300-1500 pixels tall</strong>.', 'cafe-pro' ),
		'title'       => __( 'Background Images', 'cafe-pro' ),
		'priority'    => 35,
	) );

	foreach( $images as $key => $image ) {

		$wp_customize->add_setting( $image .'-image', array(
			'default'  => sprintf( '%s/images/bg-%s.jpg', get_stylesheet_directory_uri(), $image ),
			'type'     => 'option',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $image .'-image', array(
			'label'    => sprintf( __( 'Featured Section %s Image:', 'cafe-pro' ), $image ),
			'section'  => 'cafe-settings',
			'settings' => $image . '-image',
			'priority' => $key + 1,
		) ) );

	}

	// Accent color.
	$wp_customize->add_setting(
		'cafe_accent_color',
		array(
			'default' => cafe_customizer_get_default_accent_color(),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'cafe_accent_color',
			array(
				'description' => __( 'Change the default accent color for links, buttons, and more.', 'cafe-pro' ),
				'label'       => __( 'Accent Color', 'cafe-pro' ),
				'section'     => 'colors',
				'settings'    => 'cafe_accent_color',
			)
		)
	);

}
