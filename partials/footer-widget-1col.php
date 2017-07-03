<div class="span4 left">
	<?php inn_logo(); ?>
	<p>714 W. Olympic Blvd. #931, Los Angeles, CA 90015</p>
	<p><a href="tel:+18185823560">(818) 582-3560</a> <span class="sep">|</span> <a href="mailto:info@inn.org">info@inn.org</a></p>
	<p class="footer-credit <?php echo ( !INN_MEMBER ? 'footer-credit-padding-inn-logo-missing' : ''); ?>"><?php printf( __('Built with the <a href="%s">Largo WordPress Theme</a> from the <a href="%s">Institute for Nonprofit News</a>.', 'largo'),
			'http://largoproject.org',
			'http://inn.org'
		 );
	?>
	</p>
</div>
<div class="span8 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-1' ) )
		largo_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1  ) );
	?>
</div>
</div>
<div class="span8 widget-area" role="complementary">
