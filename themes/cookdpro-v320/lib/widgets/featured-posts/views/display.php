<?php
/**
 * Cookd Pro Featured Posts Widget display markup.
 *
 * @package   Cookd\Views\Widgets
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

global $wp_query, $_genesis_displayed_ids;

echo $args['before_widget'];

if ( ! empty( $data['title'] ) ) {
	echo $args['before_title'] . apply_filters( 'widget_title', $data['title'], $data, $this->id_base ) . $args['after_title'];
}

$query_args = array(
	'post_type'           => 'post',
	'cat'                 => $data['posts_cat'],
	'showposts'           => $data['posts_num'],
	'offset'              => $data['posts_offset'],
	'orderby'             => $data['orderby'],
	'order'               => $data['order'],
	'ignore_sticky_posts' => true,
);

if ( $data['exclude_displayed'] ) {
	$query_args['post__not_in'] = (array) $_genesis_displayed_ids;
}

$wp_query = new WP_Query( $query_args );

if ( have_posts() ) :

	if ( $grid = cookd_grid_exists( $data['simple_grid'] ) ) {
		add_filter( 'post_class', "cookd_grid_{$grid}" );
	}

	while ( have_posts() ) : the_post();

		$_genesis_displayed_ids[] = get_the_ID();

		$raw_image = genesis_get_image( array(
			'format'  => 'html',
			'size'    => $data['image_size'],
			'context' => 'featured-post-widget',
			'attr'    => genesis_parse_attr( 'entry-image-widget' ),
		) );

		$image = sprintf( '<a href="%s" title="%s" class="grid-image %s">%s</a>',
			get_permalink(),
			the_title_attribute( 'echo=0' ),
			esc_attr( $data['image_alignment'] ),
			$raw_image
		);

		genesis_markup( array(
			'html5'   => '<article %s>',
			'xhtml'   => sprintf( '<div class="%s">', implode( ' ', get_post_class() ) ),
			'context' => 'entry',
		) );

		if ( ( 'before_title' === $data['show_image'] || '1' === $data['show_image'] ) && $raw_image ) {
			echo $image;
		}

		if ( ! empty( $data['show_gravatar'] ) ) {
			printf( '<span class="%s">%s</span>',
				esc_attr( $data['gravatar_alignment'] ),
				get_avatar( get_the_author_meta( 'ID' ), $data['gravatar_size'] )
			);
		}

		if ( ! empty( $data['show_title'] ) ) {
			echo '<header class="entry-header">';
		}

		if ( ! empty( $data['show_byline'] ) && ! empty( $data['post_info'] ) ) {
			printf( '<p class="entry-meta">%s</p>',
				do_shortcode( $data['post_info'] )
			);
		}

		if ( ! empty( $data['show_title'] ) ) {
			printf( '<h2 class="entry-title"><a href="%s">%s</a></h2>',
				esc_url( get_permalink() ),
				get_the_title()
			);
		}

		if ( ! empty( $data['show_title'] ) ) {
			echo '</header>';
		}

		if ( 'after_title' === $data['show_image'] && $raw_image ) {
			echo $image;
		}

		if ( ! empty( $data['show_content'] ) ) {
			echo '<div class="entry-content">';
			$this->the_entry_content( $data );
			echo '</div>';
		}

		if ( 'after_content' === $data['show_image'] && $raw_image ) {
			echo $image;
		}

		genesis_markup( array(
			'html5' => '</article>',
			'xhtml' => '</div>',
		) );

	endwhile;

	if ( $grid ) {
		remove_filter( 'post_class', "cookd_grid_{$grid}" );
	}

	wp_reset_query();

endif;

if ( ! empty( $data['more_from_category'] ) && ! empty( $data['posts_cat'] ) ) {
	printf( '<p class="more-from-category"><a href="%1$s" title="%2$s">%3$s</a></p>',
		esc_url( get_category_link( $data['posts_cat'] ) ),
		esc_attr( get_cat_name( $data['posts_cat'] ) ),
		esc_html( $data['more_from_category_text'] )
	);
}

echo $args['after_widget'];
