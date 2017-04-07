<?php
// Register Custom Post Type
function inn_member_directory() {

	$labels = array(
		'name'                  => _x( 'Members', 'Post Type General Name', 'inn' ),
		'singular_name'         => _x( 'Member', 'Post Type Singular Name', 'inn' ),
		'menu_name'             => __( 'INN Members', 'inn' ),
		'name_admin_bar'        => __( 'INN Members', 'inn' ),
		'archives'              => __( 'INN Member Archives', 'inn' ),
		'attributes'            => __( 'INN Member Attributes', 'inn' ),
		'parent_item_colon'     => __( '', 'inn' ),
		'all_items'             => __( 'All Members', 'inn' ),
		'add_new_item'          => __( 'Add New Member', 'inn' ),
		'add_new'               => __( 'Add New', 'inn' ),
		'new_item'              => __( 'New Item', 'inn' ),
		'edit_item'             => __( 'Edit Item', 'inn' ),
		'update_item'           => __( 'Update Item', 'inn' ),
		'view_item'             => __( 'View Item', 'inn' ),
		'view_items'            => __( 'View Items', 'inn' ),
		'search_items'          => __( 'Search Item', 'inn' ),
		'not_found'             => __( 'Not found', 'inn' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'inn' ),
		'featured_image'        => __( 'Featured Image', 'inn' ),
		'set_featured_image'    => __( 'Set featured image', 'inn' ),
		'remove_featured_image' => __( 'Remove featured image', 'inn' ),
		'use_featured_image'    => __( 'Use as featured image', 'inn' ),
		'insert_into_item'      => __( 'Insert into item', 'inn' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'inn' ),
		'items_list'            => __( 'Items list', 'inn' ),
		'items_list_navigation' => __( 'Items list navigation', 'inn' ),
		'filter_items_list'     => __( 'Filter items list', 'inn' ),
	);
	$args = array(
		'label'                 => __( 'Member', 'inn' ),
		'description'           => __( 'INN Member Directory', 'inn' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-users',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'member_directory', $args );

}
add_action( 'init', 'inn_member_directory', 0 );
