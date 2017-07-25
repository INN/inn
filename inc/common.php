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
 * Put the non-sticky nav logo in the main nav
 *
 * @see less/_nav.less
 */
function inn_main_nav_logo() {
	if ( of_get_option('sticky_header_logo') !== '') { ?>
		<li class="home-icon">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( of_get_option( 'banner_image_sm' ) !== '' )
					$logo = of_get_option( 'banner_image_sm' );
					$default = '<i class="icon-home orig"></i>';
					if ( ! empty( $logo ) ) {
						if ( preg_match( '/^http(s)?\:\/\//', $logo ) ) {
							echo '<img src="' . $logo . '" class="attachment-home-logo" alt="logo">';
						} else {
							echo $default;
						}
					} else {
						echo $default;
					}
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
