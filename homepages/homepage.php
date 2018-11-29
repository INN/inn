<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class INNHomepageLayout extends Homepage {
  var $name = 'INN Main Site Homepage Layout';
  var $description = 'Custom homepage layout for the main INN site.';

  function __construct($options=array()) {
    $defaults = array(
      'template' => get_stylesheet_directory() . '/homepages/templates/inn.php',
      'assets' => array(
	  		array('inn', get_stylesheet_directory_uri() . '/homepages/assets/css/inn.css', array(), '20180816'),
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
