<?php
/**
 * Provide compatibility with various plugins.
 *
 * @package   Cookd
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

/**
 * Determine whether or not Facet WP is currently active.
 *
 * @since  1.0.0
 * @access public
 * @return bool True if the plugin is activated, false otherwise.
 */
function cookd_facetwp_is_active() {
	static $is_active;

	if ( null === $is_active ) {
		$is_active = function_exists( 'FWP' );
	}

	return $is_active;
}

add_action( 'widgets_init', 'cookd_facetwp_register_widget' );
/**
 * Register a widget for displaying FacetWP facets if the plugin is active.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_facetwp_register_widget() {
	if ( cookd_facetwp_is_active() ) {
		require_once COOKD_DIR . 'lib/widgets/facet/widget.php';

		register_widget( 'Cookd_Facet_Widget' );
	}
}

add_filter( 'facetwp_is_main_query', 'cookd_facetwp_is_main_query', 10, 2 );
/**
 * Filter the main FacetWP query to allow custom queries.
 *
 * @since  1.0.0
 * @access public
 * @param  bool     $is_main_query The default value for the FacetWP main query.
 * @param  WP_Query $query The current WordPress query object.
 * @return bool $is_main_query True if the facetwp query var is true.
 */
function cookd_facetwp_is_main_query( $is_main_query, $query ) {
	if ( isset( $query->query_vars['facetwp'] ) ) {
		$is_main_query = true;
	}

	return $is_main_query;
}

add_filter( 'facetwp_assets', 'cookd_recipe_archive_pagination_js' );
/**
 * Load a custom FacetWP handler for the Genesis pagination.
 *
 * @since  1.0.0
 * @access public
 * @param  array $assets
 * @return array $assets
 */
function cookd_recipe_archive_pagination_js( $assets ) {
	$assets['cookd-pagination.js'] = COOKD_URI . 'js/facetwp-pagination.js';

	return $assets;
}
