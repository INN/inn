<?php

//USEFUL CONSTANTS
define( 'INN_MEMBER_TAXONOMY', 'ppu_focus_areas' );
define( 'INN_ABOUT_PAGE_ID', 2212 );
define( 'INN_PROGRAMS_PAGE_ID', 2587 );
define( 'INN_MEMBERS_PAGE_ID', 234260 );
define( 'INN_SERVICES_PAGE_ID', 654834);
define( 'INN_PARENT_PAGE_IDS', array( 778595 ) );
define( 'SHOW_GLOBAL_NAV', true );

// Includes
$includes = array(
	'/inc/sidebars.php',
	'/homepages/homepage.php',
	'/inc/member-directory.php',
	'/inc/woocommerce/survey-tab.php',
	'/inc/woocommerce.php',
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

remove_action('wp_print_styles', 'cc_tabby_css', 30);


/**
 * Load custom JS
 */
function inn_enqueue() {
	if ( ! is_admin() ) {
		wp_enqueue_script( 'inn-tools', get_stylesheet_directory_uri() . '/js/inn.js', array( 'jquery' ), '1.1', true );
	}

	wp_enqueue_style(
		'largo-child-styles',
		get_stylesheet_directory_uri() . '/css/style.css',
		array('largo-stylesheet'),
		filemtime( get_stylesheet_directory() . '/css/style.css' )
	);

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
	} elseif ( is_page_template( 'full-page-tabby.php' ) ) {
		wp_enqueue_style( 'tabby', get_stylesheet_directory_uri() . '/css/tabby.css', null, '1.0.0' );
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
// function inn_alert() {
// 	get_template_part( 'partials/alert' );
// }
// add_action( 'largo_after_nav', 'inn_alert' );

/**
 * Add search box to main nav
 */
// function inn_add_search_box() {
// 	if ( ! is_search() ) {
// 		get_template_part( 'partials/inn-nav-search-form' );
// 	}
// }
// add_action( 'largo_after_main_nav_shelf', 'inn_add_search_box' );


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

function inn_woocommerce_terms_replace_permalink( $id ) {
	$current_page_id = get_the_ID();

	$term_page_overrides = array(
		'481768' => array(
			'label' => 'DonorSearch Annual Subscription',
			'product_id' => '481768',
			'terms_id' => 490827,
		),
	);

	if ( ! is_string( $current_page_id ) && ! is_integer( $current_page_id ) ) {
		return $id;
	}

	if ( array_key_exists( $current_page_id, $term_page_overrides ) ) {
		return $term_page_overrides[ $current_page_id ]['terms_id'];
	}

	return $id;
}
add_filter( 'woocommerce_get_terms_page_id', 'inn_woocommerce_terms_replace_permalink' );

/**
 * Item for the WooCommerce Account Dashboard to display the news org survey prompt
 *
 * This appears on the "My Account" page that's set in Dashboard > Accounts > My Account Page
 *
 * @echo HTML
 */
function inn_woocommerce_dashboard() {
	$user = wp_get_current_user();
	$form = GFAPI::get_form( 7 );

	if ( $form['scheduleForm'] ) {
		if ( strtotime( $form['scheduleStart'] ) > time() || strtotime( $form['scheduleEnd'] < time() ) ) {
			return;
		}
	}

	if ( is_super_admin( $user->ID ) || in_array( 'inn_member_survey', $user->roles ) ) {
		echo '<div id="nonprofit-news-organization-survey">';
			echo '<h4>The INN Index</h4>';
			echo sprintf(
				'<p>Fill out the <a href="%s">Nonprofit News Organization Survey</a> by %s to participate.</p>',
				get_permalink( 486132 ),
				date( 'F j, Y', strtotime( $form['scheduleEnd'] ) )
			);
			echo sprintf(
				'<a class="btn btn-primary" href="%s">%s</a>',
				get_permalink( 486132 ),
				'Get Started'
			);
			/*
			 * Nonprofit_Survey_Submissions_My_Account_Endpoint is set up in inc/woocommerce/survey-tab.php instead of here
			 * this section is commented out because we don't have any display logic for form submissions, yet.
			$survey_class = new Nonprofit_Survey_Submissions_My_Account_Endpoint();
			$submissions = $survey_class->get_most_recent_user_form_submissions( 7 );
			*/
		echo '</div>';
	}
}
if ( class_exists( 'GFAPI' ) && class_exists( 'Nonprofit_Survey_Submissions_My_Account_Endpoint' ) ) {
	add_action( 'woocommerce_account_dashboard', 'inn_woocommerce_dashboard' );
}

function inn_member_survey_body_class( $classes ) {
	$user = wp_get_current_user();
	if ( is_super_admin( $user->ID ) || in_array( 'inn_member_survey', $user->roles ) ) {
		$classes[] = 'inn_member_survey';
	}
	return $classes;
}
add_filter( 'body_class', 'inn_member_survey_body_class' );
