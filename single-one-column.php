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

/*
 * @todo: rejigger this whole section with an array ( id => 'menu title', ... );
 */

$content_class = 'span12';

// is this a page or a post in the projects post type?
if ( is_page() || is_singular( 'pauinn_project' ) ) {

	// should we show a menu? let's find out.
	$show_menu = '';
	$ancestors = get_post_ancestors( $post );

	/*
	 * Check whether this post needs a side menu
	 */
	// bascially all child pages of the about or members pages + all the posts in the projects post type get the side menu
	if ( is_page( INN_ABOUT_PAGE_ID ) || in_array( INN_ABOUT_PAGE_ID , $ancestors) ) {
		$show_menu_title = 'About';
		$show_menu = true;
		$pg_id = INN_ABOUT_PAGE_ID;
	}
	if ( is_page( INN_MEMBERS_PAGE_ID ) || in_array( INN_MEMBERS_PAGE_ID , $ancestors) ) {
		$show_menu_title = 'Membership';
		$show_menu = true;
		$pg_id = INN_MEMBERS_PAGE_ID;
	}
	if ( is_singular( 'pauinn_project' ) || is_page( INN_PROGRAMS_PAGE_ID ) ) {
		$show_menu_title = 'Projects';
		$show_menu = true;
		$pg_id = INN_PROGRAMS_PAGE_ID;
	}
	if ( is_page( INN_SERVICES_PAGE_ID ) || in_array( INN_SERVICES_PAGE_ID , $ancestors ) ) {
		$show_menu_title = 'Services';
		$show_menu = true;
		$pg_id = INN_SERVICES_PAGE_ID;
	} 

	// check if this post or its parents are in the array of post IDs INN_PARENT_PAGE_IDS
	$intersection = array_intersect( $ancestors, INN_PARENT_PAGE_IDS );
	if ( ! empty( $intersection ) ) {
		// one or more of the post's parents are in the array
		$show_menu_title = "The Best of Nonprofit News";
		$show_menu = true;
		$pg_id = end( $ancestors );
	}
	if ( in_array( get_the_ID(), INN_PARENT_PAGE_IDS ) ) {
		// the post itself is in the array
		$show_menu_title = "The Best of Nonprofit News";
		$show_menu = true;
		$pg_id = get_the_ID();
	}

	/*
	 * determine whether to show the side menu, and display it
	 */
	if ( ! empty( $show_menu ) ) {
		// yep, we should show a menu, modify the layout appropriately.
		$content_class = 'span9 has-menu';
		echo '<div class="internal-subnav span3 visible-desktop">';

		if ( $show_menu_title === 'Projects' ) {
			// project pages show a list of projects,
			// and add the current_page_item class if necessary for consistency.
			echo '<h3>Projects</h3>';
			$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false ) );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				echo '<ul>';
				foreach ( $terms as $term ) {
					$term_link = '/project/' . $term->slug . '/';
					if ( is_single( $term->name ) ) {
						echo '<li class="current_page_item"><a href="' . esc_attr( $term_link ) . '">' . wp_kses_post( $term->name ) . '</a></li>';
					} else {
						echo '<li><a href="' . esc_attr( $term_link ) . '">' . wp_kses_post( $term->name ) . '</a></li>';
					}
				}
				echo '</ul>';
			}
		} else if ( ! empty( $pg_id ) ) {
			printf(
				'<h3><a href="%1$s">%2$s</a></h3>',
				get_permalink( $pg_id ),
				wp_kses_post( __( $show_menu_title, 'inn' ) )
			);
			echo '<ul>';
				wp_list_pages( 'title_li=&child_of=' . $pg_id . '&echo=1' );
			echo '</ul>';
		}

		// close the menu div.
		echo '</div>';

		// load the mobile-nav implementation of the above item: a <select> with <options>
		largo_render_template(
			'partials/internal',
			'subnav-dropdown',
			array( 
				'ancestors' => $ancestors,
				'show_menu_title' => $show_menu_title,
				'show_menu' => $show_menu,
				'pg_id' => $pg_id,
			),
		);
	}
}


if ( is_page( 'press' ) || is_page( 'news' ) ) {
	$content_class .= ' stories';
}
?>


<div id="content" class="<?php echo esc_attr( $content_class ); ?>" role="main">
	<?php
		while ( have_posts() ) : the_post();

			$partial = ( is_page() ) ? 'page' : 'single';

			if ( is_singular( 'pauinn_project' ) ) {

				get_template_part( 'partials/content', 'page' );

				get_template_part( 'partials/content', 'projects' );

			} elseif ( 'single' === $partial ) {

				get_template_part( 'partials/content', $partial );

				if ( is_active_sidebar( 'article-bottom' ) ) {

					do_action( 'largo_before_post_bottom_widget_area' );

					echo '<div class="article-bottom">';
					dynamic_sidebar( 'article-bottom' );
					echo '</div>';

					do_action( 'largo_after_post_bottom_widget_area' );

				}

				do_action( 'largo_before_comments' );

				do_action( 'largo_after_comments' );

			} elseif ( is_page( 'press' ) ) {

				get_template_part( 'partials/content', 'press' );

			} elseif ( is_page( 'news' ) ) {

				get_template_part( 'partials/content', 'news' );

			} else {

				get_template_part( 'partials/content', $partial );

			}

		endwhile;
	?>
</div>

<?php do_action( 'largo_after_content' ); ?>

<?php
	get_footer();
