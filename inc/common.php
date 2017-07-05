<?php
/**
 * Common functions for all INN child themes
 *
 * This should be used with:
 * less/common.less
 * less/variables.less
 * partials/footer-*
 */

/**
 * Output the INN logo, used in the footer
 *
 * Pluggable function plug, replaces image URL /img/inn_logo_gray.php with /images/logo-white.png
 *
 * @since 0.5.2
 */
function inn_logo() {
	?>
		<a href="//inn.org/" id="inn-logo-container">
			<img id="inn-logo" src="<?php echo( get_stylesheet_directory_uri() . "/images/logo-white.png" ); ?>" alt="<?php printf(__("%s is a member of the Institute for Nonprofit News", "largo"), get_bloginfo('name')); ?>" />
		</a>
	<?php
}
