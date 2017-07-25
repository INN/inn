<?php
/**
 * Display the next Event from the Tribe Events Calendar plugin
 *
 * Based heavily on Tribe__Events__List_Widget
 */

// Don't load directly
if ( !defined( 'ABSPATH' ) ) {
	die( 'Accessing this file directly is not allowed.' );
}

class inn_next_event_widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 *  @param string $id_base
	 *  @param string $name
	 *  @param array  $widget_options
	 *  @param array  $control_options
	 */
	public function __construct( $id_base = '', $name = '', $widget_options = array(), $control_options = array() ) {

		$id_base = empty( $id_base ) ? 'inn-next-event-widget' : $id_base;
		$name = empty( $name ) ? esc_html__( 'Next Event', 'inn' ) : $name;
		$widget_options['description'] = __( 'Displays the next event from the Events Calendar plugin.', 'inn' );
		parent::__construct( $id_base, $name, $widget_options, $control_options );
	}

	/**
	 * Output the widget
	 * - widget title
	 * - event featured images
	 * - event name
	 * - event date
	 * - if no thumbnail, do show excerpt
	 * - subscribe/sign-up button
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// actually need to get the correct output here
		// instead of placeholder stuff
		// but for now, let us make a placeholder item
		$events = tribe_get_events( array(
			'posts_per_page' => 1,
			'start_date' => date( 'Y-m-d H:i:s' )
		) );

		// $events is an array of WP_Posts

		echo $args['before_widget'];

		// add the link to the title
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Events', 'inn' ) : $instance['title'], $instance, $this->id_base );

		if ( $instance['title'] ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}


		// presere the global $post
		global $post;
		$preserve = $post;

		$event = $events[0];
		$event_id = $event->ID;
		echo '<div class="inner">';
			// based off of markup in Largo/partials/widget-content.php
			printf(
				'<a href="%1$s">%2$s</a>',
				esc_url( get_permalink( $event_id ) ),
				get_the_post_thumbnail( $event_id, 'large', array( 'class' => ' attachment-large' ) )
			);
			printf(
				'<h3><a href="%1$s">%2$s</a></h3>',
				esc_url( get_permalink( $event_id ) ),
				get_the_title( $event_id )
			);
			largo_excerpt( $event, 2 );

			$url = get_post_meta( $event_id, '_EventURL', true );
			if ( ! empty( $url ) ) {
				printf(
					'<a class="btn btn-small center" href="%1$s">%2$s</a>',
					esc_url( $url ),
					__( 'More Info', 'inn' )
				);
			}
		echo '</div>';

		echo $args['after_widget'];
	}

	/**
	 * This actually should have very few options, since it's literally a The Next Event widget
	 *
	 * @param array $new_instance
	 * @param array $new_instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['featured_events_only'] = $new_instance['featured_events_only'];
		$instance['widget_class'] = esc_attr( $new_instance['featured_events_only'] );
		$instance['title_link'] = esc_url_raw( $new_instance['title_link'] );

		return $instance;
	}

	/**
	 * Output the admin form for the widget.
	 *
	 * @param array $instance
	 * @return string The output for the admin widget form.
	 */
	public function form( $instance ) {
		$defaults = array(
			'title' => __( 'Next Event', 'inn' ),
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'largo' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<?php
	}

}

add_action( 'widgets_init', function() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) {
		register_widget( 'inn_next_event_widget' );
	}
});
