<?php

/*
 * Register inn_member post type
 */
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
		'rewrite'               => array( 'slug' => 'members' ),
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'inn_member', $args );

}
add_action( 'init', 'inn_member_directory', 0 );

/*
 * Add thumbnail to list table view for post type
 */
function inn_post_list_table_thumb_column( $cols ) {

	$cols['thumbnail'] = __( 'Thumbnail' );

	return $cols;
}

function inn_post_list_table_thumb_value( $column_name, $post_id ) {

	$width = (int) 60;
	$height = (int) 60;

	if ( 'thumbnail' === $column_name ) {

		// thumbnail of WP 2.9
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );

		// image from gallery
		$attachments = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );

		if ( $thumbnail_id ) {
			$thumb = wp_get_attachment_image( $thumbnail_id, array( $width, $height ), true );
		} elseif ( $attachments ) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array( $width, $height ), true );
			}
		}

		if ( isset( $thumb ) && $thumb ) {
			echo $thumb;
		} else {
			echo __( 'None' );
		}
	}
}

// for posts
add_filter( 'manage_inn_member_posts_columns', 'inn_post_list_table_thumb_column' );
add_action( 'manage_inn_member_posts_custom_column', 'inn_post_list_table_thumb_value', 10, 2 );

// for pages
add_filter( 'manage_pages_columns', 'AddThumbColumn' );
add_action( 'manage_pages_custom_column', 'AddThumbValue', 10, 2 );

function inn_project_post_type() {
	$labels = array(
		'name'                  => 'Projects',
		'singular_name'         => 'Project',
		'add_new'               => sprintf( __( 'Add New %1$s' ), 'Project' ),
		'add_new_item'          => sprintf( __( 'Add New %1$s' ), 'Project' ),
		'edit_item'             => sprintf( __( 'Edit %1$s' ), 'Projects' ),
		'new_item'              => sprintf( __( 'New %1$s' ), 'Project' ),
		'view_item'             => sprintf( __( 'View %1$s' ), 'Project' ),
		'search_items'          =>  sprintf( __( 'Search %1$s' ), 'Projects' ),
		'not_found'             => sprintf( __( 'No %1$s Found' ), 'Projects' ),
		'not_found_in_trash'    => sprintf( __( 'No %1$s Found in Trash' ), 'Projects' ),
		'parent_item_colon'     => '',
	);

	$args = array(
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'exclude_from_search'   => false,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'project' ),
		'has_archive'           => true,
		'show_in_menu'          => true,
		'capability_type'       => 'page',
		'hierarchical'          => false,
		'menu_position'         => null,
		'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'pauinn_project', $args );
}
add_action( 'init', 'inn_project_post_type', 0 );

function member_directory_tax() {

	$focus_areas_labels = array(
		'name'                       => _x( 'Focus Areas', 'Taxonomy General Name', 'inn' ),
		'singular_name'              => _x( 'Focus Area', 'Taxonomy Singular Name', 'inn' ),
		'menu_name'                  => __( 'Focus Areas', 'inn' ),
		'all_items'                  => __( 'All Focus Areas', 'inn' ),
		'parent_item'                => __( 'Parent Item', 'inn' ),
		'parent_item_colon'          => __( 'Parent Item:', 'inn' ),
		'new_item_name'              => __( 'New Focus Area', 'inn' ),
		'add_new_item'               => __( 'Add New Focus Area', 'inn' ),
		'edit_item'                  => __( 'Edit Focus Area', 'inn' ),
		'update_item'                => __( 'Update Focus Area', 'inn' ),
		'view_item'                  => __( 'View Focus Area', 'inn' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'inn' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'inn' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'inn' ),
		'popular_items'              => __( 'Popular Items', 'inn' ),
		'search_items'               => __( 'Search Items', 'inn' ),
		'not_found'                  => __( 'Not Found', 'inn' ),
		'no_terms'                   => __( 'No items', 'inn' ),
		'items_list'                 => __( 'Items list', 'inn' ),
		'items_list_navigation'      => __( 'Items list navigation', 'inn' ),
	);
	$focus_areas_args = array(
		'labels'                     => $focus_areas_labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'ppu_focus_areas', array( 'inn_member' ), $focus_areas_args );

	$member_project_labels = array(
		'name'                       => _x( 'Projects', 'inn' ),
		'singular_name'              => _x( 'Project', 'inn' ),
		'menu_name'                  => __( 'Projects', 'inn' ),
		'all_items'                  => __( 'All Projects', 'inn' ),
		'parent_item'                => __( 'Parent Item', 'inn' ),
		'parent_item_colon'          => __( 'Parent Item:', 'inn' ),
		'new_item_name'              => __( 'New Project', 'inn' ),
		'add_new_item'               => __( 'Add New Project', 'inn' ),
		'edit_item'                  => __( 'Edit Project', 'inn' ),
		'update_item'                => __( 'Update Project', 'inn' ),
		'view_item'                  => __( 'View Projects', 'inn' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'inn' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'inn' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'inn' ),
		'popular_items'              => __( 'Popular Items', 'inn' ),
		'search_items'               => __( 'Search Items', 'inn' ),
		'not_found'                  => __( 'Not Found', 'inn' ),
		'no_terms'                   => __( 'No items', 'inn' ),
		'items_list'                 => __( 'Items list', 'inn' ),
		'items_list_navigation'      => __( 'Items list navigation', 'inn' ),
	);

	$member_project_args = array(
		'labels'                     => $member_project_labels,
		'hierarchical'               => true,
		'public'                     => true,
		'rewrite'                    => array(
											'slug'         => 'projects',
											'with_front'   => true,
											'hierarchical' => true
										),
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'pauinn_project_tax', array( 'pauinn_project', 'pp_opportunity', 'post', 'inn_member' ), $member_project_args );
}
add_action( 'init', 'member_directory_tax', 0 );

add_action( 'cmb2_admin_init', 'inn_member_info' );
function inn_member_info() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_';

	/**
	 * Initiate the metabox
	 */
	$member_info = new_cmb2_box( array(
		'id'            => 'member_info',
		'title'         => __( 'Member Info', 'cmb2' ),
		'object_types'  => array( 'inn_member' ), // Post type
		'context'       => 'normal',
		'priority'      => 'low',
		'show_names'    => true, // Show field names on the left
	) );

		$member_info->add_field( array(
			'name'       => __( 'Year Founded', 'cmb2' ),
			'desc'       => __( '', 'cmb2' ),
			'id'         => $prefix . 'year_founded',
			'type'       => 'text_small',
			'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
			'sanitization_cb' => 'absint', // custom sanitization callback parameter
			'escape_cb'       => 'absint',  // custom escaping callback parameter
		) );

		$member_info->add_field( array(
			'name'       => __( 'INN Member Since', 'cmb2' ),
			'desc'       => __( '', 'cmb2' ),
			'id'         => $prefix . 'inn_join_year',
			'type'       => 'text_small',
			'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
			'sanitization_cb' => 'absint', // custom sanitization callback parameter
			'escape_cb'       => 'absint',  // custom escaping callback parameter
		) );

		// Email text field
		$member_info->add_field( array(
			'name' => __( 'Contact Email', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'email',
			'type' => 'text_email',
		) );

		$member_info->add_field( array(
			'name' => __( 'Website URL', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
		) );

		$member_info->add_field( array(
			'name' => __( 'Donate URL', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'donate_url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
		) );

		$member_info->add_field( array(
			'name' => __( 'RSS Feed', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'rss_feed',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
		) );

		$member_info->add_field( array(
			'name' => __( 'Twitter Profile', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'twitter_url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
		) );

		$member_info->add_field( array(
			'name' => __( 'Facebook Page', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'facebook_url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
		) );

		$member_info->add_field( array(
			'name' => __( 'Youtube URL', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'youtube_url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
		) );

		$member_info->add_field( array(
			'name' => __( 'Google+ URL', 'inn' ),
			'desc' => __( '', 'cmb2' ),
			'id'   => $prefix . 'google_plus_url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
		) );

		$member_info->add_field( array(
			'name' => __( 'Contact Phone', 'inn' ),
			'desc' => __( '', 'inn' ),
			'id'   => $prefix . 'phone_number',
			'type' => 'text_small',
		) );

		$member_info->add_field( array(
			'name' => __( 'Address', 'inn' ),
			'desc' => __( '', 'inn' ),
			'id'   => $prefix . 'address',
			'type' => 'address',
		) );

}

add_action( 'updated_postmeta', 'inn_geocode_address', 10, 4 );
/*
 * function to geocode address, it will return false if unable to geocode address
 * adapted from https://www.codeofaninja.com/2014/06/google-maps-geocoding-example-php.html
 */
function inn_geocode_address( $meta_id, $obj_id, $meta_key, $meta_value ) {
	if ( '_address' === $meta_key ) {

	    // url encode the address
	    $address = urlencode( implode( ' ', maybe_unserialize( $meta_value ) ) );

	    // google map geocode api url
	    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

	    // get the json response
	    $resp_json = file_get_contents( $url );

	    // decode the json
	    $resp = json_decode( $resp_json, true );

	    // response status will be 'OK', if able to geocode given address
	    if( 'OK' === $resp['status'] ) {

	        // get the important data
	        $lati = $resp['results'][0]['geometry']['location']['lat'];
	        $longi = $resp['results'][0]['geometry']['location']['lng'];
	        $formatted_address = $resp['results'][0]['formatted_address'];

	        // verify if data is complete
	        if ( $lati && $longi && $formatted_address ){

	            // put the data in the array
	            $data_arr = array();

	            array_push(
	                $data_arr,
	                $lati,
	                $longi,
	                $formatted_address
	            );

				update_post_meta( $obj_id, '_address_latlon', array( $lati, $longi ) );
				return;

	        } else {
	            return false;
	        }

	    } else {
	        return false;
	    }
	}
}
