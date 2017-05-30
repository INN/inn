<?php
/**
 * Template for various non-category archive pages (tag, term, date, etc.)
 *
 * @package INN
 * @since 0.1
 * @filter largo_partial_by_post_type
 */
get_header();
$queried_object = get_queried_object();
?>

<div class="clearfix">

	<?php
		if ( have_posts() || largo_have_featured_posts() ) {

			// queue up the first post so we know what type of archive page we're dealing with
			the_post();
		?>

		<header class="entry-header">
			<h1 class="entry-title">Member Directory</h1>
			<section class="entry-content">
				<p>Every one of INN's 120+ members is a nonprofit, nonpartisan organization committed to donor transparency. <a href="https://inn.org/for-members/membership-standards/">Learn more about our membership standards</a>.<br>
			</section>
		</header>

		<div class="row-fluid clearfix">
			<div class="inn-members span12" role="main" id="content">
			<?php
				$counter = 1;
				while ( have_posts() ) : the_post(); ?>
					<?php $meta = get_post_meta( $post->ID ); ?>
					<article id="post-<?php echo $post->ID; ?>" class="inn_member directory">
						<a href="<?php echo get_the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
						<h3><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="member-since">Member since <?php echo $meta['_inn_join_year'][0]; ?></p>
						<ul class="social">
							<li><a href="mailto:<?php echo $meta['_email'][0]; ?>"><i class="icon-mail"></i></a></li>
							<li><a href="<?php echo $meta['_rss_feed'][0]; ?>" target="_blank"><i class="icon-rss"></i></a></li>
							<li><a href="<?php echo $meta['_twitter_url'][0]; ?>" target="_blank"><i class="icon-twitter"></i></a></li>
							<li><a href="<?php echo $meta['_facebook_url'][0]; ?>" target="_blank"><i class="icon-facebook"></i></a></li>
						</ul>
						<p><a href="<?php echo $meta['_url']; ?>">Visit Website</a></p>
					</article>
					<?php $counter++; ?>
				<?php endwhile; ?>
			</div><!-- end content -->
		</div>
		<?php } else {
			get_template_part( 'partials/content', 'not-found' );
		}
	?>
</div>

<?php get_footer();
