<?php
/*
 * Template Name: Member Investigations RSS feed
 *
 * A feed of curated INN member stories.
 * Use inn.org/?feed=member_stories for all stories in the "homepage featured" prominence taxonomy term
 * - OR -
 * inn.org/?feed=member_stories&top_stories=1 for just the "top stories"
 * @see inn_member_stories_rss
 */

$term = ( get_query_var( 'top_stories' ) == 1 ) ? 'top-story' : 'homepage-featured';

function rss_date( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}

$args = array (
	'showposts' => 20,
	'post_status' => 'publish',
	'post_type' => array( 'network_content' ),
    'tax_query' => array(
		array(
			'taxonomy' 	=> 'prominence',
			'field' 	=> 'slug',
			'terms' 	=> $term
		),
	),
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) {

	header("Content-Type: application/rss+xml; charset=UTF-8");
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

	<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/">
	<channel>
	  <title>Institute For Nonprofit News: Member Investigations</title>
	  <link>http://inn.org/</link>
	  <description>A curated feed of top stories from INN members</description>
	  <language>en-us</language>

	  <managingEditor>info@inn.org (INN.org)</managingEditor>

	<?php while ( $query->have_posts() ) : $query->the_post();
    	$permalink = get_post_meta( $post->ID, 'rssmi_source_link', TRUE );
		$member_id = get_post_meta( $post->ID, 'from_member_id', TRUE );
		$member_meta = get_user_meta( $member_id);
	?>
		<item>
		    <title><?php echo get_the_title($post->ID); ?></title>
		    <link><?php echo $permalink; ?></link>
		    <description><?php echo '<![CDATA[' . largo_excerpt( $post, 5, false, '', false ) . ']]>';  ?></description>
		    <pubDate><?php rss_date( strtotime( $post->post_date_gmt ) ); ?></pubDate>
		    <guid><?php echo $permalink; ?></guid>
		    <source url="<?php echo $member_meta['inn_rss'][0] ?>"><?php echo $member_meta['organization'][0] ?></source>
			<?php
				if ( get_the_post_thumbnail( $post->ID ) ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
					echo '<media:content url="' . $image[0] . '" medium="image" />';
				}
			?>
		</item>
	<?php endwhile; ?>
	</channel>
</rss>
<?php
}
?>
