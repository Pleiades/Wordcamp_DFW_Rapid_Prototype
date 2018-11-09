<?php
/**
 * Register Customizer settings.
 *
 * @package   Cookd\Functions\Customizer
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

add_action( 'customize_register', 'cookd_register_customizer_archives' );
/**
 * Register custom sections for the Cookd Pro theme.
 *
 * @since  1.0.0
 * @access public
 * @param  object $api the customizer object.
 * @return void
 */
function cookd_register_customizer_archives( $api ) {
	$api->remove_section( 'colors' );

	$section = 'archive_grid_settings';

	$api->add_section(
		$section,
		array(
			'title'       => __( 'Archive Grid', 'cookd' ),
			'description' => __( 'These settings control how the archive grid will display on category and tag pages as well as the Genesis blog page when it is enabled.', 'cookd' ),
			'priority'    => 180,
		)
	);

	$api->add_setting( 'archive_grid', array(
		'default'           => 'full',
		'sanitize_callback' => 'wp_strip_all_tags',
	) );

	$api->add_control( 'archive_grid', array(
		'label'    => __( 'Grid Size:', 'cookd' ),
		'section'  => $section,
		'priority' => 0,
		'type'     => 'select',
		'choices'  => array(
			'full'       => __( 'Full Width', 'cookd' ),
			'one_half'   => __( 'One Half', 'cookd' ),
			'one_third'  => __( 'One Third', 'cookd' ),
			'one_fourth' => __( 'One Fourth', 'cookd' ),
			'one_sixth'  => __( 'One Sixth', 'cookd' ),
		),
	) );

	$api->add_setting( 'archive_show_title', array(
		'default'           => 1,
		'sanitize_callback' => 'absint',
	) );

	$api->add_control( 'archive_show_title', array(
		'label'    => __( 'Display The Title?', 'cookd' ),
		'section'  => $section,
		'type'     => 'checkbox',
		'priority' => 5,
	) );

	$api->add_setting( 'archive_show_info', array(
		'default'           => 1,
		'sanitize_callback' => 'absint',
	) );

	$api->add_control( 'archive_show_info', array(
		'label'    => __( 'Display The Entry Info?', 'cookd' ),
		'section'  => $section,
		'type'     => 'checkbox',
		'priority' => 6,
	) );

	$api->add_setting( 'archive_show_content', array(
		'default'           => 1,
		'sanitize_callback' => 'absint',
	) );

	$api->add_control( 'archive_show_content', array(
		'label'    => __( 'Display The Content?', 'cookd' ),
		'section'  => $section,
		'type'     => 'checkbox',
		'priority' => 7,
	) );

	$api->add_setting( 'archive_grid_image_size', array(
		'default'           => '',
		'sanitize_callback' => 'wp_strip_all_tags',
	) );

	$api->add_control( 'archive_grid_image_size', array(
		'label'    => __( 'Image Size:', 'cookd' ),
		'section'  => $section,
		'type'     => 'select',
		'choices'  => genesis_get_image_sizes_for_customizer(),
	) );

	$api->add_setting( 'archive_image_placement', array(
		'default'           => 'before_title',
		'sanitize_callback' => 'wp_strip_all_tags',
	) );

	$api->add_control( 'archive_image_placement', array(
		'label'    => __( 'Image Placement:', 'cookd' ),
		'section'  => $section,
		'type'     => 'select',
		'priority' => 10,
		'choices'  => array(
			'before_title'  => __( 'Before Title', 'cookd' ),
			'after_title'   => __( 'After Title', 'cookd' ),
			'after_content' => __( 'After Content', 'cookd' ),
		),
	) );
}

add_action( 'customize_register', 'cookd_register_customizer_single_post' );
/**
 * Register custom sections for the Cookd Pro theme.
 *
 * @since  1.0.0
 * @access public
 * @param  object $api the customizer object.
 * @return void
 */
function cookd_register_customizer_single_post( $api ) {
	$section = 'single_post_settings';

	$api->add_section(
		$section,
		array(
			'title'       => __( 'Single Posts', 'cookd' ),
			'description' => __( 'These settings control how the single post will display.', 'cookd' ),
			'priority'    => 190,
		)
	);

	$api->add_setting( 'enable_single_post_image', array(
		'default'           => 1,
		'sanitize_callback' => 'absint',
	) );

	$api->add_control( 'enable_single_post_image', array(
		'label'    => __( 'Display the featured image?', 'cookd' ),
		'section'  => $section,
		'type'     => 'checkbox',
		'priority' => 1,
	) );
}
