<section id="programs" class="normal">
	<div class="content">
		<h3><span>What We Offer</span></h3>
		<?php
			$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false, 'include' => array( '2344', '2346', '2418', '2325', '2328', '226', '2424', '2326' ) ) );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

				echo '<div class="row-fluid">';

				$count = 1;

				foreach ( $terms as $term ) {

					$post = get_posts(array(
						'name' => $term->slug,
						'posts_per_page' => 1,
						'post_type' => 'pauinn_project',
						'post_status' => 'publish'
					));
					?>

					<div class="span4">
						<?php echo '<a class="program" href="' . get_permalink( $post[0]->ID ) . '">' . get_the_post_thumbnail( $post[0]->ID ) . '</a>'; ?>
						<?php echo '<h5><a href="' . get_permalink( $post[0]->ID ) . '">' .  get_the_title( $post[0]->ID ) . '</a></h5>'; ?>
						<?php echo '<p>' . $post[0]->post_excerpt . '</p>'; ?>
					</div>

					<?php
					if ( $count % 3 == 0 ) {
						echo '</div><div class="row-fluid">';
					}
					$count++;
				}

				echo '</div>';
			}
		?>
	</div>
</section>
