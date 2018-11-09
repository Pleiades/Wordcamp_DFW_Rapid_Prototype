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
	<label for="<?php $this->field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'cookd' ); ?>:</label>
	<input type="text" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $data['title'] ); ?>" class="widefat" />
</p>

<div class="cookd-widget-box">

	<p>
		<label for="<?php $this->field_id( 'simple_grid' ); ?>"><?php esc_html_e( 'Grid Columns', 'cookd' ); ?>:</label>
		<select class="widefat" id="<?php $this->field_id( 'simple_grid' ); ?>" name="<?php $this->field_name( 'simple_grid' ); ?>">
			<option value="full" <?php selected( 'full', $data['simple_grid'] ); ?>><?php esc_html_e( 'Full Width', 'cookd' ); ?></option>
			<option value="one_half" <?php selected( 'one_half', $data['simple_grid'] ); ?>><?php esc_html_e( 'One Half', 'cookd' ); ?></option>
			<option value="one_third" <?php selected( 'one_third', $data['simple_grid'] ); ?>><?php esc_html_e( 'One Third', 'cookd' ); ?></option>
			<option value="one_fourth" <?php selected( 'one_fourth', $data['simple_grid'] ); ?>><?php esc_html_e( 'One Fourth', 'cookd' ); ?></option>
			<option value="one_sixth" <?php selected( 'one_sixth', $data['simple_grid'] ); ?>><?php esc_html_e( 'One Sixth', 'cookd' ); ?></option>
		</select>
	</p>

</div>

<div class="cookd-widget-box">

	<p>
		<label for="<?php $this->field_id( 'posts_cat' ); ?>"><?php esc_html_e( 'Category', 'cookd' ); ?>:</label>
		<?php
		wp_dropdown_categories( array(
			'name'               => $this->get_field_name( 'posts_cat' ),
			'selected'           => $data['posts_cat'],
			'orderby'            => 'Name',
			'hierarchical'       => 1,
			'show_option_all'    => __( 'All Categories', 'cookd' ),
			'hideesc_html_empty' => '0',
		) );
		?>
	</p>

	<p>
		<label for="<?php $this->field_id( 'posts_num' ); ?>"><?php esc_html_e( 'Number of Posts to Show', 'cookd' ); ?>:</label>
		<input type="text" id="<?php $this->field_id( 'posts_num' ); ?>" name="<?php $this->field_name( 'posts_num' ); ?>" value="<?php echo esc_attr( $data['posts_num'] ); ?>" size="2" />
	</p>

	<p>
		<label for="<?php $this->field_id( 'posts_offset' ); ?>"><?php esc_html_e( 'Number of Posts to Offset', 'cookd' ); ?>:</label>
		<input type="text" id="<?php $this->field_id( 'posts_offset' ); ?>" name="<?php $this->field_name( 'posts_offset' ); ?>" value="<?php echo esc_attr( $data['posts_offset'] ); ?>" size="2" />
	</p>

	<p>
		<label for="<?php $this->field_id( 'orderby' ); ?>"><?php esc_html_e( 'Order By', 'cookd' ); ?>:</label>
		<select class="widefat" id="<?php $this->field_id( 'orderby' ); ?>" name="<?php $this->field_name( 'orderby' ); ?>">
			<option value="date" <?php selected( 'date', $data['orderby'] ); ?>><?php esc_html_e( 'Date', 'cookd' ); ?></option>
			<option value="title" <?php selected( 'title', $data['orderby'] ); ?>><?php esc_html_e( 'Title', 'cookd' ); ?></option>
			<option value="parent" <?php selected( 'parent', $data['orderby'] ); ?>><?php esc_html_e( 'Parent', 'cookd' ); ?></option>
			<option value="ID" <?php selected( 'ID', $data['orderby'] ); ?>><?php esc_html_e( 'ID', 'cookd' ); ?></option>
			<option value="comment_count" <?php selected( 'comment_count', $data['orderby'] ); ?>><?php esc_html_e( 'Comment Count', 'cookd' ); ?></option>
			<option value="rand" <?php selected( 'rand', $data['orderby'] ); ?>><?php esc_html_e( 'Random', 'cookd' ); ?></option>
		</select>
	</p>

	<p>
		<label for="<?php $this->field_id( 'order' ); ?>"><?php esc_html_e( 'Sort Order', 'cookd' ); ?>:</label>
		<select class="widefat" id="<?php $this->field_id( 'order' ); ?>" name="<?php $this->field_name( 'order' ); ?>">
			<option value="DESC" <?php selected( 'DESC', $data['order'] ); ?>><?php esc_html_e( 'Descending (3, 2, 1)', 'cookd' ); ?></option>
			<option value="ASC" <?php selected( 'ASC', $data['order'] ); ?>><?php esc_html_e( 'Ascending (1, 2, 3)', 'cookd' ); ?></option>
		</select>
	</p>

	<p>
		<input id="<?php $this->field_id( 'exclude_displayed' ); ?>" type="checkbox" name="<?php $this->field_name( 'exclude_displayed' ); ?>" value="1" <?php checked( $data['exclude_displayed'] ); ?>/>
		<label for="<?php $this->field_id( 'exclude_displayed' ); ?>"><?php esc_html_e( 'Exclude Previously Displayed Posts?', 'cookd' ); ?></label>
	</p>

</div>

<div class="cookd-widget-box">

	<p>
		<label for="<?php $this->field_id( 'show_image' ); ?>"><?php esc_html_e( 'Show Image', 'cookd' ); ?>:</label>
		<select class="widefat" id="<?php $this->field_id( 'show_image' ); ?>" name="<?php $this->field_name( 'show_image' ); ?>">
			<option value="none">- <?php esc_html_e( 'Don\'t Show an Image', 'cookd' ); ?> -</option>
			<option value="before_title" <?php selected( 'before_title', $data['show_image'] ); ?>><?php esc_html_e( 'Before Title', 'cookd' ); ?></option>
			<option value="after_title" <?php selected( 'after_title', $data['show_image'] ); ?>><?php esc_html_e( 'After Title', 'cookd' ); ?></option>
			<option value="after_content" <?php selected( 'after_content', $data['show_image'] ); ?>><?php esc_html_e( 'After Content', 'cookd' ); ?></option>
		</select>
	</p>

	<p>
		<label for="<?php $this->field_id( 'image_size' ); ?>"><?php esc_html_e( 'Image Size', 'cookd' ); ?>:</label>
		<select class="widefat" id="<?php $this->field_id( 'image_size' ); ?>" class="genesis-image-size-selector" name="<?php $this->field_name( 'image_size' ); ?>">
			<?php
			foreach ( $this->get_form_image_sizes() as $name => $size ) {
				printf( '<option value="%1$s" %2$s>%1$s (%3$sx%4$s)</option>',
					esc_attr( $name ),
					selected( $name, $data['image_size'], false ),
					(int) $size['width'],
					(int) $size['height']
				);
			}
			?>
		</select>
	</p>

	<p>
		<label for="<?php $this->field_id( 'image_alignment' ); ?>"><?php esc_html_e( 'Image Alignment', 'cookd' ); ?>:</label>
		<select id="<?php $this->field_id( 'image_alignment' ); ?>" name="<?php $this->field_name( 'image_alignment' ); ?>">
			<option value="alignnone">- <?php esc_html_e( 'None', 'cookd' ); ?> -</option>
			<option value="alignleft" <?php selected( 'alignleft', $data['image_alignment'] ); ?>><?php esc_html_e( 'Left', 'cookd' ); ?></option>
			<option value="alignright" <?php selected( 'alignright', $data['image_alignment'] ); ?>><?php esc_html_e( 'Right', 'cookd' ); ?></option>
			<option value="aligncenter" <?php selected( 'aligncenter', $data['image_alignment'] ); ?>><?php esc_html_e( 'Center', 'cookd' ); ?></option>
		</select>
	</p>

</div>

<div class="cookd-widget-box">

	<p>
		<input id="<?php $this->field_id( 'show_title' ); ?>" type="checkbox" name="<?php $this->field_name( 'show_title' ); ?>" value="1" <?php checked( $data['show_title'] ); ?>/>
		<label for="<?php $this->field_id( 'show_title' ); ?>"><?php esc_html_e( 'Show Post Title', 'cookd' ); ?></label>
	</p>

	<p>
		<input id="<?php $this->field_id( 'show_byline' ); ?>" type="checkbox" name="<?php $this->field_name( 'show_byline' ); ?>" value="1" <?php checked( $data['show_byline'] ); ?>/>
		<label for="<?php $this->field_id( 'show_byline' ); ?>"><?php esc_html_e( 'Show Post Info', 'cookd' ); ?></label>
		<input type="text" id="<?php $this->field_id( 'post_info' ); ?>" name="<?php $this->field_name( 'post_info' ); ?>" value="<?php echo esc_attr( $data['post_info'] ); ?>" class="widefat" />
	</p>

	<p>
		<label for="<?php $this->field_id( 'show_content' ); ?>"><?php esc_html_e( 'Content Type', 'cookd' ); ?>:</label>
		<select class="widefat" id="<?php $this->field_id( 'show_content' ); ?>" name="<?php $this->field_name( 'show_content' ); ?>">
			<option value="content" <?php selected( 'content', $data['show_content'] ); ?>><?php esc_html_e( 'Show Content', 'cookd' ); ?></option>
			<option value="excerpt" <?php selected( 'excerpt', $data['show_content'] ); ?>><?php esc_html_e( 'Show Excerpt', 'cookd' ); ?></option>
			<option value="content-limit" <?php selected( 'content-limit', $data['show_content'] ); ?>><?php esc_html_e( 'Show Content Limit', 'cookd' ); ?></option>
			<option value="" <?php selected( '', $data['show_content'] ); ?>><?php esc_html_e( 'No Content', 'cookd' ); ?></option>
		</select>
		<br />
		<label for="<?php $this->field_id( 'content_limit' ); ?>"><?php esc_html_e( 'Limit content to', 'cookd' ); ?>
			<input type="text" id="<?php $this->field_id( 'image_alignment' ); ?>" name="<?php $this->field_name( 'content_limit' ); ?>" value="<?php echo esc_attr( intval( $data['content_limit'] ) ); ?>" size="3" />
			<?php esc_html_e( 'characters', 'cookd' ); ?>
		</label>
	</p>

	<p>
		<label for="<?php $this->field_id( 'more_text' ); ?>"><?php esc_html_e( 'More Text (if applicable)', 'cookd' ); ?>:</label>
		<input type="text" id="<?php $this->field_id( 'more_text' ); ?>" name="<?php $this->field_name( 'more_text' ); ?>" value="<?php echo esc_attr( $data['more_text'] ); ?>" />
	</p>

</div>

<div class="cookd-widget-box">

	<p>
		<input id="<?php $this->field_id( 'show_gravatar' ); ?>" type="checkbox" name="<?php $this->field_name( 'show_gravatar' ); ?>" value="1" <?php checked( $data['show_gravatar'] ); ?>/>
		<label for="<?php $this->field_id( 'show_gravatar' ); ?>"><?php esc_html_e( 'Show Author Gravatar', 'cookd' ); ?></label>
	</p>

	<p>
		<label for="<?php $this->field_id( 'gravatar_size' ); ?>"><?php esc_html_e( 'Gravatar Size', 'cookd' ); ?>:</label>
		<select class="widefat" id="<?php $this->field_id( 'gravatar_size' ); ?>" name="<?php $this->field_name( 'gravatar_size' ); ?>">
			<option value="45" <?php selected( 45, $data['gravatar_size'] ); ?>><?php esc_html_e( 'Small (45px)', 'cookd' ); ?></option>
			<option value="65" <?php selected( 65, $data['gravatar_size'] ); ?>><?php esc_html_e( 'Medium (65px)', 'cookd' ); ?></option>
			<option value="85" <?php selected( 85, $data['gravatar_size'] ); ?>><?php esc_html_e( 'Large (85px)', 'cookd' ); ?></option>
			<option value="125" <?php selected( 105, $data['gravatar_size'] ); ?>><?php esc_html_e( 'Extra Large (125px)', 'cookd' ); ?></option>
		</select>
	</p>

	<p>
		<label for="<?php $this->field_id( 'gravatar_alignment' ); ?>"><?php esc_html_e( 'Gravatar Alignment', 'cookd' ); ?>:</label>
		<select id="<?php $this->field_id( 'gravatar_alignment' ); ?>" name="<?php $this->field_name( 'gravatar_alignment' ); ?>">
			<option value="alignnone">- <?php esc_html_e( 'None', 'cookd' ); ?> -</option>
			<option value="alignleft" <?php selected( 'alignleft', $data['gravatar_alignment'] ); ?>><?php esc_html_e( 'Left', 'cookd' ); ?></option>
			<option value="alignright" <?php selected( 'alignright', $data['gravatar_alignment'] ); ?>><?php esc_html_e( 'Right', 'cookd' ); ?></option>
		</select>
	</p>

</div>

<div class="cookd-widget-box">

	<p>
		<input id="<?php $this->field_id( 'more_from_category' ); ?>" type="checkbox" name="<?php $this->field_name( 'more_from_category' ); ?>" value="1" <?php checked( $data['more_from_category'] ); ?>/>
		<label for="<?php $this->field_id( 'more_from_category' ); ?>"><?php esc_html_e( 'Show Category Archive Link', 'cookd' ); ?></label>
	</p>

	<p>
		<label for="<?php $this->field_id( 'more_from_category_text' ); ?>"><?php esc_html_e( 'Link Text', 'cookd' ); ?>:</label>
		<input type="text" id="<?php $this->field_id( 'more_from_category_text' ); ?>" name="<?php $this->field_name( 'more_from_category_text' ); ?>" value="<?php echo esc_attr( $data['more_from_category_text'] ); ?>" class="widefat" />
	</p>

</div>
