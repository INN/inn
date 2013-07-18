<?php
/**
 * A template for displaying content on the "resources" widget
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
	<div>
		<header>
			<h5 class="top-tag">Featured</h5>

	 		<h2 class="entry-title">
	 			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
	 		</h2>

	 		<h5 class="byline"><?php largo_byline(); ?></h5>
		</header><!-- / entry header -->

		<div class="entry-content">
			<?php largo_excerpt( $post, 5, true ); ?>
		</div><!-- .entry-content -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->