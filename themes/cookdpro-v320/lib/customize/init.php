<?php
/**
 * Set up and include all necessary customizer files.
 *
 * @package   Cookd\Functions\Customizer
 * @copyright Copyright (c) 2017, Feast Design Co.
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

require_once COOKD_DIR . 'lib/customize/display.php';

if ( genesis_is_customizer() ) {
	require_once COOKD_DIR . 'lib/customize/settings.php';
}
