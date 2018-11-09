<?php
/**
 * Cookd Pro Featured Posts Widget form markup.
 *
 * @package   Cookd\Views\Widgets
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

?>
<p>
	<label for="<?php $this->field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'cookd' ); ?></label>
	<input class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $data['title'] ); ?>" />
</p>

<p>
	<label for="<?php $this->field_id( 'facet' ) ?>"><?php esc_html_e( 'Show facet:', 'cookd' ) ?></label>

	<select class="widefat" name="<?php $this->field_name( 'facet' ) ?>">
		<option value=""><?php esc_html_e( 'Choose facet', 'cookd' ) ?></option>
		<?php foreach ( FWP()->helper->get_facets() as $facet ) : ?>
			<option value="<?php echo esc_attr( $facet['name'] ) ?>"<?php selected( $facet['name'], $data['facet'] ); ?>>
				<?php echo esc_attr( $facet['name'] ); ?>
			</option>
		<?php endforeach ?>
	</select>
</p>

<p>
	<label for="<?php $this->field_id( 'reset' ) ?>"><?php esc_html_e( 'Show reset button:', 'cookd' ) ?></label>
	<input id="<?php $this->field_id( 'reset' ); ?>" type="checkbox" name="<?php $this->field_name( 'reset' ); ?>"<?php checked( $data['reset'] ) ?>>
</p>

<p>
	<label for="<?php $this->field_id( 'reset_text' ); ?>"><?php esc_html_e( 'Reset button text:', 'cookd' ); ?></label>
	<input class="widefat" id="<?php $this->field_id( 'reset_text' ); ?>" name="<?php $this->field_name( 'reset_text' ); ?>" type="text" value="<?php echo esc_attr( $data['reset_text'] ); ?>" />
</p>
