<?php
/**
 * Template Name: Recipe Index
 *
 * @package   Cookd\Templates
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

add_filter( 'post_class', 'cookd_grid_one_third', 10 );

remove_action( 'genesis_entry_header', 'genesis_post_info', 8 );
remove_action( 'genesis_entry_content', 'genesis_do_post_content', 10 );
remove_action( 'genesis_loop', 'genesis_do_loop', 10 );
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav', 10 );

add_filter( 'genesis_pre_get_option_image_size', 'cookd_recipe_index_change_image_size', 10 );
/**
 * Use the grid image size on pages where the grid layout is enabled.
 *
 * @since  1.0.0
 * @return string $setting The modified setting.
 */
function cookd_recipe_index_change_image_size() {
	return 'cookd-grid';
}

add_action( 'genesis_before_sidebar_widget_area', 'cookd_filter_toggle', -1 );
add_action( 'genesis_before_sidebar_alt_widget_area', 'cookd_filter_toggle', -1 );

add_action( 'genesis_before_sidebar_widget_area', 'cookd_filter_wrap_open', 0 );
add_action( 'genesis_before_sidebar_alt_widget_area', 'cookd_filter_wrap_open', 0 );

if ( is_active_sidebar( 'recipe-index' ) ) {
	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
	remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
	add_action( 'genesis_sidebar', 'cookd_recipe_index_sidebar' );
	add_action( 'genesis_sidebar_alt', 'cookd_recipe_index_sidebar' );
}

/**
 * Output the recipe index sidebar.
 *
 * @since  1.0.0
 * @return void
 */
function cookd_recipe_index_sidebar() {
	genesis_widget_area( 'recipe-index', array(
		'before' => '',
		'after'  => '',
	));
}

add_action( 'genesis_after_sidebar_widget_area', 'cookd_filter_wrap_close', 0 );
add_action( 'genesis_after_sidebar_alt_widget_area', 'cookd_filter_wrap_close', 0 );

add_action( 'genesis_loop', 'cookd_recipe_index_loop', 10 );
/**
 * Display the recipe index sidebar.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_recipe_index_loop() {
	$args = array(
		'post_type'        => 'post',
		'posts_per_page'   => cookd_get_recipe_index_option( 'cat_num' ),
		'facetwp'          => true,
		'paged'            => get_query_var( 'paged' ),
	);

	$cat = cookd_get_recipe_index_option( 'cat' );

	if ( ! empty( $cat ) ) {
		$args['cat'] = $cat;
	}

	$exclude = cookd_get_recipe_index_option( 'cat_exclude' );

	if ( ! empty( $exclude ) ) {
		$args['category__not_in'] = explode( ',', $exclude );
	}

	echo '<div class="facetwp-template recipe-index">';

	genesis_custom_loop( $args );

	echo '</div>';
}

add_action( 'genesis_after_endwhile', 'cookd_posts_pagination', 10 );

genesis();
