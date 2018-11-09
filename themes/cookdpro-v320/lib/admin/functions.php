<?php
/**
 * Admin functions.
 *
 * @package   Cookd\Admin\Functions
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

require_once COOKD_DIR . 'lib/admin/metaboxes.php';

if ( (bool) apply_filters( 'cookd_enable_theme_dashboard', true ) ) {
	require_once COOKD_DIR . 'lib/admin/dashboard.php';
}

add_action( 'admin_enqueue_scripts', 'cookd_load_admin_styles' );
/**
 * Enqueue Genesis admin styles.
 *
 * @since  1.0.0
 * @access public
 * @uses   CHILD_THEME_VERSION
 * @return void
 */
function cookd_load_admin_styles() {
	wp_enqueue_style(
		'cookd-admin',
		COOKD_URI . 'css/admin.css',
		array(),
		CHILD_THEME_VERSION
	);
}
