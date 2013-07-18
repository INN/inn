<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 */
?>

	</div> <!-- #main -->

	<?php
	// conditionally show sponsors
	if ( show_funders() ) :
		$funders = get_page( INN_FUNDER_PAGE_ID );
	?>
	<section class="funder-thanks hidden-phone">
		<h5><?php echo $funders->post_title; ?></h5>
		<div class="row-fluid">
			<?php echo apply_filters('the_content', $funders->post_content); ?>
		</div>
	</section>
	<?php endif; ?>

</div><!-- #page -->

<div class="footer-bg clearfix">
	<footer id="site-footer" class="row-fluid">

		<div class="row-fluid">

			<div class="span1 hidden-phone footer-logo-wrapper">
				<a id="footer-logo" href="<?php echo home_url(); ?>">
	  			<?php bloginfo('name'); ?>
					</a>
			</div>

			<div class="footer-menu span8" role="complementary">
			<?php
				wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 2, 'menu_class' => 'menu clearfix'  ) );
			?>
			</div>

			<div class="widget-area hidden-phone span3" role="complementary">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>

		</div>

		<div id="boilerplate" class="row-fluid clearfix">
			<p class="hidden-phone"><strong>The Investigative News Network is a 501(c)(3) nonprofit organization advancing sustainability and excellence in nonprofit journalism.</strong></p>
			<p><?php largo_copyright_message(); ?>. All Rights Reserved.</p>
			<p><a href="/about/legal/privacy-statement/">Privacy Policy</a> | <a href="/about/legal/terms-of-service/" rel="nofollow">Terms Of Service</a></p>
			<p class="back-to-top visible-phone"><a href="#top"><?php _e('Back to top &uarr;', 'largo'); ?></a></p>
		</div><!-- /#boilerplate -->
	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>