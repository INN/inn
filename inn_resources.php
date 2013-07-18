<?php
/**
 * Let pages be categorized
 */
add_action( 'init', 'inn_page_enhancements' );
function inn_page_enhancements() {
	register_taxonomy_for_object_type( 'category', 'page' );
	add_post_type_support( 'page', 'excerpt' );
}

/**
 * Widget showing resources
 */
class resources_widget extends WP_Widget {

	var $more_offset = 0;

  function __construct() {
    $widget_ops = array( 'classname' => 'inn-resources-widget', 'description' => 'A rich widget for highlighting INN resources' );
    $control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'resources-widget' );
    $this->WP_Widget( 'resources-widget', 'INN Resources', $widget_ops, $control_ops );
  }


  function widget($args, $instance) {
    extract($args);
    echo $before_widget;
    ?>
    <?php
    if (!empty($instance['title'])) echo $before_title . $instance['title'] . $menu . $after_title; ?>

    <div class="resources-wrapper widget-content row-fluid">
	    <ul class="resources span3">
	    <?php
				$resources = get_categories( array(
					'include' => $instance['categories']
				) );

	      foreach ( $resources as $resource ) :
	      ?>
	        <li id="resource-<?php echo $resource->term_id;?>">
						<a href="#" data-panel="<?php echo $resource->category_nicename; ?>"><?php echo $resource->cat_name; ?></a>
	        </li>
	      <?php endforeach; ?>
	    </ul>
	    <div class="resource-items span9" id="resource-items">
				<?php
				foreach ( $resources as $resource ) {
					echo '<div class="item-' . $resource->category_nicename . '">';
					$this->get_top_item( $resource->term_id );
					echo '<h5 class="top-tag">';
					printf( __( 'More %s Resources', 'inn'), $resource->cat_name );
					echo '</h5>';

					$this->get_lower_items( $resource->term_id );
					$link_text = sprintf( __( 'View All %s Resources', 'inn'), $resource->cat_name );
					echo '<p class="morelink"><a href="', get_category_link( $resource->term_id ) ,'" class="view-all">', $link_text, '</a></p>';
					echo '</div>';
				}
				?>
	    </div>
    </div>
  <?php
    echo $after_widget;
  }

	function get_top_item( $cat_id ) {
		//try to get a Page tagged with this $cat_id that has a parent of INN_GUIDE_PARENT_ID
		//sort by guide_rank metafield, if present
		$args = array(
			'posts_per_page' => 1,
			'cat' => $cat_id,
			'post_type' => 'page',
			'meta_key' => 'guide_rank',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'post_parent' => INN_GUIDE_PARENT_ID,
			'suppress_filters' => 1,
			'update_post_term_cache' => 0,
			'update_post_meta_cache' => 0
		);

		$the_page = new WP_Query( $args );

		if ( !$the_page->have_posts() ) {

			//if at first you don't succeed...
			$args = array_merge( $args, array(
				'meta_key' => '',
				'orderby' => 'date',
				'order' => 'DESC'
			) );
			$the_page->query( $args );

			//we have something now, right? No? Fine, get the most recent post then.
			//sticky posts aren't preferred here, see http://wordpress.org/support/topic/sticky-posts-in-wp_query
			if ( !$the_page->have_posts() ) {
				$args = array_merge( $args, array(
					'post_type' => 'post',
					'post_parent' => '',
				) );
				$the_page->query( $args );
				//Still nothing? Fine, return a string
				if ( !$the_page->have_posts() ) {
					$the_page = __('No pages or posts found in this category', 'inn');
				} else {
					$this->more_offset = 1;
				}
			}
		}

		if ( is_string( $the_page )) {
			print $the_page;
			return false;
		}
		$the_page->the_post();
		get_template_part( 'content', 'resource' );
		wp_reset_postdata();	//always give back
		return $the_page;
	}

	function get_lower_items( $cat_id ) {
		//try to get a Page tagged with this $cat_id that has a parent of INN_GUIDE_PARENT_ID
		//sort by guide_rank metafield, if present
		$args = array(
			'posts_per_page' => 2,
			'cat' => $cat_id,
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'suppress_filters' => 1,
			'update_post_term_cache' => 0,
			'update_post_meta_cache' => 0,
			'offset' => $this->more_offset
		);

		$res = new WP_Query( $args );

		echo '<div class="more-resources">';

		while ( $res->have_posts() ) {
			$res->the_post();
			get_template_part( 'content', 'resource' );
		}

		echo '</div>';

		wp_reset_postdata();	//always give back
		return $res;
	}


  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    /* Strip tags (if needed) and update the widget settings. */
    $instance['title'] = strip_tags( $new_instance['title'] );
    //category selector isn't all smart about widget fields, so
		$instance['categories']  = ( is_array( $_POST['post_category'] ) ) ? $_POST['post_category'] : array() ;
    return $instance;
  }

  function form($instance) { ?>
    <p>
     <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title"); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
    </p>
    <div>
      <label>Included Categories: </label>
    	<div class="wp-tab-panel categorydiv">
		    <ul class="categorychecklist">
		    	<?php wp_category_checklist( 0, 0, $instance['categories'] ); ?>
		    </ul>
    	</div>
    	<br/>
    </div>
  <?php
  }
}


/**
 * Widget for featuring a guide (aka resource), presumably atop or beside a category archive
 */
class resource_widget extends WP_Widget {

	var $more_offset = 0;

  function __construct() {
    $widget_ops = array( 'classname' => 'inn-featured-resource-widget', 'description' => 'A widget for highlighting a resource on a category archive' );
    $control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'resource-widget' );
    $this->WP_Widget( 'resource-widget', 'INN Category Resource', $widget_ops, $control_ops );
  }


  function widget($args, $instance) {
    extract($args);
    if ( !is_category() ) return;

    global $wp_query;
    $cat_id = $wp_query->query_vars['cat'];

		$featured = $this->get_item( $cat_id );

    if ( false === $featured ) return;

    echo $before_widget;
		$featured->the_post();
		global $post;
		?>

    <div class="widget-content">
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				<div>
					<header>
						<h5 class="top-tag"><?php echo $instance['title']; ?></h5>

				 		<h2 class="entry-title">
				 			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
				 		</h2>

				 		<h5 class="byline"><?php largo_byline(); ?></h5>
					</header><!-- / entry header -->

					<div class="entry-content">
						<?php largo_excerpt( $post, 5, true ); ?>
					</div><!-- .entry-content -->
					<div class="links">
						<a href="<?php the_permalink(); ?>"><?php echo $instance['link_text']; ?></a>
						<a href="<?php echo get_permalink($instance['guides_id']); ?>"><?php echo $instance['more_text']; ?></a>
					</div>
				</div>
			</article><!-- #post-<?php the_ID(); ?> -->
    </div>
  <?php
    echo $after_widget;
  }

	function get_item( $cat_id ) {
		//try to get a Page tagged with this $cat_id that has a parent of INN_GUIDE_PARENT_ID
		//sort by guide_rank metafield, if present
		$args = array(
			'posts_per_page' => 1,
			'cat' => $cat_id,
			'post_type' => 'page',
			'meta_key' => 'guide_rank',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'post_parent' => INN_GUIDE_PARENT_ID,
			'suppress_filters' => 1,
			'update_post_term_cache' => 0,
			'update_post_meta_cache' => 0
		);

		$the_page = new WP_Query( $args );

		if ( $the_page->have_posts() ) {

			wp_reset_postdata();	//always give back
			return $the_page;

		} else {

			//if at first you don't succeed...
			$args = array_merge( $args, array(
				'meta_key' => '',
				'orderby' => 'date',
				'order' => 'DESC'
			) );
			$the_page->query( $args );

			//we have something now, right? No? Fine, give up
			if ( !$the_page->have_posts() ) {

				return false;

			} else {

				wp_reset_postdata();	//always give back
				return $the_page;

			}
		}
	}

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    /* Strip tags (if needed) and update the widget settings. */
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['link_text'] = strip_tags( $new_instance['link_text'] );
    $instance['guides_id'] = (int) $_POST['guide_page_id'];
    $instance['more_text'] = strip_tags( $new_instance['more_text'] );
    return $instance;
  }

  function form($instance) { ?>
    <p>
     <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title"); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
    </p>
    <p>
     <label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e("Link Text"); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" value="<?php echo ($instance['link_text']) ? $instance['link_text'] : __('Read This Guide', 'inn'); ?>" class="widefat" />
    </p>
    <p>
     <label for="<?php echo $this->get_field_id('guides_id'); ?>"><?php _e("Guides Page"); ?>:</label>
		 <?php wp_dropdown_pages( array(
			 'depth' => 0,
			 'selected' => $instance['guides_id'],
			 'name' => 'guide_page_id'
		 )); ?>
    </p>
    <p>
     <label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e("Guides Link Text"); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" value="<?php echo ($instance['more_text']) ? $instance['more_text'] : __('More Guides from INN', 'inn'); ?>" class="widefat" />
    </p>
   <?php
  }
}
