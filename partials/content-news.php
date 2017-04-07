<h1 class="entry-title">Network News</h1>
<?php
	global $wp_query;
	$query_args = array(
		'posts_per_page' => 10,
		'post_status' => 'publish',
	);
	$wp_query = new WP_Query( $query_args );

	add_filter('largo_load_more_posts_json', function($LMP) use ($query_args) {
		$LMP['query'] = $query_args;
		return $LMP;
	});

	if ( $wp_query->have_posts() ) {
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			get_template_part( 'partials/content', 'home' );
		endwhile;
		largo_content_nav( 'nav-below' );
	}

	wp_reset_query();
?>

