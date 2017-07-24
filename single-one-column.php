<?php
/**
 * Single Post Template: One Column (Standard Layout)
 * Template Name: One Column (Standard Layout)
 * Description: Shows the post but does not load any sidebars.
 */
add_filter( 'body_class', function( $classes ) {
	$classes[] = 'normal';
	return $classes;
} );

get_header();

$about_pg_id = INN_ABOUT_PAGE_ID;
$programs_pg_id = INN_PROGRAMS_PAGE_ID;
$members_pg_id = INN_MEMBERS_PAGE_ID;

//is this a page or a post in the projects post type
if ( is_page() || is_singular( 'pauinn_project' ) ) {
	get_template_part('partials/internal-subnav');
}

get_template_part('partials/internal-subnav-dropdown');

$content_class = apply_filters( 'inn_single_one_column_content_class', 'span12' );

if ( is_page( 'press' ) || is_page( 'news' ) ) $content_class .= ' stories';
?>


<div id="content" class="<?php echo $content_class; ?>" role="main">
	<?php
		while ( have_posts() ) : the_post();

			$partial = ( is_page() ) ? 'page' : 'single';

			if ( is_singular( 'pauinn_project' ) ) {

				get_template_part( 'partials/content', 'page' );

				get_template_part( 'partials/content', 'projects' );

			} else if ( $partial === 'single' ) {

				get_template_part( 'partials/content', $partial );

				if ( is_active_sidebar( 'article-bottom' ) ) {

					do_action( 'largo_before_post_bottom_widget_area' );

					echo '<div class="article-bottom">';
					dynamic_sidebar( 'article-bottom' );
					echo '</div>';

					do_action( 'largo_after_post_bottom_widget_area' );

				}

				do_action(' largo_before_comments' );

				do_action( 'largo_after_comments' );

			} else if ( is_page( 'press' ) ) {

				get_template_part( 'partials/content', 'press' );

			} else if ( is_page ( 'news' ) ) {

				get_template_part( 'partials/content', 'news' );

			} else  {

				get_template_part( 'partials/content', $partial );

			}

		endwhile;
	?>
</div>

<?php do_action( 'largo_after_content' ); ?>

<?php get_footer();
