<section id="testimonial" class="interstitial branding">
	<div class="content">
		<?php $testimonial = inn_get_testimonial(); ?>
		<img src="<?php echo $testimonial['photo_url']; ?>" />
		<p><?php echo $testimonial['text']; ?></p>
		<p class="credit">&ndash; <?php echo $testimonial['name'] . ', <a href="' . $testimonial['org_link'] . '">' . $testimonial['org'] . '</a>'; ?></p>
	</div>
</section>
