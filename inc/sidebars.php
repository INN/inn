<?php
	
function inn_register_sidebars() {
	$sidebars = array (
		// the default widget areas
		array (
			'name'	=> __( 'Homepage Top Left', 'inn' ),
			'desc' 	=> __( 'Homepage Top Left.', 'inn' ),
			'id' 	=> 'homepage-top-left'
		),
		array (
			'name' 	=> __( 'Homepage Top Right', 'inn' ),
			'desc' 	=> __( 'Homepage Top Right.', 'inn' ),
			'id' 	=> 'homepage-top-right'
		),
	);
	// register the active widget areas
	foreach ( $sidebars as $sidebar ) {
		register_sidebar( array(
			'name' 		=> $sidebar['name'],
			'description' 	=> $sidebar['desc'],
			'id' 		=> $sidebar['id'],
			'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
			'after_widget' 	=> "</aside>",
			'before_title' 	=> '<h4>',
			'after_title' 	=> '</h4>',
		) );
	}
}
add_action( 'widgets_init', 'inn_register_sidebars' );