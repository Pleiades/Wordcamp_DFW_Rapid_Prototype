<?php
/**
 * Template helper functions.
 *
 * @package   Cookd
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

/**
 * Return layout key 'full-width-slim'.
 *
 * Used as shortcut second parameter for `add_filter()`.
 *
 * @since  1.0.0
 * @access public
 * @return string 'full-width-slim'
 */
function cookd_return_full_width_slim() {
	return 'full-width-slim';
}

/**
 * Return layout key 'alt-sidebar-content'.
 *
 * Used as shortcut second parameter for `add_filter()`.
 *
 * @since  1.0.0
 * @access public
 * @return string 'alt-sidebar-content'
 */
function cookd_return_alt_sidebar_content() {
	return 'alt-sidebar-content';
}

/**
 * Check to see if the current blog page has the blog grid layout enabled.
 *
 * @since  1.0.0
 * @param  int $post_id The post ID to check.
 * @return bool true if the grid is enabled, false otherwise.
 */
function cookd_is_grid_enabled( $post_id = false ) {
	static $enabled;

	if ( null === $enabled ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$enabled = get_post_meta( $post_id, '_cookd_enable_grid', true );

		if ( empty( $enabled ) ) {
			$enabled = 'yes';
		}
	}

	return 'yes' === $enabled;
}

/**
 * Determine if we're viewing a "plural" page.
 *
 * Note that this is similar to, but not quite the same as `! is_singular()`,
 * which wouldn't account for the 404 page.
 *
 * @since  1.0.0
 * @access public
 * @return bool True if we're on any page which displays multiple entries.
 */
function cookd_is_plural() {
	if ( genesis_is_blog_template() ) {
		return cookd_is_grid_enabled();
	}

	return is_archive() || is_search();
}

/**
 * Determine if we're within a blog section archive.
 *
 * @since  1.0.0
 * @access public
 * @return bool True if we're on a blog archive page.
 */
function cookd_is_blog_archive() {
	return cookd_is_plural() && ! ( is_post_type_archive() || is_tax() );
}

/**
 * Determine if we're anywhere within the blog section of a Genesis site.
 *
 * @since  1.0.0
 * @access public
 * @return bool True if we're on any section of the blog.
 */
function cookd_is_blog() {
	return cookd_is_blog_archive() || is_singular( 'post' );
}

/**
 * Add post classes for a simple grid loop.
 *
 * @since  1.0.0
 * @access public
 * @param  int $columns The number of grid items desired.
 * @return array $classes The grid classes
 */
function cookd_grid( $columns ) {
	if ( ! in_array( $columns, array( 2, 3, 4, 6 ), true ) ) {
		return (array) $classes;
	}

	global $wp_query;

	$classes = array( 'simple-grid' );

	$column_classes = array(
		2 => 'one-half',
		3 => 'one-third',
		4 => 'one-fourth',
		6 => 'one-sixth',
	);

	$classes[] = $column_classes[ absint( $columns ) ];

	if ( ( $wp_query->current_post + 1 ) % 2 ) {
		$classes[] = 'odd';
	}

	if ( 0 === $wp_query->current_post || 0 === $wp_query->current_post % $columns ) {
		$classes[] = 'first';
	}

	return (array) $classes;
}

/**
 * Set up a grid of one-half elements for use in a post_class filter.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_half( $class ) {
	return array_merge( cookd_grid( 2 ), $class );
}

/**
 * Add a one half grid class to posts within the main query.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_half_main( $class ) {
	return in_the_loop() && is_main_query() ? cookd_grid_one_half( $class ) : $class;
}

/**
 * Set up a grid of one-third elements for use in a post_class filter.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_third( $class ) {
	return array_merge( cookd_grid( 3 ), $class );
}

/**
 * Add a one third grid class to posts within the main query.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_third_main( $class ) {
	return in_the_loop() && is_main_query() ? cookd_grid_one_third( $class ) : $class;
}

/**
 * Set up a grid of one-fourth elements for use in a post_class filter.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_fourth( $class ) {
	return array_merge( cookd_grid( 4 ), $class );
}

/**
 * Add a one fourth grid class to posts within the main query.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_fourth_main( $class ) {
	return in_the_loop() && is_main_query() ? cookd_grid_one_fourth( $class ) : $class;
}

/**
 * Set up a grid of one-sixth elements for use in a post_class filter.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_sixth( $class ) {
	return array_merge( cookd_grid( 6 ), $class );
}

/**
 * Add a one sixth grid class to posts within the main query.
 *
 * @since  1.0.0
 * @access public
 * @param  array $class An array of the current post classes.
 * @return array $class The post classes with the grid appended.
 */
function cookd_grid_one_sixth_main( $class ) {
	return in_the_loop() && is_main_query() ? cookd_grid_one_sixth( $class ) : $class;
}

/**
 * Helper function to determine if the requested grid function exists.
 *
 * @since  1.0.0
 * @access public
 * @param  string $grid the grid type to check.
 * @return bool|string false if no grid function exists, grid name otherwise.
 */
function cookd_grid_exists( $grid ) {
	return function_exists( "cookd_grid_{$grid}" ) ? $grid : false;
}

/**
 * Determine if a given single post has a featured image.
 *
 * @since  1.0.0
 * @access public
 * @return bool
 */
function cookd_has_single_post_image() {
	if ( ! is_singular( 'post' ) || ! has_post_thumbnail() ) {
		return false;
	}

	if ( get_theme_mod( 'enable_single_post_image', true ) ) {
		return true;
	}

	return false;
}

/**
 * Get the default recipe index options.
 *
 * @since  1.0.0
 * @return array $options An array of default recipe index options.
 */
function cookd_get_recipe_index_defaults() {
	return array(
		'cat'         => '',
		'cat_exclude' => '',
		'cat_num'     => 9,
	);
}

/**
 * Get the recipe index options for a given page.
 *
 * @since  1.0.0
 * @param  int $post_id The post ID to check.
 * @return array $options An array of recipe index options.
 */
function cookd_get_recipe_index_options( $post_id ) {
	static $options;

	if ( null === $options ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$options = get_post_meta( $post_id, '_cookd_recipe_options', true );
	}

	return $options;
}

/**
 * Get a recipe index option for a given page.
 *
 * @since  1.0.0
 * @param  string $key The key for the option to be retrieved.
 * @param  int    $post_id The post ID to check.
 * @return mixed $option The value of the option key provided.
 */
function cookd_get_recipe_index_option( $key, $post_id = false ) {
	$options = cookd_get_recipe_index_options( $post_id );
	$defaults = cookd_get_recipe_index_defaults();

	return empty( $options[ $key ] ) ? $defaults[ $key ] : $options[ $key ];
}

/**
 * Add a button to toggle the filters on mobile.
 *
 * @since  1.1.0
 * @return void
 */
function cookd_filter_toggle() {
	echo '<button class="filter-toggle">' . esc_html__( 'Filter', 'cookd' ) . '</button>';
}

/**
 * Add an opening wrapper around the sidebar widgets to make show/hiding them easier.
 *
 * @since  1.1.0
 * @return void
 */
function cookd_filter_wrap_open() {
	echo '<div class="filter-wrap">';
}

/**
 * Add a closing wrapper around the sidebar widgets to make show/hiding them easier.
 *
 * @since  1.1.0
 * @return void
 */
function cookd_filter_wrap_close() {
	echo '</div>';
}

/**
 * Displays a paginated navigation to next/previous set of posts, when applicable.
 *
 * @since  1.1.0
 * @access public
 * @return void
 */
function cookd_posts_pagination() {
	echo get_the_posts_pagination( array(
		'prev_text' => apply_filters( 'genesis_prev_link_text', '&#x000AB; ' . __( 'Previous Page', 'cookd' ) ),
		'next_text' => apply_filters( 'genesis_next_link_text', __( 'Next Page', 'cookd' ) . ' &#x000BB;' ),
	) );
}
