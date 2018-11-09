<?php
/**
 * Modify display output based on customizer selections.
 *
 * @package   Cookd\Functions\Customizer
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

add_action( 'genesis_before_loop',  'cookd_blog_page_maybe_add_grid', 99 );
/**
 * Add the archive grid filter to the main loop.
 *
 * @since  1.0.0
 * @uses   genesis_is_blog_template()
 * @uses   cookd_is_blog_archive()
 * @return void
 */
function cookd_blog_page_maybe_add_grid() {
	if ( genesis_is_blog_template() && cookd_is_grid_enabled() ) {
		if ( $grid = cookd_grid_exists( get_theme_mod( 'archive_grid', 'full' ) ) ) {
			add_filter( 'post_class', "cookd_grid_{$grid}" );
		}
	}
}

add_action( 'genesis_after_loop', 'cookd_blog_page_maybe_remove_grid', 5 );
/**
 * Remove the archive grid filter to ensure other loops are unaffected.
 *
 * @since  1.0.0
 * @uses   genesis_is_blog_template()
 * @uses   cookd_is_blog_archive()
 * @return void
 */
function cookd_blog_page_maybe_remove_grid() {
	if ( genesis_is_blog_template() && cookd_is_grid_enabled() ) {
		if ( $grid = cookd_grid_exists( get_theme_mod( 'archive_grid', 'full' ) ) ) {
			remove_filter( 'post_class', "cookd_grid_{$grid}" );
		}
	}
}

add_action( 'genesis_before_entry', 'cookd_archive_maybe_add_grid', 10 );
/**
 * Add the archive grid filter to the main loop.
 *
 * @since  1.0.0
 * @uses   cookd_is_blog_archive()
 * @return void
 */
function cookd_archive_maybe_add_grid() {
	if ( cookd_is_blog_archive() ) {
		if ( $grid = cookd_grid_exists( get_theme_mod( 'archive_grid', 'full' ) ) ) {
			add_filter( 'post_class', "cookd_grid_{$grid}_main" );
		}
	}
}

add_action( 'genesis_before_entry', 'cookd_archive_maybe_remove_title', 10 );
/**
 * Remove the entry title if the user has disabled it via the customizer.
 *
 * @since  1.0.0
 * @uses   cookd_is_blog_archive()
 * @return void
 */
function cookd_archive_maybe_remove_title() {
	if ( cookd_is_blog_archive() && ! get_theme_mod( 'archive_show_title', true ) ) {
		remove_action( 'genesis_entry_header',  'genesis_do_post_title', 10 );
	}
}

add_action( 'genesis_before_entry', 'cookd_archive_maybe_remove_info', 10 );
/**
 * Remove the entry info if the user has disabled it via the customizer.
 *
 * @since  1.0.0
 * @uses   cookd_is_blog_archive()
 * @return void
 */
function cookd_archive_maybe_remove_info() {
	if ( cookd_is_blog_archive() && ! get_theme_mod( 'archive_show_info', true ) ) {
		remove_action( 'genesis_entry_header', 'genesis_post_info', 8 );
	}
}

add_action( 'genesis_before_entry', 'cookd_archive_maybe_remove_content', 10 );
/**
 * Remove the entry content if the user has disabled it via the customizer.
 *
 * @since  1.0.0
 * @uses   cookd_is_blog_archive()
 * @return void
 */
function cookd_archive_maybe_remove_content() {
	if ( cookd_is_blog_archive() && ! get_theme_mod( 'archive_show_content', true ) ) {
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	}
}

add_filter( 'genesis_pre_get_option_image_size', 'cookd_archive_maybe_change_image_size' );
/**
 * Use the grid image size on pages where the grid layout is enabled.
 *
 * @since  1.0.0
 * @uses   cookd_is_blog_archive()
 * @param  string $setting The current setting.
 * @return string $setting The modified setting,.
 */
function cookd_archive_maybe_change_image_size( $setting ) {
	if ( ! cookd_is_blog_archive() ) {
		return $setting;
	}

	$size = get_theme_mod( 'archive_grid_image_size', '' );

	if ( empty( $size ) ) {
		return $setting;
	}

	return $size;
}

add_action( 'genesis_before_entry', 'cookd_archive_maybe_move_image', 10 );
/**
 * Move the post image if the user has changed the placement via the customizer.
 *
 * @since  1.0.0
 * @uses   cookd_is_blog_archive()
 * @return void
 */
function cookd_archive_maybe_move_image() {
	if ( ! cookd_is_blog_archive() ) {
		return;
	}

	$placement = get_theme_mod( 'archive_image_placement', 'before_title' );

	if ( 'before_title' !== $placement ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_image', 6 );
	}

	if ( 'after_title' === $placement ) {
		add_action( 'genesis_entry_header', 'genesis_do_post_image', 14 );
	} elseif ( 'after_content' === $placement ) {
		add_action( 'genesis_entry_footer', 'genesis_do_post_image', 0 );
	}
}
