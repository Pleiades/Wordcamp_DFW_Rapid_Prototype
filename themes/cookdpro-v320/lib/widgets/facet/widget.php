<?php
/**
 * Cookd Pro Facet WP widget.
 *
 * @package   Cookd\Widgets
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   GPL-2.0+
 * @since     1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cookd Pro Facet widget class.
 *
 * @since   1.0.0
 * @package Cookd\Widgets
 */
class Cookd_Facet_Widget extends WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor. Set the default widget options and create widget.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->defaults = array(
			'title'       => '',
			'facet'       => '',
			'reset'       => '',
			'reset_text' => __( 'Reset', 'cookd' ),
		);

		$widget_ops = array(
			'classname'   => 'facetwp',
			'description' => __( 'Displays a FacetWP facet.', 'cookd' ),
		);

		$control_ops = array(
			'id_base' => 'cookd-facet',
		);

		parent::__construct( 'cookd-facet', __( 'Cookd Pro - Facet', 'cookd' ), $widget_ops, $control_ops );
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

		require COOKD_DIR . "lib/widgets/facet/views/{$slug}.php";
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
		$instance = $old_instance;

		$instance['title']       = wp_strip_all_tags( $new_instance['title'] );
		$instance['facet']       = wp_strip_all_tags( $new_instance['facet'] );
		$instance['reset']       = (bool) $new_instance['reset'];
		$instance['reset_text'] = wp_strip_all_tags( $new_instance['reset_text'] );

		return $instance;
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
		echo $args['before_widget'];
		$title = '';

		if ( ! empty( $instance['title'] ) ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		}

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( ! empty( $instance['facet'] ) ) {
			echo facetwp_display( 'facet', sanitize_key( $instance['facet'] ) );
		}

		if ( ! empty( $instance['reset'] ) && ! empty( $instance['reset_text'] ) ) {
			echo '<a class="button" onclick="FWP.reset()">' . esc_html( $instance['reset_text'] ) . '</a>';
		}

		echo $args['after_widget'];
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
	 * Echo the settings update form.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$this->get_widget_template( 'form', $instance );
	}
}
