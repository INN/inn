<?php
/**
 * The dropdown used on mobile for these pages
 *
 * Depends on the following variables being populated:
 *
 * @param Bool $show_menu
 * @param String $show_menu_title the title of the menu
 * @param Array $ancestors An array of post IDs from nearest to farthest ancestor
 * @Param Int $pg_id The post ID of the menu parent
 */


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
