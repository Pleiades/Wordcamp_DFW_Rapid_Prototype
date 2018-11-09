<?php
/**
 * Admin metaboxes.
 *
 * @package   Cookd\Functions\Admin
 * @copyright Copyright (c) 2017, Feast Design Co.
 * @license   GPL-2.0+
 * @since     3.1.0
 */

defined( 'WPINC' ) || die;

/**
 * Perform a check to see whether or not a page template which does not is
 * require the default WordPress editor is being used.
 *
 * @since  1.0.0
 * @access public
 * @param  array $templates a list of templates to check for.
 * @return bool
 */
function cookd_using_non_editor_template( $templates = array() ) {
	if ( ! isset( $_REQUEST['post'] ) ) { // Input var okay.
		return false;
	}

	if ( empty( $templates ) ) {
		$templates[] = 'templates/page-recipes.php';
		$templates[] = 'page_blog.php';
	}

	foreach ( (array) $templates as $template ) {
		if ( get_page_template_slug( absint( $_REQUEST['post'] ) ) === $template ) { // Input var okay.
			return true;
		}
	}

	return false;
}

add_action( 'admin_head-post.php', 'cookd_maybe_remove_editor' );
/**
 * Check to make sure a widgeted page template is is selected and then disable
 * the default WordPress editor.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function cookd_maybe_remove_editor() {
	if ( cookd_using_non_editor_template() ) {
		remove_post_type_support( 'page', 'editor' );
	}
}

add_action( 'post_submitbox_misc_actions', 'cookd_admin_meta_grid_box_view' );
/**
 * Output the content of our meta box.
 *
 * @since  1.0.0
 * @access public
 *
 * @param WP_Post $post Post object.
 * @return void
 */
function cookd_admin_meta_grid_box_view( WP_Post $post ) {
	if ( get_page_template_slug( $post->ID ) !== 'page_blog.php' ) {
		return;
	}

	$type = get_post_type_object( $post->post_type );

	if ( ! is_object( $type ) ) {
		return;
	}

	if ( current_user_can( $type->cap->edit_post, $post->ID ) ) {
		$enable = cookd_is_grid_enabled( $post->ID );
		require_once COOKD_DIR . 'lib/admin/views/publish-meta-box.php';
	}
}

add_action( 'add_meta_boxes', 'cookd_admin_add_recipe_box', 0, 2 );
/**
 * Add a meta box for the recipe archive template.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $post_type The current post type.
 * @param  WP_Post $post Post object.
 * @return void
 */
function cookd_admin_add_recipe_box( $post_type, $post ) {
	if ( get_page_template_slug( $post->ID ) !== 'templates/page-recipes.php' ) {
		return;
	}

	$type = get_post_type_object( $post_type );

	if ( ! is_object( $type ) ) {
		return;
	}

	if ( current_user_can( $type->cap->edit_post, $post->ID ) ) {
		add_meta_box(
			'cookd-recipe-meta',
			'Recipe Index Options',
			'cookd_admin_meta_recipe_box',
			'page',
			'normal',
			'high'
		);
	}
}

/**
 * Callback for Theme Settings Blog page template meta box.
 *
 * @since 1.0.0
 *
 * @uses \Genesis_Admin::get_field_id()    Construct field ID.
 * @uses \Genesis_Admin::get_field_name()  Construct field name.
 * @uses \Genesis_Admin::get_field_value() Retrieve value of key under $this->settings_field.
 *
 * @see \Genesis_Admin_Settings::metaboxes() Register meta boxes on the Theme Settings page.
 */
function cookd_admin_meta_recipe_box( $post ) {
	require_once COOKD_DIR . 'lib/admin/views/recipe-index-meta-box.php';
}

/**
 * Determine if the request to save data should be allowed to proceed.
 *
 * @since  1.0.0
 * @access protected
 * @param  int    $post_id Post ID.
 * @param  string $nonce The name of the nonce to check.
 * @param  string $action The name of the nonce action to check.
 * @return bool Whether or not this is a valid request to save our data.
 */
function _cookd_admin_meta_validate_request( $post_id, $nonce, $action ) {
	if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) { // Input var okay.
		return false;
	}

	$auto = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
	$ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
	$cron = defined( 'DOING_CRON' ) && DOING_CRON;

	if ( $auto || $ajax || $cron ) {
		return false;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}

	if ( ! isset( $_POST[ $nonce ] ) ) { // Input var okay.
		return false;
	}

	if ( ! wp_verify_nonce( sanitize_key( $_POST[ $nonce ] ), $action ) ) { // Input var okay.
		return false;
	}

	// @link http://make.marketpress.com/multilingualpress/2014/10/how-to-disable-broken-save_post-callbacks/
	if ( is_multisite() && ms_is_switched() ) {
		return false;
	}

	return wp_unslash( $_POST ); // Input var okay.
}

add_action( 'save_post', 'cookd_admin_meta_blog_grid_save' );
/**
 * Callback function for saving our meta box data.
 *
 * @since  1.0.0
 * @access public
 * @param  int $post_id Post ID.
 * @return bool Whether or not data has been saved.
 */
function cookd_admin_meta_blog_grid_save( $post_id ) {
	if ( ! $valid_request = _cookd_admin_meta_validate_request( $post_id, 'cookd_metabox_nonce', 'save_cookd_metabox' ) ) {
		return false;
	}

	$value = empty( $valid_request['_cookd_enable_grid'] ) ? 'no' : 'yes';

	return (bool) update_post_meta( $post_id, '_cookd_enable_grid', $value );
}


add_action( 'save_post', 'cookd_admin_meta_recipe_index_save' );
/**
 * Callback function for saving our meta box data.
 *
 * @since  1.0.0
 * @access public
 * @param  int $post_id Post ID.
 * @return bool Whether or not data has been saved.
 */
function cookd_admin_meta_recipe_index_save( $post_id ) {
	if ( ! $valid_request = _cookd_admin_meta_validate_request( $post_id, 'cookd_metabox_nonce', 'save_cookd_metabox' ) ) {
		return false;
	}

	$slug  = '_cookd_recipe_options';
	$data  = $valid_request[ $slug ];
	$safe_value = array();

	if ( ! empty( $data['cat'] ) ) {
		$safe_value['cat'] = sanitize_key( $data['cat'] );
	} else {
		$safe_value['cat'] = '';
	}

	if ( ! empty( $data['cat_exclude'] ) ) {
		$safe_value['cat_exclude'] = str_replace( ' ', '', wp_strip_all_tags( $data['cat_exclude'] ) );
	} else {
		$safe_value['cat_exclude'] = '';
	}

	if ( ! empty( $data['cat_num'] ) ) {
		$safe_value['cat_num'] = absint( $data['cat_num'] );
	} else {
		$safe_value['cat_num'] = '';
	}

	return (bool) update_post_meta( $post_id, $slug, $safe_value );
}
