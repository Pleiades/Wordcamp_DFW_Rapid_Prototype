<?php

/**
 * Registers Community custom post type and Community Type taxonomy.
 * Extends the Winning Agent Pro Communities Widget.
 *
 * @package Agent Focused Pro
 * @author  Marcy Diaz for Winning Agent
 * @subpackage Customizations
 * @license GPL-2.0+
 *
 */

// Register Community Type Taxonomy taxonomy for the Community custom post type.
register_taxonomy( 'wap-community-type', 'wap-community',
	array(
		'labels' 				=> array(
			'name'          	=> _x( 'Community Type', 'taxonomy general name', 'agentfocused' ),
			'add_new_item'  	=> __( 'Add New Community Type', 'agentfocused' ),
			'new_item_name' 	=> __( 'New Community Type', 'agentfocused' ),
		),
		'exclude_from_search' 	=> true,
		'has_archive'         	=> true,
		'hierarchical'        	=> true,
		'rewrite'             	=> array( 'slug' => 'community/type', 'with_front' => false ),
		'show_ui'             	=> true,
		'show_admin_column' 	=> true,
		'show_tagcloud'       	=> true,
	)
);

// Register Community custom post type.
register_post_type( 'wap-community',
	array(
		'labels' 			=> array(
			'name'          => __( 'Communities', 'agentfocused' ),
			'singular_name' => __( 'Community', 'agentfocused' ),
			'all_items'             => __( 'All Communities', 'agentfocused' ),
			'add_new_item'          => __( 'Add New Community', 'agentfocused' ),
			'add_new'               => __( 'Add New', 'agentfocused' ),
			'edit_item'             => __( 'Edit Community', 'agentfocused' ),
		),
		'has_archive'  		=> true,
		'hierarchical' 		=> true,
		'menu_icon'			=> 'dashicons-admin-home',
		'public'       		=> true,
		'rewrite'      		=> array( 'slug' => 'community', 'with_front' => false ),
		'supports'     		=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'page-attributes', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings' ),
		'taxonomies'   		=> array( 'wap-community-type' ),

	)
);

// Add Community Type Taxonomy to columns.
add_filter( 'manage_taxonomies_for_wap_community_columns', 'wap_community_type_columns' );
function wap_community_type_columns( $taxonomies ) {

	$taxonomies[] = 'wap-community-type';
	return $taxonomies;

}
