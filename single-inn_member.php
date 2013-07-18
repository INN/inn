<?php
/**
 * The Template for displaying all single inn_members
 */
get_header();
?>

<div id="content" class="span8" role="main">
	<?php

		//load the member info and display it
		global $post;

		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'member' );
			$rss_url = get_post_meta( $post->ID, 'inn_rss', TRUE );
			$post_id = $post->ID;
		endwhile;

		//load up recent stories
		if ($rss_url) :
			$oldpost = $post;
			//get our posts
			$wp_query->query( array(
				'post_type' => 'network_content',
				'posts_per_page' => 5,
				'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
				'suppress_filters' => false,
				'meta_key' => 'from_member_id',
				'meta_value' => $post_id
				)
			);

			if ( have_posts() ) : ?>
				<div class="recent-posts-wrapper stories">
					<h3 class="recent-posts clearfix">
					<?php
						printf(__('Recent %s<a class="rss-link" href="%s"><i class="icon-rss"></i></a>', 'largo'),
							of_get_option('posts_term_plural', 'stories'),
							$rss_url
						);
					?>
				</h3>
				<?php

				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'archive' );
				endwhile;

				largo_content_nav( 'nav-below' );
				echo '</div>';
			endif;	//have_posts

			//put everything back
			$post = $oldpost;
			setup_postdata( $post );
		endif; //rss_url ?>

</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>