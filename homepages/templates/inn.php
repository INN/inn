<section class="normal">
	<div id="homepage-top-widgets" class="row-fluid">
		<div id="homepage-top-right" class="span4">
			<?php dynamic_sidebar( 'homepage-top-right' ); ?>
		</div>
		<div id="homepage-top-left" class="span8">
			<?php dynamic_sidebar( 'homepage-top-left' ); ?>
		</div>
	</div>
</section>

<?php get_template_part('partials/home-widget-row'); ?>

<?php get_template_part('partials/email-signup'); ?>

<?php get_template_part('partials/testimonials'); ?>

