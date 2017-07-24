<?php
/**
 * From INN Members widget
 * displays a single recently-saved link
 *
 * it's pretty much a duplication and rewrite of the class saved_links_widget from INN/link-roundups
 */
class from_inn_members_widget extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'from-inn-members',
			'description' 	=> __( 'Show your most recent saved link in a widget', 'inn' )
		);
		parent::__construct( 'from-inn-members', __( 'From INN Members', 'inn' ), $widget_ops );
	}
	function widget( $args, $instance ) {
		// make it possible for the widget title to be a link
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('From INN Members' , 'link-roundups') : $instance['title'], $instance, $this->id_base);
		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$query_args = array (
			'post__not_in' => get_option( 'sticky_posts' ),
			'showposts'    => 1,
			'post_type'    => 'rounduplink',
			'post_status'  => 'publish'
		);
		$my_query = new WP_Query( $query_args );
		
		if ( $my_query->have_posts() ) {
			while ( $my_query->have_posts() ) : $my_query->the_post();
			$custom = get_post_custom( $post->ID );
			// skip roundups
			if ( get_post_type( $post ) === 'roundup' ) continue; ?>

			<div class="post-lead clearfix">
				<?php
					// source, which takes the appearance of an h5.top-tag
					if ( isset($custom["lr_source"][0] ) ) {
						$lr_source = '<h5 class="top-tag source">';
						if ( !empty( $custom["lr_url"][0] ) ) {
							$lr_source .= '<a href="' . $custom["lr_url"][0] . '" ';
							if ( $instance['new_window'] == 'on' ) {
								$lr_source .= 'target="_blank" ';
							}
							$lr_source .= '>' . $custom["lr_source"][0] . '</a>';
						} else {
							$lr_source .= $custom["lr_source"][0];
						}
						$lr_source .= '</h5>';
						echo $lr_source;
					}
				?>

				<?php
					// thumbnail
					if (has_post_thumbnail( $post->ID ) ) {
						echo get_the_post_thumbnail( $post->ID, 'large' );
					}
				?>

				<h3><?php
					// headline
					if ( isset( $custom["lr_url"][0] ) ) {
						$output = '<a href="' . $custom["lr_url"][0] . '" ';
						if ( $instance['new_window'] == 'on' ) {
							$output .= 'target="_blank" ';
						}
						$output .= '>' . get_the_title() . '</a>';
					} else {
						// this scenario makes no sense, but we must account for it.
						$output = get_the_title();
					}
					echo $output;
					?>
				</h3>

				<?php
					// description
					if ( isset( $custom["lr_desc"][0] ) ) {
						echo '<p class="description">';
						echo ( function_exists( 'largo_trim_sentences' ) ) ? largo_trim_sentences($custom["lr_desc"][0], $instance['num_sentences']) : $custom["lr_desc"][0];
						echo '</p>';
					}
				?>

			</div> <!-- /.post-lead -->
			
		<?php
			endwhile;
		} else {
			_e( '<p class="error"><strong>You don\'t have any recent links or the link roundups plugin is not active.</strong></p>', 'link-roundups' );
		} // end recent links
		echo $args['after_widget'];
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num_sentences'] = strip_tags( $new_instance['num_sentences'] );
		$instance['new_window'] = $new_instance['new_window'];
		return $instance;
	}
	function form( $instance ) {
		$defaults = array(
			'title' => __( 'From INN Members', 'inn' ),
			'new_window' => 1,
			'num_sentences' => 2,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'link-roundups' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('new_window'); ?>" name="<?php echo $this->get_field_name('new_window'); ?>" <?php checked($instance['new_window'], 'on'); ?> />
			<label for="<?php echo $this->get_field_id('new_window'); ?>"><?php _e('Open links in new window', 'link-roundups'); ?></label>
		</p>

		<?php if ( function_exists( 'largo_trim_sentences' ) ) : ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_sentences' ); ?>"><?php _e( 'Excerpt Length (# of Sentences):', 'link-roundups' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_sentences' ); ?>" name="<?php echo $this->get_field_name( 'num_sentences' ); ?>" value="<?php echo $instance['num_sentences']; ?>" style="width:90%;" />
		</p>
		<?php endif; ?>

	<?php
	}
}

add_action( 'widgets_init', function() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'link-roundups/link-roundups.php' ) ) {
		register_widget( 'from_inn_members_widget' );
	}
});
