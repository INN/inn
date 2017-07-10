<?php $img_path = get_stylesheet_directory_uri() . '/homepages/assets/img/'; ?>

<section id="hero" class="clearfix">
	<div class="flex-container">
		<div class="gradient-overlay">
			<div class="content">
				<a href="https://inn.org/project/emerging-leaders/"><h4>INN's Emerging Leaders group has its first meeting in Phoenix, Arizona</h4></a>
			</div>
		</div>
	</div>
</section>
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

