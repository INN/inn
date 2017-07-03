<div id="boilerplate">
	<div class="row-fluid clearfix">
		<div class="span8 right">
			<ul id="footer-social" class="social-icons">
				<?php largo_social_links(); ?>
			</ul>
			<div class="footer-bottom clearfix">

				<p class="footer-credit"><?php largo_copyright_message(); ?></p>
				<?php do_action('largo_after_footer_copyright'); ?>
				<?php largo_nav_menu(
					array(
						'theme_location' => 'footer-bottom',
						'container' => false,
						'depth' => 1
					) );
				?>
			</div>
		</div>
	</div>

	<div class="row-fluid clearfix">
		<div class="span8 right">
			<?php if (INN_MEMBER) { ?>
				<?php inn_logo(); ?>
			<?php } ?>
			<p class="footer-credit <?php echo ( !INN_MEMBER ? 'footer-credit-padding-inn-logo-missing' : ''); ?>"><?php printf( __('Built with the <a href="%s">Largo WordPress Theme</a> from the <a href="%s">Institute for Nonprofit News</a>.', 'largo'),
					'http://largoproject.org',
					'http://inn.org'
				 );
			?></p>
		</div>
	</div>

	<p class="back-to-top visuallyhidden"><a href="#top"><?php _e('Back to top', 'largo'); ?> &uarr;</a></p>
</div>
