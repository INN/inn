<?php
	//get post meta
	$meta = get_post_custom( $post->ID );
	$social = array('rss', 'twitter', 'facebook', 'googleplus', 'youtube');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
	<div>
	<header>
 		<h2 class="entry-title">
 			<?php if ( !is_single() ) { ?>
 			 	<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
 			<?php } else { ?>
	 			<?php the_title(); ?>
 			<?php } ?>
 		</h2>
 		<h5 class="history byline">
 			<?php if ( !empty($meta['inn_founded'][0]) ) echo "Founded " . $meta['inn_founded'][0] . ";"; ?>
 			<?php if ( !empty($meta['inn_since'][0]) ) echo "Member of INN since " . $meta['inn_since'][0]; ?>
 		</h5>
	</header>
	<div class="entry-content">
		<?php largo_excerpt( $post, 5, true ); ?>
	</div><!-- .entry-content -->
	<footer>
		<?php if ( !empty($meta['inn_site_url'][0])) : ?>
			<h6><strong>Website:</strong> <a href="<?php echo safe_url($meta['inn_site_url'][0]); ?>"><?php echo $meta['inn_site_url'][0]; ?></a></h6>
		<?php endif; ?>
		<ul class="social"><?php
			foreach ($social as $network) {
				if ( !empty($meta['inn_'.$network][0])) {
					if ( 'facebook' == $network ) {
						$url = "https://fb.com/" . $meta['inn_facebook'][0];
					} else if ( 'twitter' == $network ) {
						$url = "https://twitter.com/" . $meta['inn_twitter'][0];
					} else {
						$url = safe_url( $meta['inn_'.$network][0] );
					}
					if ( 'googleplus' == $network ) $network = 'gplus';
					?>
					<li><a href="<?php echo $url; ?>" target="_blank"><i class="icon-<?php echo $network; ?>"></i></a></li>
					<?php
				}
			}
		?>
		</ul>
	</footer>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->