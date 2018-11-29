<?php
/**
 * Functions for WooCommerce
 */

/**
 * Declare Woocommerce support
 *
 * @link https://github.com/INN/inn/pull/72
 * @link https://docs.woocommerce.com/document/woocommerce-theme-developer-handbook/
 */
add_action( 'after_setup_theme', function() {
	add_theme_support( 'woocommerce' );
});
