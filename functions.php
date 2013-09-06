<?php

//USEFUL CONSTANTS
define( 'INN_FUNDER_PAGE_ID', 2747 );
define( 'INN_ABOUT_PAGE_SLUG', 'about' );
define( 'INN_GUIDE_PARENT_ID', 2500 );


/**
 * Load typekit stylesheet stuff
 */
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/gzd1rgv.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


/**
 * Load custom JS
 */
function inn_enqueue() {
	if ( !is_admin() ) {
		wp_enqueue_script( 'inn-tools', get_stylesheet_directory_uri() . '/js/inn.js', array('jquery'), '1.0.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'inn_enqueue' );

/**
 * Membership stuff
 */
require_once( 'inn_members.php' );

/**
 * Membership stuff
 */
require_once( 'inn_resources.php' );


/**
 * Custom Widgets
 */
add_action('widgets_init', 'inn_widgets', 11);

function inn_widgets() {
  register_widget('members_widget');
  register_widget('resources_widget'); //for homepage
  register_widget('resource_widget'); //for category archives

	register_sidebar( array(
		'name' 			=> __( 'INN Homepage Bottom', 'inn' ),
		'description' 	=> __( 'A widget area at the bottom of the INN homepage', 'inn' ),
		'id' 			=> 'inn-home-bottom',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
		'class' => 'span12'
	) );

	register_sidebar( array(
		'name' 			=> __( 'Category Header', 'inn' ),
		'description' 	=> __( 'A widget area sandwiched between the title and list of items in a category archive', 'inn' ),
		'id' 			=> 'category-topper',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>'
	) );

	// get rid of the unused widget areas
	unregister_sidebar( 'homepage-bottom' );
	unregister_sidebar( 'header-ads' );
	unregister_sidebar( 'footer-2' );
	unregister_sidebar( 'footer-3' );

}


/**
 * Misc helper functions
 */

//make sure links always start with HTTP, users often forget this
function safe_url( $url ) {
	$url = trim( $url );
	if (strpos( $url, "http://" ) === 0 || strpos( $url, "https://" ) === 0) return $url;
	return "http://" . $url;
}

// Decide whether or not we're on a page that shoudl show the sponsors
function show_funders( $slug = NULL ) {

	global $post;
	$slug = ( $slug ) ? $slug : INN_ABOUT_PAGE_SLUG;

	$parent_page_id = 0;
	$about_page = 	get_page_by_path( $slug );
	if ( $about_page ) $parent_page_id = $about_page->ID;

	if ( is_front_page() || $post->ID == $parent_page_id || in_array( $parent_page_id, get_ancestors( $post->ID, 'page') ) ) {
		return true;
	}

	return false;
}


/**
 * Shorthand for querying posts from a custom taxonomy
 * Used in homepage templates and sidebar widgets
 *
 * @param array $args query args
 * @return array of featured post objects
 * @since 1.0
 */
function inn_get_featured_posts( $args = array() ) {
    $defaults = array(
    		'post_type' => array('post', 'network_content'),
        'showposts' => 3,
        'offset' 	=> 0,
        'orderby' 	=> 'date',
        'order' 	=> 'DESC',
        'tax_query' => array(
			array(
				'taxonomy' 	=> 'prominence',
				'field' 	=> 'slug',
				'terms' 	=> 'footer-featured'
			)
		),
        'ignore_sticky_posts' => 1,
    );
    $args = wp_parse_args( $args, $defaults );
    $featured_query = new WP_Query( $args );
    wp_reset_postdata();
    return $featured_query;
}

/**
 * INN homepage credits: use a Member name if present, otherwise fall back to Largo technique
 */
function inn_homepage_tag( $echo = false ) {
	global $post, $tags;

	//normal
	if ( $post->post_type != 'network_content' && largo_has_categories_or_tags() ) {
		return ( $echo ) ? largo_categories_and_tags(1) : true ;
	}

	//network content
	$mem_id = get_post_meta( $post->ID, 'from_member_id', TRUE );
	$mem = get_membername_from_post( $post->ID );
	if ( !$mem ) {
		return false;
	} else {
		return ( $echo ) ? printf('<a href="%s" title="Member page for %s">%s</a>', get_permalink($mem_id), esc_attr($mem), $mem) : true ;
	}
}

/**
 * Override largo_byline to handle network_content
 */
function largo_byline( $echo = true ) {
	global $post;
	$values = get_post_custom( $post->ID );
	$authors = ( function_exists( 'coauthors_posts_links' ) && !isset( $values['largo_byline_text'] ) ) ? coauthors_posts_links( null, null, null, null, false ) : largo_author_link( false );

	if ( strlen($authors) ) {
		if ( $post->post_type == 'network_content' ) {
			$output_format = '<span class="by-author"><span class="by">By:</span> <span class="author vcard">%1$s</span> <span class="member">(%4$s)</span></span><span class="sep"> | </span><time class="entry-date updated dtstamp pubdate" datetime="%2$s">%3$s</time>';
		} else {
			$output_format = '<span class="by-author"><span class="by">By:</span> <span class="author vcard">%1$s</span></span><span class="sep"> | </span><time class="entry-date updated dtstamp pubdate" datetime="%2$s">%3$s</time>';
		}
	} else {
		$output_format = '<span class="by-author"><span class="author vcard">%4$s</span></span><span class="sep"> | </span><time class="entry-date updated dtstamp pubdate" datetime="%2$s">%3$s</time>';
	}

	$output = sprintf( $output_format,
		$authors,
		esc_attr( get_the_date( 'c' ) ),
		largo_time( false ),
		get_membername_from_post()
	);

	if ( current_user_can( 'edit_post', $post->ID ) )
		$output .=  sprintf( ' | <span class="edit-link"><a href="%1$s">Edit This Post</a></span>', get_edit_post_link() );

 	if ( is_single() && of_get_option( 'clean_read' ) === 'byline' )
 		$output .=	__('<a href="#" class="clean-read">View as "Clean Read"</a>', 'largo');

	if ( $echo )
		echo $output;
	return $output;
}
function largo_google_analytics() {
		if ( !is_user_logged_in() ) : // don't track logged in users ?>
			<script>
			    var _gaq = _gaq || [];
			    var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
				_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
			<?php if ( of_get_option( 'ga_id', true ) ) : // make sure the ga_id setting is defined ?>
				_gaq.push(['_setAccount', '<?php echo of_get_option( "ga_id" ) ?>']);
				_gaq.push(['_trackPageview']);
			<?php endif; ?>
			    _gaq.push(
					["largo._setAccount", "UA-17578670-4"],
					["largo._setCustomVar", 1, "SiteName", "<?php bloginfo('name') ?>"],
					["largo._setDomainName", "<?php echo str_replace( 'http://' , '' , home_url()) ?>"],
					["largo._setAllowLinker", true],
					["largo._trackPageview"]
				);

			    (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>
	<?php endif;
}
