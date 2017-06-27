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

		echo $args['before_widget'];

		if ( $instance['title'] ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		?>
		<div class="inner">
			<a href=""><img src="https://inn.org/wp-content/uploads/2017/05/page-header-wide.jpg" alt="serious placeholder content"/></a>
			<h3><a href="">(R)amp Up Your Email List Now, Boost Donations Later (3-part series)</a></h3>
			<p class="">Thursday, Jun 1 at 1:00pm</p>
			<a class="btn btn-small" href="">Sign up</a>
		</div>
		<?php
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