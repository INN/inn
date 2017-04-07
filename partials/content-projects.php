<?php
// get the equivalent term in the project taxonomy associated with this post
$queried_object = get_queried_object();
$term = get_term_by( 'slug', $queried_object->post_name, 'pauinn_project_tax' );
$term_id = $term->term_id;
?>

<!--
<div class="bottom row-fluid">
	<div class="span6">
		<h3>Latest News</h3>
		<?php
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 5,
				'tax_query' => array(
					array(
						'taxonomy' => 'pauinn_project_tax',
						'field'    => 'slug',
						'terms'    => $post->post_name,
					),
				),
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				echo '<ul>';
		        while ( $query->have_posts() ) : $query->the_post();
					echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
				endwhile;
				echo '</ul>';
			} else {
				echo '<ul><li>No news yet, check back soon!</li></ul>';
			}
		?>
	</div>
	<div class="span6 members">
		<h3>Participating Members</h3>
		<?php
			$search = array();
			$search['mod_results'] = false;
			$search['search'][] = array(
				'type' 			=> 'user',
				'field' 		=> 'pauinn_project_tax',
				'operator' 		=> 'is',
				'sub_operator' 	=> 'any',
				'query' 		=> $term_id
			);
			$query = paupress_filter_process( $search );

			if ( ! empty( $query['member_search'] ) ) {
				foreach ( $query['member_search'] as $user_id ) {
					echo '<a href="' . get_author_posts_url( $user_id ) . '">' . get_avatar( $user_id ) . '</a>';
				}
			} else {
				echo 'No members participating in this program yet.';
			}
		?>
	</div>
</div>
-->
