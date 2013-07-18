<?php
/**
 * The homepage template
 */
get_header();

/*
 * Collect post IDs in each loop so we can avoid duplicating posts
 * and get the theme option to determine if this is a two column or three column layout
 */
$ids = array();
$layout = of_get_option('homepage_layout');
$tags = of_get_option ('tag_display');
?>

<div id="content" class="stories span8" role="main">

	<div id="content-main" class="span12">
		<?php get_template_part( 'home-part-topstories' ); ?>
	</div>

</div><!-- #content-->

<?php get_sidebar(); ?>

<div class="clearfix row-fluid" id="home-bottom">
	<?php
		if ( is_active_sidebar( 'inn-home-bottom' ) )	dynamic_sidebar( 'inn-home-bottom' );
	?>
</div>

<?php get_footer(); ?>