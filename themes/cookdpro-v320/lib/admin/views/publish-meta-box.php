<?php
/**
 * Template to display the WP Featherlight admin sidebar meta box.
 *
 * @package   Cookd\Views
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

?>
<div id="cookd-enable-wrap" class="misc-pub-section cookd-enable" style="position:relative;">
	<label for="_cookd_enable_grid">
		<input type="checkbox" name="_cookd_enable_grid" id="_cookd_enable_grid" value="yes"<?php checked( $enable, true ); ?> />
		<?php esc_html_e( 'Enable Grid Layout', 'cookd' ); ?>
	</label>
</div>
<?php wp_nonce_field( 'save_cookd_metabox', 'cookd_metabox_nonce' ); ?>
