<?php
/**
 * Template Name: Guides
 */
get_header();
?>

<div id="content" class="span12" role="main">
	<?php
		while ( have_posts() ) : the_post();

			// get the ID of the main page for a given guide
			$this_page_id = $post->ID;
			$ancestors = get_post_ancestors( $this_page_id );

			if ( count($ancestors) === 1 ) {
				// this is the main page of the guide so we can just list all of it's children
				$top_page = TRUE;
				$children = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0');
			} else {
				// it's not and we need to do get the full page tree
				end($ancestors); // the topmost parent is actually the "guides" page so let's back it up one
				$guide_parent = prev($ancestors); // much better

				// now get the complete tree of child pages for the guide's top page
				$children = wp_list_pages("title_li=&child_of=".$guide_parent."&echo=0");
			} ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

				<nav class="guide-nav span3">
						<?php if ( $top_page ) { ?>
							<h4>In This Guide</h4>
						<?php } else { ?>
							<h4 class="guide-top"><a href="<?php echo get_permalink($guide_parent); ?>"><?php echo get_the_title($guide_parent); ?></a></h4>
						<?php } ?>
						<ul>
							<?php echo $children; ?>
						</ul>
				</nav>
				<header class="entry-header span9">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php edit_post_link(__('Edit This Page', 'largo'), '<h5 class="byline"><span class="edit-link">', '</span></h5>'); ?>
				</header><!-- .entry-header -->

				<div class="entry-content span9">
					<?php the_content(); ?>

					<nav id="nav-below" class="pager post-nav clearfix">
						<?php
							$pagelist = get_pages('sort_column=menu_order&sort_order=asc&child_of='.$guide_parent);
							$pages = array();
							foreach ($pagelist as $page) {
							   $pages[] += $page->ID;
							}

							$current = array_search(get_the_ID(), $pages);
							$prevID = $pages[$current-1];
							$nextID = $pages[$current+1];

						if ( $top_page ) {
							printf( '<div class="next"><a href="%1$s"><h5>Start Reading &rarr;</h5></a></div>',
								get_permalink( $nextID ),
								get_the_title( $nextID )
							);
						} else {
							if (!empty($prevID)) {
								printf( '<div class="previous"><a href="%1$s"><h5>Previous Section</h5><span class="meta-nav">%2$s</span></a></div>',
									get_permalink( $prevID ),
									get_the_title($prevID)
								);
							}

							if (!empty($nextID)) {
								printf( '<div class="next"><a href="%1$s"><h5>Next Section</h5><span class="meta-nav">%2$s</span></a></div>',
									get_permalink( $nextID ),
									get_the_title( $nextID )
								);
							}
						}
						?>

					</nav><!-- #nav-below -->
				</div><!-- .entry-content -->

			</article><!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; // end of the loop.
	?>
</div><!--#content-->

<?php get_footer(); ?>