<?php
/**
 * The Theme Dashboard.
 *
 * @package   Cookd\Functions\Admin
 * @copyright Copyright (c) 2017, Feast Design Co.
 * @license   GPL-2.0+
 * @since     3.0.0
 */

defined( 'WPINC' ) || die;

add_action( 'after_switch_theme', 'feast_dashboard_setup', 10 );
/**
 * Set up the dashboard options.
 *
 * @since   3.1.0
 * @access  public
 * @return  void
 */
function feast_dashboard_setup() {
	if ( ! is_network_admin() ) {
		add_option( 'feast_dashboard_redirect', true, '', 'no' );
	}
}

add_action( 'after_switch_theme', 'feast_dashboard_redirect', 12 );
/**
 * Add options and fire a redirect when the theme is first activated.
 *
 * @since   3.1.0
 * @access  public
 * @return  void
 */
function feast_dashboard_redirect() {
	// Bail if we've already been redirected.
	if ( is_network_admin() || ! get_option( 'feast_dashboard_redirect' ) ) {
		return;
	}

	// Make sure this doesn't go into a redirect loop.
	update_option( 'feast_dashboard_redirect', false );

	wp_safe_redirect( admin_url( 'admin.php?page=feast-dashboard' ) );
	exit;
}

add_action( 'switch_theme', 'feast_dashboard_cleanup', 10 );
/**
 * Remove our redirect option so we will be redirected again on the next activation.
 *
 * @since   3.1.0
 * @access  public
 * @return  void
 */
function feast_dashboard_cleanup() {
	delete_option( 'feast_dashboard_redirect' );
}

/**
 * Return the theme's SVG icon.
 *
 * @since  3.1.0
 * @access public
 * @return string A base64 encoded SVG icon for the theme.
 */
function feast_get_svg_icon() {
	$icon = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMTguMyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjAgMTguMzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxnPjxwYXRoIGQ9Ik0xMCwxOC4zTDkuNiwxOEM5LjIsMTcuNywwLDExLjEsMCw1LjZDMCwyLjIsMi4xLDAsNS4yLDBDNi44LDAsOC42LDAuOCwxMCwyLjNDMTEuNCwwLjgsMTMuMiwwLDE0LjgsMEMxNy45LDAsMjAsMi4yLDIwLDUuNmMwLDUuNS05LjIsMTIuMi05LjYsMTIuNUwxMCwxOC4zeiBNNS4yLDEuNWMtMi43LDAtMy43LDIuMS0zLjcsNC4xYzAsMy43LDUuOCw4LjksOC41LDEwLjljMi43LTIuMSw4LjUtNy4yLDguNS0xMC45YzAtMi0xLTQuMS0zLjctNC4xYy0xLjMsMC0yLjcsMC43LTMuOSwyYzEsMS40LDEuNiwzLDEuNiw0LjNjMCwwLjgtMC4zLDEuNy0wLjksMi4yYy0wLjUsMC41LTEuMSwwLjgtMS43LDAuOFM4LjcsMTAuNSw4LjMsMTBDNy43LDkuNCw3LjQsOC42LDcuNCw3LjhDNy40LDYuNCw4LDQuOSw5LDMuNUM3LjksMi4yLDYuNSwxLjUsNS4yLDEuNXogTTEwLDQuN2MtMC43LDEtMS4xLDIuMi0xLjEsMy4xQzguOSw4LjIsOSw4LjcsOS4zLDljMC40LDAuNCwxLDAuNCwxLjQsMGMwLjMtMC4zLDAuNC0wLjcsMC40LTEuMkMxMS4xLDYuOSwxMC43LDUuOCwxMCw0Ljd6Ii8+PC9nPjwvZz48L3N2Zz4=';

	return "data:image/svg+xml;base64,{$icon}";
}

add_action( 'admin_menu', 'feast_dashboard_menu', 0 );
/**
 * Add the theme dashboard to the main WordPress dashboard menu.
 *
 * @since   3.1.0
 * @access  public
 * @return  void
 */
function feast_dashboard_menu() {
	add_menu_page(
		'Cook\'d Pro',
		'Cook\'d Pro',
		'edit_theme_options',
		'feast-dashboard',
		'feast_dashboard_page',
		feast_get_svg_icon(),
		'58.997'
	);
}

/**
 * Include the base template for our dashboard page.
 *
 * @since   3.1.0
 * @access  public
 * @return  void
 */
function feast_dashboard_page() {
	require_once FEAST_DIR . 'lib/admin/views/dashboard.php';
}

add_action( 'admin_enqueue_scripts', 'feast_dashboard_scripts', 10 );
/**
 * Load scripts and styles for the dashboard.
 *
 * @since   3.1.0
 * @access  public
 * @param   object $screen The current screen object.
 * @return  void
 */
function feast_dashboard_scripts( $screen ) {
	if ( 'toplevel_page_feast-dashboard' === $screen ) {
		wp_enqueue_style(
			'feast-dashboard',
			FEAST_URI . 'css/dashboard.css',
			array(),
			CHILD_THEME_VERSION
		);
	}
}
