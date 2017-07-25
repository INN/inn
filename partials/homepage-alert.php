<?php
// modified from the default Largo partial to display on all pages
if (is_active_sidebar('homepage-alert')) { ?>
<div class="alert-wrapper max-wide">
	<div id="alert-container">
		<?php dynamic_sidebar('homepage-alert'); ?>
	</div>
</div>
<?php } ?>
