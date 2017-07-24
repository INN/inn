<?php

$about_pg_id = INN_ABOUT_PAGE_ID;
$programs_pg_id = INN_PROGRAMS_PAGE_ID;
$members_pg_id = INN_MEMBERS_PAGE_ID;

//is this a page or a post in the projects post type
if ( is_page() || is_singular( 'pauinn_project' ) ) {
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
	if ( $show_menu != '' ) { ?>
		<div class="hidden-desktop internal-subnav-dropdown">
			<h3><a href="<?php echo get_permalink( $pg_id ); ?>"><?php echo $show_menu; ?></a></h3>
			<p class="choose-a-page">Choose a page...</p>
			<select class="internal-subnav">
		<?php
	}

	// about and member pages and children get their respective page trees
	if ( $show_menu == 'About INN' || $show_menu == 'Membership' ) {
		$pg_id = ( $show_menu == 'About' ) ? $about_pg_id : $members_pg_id;
		$show_pages = get_pages('child_of=' . $pg_id);

		foreach ($show_pages as $show_page) {
			if (is_page($show_page->ID))
				echo '<option selected data-href="' . get_permalink($show_page->ID) . '">' . $show_page->post_title . '</option>';
			else
				echo '<option data-href="' . get_permalink($show_page->ID) . '">' . $show_page->post_title . '</option>';
		}

	// project pages show a list of projects, add the current_page_item class if necessary for consistency
	} else if ( $show_menu == 'Projects' ) {
		$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false ) );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$term_link = '/project/' . $term->slug;
				if ( is_single( $term->name ) ) {
					echo '<option data-href="' . $term_link . '" selected class="current_page_item">' . $term->name . '</option>';
				} else {
					echo '<option data-href="' . $term_link . '">' . $term->name . '</option>';
				}
			}
		}
	}

	// close the menu div
	if ( $show_menu != '' ) {
		echo '</select></div>';

		?>
		<script type="text/javascript">
			(function() {
				var $ = jQuery;

				$(function() {
					$('select.internal-subnav').on('change', function(event) {
						var href = $(this).find(':selected').data('href');
						window.location.href = href;
						return false;
					});
				});
			})();
		</script>
		<?php
	}
}
