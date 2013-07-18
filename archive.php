<?php
/**
 * Template for various archive pages (category, tag, term, date, etc.
 */
get_header();
$post_term = of_get_option( 'posts_term_plural', 'stories');
?>

<div id="content" class="stories span8" role="main">

		<?php if ( have_posts() ) { ?>

		<?php

			// queue up the first post so we know what type of archive page we're dealing with
			the_post();

			/*
			 * Display some different stuff in the header
			 * depending on what type of archive page we're looking at
			 */

			// if it's an author page, show the author box with their bio, social links, etc.

			if ( is_author() ) {
				get_template_part( 'largo-author-box' );

			// for categories, tags, and custom taxonomies we show the term name and description

			} elseif ( is_category() || is_tag() || is_tax() ) {
				if ( is_category() ) {
					$title = single_cat_title( '', false );
					$description = category_description();
				} elseif ( is_tag() ) {
					$title = single_tag_title( '', false );
					$description = tag_description();
				} elseif ( is_tax() ) {
					$title = single_term_title( '', false );
					$description = term_description();
				}
		?>
			<header class="archive-background clearfix">
				<?php
					if ( $title)
						echo '<h1 class="page-title">' . $title . '</h1>';
					if ( $description )
						echo '<div class="archive-description">' . $description . '</div>';

					// category pages show a list of related terms

					if ( is_category() && largo_get_related_topics_for_category( get_queried_object() ) != '<ul></ul>' ) { ?>
						<div class="related-topics clearfix">
							<h5><?php _e('Related Topics:', 'largo'); ?> </h5>
							<?php echo largo_get_related_topics_for_category( get_queried_object() ); ?>
						</div> <!-- /.related-topics -->
				<?php
					}

					if ( is_category() ) {
						echo '<div class="row-fluid clearfix category-topper">';
						dynamic_sidebar( 'category-topper' );
						echo '</div>';
					}

			// if it's a date archive we'll show the date dropdown in lieu of a description

			} elseif ( is_date() ) {
		?>
					<nav class="archive-dropdown">
						<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'><option value=""><?php _e('Select Month', 'largo'); ?></option>
						<?php wp_get_archives( array('type' => 'monthly', 'format' => 'option' ) ); ?>
						</select>
					</nav>
		<?php } elseif ( is_post_type_archive('network_content') ) { ?>
				<header class="archive-background clearfix">
					<h1 class="page-title">Stories From Our Members</h1>
					<div class="archive-description">
						<p>The members of the Investigative News Network are all 501(c)(3) nonprofit organizations committed to producing the highest quality journalism in the public interest. To receive regular updates from our members you can subscribe to a <a href="http://feeds.feedburner.com/INNMemberInvestigations">curated RSS feed of member investigations</a>, an <a href="http://feeds.feedburner.com/INNMemberStories">RSS feed of all member stories</a> or <a href="http://twitter.com/inn">follow @INN on Twitter</a>.</p>
					</div>
				</header>
		<?php } ?>
			</header>

			<h3 class="recent-posts clearfix">
				<?php

					/*
					 * Show a label for the list of recent posts
					 * again, tailored to the type of page we're looking at
					 */

					if ( is_author() ) {
						printf(__('Recent %s<a class="rss-link" href="%s"><i class="icon-rss"></i></a>', 'largo'), $post_term, get_author_feed_link( get_the_author_meta('ID') ) );
					} elseif ( is_category() ) {
						printf(__('Recent %s<a class="rss-link" href="%s"><i class="icon-rss"></i></a>', 'largo'), $post_term, get_category_feed_link( get_queried_object_id() ) );
					} elseif ( is_tag() ) {
						printf(__('Recent %s<a class="rss-link" href="%s"><i class="icon-rss"></i></a>', 'largo'), $post_term, get_tag_feed_link( get_queried_object_id() ) );
					} elseif ( is_tax() ) {
						$queried_object = get_queried_object();
						$term_id = intval( $queried_object->term_id );
						$tax = $queried_object->taxonomy;
						printf(__('Recent %s<a class="rss-link" href="%s"><i class="icon-rss"></i></a>', 'largo'), $post_term, get_term_feed_link( $term_id, $tax ) );
					} elseif ( is_month() ) {
						printf(__('Monthly Archives: <span>%s</span>', 'largo'), get_the_date('F Y') );
					} elseif ( is_year() ) {
						printf(__('Yearly Archives: <span>%s</span>', 'largo'), get_the_date('Y') );
					} elseif ( is_post_type_archive('network_content') ) {
						printf(__('Recent %s<a class="rss-link" href="http://feeds.feedburner.com/INNMemberStories"><i class="icon-rss"></i></a>', 'largo'), $post_term );
					} else {
						_e('Archives', 'largo');
					}
					?>
			</h3>

	<?php
			// and finally wind the posts back so we can go through the loop as usual

			rewind_posts();

			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'archive' );
			endwhile;

			largo_content_nav( 'nav-below' );
		} else {
			get_template_part( 'content', 'not-found' );
		}
	?>

</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>