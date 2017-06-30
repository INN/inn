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

$member = get_queried_object();
$meta = get_post_meta( $post->ID );
?>

<div id="content" class="row-fluid" role="main">
	<a class="back-link" href="/members/">&larr; Back to Member Directory</a>
	<article id="author-<?php echo $post->ID; ?>" class="inn_member clearfix">
		<div class="span3">
			<?php
				the_post_thumbnail( 'thumbnail' );

				if ( ! empty( $meta['_year_founded'][0] ) ) {
					echo '<p class="founded">Founded ' . $meta['_year_founded'][0] . '</p>';
				}
				if ( ! empty( $meta['_inn_join_year'][0] ) ) {
					echo '<p class="member-since">INN member since ' . $meta["_inn_join_year"][0] . '</p>';
				}
			?>

			<ul class="social">
				<?php
					if ( ! empty( $meta['_facebook_url'][0] ) ) {
						$url = esc_url_raw( $meta['_facebook_url'][0] );
						echo '<li><a href="' . $url . '" target="_blank"><i class="icon-facebook"></i></a></li>';
					}
					if ( ! empty( $meta['_twitter_url'][0] ) ) {
						$url = esc_url_raw( $meta['_twitter_url'][0] );
						echo '<li><a href="' . $url . '" target="_blank"><i class="icon-twitter"></i></a></li>';
					}
					if ( ! empty( $meta['_youtube_url'][0] ) ) {
						$url = esc_url_raw( $meta['_youtube_url'][0] );
						echo '<li><a href="' . $url . '" target="_blank"><i class="icon-youtube"></i></a></li>';
					}
					if ( ! empty( $meta['_google_plus_url'][0] ) ) {
						$url = esc_url_raw( $meta['_google_plus_url'][0] );
						echo '<li><a href="' . $url . '" target="_blank"><i class="icon-googleplus"></i></a></li>';
					}
					if ( ! empty( $meta['_rss_feed'][0] ) ) {
						$url = esc_url_raw( $meta['_rss_feed'][0] );
						echo '<li><a href="' . $url . '" target="_blank"><i class="icon-rss"></i></a></li>';
					}
				?>
			</ul>
		</div>
		<div class="span9">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="entry-content">
				<?php
					the_content();

					$focus_areas = get_the_terms( get_the_ID(), 'ppu_focus_areas' );

					if ( $focus_areas ) { ?>
						<p class="foci">
						<strong>Focus Areas:</strong>
						<?php foreach ( $focus_areas as $key => $focus_area ) {
							echo $focus_area->name;
							if ( $key < count( $focus_areas ) - 1 ) {
								echo ', ';
							}
						}
					}

					$inn_projects = get_the_terms( get_the_ID(), 'pauinn_project_tax' );

					if ( $inn_projects ) { ?>
						<p class="projects">
						<strong>INN Projects:</strong>
						<?php foreach ( $inn_projects as $key => $inn_project ) {
							echo '<a href="' . get_term_link( $inn_project->term_id ) . '">' .  $inn_project->name . '</a>';
							if ( $key < count( $inn_projects ) - 1 ) {
								echo ', ';
							}
						}
					}

					echo '<div class="buttons">';
						if ( !empty ( $meta['_donate_url'][0] ) ) {
							echo '<a class="btn btn-primary donate" href="' . $meta['_donate_url'][0] . '">Donate Now</a>';
						}

						if ( !empty( $meta['_url'] ) ) {
							echo '<a class="btn website" href="' . $meta['_url'][0] . '">Visit Website</a>';
						}

						if ( !empty ( $meta['_email'] ) ) {
							echo '<a class="btn email" href="mailto:' . $meta['_email']  . '">Contact This Member</a>';
						}
					echo '</div>';


				?>
			</div>
		</div>
	</article>
</div>

<?php get_footer();
