<?php
/*
 * Template Name: Member Investigations
 * A feed of curated INN member stories.
 * Use investigativenewsnetwork.org/?feed=member_stories for all stories in the "homepage featured" prominence taxonomy term
 * - OR -
 * investigativenewsnetwork.org/?feed=member_stories&top_stories=1 for just the "top stories"
 */

$numposts = 20;
$terms = ( get_query_var( 'top_stories' ) == 1 ) ? 'top-story' : 'homepage-featured';

function rss_date( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}

$posts = query_posts( array(
     'post_type' => array( 'network_content' ),
     'tax_query' => array(
		array(
			'taxonomy' 	=> 'prominence',
			'field' 	=> 'slug',
			'terms' 	=> $terms
		)
	 ),
     'showposts' => $numposts
) );

$lastpost = $numposts - 1;

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
  <title>Investigative News Network: Member Investigations</title>
  <link>http://investigativenewsnetwork.org/</link>
  <description>A curated feed of investigative stories from members of the Investigative News Network</description>
  <language>en-us</language>
  <pubDate><?php rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></pubDate>
  <lastBuildDate><?php rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></lastBuildDate>
  <managingEditor>info@investigativenewsnetwork.org</managingEditor>

<?php foreach ($posts as $post) {
	$permalink = get_post_meta( $post->ID, 'permalink', TRUE );
	$member_id = get_post_meta( $ids[0], 'from_member_id', TRUE );
	$member_permalink = get_permalink( $member_id );
	$member_name = get_membername_from_post( $ids[0] );
	$member_meta = get_post_custom( $member_id );
	$member_rss = get_post_meta( $post->ID, 'feed_url', TRUE );
?>
  <item>
    <title><?php echo get_the_title($post->ID); ?></title>
    <link><?php echo $permalink; ?></link>
    <description><?php echo '<![CDATA[' . largo_excerpt( $post, 5, false, '', false ) . ']]>';  ?></description>
    <pubDate><?php rss_date( strtotime($post->post_date_gmt) ); ?></pubDate>
    <guid><?php echo $permalink; ?></guid>
    <source url="<?php echo $member_rss; ?>"><?php echo $member_name; ?></source>
	<?php if( get_the_post_thumbnail( $post->ID ) ):
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
	?>
    	<media:content url="<?php echo $image[0]; ?>" medium="image" />
	<?php endif; ?>
  </item>
<?php } // endforeach ?>
</channel>
</rss>