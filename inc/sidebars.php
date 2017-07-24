<?php
function inn_register_sidebars() {
	$sidebars = array (
		// the default widget areas
		array (
			'name'	=> __( 'Homepage Top Left', 'inn' ),
			'description' 	=> __( 'Homepage Top Left.', 'inn' ),
			'id' 	=> 'homepage-top-left',
		),
		array (
			'name' 	=> __( 'Homepage Top Right', 'inn' ),
			'description' 	=> __( 'Homepage Top Right.', 'inn' ),
			'id' 	=> 'homepage-top-right',
		),
		array (
			'name' 	=> __( 'Homepage Middle', 'inn' ),
			'description' 	=> __( 'Homepage Midle', 'inn' ),
			'id' 	=> 'homepage-middle',
			'before_widget' => '<aside id="%1$s" class="span4 %2$s clearfix">',
		),
	);
	// register the active widget areas
	foreach ( $sidebars as $sidebar ) {
		register_sidebar( array_merge(
			array(
				'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
				'after_widget' 	=> "</aside>",
				'before_title' 	=> '<h4 class="widgettitle">',
				'after_title' 	=> '</h4>',
			),
			$sidebar
		) );
	}
}
add_action( 'widgets_init', 'inn_register_sidebars' );

/**
 * Add the page navigation to the bottom of the sidebar
 */
function inn_sidebar_subnav_dropdown() {
	if ( is_page() || is_singular( 'pauinn_project' ) ) {
		get_template_part('partials/internal-subnav');
	}
}
add_action( 'largo_before_sidebar_widgets', 'inn_sidebar_subnav_dropdown' );
