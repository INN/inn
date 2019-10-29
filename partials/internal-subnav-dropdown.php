<?php
/**
 * The dropdown used on mobile for these pages
 */

// should we show a menu? let's find out.
$show_menu_title = '';
$show_menu = false;
$ancestors = get_post_ancestors( $post );

/*
 * @todo: rejigger this whole section with an array ( id => 'menu title', ... );
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

// yep, we should show a menu, modify the layout appropriately
if ( $show_menu === true ) {
	?>
	<div class="hidden-desktop visible-tablet internal-subnav-dropdown">
		<h3><a href="<?php echo get_permalink( $pg_id ); ?>"><?php echo $show_menu_title; ?></a></h3>
		<p class="choose-a-page">Choose a page...</p>
		<select class="internal-subnav">
			<?php
				if ( $show_menu_title === 'Projects' ) {
					// projects has its own special case
					// project pages show a list of projects, add the current_page_item class if necessary for consistency
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
				} else if ( ! empty ( $pg_id ) ) {
				// about and member pages and children get their respective page trees
					$show_pages = get_pages('child_of=' . $pg_id);

					foreach ($show_pages as $show_page) {
						if (is_page($show_page->ID))
							echo '<option selected data-href="' . get_permalink($show_page->ID) . '">' . $show_page->post_title . '</option>';
						else
							echo '<option data-href="' . get_permalink($show_page->ID) . '">' . $show_page->post_title . '</option>';
					}
				}
			?>
		</select>
	</div>

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
