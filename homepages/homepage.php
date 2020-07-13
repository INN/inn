<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class INNHomepageLayout extends Homepage {
	var $name = 'INN Main Site Homepage Layout';
	var $description = 'Custom homepage layout for the main INN site.';

	function __construct($options=array()) {
		$defaults = array(
			'template' => get_stylesheet_directory() . '/homepages/templates/inn.php',
			'assets' => array(
				array(
					'inn',
					get_stylesheet_directory_uri() . '/homepages/assets/css/inn.css',
					array(),
					filemtime( get_stylesheet_directory() . '/homepages/assets/css/inn.css' ),
				),
			)
		);
	$options = array_merge($defaults, $options);
		$this->init();
		$this->load($options);
	}
}

function inn_custom_homepage_layouts() {
	$unregister = array(
		'HomepageBlog',
		'HomepageSingle',
		'HomepageSingleWithFeatured',
		'HomepageSingleWithSeriesStories',
		'TopStories',
		'Slider',
		'LegacyThreeColumn'
	);

	foreach ($unregister as $layout)
		unregister_homepage_layout($layout);

	register_homepage_layout('INNHomepageLayout');

}
add_action('init', 'inn_custom_homepage_layouts', 10);


function inn_get_testimonial() {

	$img_path = get_stylesheet_directory_uri() . '/homepages/assets/img/testimonials/';

	$data = array (
		array(
			'photo_url' => $img_path . 'lahood.jpg',
			'text' => '&ldquo;More than anything, INN has given us a sense that we are not toiling in isolation, that we are part of a larger community of nonprofit news organizations that are thriving through collaboration, pursuit of common goals and expression of mutual support.&rdquo;',
			'name' => 'Lila Lahood',
			'org' => 'San Francisco Public Press',
			'org_link' => 'http://sfpublicpress.org'
		),
		array(
			'photo_url' => $img_path . 'horvit.jpg',
			'text' => '&ldquo;INN provides support and business-model training for nonprofit news organizations, helping to create a solid foundation upon which they can build.&rdquo;',
			'name' => 'Mark Horvit',
			'org' => 'National Institute for Computer Assisted Reporting',
			'org_link' => 'http://nicar.org'
		),
		array(
			'photo_url' => $img_path . 'brown.jpg',
			'text' => '&ldquo;INN is the glue that connects the nonprofit news industry and pushes it to move forward, smarter and stronger.&rdquo;',
			'name' => 'Mary Brown',
			'org' => 'Voice of San Diego',
			'org_link' => 'http://voiceofsandiego.org'
		)
	);

	$num = mt_rand(0, count( $data ) - 1 );

	return $testimonial = $data[$num];
}

/**
 * WP Customizer functionality for managing the homepage featured image and other suchlike
 */
function inn_homepage_customize_image( $wp_customize ) {
	$wp_customize->remove_section( 'largo_homepage' );
	$wp_customize->add_section( 'inn_homepage', array(
		'title' => __( 'INN Homepage Featured Image', 'inn' ),
		'capability' => 'edit_theme_options',
		'description' => __( 'Options for the big image on the homepage, and its text', 'inn' ),
		'active_callback' => 'is_home',
	) );

	$wp_customize->add_setting( 'inn_homepage_image', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => '',
		'sanitize_js_callback' => '',
	) );
	// this saves a post ID
	$wp_customize->add_control( new WP_Customize_Media_control(
		$wp_customize,
		'inn_homepage_image',
		array(
			'label' => __( 'Featured Homepage Image', 'inn' ),
			'mime_type' => 'image',
			'section' => 'inn_homepage',
		)
	) );

	$wp_customize->add_setting( 'inn_homepage_headline', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
		'sanitize_js_callback' => '',
	) );
	$wp_customize->add_control(
		'inn_homepage_headline',
		array(
			'type' => 'text',
			'label' => __( 'Featured Headline', 'inn' ),
			'description' => __( 'This appears below the image, and above the blurb.', 'inn' ),
			'section' => 'inn_homepage',
		)
	);

	$wp_customize->add_setting( 'inn_homepage_blurb', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_textarea_field',
		'sanitize_js_callback' => '',
	) );
	$wp_customize->add_control(
		'inn_homepage_blurb',
		array(
			'label' => __( 'Featured Blurb', 'inn' ),
			'description' => __( 'This text appears beneath the headline, but above the button. To break a paragraph into two, use an empty line.', 'inn' ),
			'type' => 'textarea',
			'section' => 'inn_homepage',
		)
	);

	$wp_customize->add_setting( 'inn_homepage_button_text', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
		'sanitize_js_callback' => '',
	) );
	// this saves a post ID
	$wp_customize->add_control(
		'inn_homepage_button_text',
		array(
			'label' => __( 'Label for Featured Button', 'inn' ),
			'description' => __( 'This text is shown on the button, which appears below the blurb text.', 'inn' ),
			'section' => 'inn_homepage',
			'type' => 'text',
		)
	);

	$wp_customize->add_setting( 'inn_homepage_featured_link', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'esc_url_raw',
		'validate_callback' => 'inn_homepage_featured_link_validate',
		'sanitize_js_callback' => '',
	) );
	// this saves a post ID
	$wp_customize->add_control(
		'inn_homepage_featured_link',
		array(
			'label' => __( 'Featured Link', 'inn' ),
			'description' => __( 'This link applies to the homepage featured image, headline, blurb and button.', 'inn' ),
			'section' => 'inn_homepage',
			'type' => 'url',
		)
	);

	/*
	 * Newsletter section
	 */
	$wp_customize->add_section( 'inn_homepage_newsletter', array(
		'title' => __( 'INN Homepage Newsletter Section', 'inn' ),
		'capability' => 'edit_theme_options',
		'description' => __( 'Options for the Newsletter subscription area image on the homepage, and its text', 'inn' ),
		'active_callback' => 'is_home',
	) );

	// Newsletter section header
	$wp_customize->add_setting( 'inn_homepage_newsletter_headline', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
		'sanitize_js_callback' => '',
	) );
	// this saves a post ID
	$wp_customize->add_control(
		'inn_homepage_newsletter_headline',
		array(
			'label' => __( 'Label for Newsletter Section header', 'inn' ),
			'description' => __( 'This text is shown at the top of the newsletter section.', 'inn' ),
			'section' => 'inn_homepage_newsletter',
			'type' => 'text',
		)
	);

	// blurb text
	$wp_customize->add_setting( 'inn_homepage_newsletter_blurb', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_kses_post',
		'sanitize_js_callback' => '',
	) );
	$wp_customize->add_control(
		'inn_homepage_newsletter_blurb',
		array(
			'label' => __( 'Newsletter Section Blurb', 'inn' ),
			'description' => __( 'This text appears beneath the headline, but above the button. To break a paragraph into two, use an empty line.', 'inn' ),
			'type' => 'textarea',
			'section' => 'inn_homepage_newsletter',
		)
	);

	// button text
	$wp_customize->add_setting( 'inn_homepage_newsletter_button_text', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
		'sanitize_js_callback' => '',
	) );
	$wp_customize->add_control(
		'inn_homepage_newsletter_button_text',
		array(
			'label' => __( 'Label for Newsletter Button', 'inn' ),
			'description' => __( 'This text is shown on the button, which appears below the blurb text.', 'inn' ),
			'section' => 'inn_homepage_newsletter',
			'type' => 'text',
		)
	);

	// button linke
	$wp_customize->add_setting( 'inn_homepage_newsletter_button_link', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => null,
		'transport' => 'refresh',
		'sanitize_callback' => 'esc_url_raw',
		'validate_callback' => 'inn_homepage_featured_link_validate',
		'sanitize_js_callback' => '',
	) );
	$wp_customize->add_control(
		'inn_homepage_newsletter_button_link',
		array(
			'label' => __( 'Featured Link', 'inn' ),
			'description' => __( 'This link applies to the newsletter section button.', 'inn' ),
			'section' => 'inn_homepage_newsletter',
			'type' => 'url',
		)
	);


}
add_action( 'customize_register', 'inn_homepage_customize_image' );

/**
 * Validation callback for the inn_homepage_featured_link customizer option
 *
 * @link https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#validating-settings%c2%a0in-php
 * @see inn_homepage_customize_image
 */
function inn_homepage_featured_link_validate( $validity, $value ) {
	if ( empty( esc_url( $value ) ) || filter_var( $value, FILTER_VALIDATE_URL) === FALSE ) {
		$validity->add( 'required', __( 'You must supply a valid URL.', 'inn' ) );
	}
	return $validity;
}
