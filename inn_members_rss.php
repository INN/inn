<?php

/**
 *  ==============  Membership RSS Feed Import stuff  ==============
 */
if ( !defined( 'MEMBER_POST_COUNT' ) ) {
	define( 'MEMBER_POST_COUNT', 20);
}
if ( !defined( 'MEMBER_EXPIRE_MEDIA' ) ) {
	define( 'MEMBER_EXPIRE_MEDIA', true);
}

/**
 * Define the custom post type
 */
function inn_init_member_content() {

	//post type for rss-imported member content
  register_post_type( 'network_content',
    array(
      'labels' => array(
        'name' => _x('Network Content', 'post type general name'),
        'singular_name' => _x('Content Item', 'post type singular name'),
        'add_new' => _x('Add Network Item', 'new inn network content item '),
        'add_new_item' => __('Add Network Content'),
        'edit_item' => __('Edit Item'),
        'new_item' => __('New Item'),
        'all_items' => __('All Network Content'),
        'view_item' => __('View Item'),
        'search_items' => __('Search Network Content'),
        'not_found' =>  __('No content found'),
        'not_found_in_trash' => __('No content found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => __('Network Content')
      ),
    'menu_position' => 22,
    'show_ui' => true,
    'description' => 'Content imported from INN member sites',
    'exclude_from_search' => true,
    'publicly_queryable' => true,
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'network-content'),
    'hierarchical' => false,
    'supports' => array('title','editor','thumbnail','author','custom-fields','excerpt'),
    'taxonomies' => array('category', 'post_tag')
    )
  );
}
add_action( 'init', 'inn_init_member_content', 8 );	//need to make this exist before cron initiates

function inn_init_member_tax() {
	register_taxonomy_for_object_type( 'prominence', 'network_content' );
}
add_action( 'init', 'inn_init_member_tax', 11 );

/**
 * Trigger feedimport addition
 */
function inn_member_feed( $post_id ) {

	//get our feed URL and post title (for taxonomy term)
	$feed_url = get_post_meta( $post_id, 'inn_rss', TRUE );
	$title = get_the_title( $post_id );

	//do nothing, if the plugin doesn't exist
	if ( !function_exists('feedinput_register_feed') || $feed_url == "" ) return;

	//create the feed (this needs to try and update first)
	feedinput_register_feed(
		$title,
		array(
			array( 'url' => $feed_url, 'custom_term' => $title )
		),
		array(
			'convert_to_post' => TRUE,
			'convert_post_type' => 'network_content',
			'days_before_delete_items' => 30,
			'expire_converted_posts' => array( 'days_before_expire' => 90 ),
			'convert' => array(
				'post' => array(
					'post_status' => array( 'type' => 'literal', 'value' => 'publish' ),
				),
				'meta' => array(
					'from_member_id' => array( 'type' => 'literal', 'value' => $post_id ),
					'largo_byline_text' => array( 'type' => 'callback', 'value' => 'inn_largo_author' ),
				),
			)
		)
	);

	//load stuff (do we really want to do this now?)
	//feedinput_force_update_feed( $title );
}
add_action( 'save_post', 'inn_member_feed' );


/**
 * Add authors to imported feed items
 */
function inn_largo_author( $data ) {
	//try to get author of item
	$author_names = array();
	$imported_authors = $data['authors'];
	if (is_array($imported_authors)) {
		foreach( $imported_authors as $author ) {
			$author_names[] = $author['name'];
		}
	}
	if ( array_key_exists('name', $data) ) $author_names[] = $data['name'];
	return implode(", ", $author_names);
}


/**
 * Grab all the feeds and register
 */
function inn_register_all_feeds() {
	if ( !function_exists('feedinput_register_feed') ) return;

	//get all the members
	$posts_with_feeds = get_posts( array(
		'posts_per_page' => -1,
		'post_type' => 'inn_member',
		)
	);

	//loop thru and process
	foreach( $posts_with_feeds as $post ) {
		inn_member_feed( $post->ID );
	}
}
add_action( 'init', 'inn_register_all_feeds', 9 );	//priority 9 to run before cron


/**
 * Override permalinks for posts from feeds
 */
function feed_post_permalink( $url ) {
	global $post;
	$source_url = get_post_meta( $post->ID, 'permalink', TRUE );
	if ( $source_url && $post->post_type == 'network_content' ) return $source_url;
	return $url;
}
add_filter('the_permalink', 'feed_post_permalink');
add_filter('the_permalink_rss', 'feed_post_permalink');


/**
 * Delete old network content
 * Retains MEMBER_POST_COUNT most-recent posts from each source
 * And any items currently featured
 * Optionally axes media attachments affiliated with posts
 */

function expire_imported( ) {
	//get members
	$members = get_members( false );

	//for each member, get affiliated posts
	foreach ( $members as $mem ) {

		$args = array(
			'post_type' => 'network_content',
			'posts_per_page' => 32767,	//can't be -1 or the LIMIT clause (OFFSET is set) gets lopped off
			'offset' => MEMBER_POST_COUNT,
			'meta_query' => array(
				array(
					'key' => 'from_member_id',
					'value' => $mem->ID,
					'compare' => '='
				)
			)
		);

		$old_posts = new WP_Query($args);

		//for each post...
		while( $old_posts->have_posts()) {

			$old_posts->the_post();
			global $post;

			//make sure it's not featured somewhere
			if ( has_term( array('homepage-featured', 'sidebar-featured', 'footer-featured', 'top-story'), 'prominence', $post) ) {
				continue;
			}

			//get and delete its attachments, bypassing trash
			if ( MEMBER_EXPIRE_MEDIA ) {
				$assets = get_children('post_type=attachment&post_parent=' . $post->ID );
				foreach ( $assets as $asset ) {
					wp_delete_attachment( $asset->ID, true );
				}
			}

			//delete the post itself, bypassing trash
			wp_delete_post( $post->ID, true );
		}
	}
	wp_reset_query();
}

/**
 * Schedule member content deletion
 */
add_action('inn_scheduled', 'expire_imported');

function schedule_deletion() {
	if ( !wp_next_scheduled( 'inn_scheduled' ) ) {
		wp_schedule_event( time(), 'twicedaily', 'inn_scheduled');
	}
}
add_action('init', 'schedule_deletion');

/**
 * Hide "Feed Items" since they're irrelevant here
 */
add_action('admin_menu', 'inn_hide_feed_items', 20);
function inn_hide_feed_items() {
	global $menu;
	$restricted = array(__('Feed Items', 'inn'));
	if ( !is_array($menu)) return;

	foreach( $menu as $key => $item) {
		$text_title = trim(strip_tags($item[0]));
		if ( in_array($text_title, $restricted) ) {
			unset( $menu[$key] );
		}
	}
}

/**
 * Change the post manager page
 */
function inn_nc_cols( $columns ) {
	//remove coauthors, tags, add thumb, source
	$columns = array(
		'cb' => '<input type="checkbox">',
		'thumb' => '⚘',
		'title' => 'Title',
		'source' => 'Source',
		'prominence' => 'Prominence',
		'categories' => 'Categories',
		'date' => 'Date'
	);

	return $columns;
}
add_filter('manage_network_content_posts_columns' , 'inn_nc_cols');

function inn_custom_column( $column, $post_id ) {
    switch ( $column ) {
      case 'thumb' :
      	echo get_the_post_thumbnail( $post_id, array(32,32) );
        break;

      case 'source' :
      	$member_id = get_post_meta( $post_id , 'from_member_id' , true );
      	if ( $member_id ) {
      		$mem = get_post( $member_id );
          echo $mem->post_title;
				}
        break;

      case 'prominence' :
      	$terms = get_the_term_list( $post_id, 'prominence', '', ', ', '');
      	echo ( $terms ) ? $terms : '—';
      	break;
    }
}
add_action( 'manage_network_content_posts_custom_column' , 'inn_custom_column', 10, 2 );

function inn_col_style() {
	?>
	<style>
		.column-thumb { width: 32px; }
		th#thumb { font-size: 150%; text-align: center; color: #666;	}
	</style>
	<?php
}
add_action( 'admin_head', 'inn_col_style' );

/**
 * A helper function for network_content source names
 */
function get_membername_from_post( $post_id = NULL ) {

	if ( $post_id == NULL ) {
		global $post;
		$post_id = $post->ID;
	}

	$member_id = get_post_meta( $post_id, 'from_member_id', TRUE );
	if ( $member_id == '' ) return false;

	return get_the_title( $member_id );
}

/**
 * Change author names on network_content, mostly for RSS
 */
function inn_author( $author_name ) {

	if ( is_feed() && 'network_content' == get_post_type() ) {
		global $post;
		$author_name = get_post_meta( $post->ID, 'largo_byline_text', true );
		$member_name = get_membername_from_post();
		return ( empty($author_name) ) ? $member_name : $author_name . ", " . $member_name;
	}

	return $author_name;

}
add_filter( 'the_author', 'inn_author' );