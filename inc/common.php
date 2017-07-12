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
 * Put the sticky nave logo in the main nav
 *
 * @see less/_nav.less
 */
function inn_main_nav_logo() {
	if ( of_get_option('sticky_header_logo') !== '') { ?>
		<li class="home-icon">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( of_get_option( 'sticky_header_logo' ) !== '' )
					largo_home_icon( 'icon-white' , 'orig' );
				?>
			</a>
		</li>
	<?php } else { ?>
		<li class="site-name"><a href="/"><?php echo $site_name; ?></a></li>
	<?php }
}
add_action( 'largo_before_main_nav_shelf', 'inn_main_nav_logo' );

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
