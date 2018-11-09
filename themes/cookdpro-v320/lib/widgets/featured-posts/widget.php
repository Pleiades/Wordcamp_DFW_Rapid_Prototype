<?php
/**
 * Cookd Pro Featured Posts
 *
 * @package   Cookd\Widgets
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'WPINC' ) || die;

/**
 * Cookd Pro Featured Post widget class.
 *
 * @since   1.0.0
 * @package Cookd\Widgets
 */
class Cookd_Featured_Posts extends WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Holds custom read more text.
	 *
	 * @var string
	 */
	protected $more_text;

	/**
	 * Constructor. Set the default widget options and create widget.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->defaults = array(
			'title'                   => '',
			'posts_cat'               => '',
			'posts_num'               => 1,
			'posts_offset'            => 0,
			'orderby'                 => '',
			'order'                   => '',
			'exclude_displayed'       => 0,
			'show_image'              => 'none',
			'image_alignment'         => '',
			'image_size'              => '',
			'show_gravatar'           => 0,
			'gravatar_alignment'      => '',
			'gravatar_size'           => '',
			'simple_grid'             => 'full',
			'show_title'              => 0,
			'show_byline'             => 0,
			'post_info'               => '[post_date] ' . __( 'By', 'cookd' ) . ' [post_author_posts_link] [post_comments]',
			'show_content'            => 'excerpt',
			'content_limit'           => '',
			'more_text'               => __( 'Read More', 'cookd' ),
			'more_from_category'      => '',
			'more_from_category_text' => __( 'More Posts from this Category', 'cookd' ),
		);

		parent::__construct(
			'featured-post',
			__( 'Cookd Pro - Featured Posts', 'cookd' ),
			array(
				'classname'   => 'featured-content featuredpost',
				'description' => __( 'Displays featured posts with thumbnails', 'cookd' ),
			),
			array(
				'id_base' => 'featured-post',
			)
		);
	}

	/**
	 * Get the read more text property.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Cookd_Pro_Featured_Posts::$more_text the read more text for the current widget.
	 */
	public function get_read_more_text() {
		return $this->more_text;
	}

	/**
	 * Set the read more text property.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $text text to use as the read more text for the current widget.
	 * @return void
	 */
	public function set_read_more_text( $text ) {
		$this->more_text = (string) esc_html( $text );
	}

	/**
	 * Load a widget template file.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  string $slug the slug of a template file to be included.
	 * @param  array  $data a data array to be passed to the template.
	 * @param  bool   $extract whether or not to extract the data array.
	 * @return void
	 */
	protected function get_widget_template( $slug, $data = array(), $extract = false ) {
		if ( $extract ) {
			extract( $data );
			unset( $extract );
		}

		require COOKD_DIR . "lib/widgets/featured-posts/views/{$slug}.php";
	}

	/**
	 * Retrieve translated strings from WPML.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array $data a data array which contains the translated string keys.
	 * @return array $data the data array with the translated strings.
	 */
	protected function wpml_translate( $data ) {
		if ( ! function_exists( 'icl_translate' ) ) {
			return $data;
		}

		$data['post_info'] = icl_translate(
			'Widgets',
			"Featured Posts - Post Info {$this->number}",
			$data['post_info']
		);
		$data['more_text'] = icl_translate(
			'Widgets',
			"Featured Posts - Read More {$this->number}",
			$data['more_text']
		);
		$data['more_from_category_text'] = icl_translate(
			'Widgets',
			"Featured Posts - More Category Text {$this->number}",
			$data['more_from_category_text']
		);

		return $data;
	}

	/**
	 * Helper for loading the correct content type on the widget front-end.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array $data a data array with user options.
	 * @return void
	 */
	protected function the_entry_content( $data ) {
		$this->set_read_more_text( $data['more_text'] );

		add_filter( 'cookd_read_more_text', array( $this, 'get_read_more_text' ) );

		if ( 'excerpt' === $data['show_content'] ) {
			the_excerpt();
		} elseif ( 'content-limit' === $data['show_content'] ) {
			the_content_limit( absint( $data['content_limit'] ) );
		} else {
			the_content();
		}

		remove_filter( 'cookd_read_more_text', array( $this, 'get_read_more_text' ) );
	}

	/**
	 * Echo the widget content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global WP_Query $wp_query Query object.
	 * @global array    $_genesis_displayed_ids Array of displayed post IDs.
	 * @param  array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param  array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$instance = $this->wpml_translate( $instance );

		$data = array(
			'args' => $args,
			'data' => $instance,
		);

		$this->get_widget_template( 'display', $data, true );
	}

	/**
	 * Register translated strings for WPML.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array $data An array of data with strings to be translated.
	 * @return array $data The data array with the translated strings registered.
	 */
	protected function wpml_register( $data ) {
		if ( ! function_exists( 'icl_register_string' ) ) {
			return $data;
		}

		icl_register_string(
			'Widgets',
			"Featured Posts - Post Info {$this->number}",
			$data['post_info']
		);
		icl_register_string(
			'Widgets',
			"Featured Posts - Read More {$this->number}",
			$data['more_text']
		);
		icl_register_string(
			'Widgets',
			"Featured Posts - More Category Text {$this->number}",
			$data['more_from_category_text']
		);

		return $data;
	}

	/**
	 * Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $new_instance New settings for this instance as input by the user via form().
	 * @param  array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving
	 */
	public function update( $new_instance, $old_instance ) {
		$new_instance['title']                   = wp_strip_all_tags( $new_instance['title'] );
		$new_instance['post_info']               = wp_kses_post( $new_instance['post_info'] );
		$new_instance['more_text']               = wp_strip_all_tags( $new_instance['more_text'] );
		$new_instance['more_from_category_text'] = wp_strip_all_tags( $new_instance['more_from_category_text'] );

		// Update the legacy show_image value for consistency moving forward.
		if ( '1' === $old_instance['show_image'] ) {
			$new_instance['show_image'] = 'before_title';
		}

		$new_instance = $this->wpml_register( $new_instance );

		return $new_instance;
	}

	/**
	 * Echo a field ID.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  string $field the field from which to retrieve the ID.
	 * @return void
	 */
	protected function field_id( $field ) {
		echo $this->get_field_id( $field );
	}

	/**
	 * Echo a field name.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  string $field the field from which to retrieve the name.
	 * @return void
	 */
	protected function field_name( $field ) {
		echo $this->get_field_name( $field );
	}

	/**
	 * Get all image sizes for the form.
	 *
	 * @since  1.2.0
	 * @access protected
	 * @return array
	 */
	protected function get_form_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();
		$core_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large' );
		$extra_sizes = $_wp_additional_image_sizes;

		foreach ( (array) get_intermediate_image_sizes() as $size ) {
			if ( in_array( $size, $core_sizes, true ) ) {
				$sizes[ $size ]['width']  = get_option( "{$size}_size_w" );
				$sizes[ $size ]['height'] = get_option( "{$size}_size_h" );
				$sizes[ $size ]['crop']   = (bool) get_option( "{$size}_crop" );
			} elseif ( is_array( $extra_sizes ) && isset( $extra_sizes[ $size ] ) ) {
				$sizes[ $size ] = array(
					'width'  => $extra_sizes[ $size ]['width'],
					'height' => $extra_sizes[ $size ]['height'],
					'crop'   => $extra_sizes[ $size ]['crop'],
				);
			}
		}

		return $sizes;
	}

	/**
	 * Echo the settings update form.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		if ( isset( $instance['show_image'] ) && '1' === $instance['show_image'] ) {
			$instance['show_image'] = 'before_title';
		}

		$this->get_widget_template( 'form', $instance );
	}
}
