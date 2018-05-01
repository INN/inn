<?php
/**
 * File for the NonProfit Survey submissions customizations to the WooCommerce thing.
 *
 * "Endpoint" may be misleadingly named here - for most intents and purposes the
 * "endpoint" is the box at /my-account/ labeled "Nonprofit News Organization Survey"
 */

/**
 * Creates the Nonprofit Survey and the associated user interface items.
 */
class Nonprofit_Survey_Submissions_My_Account_Endpoint {

	/**
	 * Custom endpoint name.
	 *
	 * @var string
	 */
	public static $endpoint = 'nonprofit-survey-submissions';

	/**
	 * Plugin constructor, adding actions
	 *
	 * - Registers an "endpoint"
	 * - Filters query vars to do ???
	 * - Changes the "My Account" page title if the user has submitted stuff.
	 *
	 * This appears on the "My Account" page that's set in Dashboard > Accounts > My Account Page
	 *
	 * @return null
	 */
	public function __construct() {
		// Actions used to insert a new endpoint in the WordPress REST API.
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

		if ( $this->get_most_recent_user_form_submissions( 7 ) ) {

			// Change the My Account page title in the endpoint
			add_filter( 'the_title', array( $this, 'endpoint_title' ) );

			// Insering your new tab/page into the My Account page.
			add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
			add_action( 'woocommerce_account_' . self::$endpoint .  '_endpoint', array( $this, 'endpoint_content' ) );
		}
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
	 */
	public function add_endpoints() {
		add_rewrite_endpoint( self::$endpoint, EP_ROOT | EP_PAGES );
	}

	/**
	 * Add new query var.
	 *
	 * @param array $vars
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = self::$endpoint;

		return $vars;
	}

	/**
	 * Set survey box title to "Nonprofit News Organization Survey" via filter
	 *
	 * @param string $title
	 * @return string
	 */
	public function endpoint_title( $title ) {
		global $wp_query;

		$is_endpoint = isset( $wp_query->query_vars[ self::$endpoint ] );

		if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
			// New page title.
			$title = esc_html__( 'Nonprofit News Organization Survey', 'inn' );

			remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
		}

		return $title;
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 *
	 * @param array $items
	 * @return array
	 */
	public function new_menu_items( $items ) {
		// Remove the logout menu item: we'll re-add it later to make sure it's at the end.
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		// Insert your custom endpoint.
		$items[ self::$endpoint ] = __( 'Survey Submissions', 'woocommerce' );

		// Insert back the logout item.
		$items['customer-logout'] = $logout;

		return $items;
	}

	/**
	 * Get most recent user form submission for a given form
	 *
	 * @param Int The form ID the entries of which we desire.
	 * @return Array|Boolean Either an array of entries or False.
	 */
	public function get_most_recent_user_form_submissions( $form_id ) {
		$current_user = wp_get_current_user();
		$search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
		$sorting = array( 'key' => 'id', 'direction' => 'ASC', 'is_numeric' => true );
		$paging = array( 'offset' => 0, 'page_size' => 1 );
		$entries = GFAPI::get_entries( $form_id, $search_criteria, $sorting, $paging );
		if ( is_array( $entries ) & count( $entries ) > 0 ) {
			return $entries;
		} else {
			return false;
		}
	}

	/**
	 * Endpoint HTML content.
	 * @uses get_most_recent_user_form_submissions
	 * @returns null
	 * @echo HTML
	 */
	public function endpoint_content() {
		$entries = $this->get_most_recent_user_form_submissions( 7 );
		$entry = GFAPI::get_entry( $entries[0]['id'] );
		$form = GFAPI::get_form( 7 );

		// If there are form fields.
		if ( count( $form['fields'] ) > 0 ) {

			echo '<h2>Your Submitted Information:<h2>';
			echo '<hr />';

			// Print data for each form field.
			foreach ( $form['fields'] as $field ) {
				if ( 'html' === $field->type ) {
					echo '<h5>' . $field->content . '</h5>';
				} else {
					echo '<h5>' . $field->label . '</h5>';
					echo '<p>' . $entry[ $field->id ] . '</p>';
				}
			}
		}
	}

	/**
	 * Plugin install action.
	 * Flush rewrite rules to make our custom endpoint available.
	 */
	public static function install() {
		flush_rewrite_rules();
	}
}

if ( class_exists( 'GFAPI' ) ) {
	add_action( 'after_setup_theme', function() {
		$Nonprofit_Survey_Submissions_My_Account_Endpoint = new Nonprofit_Survey_Submissions_My_Account_Endpoint();
	});
}

// Flush rewrite rules on plugin activation.
register_activation_hook( __FILE__, array( 'Nonprofit_Survey_Submissions_My_Account_Endpoint', 'install' ) );
