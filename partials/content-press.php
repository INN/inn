<?php
	get_template_part( 'partials/content', 'page' );

	if ( ! class_exists( 'LinkRoundups' ) ) {
		return;
	}

	global $wp_query;
	// argo links
	$query_args = array (
		'posts_per_page' => 10,
		'post_type' => 'rounduplink',
		'post_status' => 'publish'
	);
	$wp_query = new WP_Query( $query_args );

	add_filter('largo_load_more_posts_json', function($LMP) use ($query_args) {
		$LMP['query'] = $query_args;
		return $LMP;
	});

	if ( $wp_query->have_posts() ) {
		echo '<h3>INN in the Press</h3>';
		echo '<!-- Powered by Link Roundups https://wordpress.org/plugins/link-roundups/ and https://github.com/INN/inn/blob/master/partials/content-press.php -->';

		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			get_template_part( 'partials/content', 'argolinks' );
		endwhile;
		largo_content_nav( 'nav-below' );
	}

	wp_reset_query();
?>
