<?php
/**
 * Template Name: Full Width Page Plus Tabs
 * Single Post Template: Full-width (no sidebar)
 * Description: Shows the post but does not load any sidebars, allowing content to span full container width. Also loads in the Tabby CSS to create a tab structure, a la the INN Days page.
 *
 * @package Largo
 * @since 0.1
 */
get_header();
?>

<div id="content" class="span12" role="main">
	<?php
		while ( have_posts() ) : the_post();
			if ( is_page() ) {
				get_template_part( 'partials/content', 'page' );
			} else {
				get_template_part( 'partials/content', 'single' );
				comments_template( '', true );
			}
		endwhile; // end of the loop.
	?>
</div><!--#content-->

<?php get_footer();
