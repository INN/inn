<?php

//USEFUL CONSTANTS
define( 'INN_MEMBER_TAXONOMY', 'ppu_focus_areas' );
define( 'INN_ABOUT_PAGE_ID', 2212 );
define( 'INN_PROGRAMS_PAGE_ID', 2587 );
define( 'INN_MEMBERS_PAGE_ID', 234260 );
define( 'SHOW_GLOBAL_NAV', false );

// Includes
$includes = array(
	'/inc/sidebars.php',
	'/homepages/homepage.php',
	'/inc/member-directory.php',
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


// Typekit
function inn_head() {
	?>
	<script src="//use.typekit.net/cui8tby.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
	<link rel="author" name="Institute for Nonprofit News" data-paypal="kevin.davis@investigativenewsnetwork.org">
	<?php
}
add_action( 'wp_head', 'inn_head' );


// Enable excerpts for pages
function inn_init() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'inn_init' );


/**
 * Load custom JS
 */
function inn_enqueue() {
	if ( ! is_admin() ) {
		wp_enqueue_script( 'inn-tools', get_stylesheet_directory_uri() . '/js/inn.js', array( 'jquery' ), '1.1', true );
	}

	wp_enqueue_style( 'largo-child-styles', get_stylesheet_directory_uri() . '/style.css', array('largo-stylesheet'), '20170707' );

	if ( is_archive( 'inn_member' ) ) {
		wp_add_inline_script( 'jquery-core', "
			jQuery(document).ready(function($){

				$('#member-category').on('change', updateDisplay );
				$('#member-state').on('change', updateDisplay );

				// trigger change on load
				$('#member-category').change();
				$('#member-state').change();

				function updateDisplay() {
					$( '#inn-members-no-results' ).addClass( 'hidden' );
					var catFilter = $('#member-category').val() ? '.' + $('#member-category').val() : '';
						stateFilter = $('#member-state').val() ? '.' + $('#member-state').val() : '';


					$( '.inn_member' ).not( catFilter + ' ' + stateFilter ).addClass( 'member-hide' );
					$( '.inn_member' + catFilter + stateFilter ).removeClass( 'member-hide' );

					if ( 0 == $( '.inn_member' + catFilter + stateFilter ).size() ) {
						$( '#inn-members-no-results' ).removeClass( 'hidden' );
					}
				}

				var states = [];

				// Build states array from member list
				$('.inn_member').each(function(){
					var state = this.getAttribute('data-state');
					states.push(state);
				});

				// Loop through options
				$('#member-state option').each(function(){

					// If the current item is not found in state array, hide it
					if ( $.inArray( $(this).val(), states ) == -1 ) {
						$(this).addClass( 'hidden' );
					}

				});
			});
		" );
	}
}
add_action( 'wp_enqueue_scripts', 'inn_enqueue' );

function inn_landing_page_enqueue() {
	if ( is_page( 'for-members' ) || is_page( 'for-funders' ) ) {
		wp_enqueue_style( 'landing', get_stylesheet_directory_uri() . '/css/landing.css', null, '1.0.0' );
	} elseif ( is_page( 'press' ) ) {
		wp_enqueue_style( 'press', get_stylesheet_directory_uri() . '/css/press.css', null, '1.0.0' );
	} elseif ( is_page( 'people' ) ) {
		wp_enqueue_style( 'people', get_stylesheet_directory_uri() . '/css/people.css', null, '1.0.0' );
	}
	wp_enqueue_style( 'members', get_stylesheet_directory_uri() . '/css/members.css', null, '1.2' );
}
add_action( 'wp_enqueue_scripts', 'inn_landing_page_enqueue', 200 );

/**
 * Track things
 */
function largo_google_analytics() {
		if ( ! is_user_logged_in() ) : // don't track logged in users ?>
			<script>
			    var _gaq = _gaq || [];
			    var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
				_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
			<?php if ( of_get_option( 'ga_id', true ) ) : // make sure the ga_id setting is defined ?>
				_gaq.push(['_setAccount', '<?php echo of_get_option( 'ga_id' ) ?>']);
				_gaq.push(['_trackPageview']);
			<?php endif; ?>
			    _gaq.push(
					["largo._setAccount", "UA-17578670-4"],
					["largo._setCustomVar", 1, "SiteName", "<?php bloginfo( 'name' ) ?>"],
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

/**
 * Add custom RSS feeds for member stories
 * Template used is feed-member-stories.php
 */
function add_query_vars_filter( $vars ) {
  $vars[] = 'top_stories';
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

function inn_member_stories_rss() {
	add_filter( 'pre_option_rss_use_excerpt', '__return_zero' );
	load_template( get_stylesheet_directory() . '/feed-member-stories.php' );
}
add_feed( 'member_stories', 'inn_member_stories_rss' );

/**
 * Allow SVGs as featured images
 */
function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

/**
 * Custom largoCore.js removes sticky nav functionality
 */
function inn_print_scripts() {
	wp_deregister_script( 'largoCore' );
	wp_enqueue_script(
		'largoCore',
		get_stylesheet_directory_uri() . '/js/largoCore.js',
		array( 'jquery' ), '1.0', true
	);
}
//add_action('wp_print_styles', 'inn_print_scripts', 100);

/**
 * Add alert banner to nav
 */
function inn_alert() {
	get_template_part( 'partials/alert' );
}
//add_action( 'largo_after_nav', 'inn_alert' );

/**
 * Add search box to main nav
 * uncomment this and remove partials/nav-main.php when 0.5.5 ships
 */
function inn_add_search_box() {
	get_template_part( 'partials/inn-nav-search-form' );
}
add_action( 'largo_after_main_nav_shelf', 'inn_add_search_box' );


function inn_member_archive_query( $query ) {
if ( $query->is_archive( 'inn_member') && $query->is_main_query() && ! is_admin() ) {
        $query->set( 'posts_per_page', 500 );
		$query->set( 'order', 'ASC' );
		$query->set( 'orderby', 'title' );
    }
}
add_action( 'pre_get_posts', 'inn_member_archive_query' );

/*
 * Add org name to list table view for network content
 */
function inn_post_list_table_org_column( $cols ) {

	$cols['org'] = __( 'Org Name' );

	return $cols;
}

function inn_post_list_table_org_value( $column_name, $post_id ) {

	if ( 'org' === $column_name ) {

		// thumbnail of WP 2.9
		$member = get_post_meta( $post_id, 'from_member_id', true );

		if ( isset( $member ) && $member ) {
			echo $member;
		} else {
			echo __( '' );
		}
	}
}

// for posts
add_filter( 'manage_network_content_posts_columns', 'inn_post_list_table_org_column' );
add_action( 'manage_network_content_posts_custom_column', 'inn_post_list_table_org_value', 10, 2 );

// WooCommerce overrides
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

add_filter( 'wc_product_sku_enabled', '__return_false' );

add_filter( 'woocommerce_product_tabs', 'inn_woo_remove_product_tabs', 98 );
function inn_woo_remove_product_tabs( $tabs ) {
//    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;

}

add_filter( 'woocommerce_checkout_login_message', 'inn_checkout_login_message' );
function inn_checkout_login_message( $message ) {
	return __( 'Have an account?', 'woocommerce' );
}
