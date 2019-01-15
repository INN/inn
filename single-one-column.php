<?php
/**
 * Single Post Template: One Column (Standard Layout)
 * Template Name: One Column (Standard Layout)
 * Description: Shows the post but does not load any sidebars.
 *
 * Contains a large chunk of conditional logic that may load
 * any of the following partials:
 * - partials/content-page
 * - partials/content-projects
 * - partials/content-single
 * - partials/content-press
 * - partials/content-news
 * - partials/content.php
 */
add_filter( 'body_class', function( $classes ) {
	$classes[] = 'normal';
	return $classes;
} );

get_header();

$about_pg_id = INN_ABOUT_PAGE_ID;
$programs_pg_id = INN_PROGRAMS_PAGE_ID;
$members_pg_id = INN_MEMBERS_PAGE_ID;
$services_pg_id = INN_SERVICES_PAGE_ID;
$content_class = 'span12';

//is this a page or a post in the projects post type
if ( is_page() || is_singular( 'pauinn_project' ) ) {

	// should we show a menu? let's find out.
	$show_menu = '';
	$ancestors = get_post_ancestors( $post );

	// bascially all child pages of the about, members or services pages + all the posts in the projects post type get the side menu
	if ( is_page( $about_pg_id ) || in_array( $about_pg_id , $ancestors) ) {
		$show_menu = 'About';
	}
	if ( is_page( $members_pg_id ) || in_array( $members_pg_id , $ancestors) ) {
		$show_menu = 'Membership';
	}
	if ( is_singular( 'pauinn_project' ) || is_page( $programs_pg_id ) ) {
		$show_menu = 'Projects';
	}
	if ( is_page( $services_pg_id ) || in_array( $services_pg_id , $ancestors) ) {
		$show_menu = 'Services';
	}

	// yep, we should show a menu, modify the layout appropriately
	if ( $show_menu != '' ) {
		$content_class = 'span9 has-menu';
		echo '<div class="internal-subnav span3 visible-desktop">';
	}

	// about, member and services pages and children get their respective page trees
	switch ( $show_menu) {
		case "About":
			$pg_id = $about_pg_id;
			break;
		case "Membership":
			$pg_id = $members_pg_id;
			break;
		case "Services":
			$pg_id = $services_pg_id;
			break;
	}

	if ( ! empty ($pg_id)) {
		echo '<h3><a href="' . get_permalink( $pg_id ) . '">' . $show_menu . '</a></h3>';
		echo '<ul>';
			wp_list_pages('title_li=&child_of=' . $pg_id . '&echo=1');
		echo '</ul>';
	} else if ( $show_menu == 'Projects' ) {
		// project pages show a list of projects, and
		// add the current_page_item class if necessary for consistency
		echo '<h3>Projects</h3>';
		$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false ) );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			echo '<ul>';
			foreach ( $terms as $term ) {
				$term_link = '/project/' . $term->slug . '/';
				if ( is_single( $term->name ) ) {
					echo '<li class="current_page_item"><a href="' . $term_link . '">' . $term->name . '</a></li>';
				} else {
					echo '<li><a href="' . $term_link . '">' . $term->name . '</a></li>';
				}
			}
			echo '</ul>';
		}
	}

	// close the menu div
	if ( $show_menu != '' ) {
		echo '</div>';
	}
}

get_template_part('partials/internal-subnav-dropdown');

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
