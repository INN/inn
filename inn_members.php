<?php

/**
 *  ==============  Membership Directory stuff  ==============
 */

/**
 * Define the custom post type
 */
function inn_init_members() {

	//Members
  register_post_type( 'inn_member',
    array(
      'labels' => array(
        'name' => _x('Members', 'post type general name'),
        'singular_name' => _x('Member', 'post type singular name'),
        'add_new' => _x('Add New Member', 'new inn member'),
        'add_new_item' => __('Add New Member'),
        'edit_item' => __('Edit Member'),
        'new_item' => __('New Member'),
        'all_items' => __('All Members'),
        'view_item' => __('View Member'),
        'search_items' => __('Search Members'),
        'not_found' =>  __('No members found'),
        'not_found_in_trash' => __('No members found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => __('INN Members')
      ),
    'menu_position' => 21,
    'show_ui' => true,
    'description' => 'INN Member publications/groups',
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'member'),
    'hierarchical' => false,
    'supports' => array('title','editor','thumbnail'), //see add_post_type_support()  - leave editor blank for no Case Study
    )
  );

	//set an image size for the member widget
	add_image_size( 'member-thumbnail', 60, 60, true );

	//build a menu for the widget and listing header
	register_nav_menu( 'membership', 'Members Menu' );

}
add_action( 'init', 'inn_init_members', 11 );


/**
 * Re-define paths to load meta-box plugin for member fields
 */
define( 'RWMB_URL', trailingslashit( get_stylesheet_directory_uri() . '/meta-box' ) );
define( 'RWMB_DIR', trailingslashit( get_stylesheet_directory() . '/meta-box' ) );
// Include the meta box script
require_once RWMB_DIR . 'meta-box.php';


/**
 * Build the meta-boxes for Member PT
 */
function inn_meta_boxes() {
  // Check if plugin is activated or included in theme
  if ( !class_exists( 'RW_Meta_Box' ) ) return;
  $prefix = 'inn_';

  //YEARS
  $meta_box = array(
    'id'       => 'details',
    'title'    => 'Details',
    'pages'    => array( 'inn_member' ),
    'context'  => 'normal',
    'priority' => 'high',
    'fields' => array(
      array(
        'name'  => 'Contact Email',
        'id'    => $prefix . 'email',
        'type'  => 'text',
      ),
      array(
        'name'  => 'Contact Phone',
        'id'    => $prefix . 'phone',
        'type'  => 'text',
      ),
      array(
        'name'  => 'Mailing Address',
        'id'    => $prefix . 'address',
        'type'  => 'textarea',
        'rows'	=> 5,
        'desc'   => "Location of member, used in members map."
      ),
      array(
        'name'  => 'Year Founded',
        'id'    => $prefix . 'founded',
        'type'  => 'text',
        'size'	=> 5,
        'desc'   => "The full year of founding, if known."
      ),
      array(
        'name'  => 'Member Since',
        'id'    => $prefix . 'since',
        'type'  => 'text',
        'size'	=> 5,
        'desc'   => "The year this group joined INN, if known."
      ),

    )
  );
  new RW_Meta_Box( $meta_box ); //Details

  //YEARS
  $meta_box = array(
    'id'       => 'links',
    'title'    => 'URLs',
    'pages'    => array( 'inn_member' ),
    'context'  => 'normal',
    'priority' => 'high',
    'fields' => array(
      array(
        'name'  => 'Website',
        'id'    => $prefix . 'site_url',
        'type'  => 'text',
        'desc'   => "The URL of this member’s website, including http(s)://"
      ),
      array(
        'name'  => 'Donation Page',
        'id'    => $prefix . 'donate',
        'type'  => 'text',
        'desc'   => "The URL of this member’s donation page, including http(s)://"
      ),
      array(
        'name'  => 'RSS',
        'id'    => $prefix . 'rss',
        'type'  => 'text',
        'desc'   => "The URL of this member’s main RSS feed, including http(s)://"
      ),
      array(
        'name'  => 'Twitter handle',
        'id'    => $prefix . 'twitter',
        'type'  => 'text',
        'desc'   => "The Twitter username for this member. Exclude @ sign."
      ),
      array(
        'name'  => 'Facebook path',
        'id'    => $prefix . 'facebook',
        'type'  => 'text',
        'desc'   => "The path of this member’s FB account (the part that comes <em>after</em> http://facebook.com/)"
      ),
      array(
        'name'  => 'Google+ URL',
        'id'    => $prefix . 'googleplus',
        'type'  => 'text',
        'desc'   => "The URL of this member’s Google+ account, including http(s)://"
      ),
      array(
        'name'  => 'YouTube URL',
        'id'    => $prefix . 'youtube',
        'type'  => 'text',
        'desc'   => "The URL of this member’s YouTube account/channel, including http(s)://"
      ),

    )
  );
  new RW_Meta_Box( $meta_box ); //URLs
}
add_action( 'admin_init', 'inn_meta_boxes' );


/**
 * Geocode the coordinates for members when edited
 */
function inn_geocode_member( $post_id ) {
	// Don't do anything if we're not supposed to
	// Might use $post->post_type instead of $_POST?
	if ( 'inn_member' != get_post_type( $post_id ) || !current_user_can( 'edit_post', $post_id ) || !strlen(trim( $_POST['inn_address'] )) ) return;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

	// Get & format the entered address
	$post_address = urlencode( $_POST['inn_address'] );

	// Try to geocode it
	$ch = curl_init();
	$curl_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $post_address . "&sensor=false";
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $curl_url);
	$output = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($output);

	if ( $result->status == 'OK' ) {
		$coords = $result->results[0]->geometry->location->lat . "," . $result->results[0]->geometry->location->lng;
		update_post_meta( $post_id, 'inn_coords', $coords );
	} else {
		update_post_meta( $post_id, 'inn_coords', '' );
		//TO DO: display error message on geocode fail
		add_filter( 'redirect_post_location', 'inn_geocode_error' );
	}
}
add_action( 'save_post', 'inn_geocode_member' );


/**
 * Adds a query parameter to flag when an error message should display for failed geocode
 */
function inn_geocode_error( $loc ) {
	return add_query_arg( 'geocode', 'fail', $loc );
}


/**
 * If query parameter is present, display an admin notice
 */
function inn_display_errors() {
	if ( array_key_exists('geocode', $_GET) && $_GET['geocode'] == 'fail' ) : ?>
	<div class="error fade">
		<p><?php _e( 'Note: Could not geocode provided address.', 'inn' ); ?></p>
	</div>
	<?php endif;
}
add_action( 'admin_notices', 'inn_display_errors', 20 );


/**
 * Helper function for getting members list
 */
function get_members( $get_meta = TRUE ) {

  $mem = new WP_Query( array(
    'post_type' => 'inn_member',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page'=> -1
  ));

  $members = array();

  while( $mem->have_posts()) {
    $mem->next_post();
    $id = $mem->post->ID;
    if ( $get_meta ) {
	    $meta = get_post_custom( $id );
	    foreach ( $meta as $key => $value_arr ) {
		  	$mem->post->$key = $value_arr[0];
	    }
    }
    $mem->post->logo_id = get_post_thumbnail_id( $id );
    $members[ ] = $mem->post;
  }

  //give it up
  return $members;
}


/**
 * Widget listing members
 */
class members_widget extends WP_Widget {

  function __construct() {
    $options = get_option('members_options');
    $widget_ops = array( 'classname' => 'inn-members-widget', 'description' => 'A list of INN members, showing logo icons' );
    $control_ops = array( 'width' => 300, 'height' => 250, 'id_base' => 'members-widget' );
    $this->WP_Widget( 'members-widget', 'INN Member List', $widget_ops, $control_ops );
  }


  function widget($args, $instance) {
    extract($args);
    echo $before_widget;
		$menu = wp_nav_menu( array(
			'theme_location' => 'membership',
			'container' => false,
			'menu_class' => 'members-menu in-widget',
			'depth' => 1,
			'echo' => 0)
		);

    ?>
    <?php if (!empty($instance['title'])) echo $before_title . '<span>' . $instance['title'] . '</span>' . $menu . $after_title; ?>

    <div class="member-wrapper widget-content hidden-phone">
	    <ul class="members">
	    <?php
	      $counter = 1;
	      $member_list = get_members();
	      foreach ($member_list as $member) :
	      	if ( !$member->logo_id ) continue;	//skip members without logos
	      ?>
	        <li id="member-list-<?php echo $member->ID;?>" class="<?php echo $member->logo_id; ?>">
	        	<a href="<?php echo get_permalink($member->ID) ?>" class="member-thumb" title="<?php esc_attr_e($member->post_title) ?>">
	        		<?php echo wp_get_attachment_image(
	        			$member->logo_id,
	        			'member-thumbnail',
	        			0,
	        			array(
	        				'alt' => $member->post_title
	        			)
	        		); ?>
	        	</a>
	        </li>
	      <?php endforeach; ?>
	    </ul>
	    <div class="member-details-wrapper">
	    	<span class="close"><i class="icon-cancel"></i></span>
	    	<div class="member-details"></div>
	    </div>
    </div>
  <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    /* Strip tags (if needed) and update the widget settings. */
    $instance['title'] = strip_tags( $new_instance['title'] );
    return $instance;
  }

  function form($instance) { ?>
    <p>
     <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title"); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
    </p>
  <?php
  }
}


/**
 * Map on archive page
 */
function inn_member_map() {

	$members = get_members();
	$api_key = "AIzaSyD82h0mNBtvoOmhC3N4YZwqJ_xLkS8yTuw";
	?>
	<div id="map-container">
	</div>
	<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&sensor=false"></script>
	<script type="text/javascript">
		//convenience objects
		var $map = jQuery("#map-container"),
			gm = google.maps,
			infoWin = new gm.InfoWindow({ content: "default" }),
			markers = [];

		//new look!
		gm.visualRefresh = true;

		//create the map
		var gMap = new gm.Map(document.getElementById("map-container"), {
			center: new gm.LatLng(39.828328, -98.579416),
			zoom: 4,
			mapTypeId: google.maps.MapTypeId.TERRAIN
		});

		// Function for creating a marker on the map
		function createMarker( markerinfo ) {
			var marker = new gm.Marker({
				map: gMap,
				draggable: false,
				animation: gm.Animation.DROP,
				position: markerinfo.latLng,
				title: markerinfo.title
			});
			marker.data = markerinfo.d;

			//event listening
			gm.event.addListener(marker, 'click', function() {
				infoWin.setContent( marker.data );
				infoWin.open(gMap, marker);
			});

			//just making sure we have these?
			markers.push(marker);
		}

		// The array of places
		var marker_list = [
		<?php
		 foreach ( $members as $member ) :
		 	//skip members without coordinates
		 	if ( !isset($member->inn_coords) || empty($member->inn_coords)) continue;
		 	$info = sprintf('<div class="map-popup"><a href="%s" class="map-name">%s</a><br/><a href="%s" target="_blank">%s</a></div>',
		 		get_permalink($member->ID),
		 		htmlspecialchars($member->post_title, ENT_QUOTES),
		 		$member->inn_site_url,
		 		$member->inn_site_url
		 	);
			 ?>{
title: "X<?php echo htmlspecialchars($member->post_title, ENT_QUOTES); ?>",
latLng: new gm.LatLng(<?php echo $member->inn_coords ?>),
d: '<?php echo $info; ?>'
},<?php
		 endforeach;
		?>
		];

		//now load 'em up
		for (var i = 0; i < marker_list.length; i++) {
			(function(newmarker, idx) {
				setTimeout( function() {
					createMarker( newmarker );
				}, idx * 20 );
			})(marker_list[i], i);
		}

	</script>
	<?php
}


/**
 * Alphabetical links
 */
function inn_member_alpha_links() {

	global $wp;
	$core_url = "/" . preg_replace( '/page\/(\d+)/', '', $wp->request );

	//populate an array of potentially linked letters
	$links = array_merge( array("num"), range('A','Z'), array("All") );

	//populate an array of all the letters that have entries
	$members = get_members();
	$member_firsts = array('All');
	foreach ($members as $mem) {
		$first = strtoupper($mem->post_title[0]);
		if ( is_numeric($first) ) $first = "num";
		if ( !in_array($first, $member_firsts) ) $member_firsts[] = $first;
	}

	//Loop thru and display links as appropriate
	print '<div class="member-nav"><ul>';
	foreach( $links as $link ) {
		$class = ( $link == $_GET['letter'] ) ? 'class="current-letter"' : "" ;
		print "<li $class>";
		if ( in_array($link, $member_firsts) ) {
			$url = $core_url;
			if ( $link != "All" )	$url .= "?letter=" . $link;
			if ( $link == "num" ) $link = "0-9";
			printf('<a href="%s">%s</a>', $url, $link);
		} else {
			print $link;
		}
	}
	print "</ul></div>";
}


/**
 * Make WP_Query support title_starts_with
 */
add_filter( 'posts_where', 'inn_title_starts_with', 10, 2 );
function inn_title_starts_with( $where, &$wp_query ) {
  global $wpdb;
  //needs to handle digits, ugh
  if ( $title_starts_with = $wp_query->get( 'title_starts_with' ) ) {
  	if ( 'num' == $title_starts_with ) {
	  	$where .= ' AND ' . $wpdb->posts . '.post_title NOT REGEXP \'^[[:alpha:]]\'';
  	} else {
  		$where .= ' AND UPPER(' . $wpdb->posts . '.post_title) LIKE \'' . esc_sql( like_escape( $title_starts_with ) ) . '%\'';
		}
  }
  return $where;
}


/**
 * Modify queries to pass querystrings for member letters
 */
function inn_members_by_letter( $query ) {

  //get members by letter
  if ( $query->is_post_type_archive('inn_member') && $query->is_main_query() && isset($_GET['letter']) ) {
  	$query->set( 'title_starts_with', $_GET['letter'] );
  	$query->set( 'posts_per_page', -1 );
  }

	//order members by name
  if ( $query->is_post_type_archive('inn_member') && $query->is_main_query()  ) {
	  $query->set( 'orderby', 'title' );
	  $query->set( 'order', 'ASC' );
	  $query->set( 'posts_per_page', -1 );
  }

  //include network content on homepage-featured archive
  if( is_tax( 'prominence', 'homepage-featured' ) && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'network_content'
		));
	  return $query;
	}
}
add_action( 'pre_get_posts', 'inn_members_by_letter' );


/**
 * Kill redirect so pagination works for single members that have RSS items
 * See http://petetasker.wordpress.com/2012/05/18/wordpress-pagination-on-custom-posts/
 */
function inn_disable_member_redirect( $redirect_url ) {
	if (is_singular('inn_member')) $redirect_url = false;
	return $redirect_url;
}
add_filter('redirect_canonical', 'inn_disable_member_redirect');


/**
 * Load up the RSS handling
 */
require_once('inn_members_rss.php');