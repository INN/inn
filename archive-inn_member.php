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

$states = array(
	'AL' => __( 'Alabama', 'inn' ),
	'AK' => __( 'Alaska', 'inn' ),
	'AZ' => __( 'Arizona', 'inn' ),
	'AR' => __( 'Arkansas', 'inn' ),
	'CA' => __( 'California', 'inn' ),
	'CO' => __( 'Colorado', 'inn' ),
	'CT' => __( 'Connecticut', 'inn' ),
	'DE' => __( 'Delaware', 'inn' ),
	'DC' => __( 'District Of Columbia', 'inn' ),
	'FL' => __( 'Florida', 'inn' ),
	'GA' => __( 'Georgia', 'inn' ),
	'HI' => __( 'Hawaii', 'inn' ),
	'ID' => __( 'Idaho', 'inn' ),
	'IL' => __( 'Illinois', 'inn' ),
	'IN' => __( 'Indiana', 'inn' ),
	'IA' => __( 'Iowa', 'inn' ),
	'KS' => __( 'Kansas', 'inn' ),
	'KY' => __( 'Kentucky', 'inn' ),
	'LA' => __( 'Louisiana', 'inn' ),
	'ME' => __( 'Maine', 'inn' ),
	'MD' => __( 'Maryland', 'inn' ),
	'MA' => __( 'Massachusetts', 'inn' ),
	'MI' => __( 'Michigan', 'inn' ),
	'MN' => __( 'Minnesota', 'inn' ),
	'MS' => __( 'Mississippi', 'inn' ),
	'MO' => __( 'Missouri', 'inn' ),
	'MT' => __( 'Montana', 'inn' ),
	'NE' => __( 'Nebraska', 'inn' ),
	'NV' => __( 'Nevada', 'inn' ),
	'NH' => __( 'New Hampshire', 'inn' ),
	'NJ' => __( 'New Jersey', 'inn' ),
	'NM' => __( 'New Mexico', 'inn' ),
	'NY' => __( 'New York', 'inn' ),
	'NC' => __( 'North Carolina', 'inn' ),
	'ND' => __( 'North Dakota', 'inn' ),
	'OH' => __( 'Ohio', 'inn' ),
	'OK' => __( 'Oklahoma', 'inn' ),
	'OR' => __( 'Oregon', 'inn' ),
	'PA' => __( 'Pennsylvania', 'inn' ),
	'RI' => __( 'Rhode Island', 'inn' ),
	'SC' => __( 'South Carolina', 'inn' ),
	'SD' => __( 'South Dakota', 'inn' ),
	'TN' => __( 'Tennessee', 'inn' ),
	'TX' => __( 'Texas', 'inn' ),
	'UT' => __( 'Utah', 'inn' ),
	'VT' => __( 'Vermont', 'inn' ),
	'VA' => __( 'Virginia', 'inn' ),
	'WA' => __( 'Washington', 'inn' ),
	'WV' => __( 'West Virginia', 'inn' ),
	'WI' => __( 'Wisconsin', 'inn' ),
	'WY' => __( 'Wyoming', 'inn' ),
	'AS' => __( 'American Samoa', 'inn' ),
	'FM' => __( 'Micronesia', 'inn' ),
	'GU' => __( 'Guam', 'inn' ),
	'MH' => __( 'Marshall Islands', 'inn' ),
	'PR' => __( 'Puerto Rico', 'inn' ),
	'VI' => __( 'U.S. Virgin Islands', 'inn' ),
	'intl' => __( 'International', 'inn' ),
);
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

		<div class="member-nav">
			<label><?php _e( 'Filter List By: ', 'inn' ); ?></label>
			<select id="member-category">
				<option value="" disabled selected><?php echo __( 'Focus Area', 'inn' ); ?></option>
				<option value="all">- All -</option>
				<?php
				$terms = get_terms( 'ppu_focus_areas', array( 'hide_empty' => FALSE ) );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						echo '<option value=".' . $term->slug . '">' . $term->name . '</option>';
					}
				}
				?>
			</select>
			<select id="member-state">
				<option value="" disabled selected><?php echo __( 'State', 'inn' ); ?></option>
				<option value="all">- All -</option>
				<?php
				foreach ( $states as $key => $state ) {
					echo '<option value=".' . $key . '">' . $state . '</option>';
				}
				?>
			</select>

			<label><a href="/member-map">View as map</a></label>
		</div>

		<div class="row-fluid clearfix">
			<div class="inn-members span12" role="main" id="content">
				<?php
				$counter = 1;
				while ( have_posts() ) : the_post(); ?>
					<?php
					$meta = get_post_meta( $post->ID );
					$address = maybe_unserialize( $meta['_address'][0] );
					$focus_areas_obj = get_the_terms( $post->ID, 'ppu_focus_areas' );
					if ( $focus_areas_obj ) {
						foreach ( $focus_areas_obj as $item ) {
							$focus_areas[] = $item->slug;
						}
					}
					?>
					<article id="post-<?php echo $post->ID; ?>" class="inn_member directory mix <?php echo implode( ' ', $focus_areas ) . ' ' . $address['state']; ?>">
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
	<?php
	} else {
		get_template_part( 'partials/content', 'not-found' );
	} // End if().
	?>
</div>

<?php get_footer();
