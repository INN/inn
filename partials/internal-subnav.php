<?php
/**
 * non-dropdown for internal sub navigation of pages
 */

$about_pg_id = INN_ABOUT_PAGE_ID;
$programs_pg_id = INN_PROGRAMS_PAGE_ID;
$members_pg_id = INN_MEMBERS_PAGE_ID;
$content_class = 'span12';

// should we show a menu? let's find out.
$show_menu = '';
$ancestors = get_post_ancestors( $post );

// bascially all child pages of the about or members pages + all the posts in the projects post type get the side menu
if ( is_page( $about_pg_id ) || in_array( $about_pg_id , $ancestors) )
	$show_menu = 'About INN';
if ( is_page( $members_pg_id ) || in_array( $members_pg_id , $ancestors) )
	$show_menu = 'Membership';
if ( is_singular( 'pauinn_project' ) || is_page( $programs_pg_id ) )
	$show_menu = 'Projects';

// yep, we should show a menu, modify the layout appropriately
if ( $show_menu != '' ) {
	add_filter( 'inn_single_one_column_content_class', function( $var ) {
		return 'span9 has-menu';
	});
	echo '<div class="internal-subnav span3 visible-desktop">';
}

// about and member pages and children get their respective page trees
if ( $show_menu == 'About INN' || $show_menu == 'Membership' ) {
	$pg_id = ( $show_menu == 'About' ) ? $about_pg_id : $members_pg_id;
	echo '<h3><a href="' . get_permalink( $pg_id ) . '">' . $show_menu . '</a></h3>';
	echo '<ul>';
		wp_list_pages('title_li=&child_of=' . $pg_id . '&echo=1');
	echo '</ul>';

// project pages show a list of projects, add the current_page_item class if necessary for consistency
} else if ( $show_menu == 'Projects' ) {
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
