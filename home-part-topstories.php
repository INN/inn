<?php global $layout, $tags; ?>
<div id="homepage-featured" class="row-fluid clearfix">
	<h3 class="widgettitle"><a href="http://investigativenewsnetwork.org/network-content/">From Our Members</a><span class="note">(links open in new window)</span>
		<a class="rss-link" href="http://feeds.feedburner.com/INNMemberInvestigations"><i class="icon-rss"></i></a>
	</h3>
	<?php if ( $layout === '3col' ) { ?>
	<div class="top-story span12">
	<?php } else { ?>
	<div class="top-story span8">
	<?php }
		global $ids;
		$topstory = inn_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'top-story'
				)
			),
			'showposts' => 1
		) );
		if ( $topstory->have_posts() ) :
			while ( $topstory->have_posts() ) : $topstory->the_post(); $ids[] = get_the_ID();

				if( $has_video = get_post_meta( $post->ID, 'youtube_url', true ) ) { ?>
					<div class="embed-container">
						<iframe src="http://www.youtube.com/embed/<?php echo substr(strrchr( $has_video, "="), 1 ); ?>?modestbranding=1" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php } else { ?>
					<a href="<?php the_permalink(); ?>" target="_blank"><?php the_post_thumbnail( 'large' ); ?></a>
				<?php } ?>
        	<?php if ( $tags === 'top' && inn_homepage_tag() ) : ?>
        		<h5 class="top-tag"><?php inn_homepage_tag(1); ?></h5>
        	<?php endif; ?>
				<h2><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>

			    <h5 class="byline"><?php largo_byline(); ?></h5>
			    <?php largo_excerpt( $post, 4, false ); ?>
			    <?php if ( largo_post_in_series() ):
						$feature = largo_get_the_main_feature();
						$feature_posts = largo_get_recent_posts_for_term( $feature, 1, 1 );
						if ( $feature_posts ):
							foreach ( $feature_posts as $feature_post ): ?>
							<h4 class="related-story"><?php _e('RELATED:', 'largo'); ?> <a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></h4>
						<?php endforeach;
						endif;
					endif;

					if ( 'network_content' == get_post_type() ) :
						$member_id = get_post_meta( $ids[0], 'from_member_id', TRUE );
						$member_permalink = get_permalink( $member_id );
						$member_name = get_membername_from_post( $ids[0] );
						$member_meta = get_post_custom( $member_id );
						if ($member_meta) $member_donate_link = $member_meta['inn_donate'][0];
					?>
						<div class="more"><a href="<?php echo $member_permalink; ?>">More From <?php echo $member_name; ?> »</a></div>
						<?php if ($member_donate_link) { ?>
							<div class="donate">
								<p><strong><?php echo $member_name; ?> is a nonprofit organization.</strong><br />If you value their work, please help support it.</p>
								<div class="donate-btn"><a href="<?php echo $member_donate_link; ?>"><i class="icon-heart"></i>Donate Now</a></div>
							</div>
						<?php } ?>
					<?php endif;

			endwhile;
		endif; // end top story ?>
	</div>

	<?php if ( $layout === '2col' ) { ?>
	<div class="sub-stories span4">
		<?php $substories = inn_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'homepage-featured'
				)
			),
			'showposts'		=> 3,
			'post__not_in' 	=> $ids
		) );
		if ( $substories->have_posts() ) :
			$count = 1;
			while ( $substories->have_posts() ) : $substories->the_post(); $ids[] = get_the_ID();
				if ($count <= 3) : ?>
					<div class="story">
			        	<?php
			        		if ( $tags === 'top' && inn_homepage_tag() ) :
			        			if ( 'network_content' == get_post_type() ) :
									$member_id = get_post_meta( $ids[0], 'from_member_id', TRUE );
									$member_meta = get_post_custom( $member_id );
									if ($member_meta) $member_donate_link = $member_meta['inn_donate'][0];
								endif;
			        	?>
			        		<h5 class="top-tag">
			        			<?php
			        				inn_homepage_tag(1);
									if ( $member_donate_link ) {
										echo ' <span class="donate-link"><a href="' . $member_donate_link . '"><i class="icon-heart"></i>Donate Now</a></span>';
									}
								?>
							</h5>
			        	<?php endif; ?>
			        	<h3><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h3>
			        	<a href="<?php the_permalink(); ?>" target="_blank"><?php the_post_thumbnail(); ?></a>
			            <?php largo_excerpt( $post, 3, false ); ?>
			        </div>
			    <?php elseif ($count == 4) : ?>
			        <h4 class="subhead"><?php _e('More Headlines', 'largo'); ?></h4>
			        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			    <?php else : ?>
			        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			    <?php endif;
				$count++;
			endwhile;
		endif; // end more featured posts ?>
		<div class="more"><a href="<?php echo get_post_type_archive_link( 'network_content' ); ?>">More Member Stories »</a></div>
	</div>
	<?php } ?>
</div>