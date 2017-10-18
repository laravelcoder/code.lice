<?php
/**
 * 
 * DP Recent Portfolio Widget class
 *
 **/

class DP_Recent_Port_Grid_Widget extends WP_Widget {
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function DP_Recent_Port_Grid_Widget() {
		parent::__construct(
			'widget_dp_recent_port_grid', 
			__( 'DP Recent Portfolio Grid', 'dp-theme' ), 
			array( 
				'classname' => 'widget_dp_recent_port_grid', 
				'description' => __( 'Use this widget to show recent portfolio items in thumbnail grid form', 'dp-theme') 
			)
		);
		
		$this->alt_option_name = 'widget_dp_recent_port_grid';
	}

	/**
	 *
	 * Outputs the HTML code of this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void
	 *
	 **/
	function widget($args, $instance) {
		$cache = wp_cache_get('widget_dp_recent_port_grid', 'widget');
		
		if(!is_array($cache)) {
			$cache = array();
		}

		if(!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		if(isset($cache[$args['widget_id']])) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		//
		extract($args, EXTR_SKIP);
		//
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$show_num = empty($instance['$show_num']) ? '' : $instance['$show_num'];
		$columns = empty($instance['$columns']) ? '' : $instance['$columns'];
		$categories = empty($instance['$categories']) ? '' : $instance['$categories'];
		$show_filter = empty($instance['$show_filter']) ? '' : $instance['$show_filter'];
		$links = empty($instance['$links']) ? '' : $instance['$links'];

		//
		
		
			echo $before_widget;
			echo $before_title;
			echo $title;
			echo $after_title;
			//
			echo dp_print_recent_projects_grid($show_num,$columns,$categories,$show_filter,$links);
			// 
			echo $after_widget;
		// save the cache results
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_dp_recent_port_grid', $cache, 'widget');
	}

	/**
	 *
	 * Used in the back-end to update the module options
	 *
	 * @param array new instance of the widget settings
	 * @param array old instance of the widget settings
	 * @return updated instance of the widget settings
	 *
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['$show_num'] = strip_tags($new_instance['$show_num']);
		$instance['$columns'] = strip_tags($new_instance['$columns']);
		$instance['$categories'] = strip_tags($new_instance['$categories']);
		$instance['$show_filter'] = strip_tags($new_instance['$show_filter']);
		$instance['$links'] = strip_tags($new_instance['$links']);
		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_dp_recent_port_grid'])) {
			delete_option( 'widget_dp_recent_port_grid' );
		}

		return $instance;
	}

	/**
	 *
	 * Refreshes the widget cache data
	 *
	 * @return void
	 *
	 **/

	function refresh_cache() {
		wp_cache_delete( 'widget_dp_recent_port_grid', 'widget' );
	}

	/**
	 *
	 * Outputs the HTML code of the widget in the back-end
	 *
	 * @param array instance of the widget settings
	 * @return void - HTML output
	 *
	 **/
	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$show_num = isset($instance['$show_num']) ? esc_attr($instance['$show_num']) : '';
		$word_limit = isset($instance['$word_limit']) ? esc_attr($instance['$word_limit']) : '';
		$columns = isset($instance['$columns']) ? esc_attr($instance['$columns']) : '';
		$categories = isset($instance['$categories']) ? esc_attr($instance['$categories']) : '';
		$show_filter = isset($instance['$show_filter']) ? esc_attr($instance['$show_filter']) : '';
		$links = isset($instance['$links']) ? esc_attr($instance['$links']) : '';

	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
				
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$show_num' ) ); ?>"><?php _e( 'Item count:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( '$show_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$show_num' ) ); ?>" type="text" value="<?php echo esc_attr( $show_num ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$columns' ) ); ?>" class="label100"><?php _e( 'Columns count:', 'dp-theme' ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id( '$columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$columns' ) ); ?>">
				<option value="2" <?php if(esc_attr( $columns ) == '2') : ?>selected="selected"<?php endif; ?>>2</option>
				<option value="3" <?php if(esc_attr( $columns ) == '3') : ?>selected="selected"<?php endif; ?>>3</option>
				<option value="4" <?php if(esc_attr( $columns ) == '4') : ?>selected="selected"<?php endif; ?>>4</option>
				<option value="5" <?php if(esc_attr( $columns ) == '5') : ?>selected="selected"<?php endif; ?>>5</option>
				<option value="6" <?php if(esc_attr( $columns ) == '6') : ?>selected="selected"<?php endif; ?>>6</option>
				<option value="8" <?php if(esc_attr( $columns ) == '8') : ?>selected="selected"<?php endif; ?>>8</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$columns' ) ); ?>"><?php _e( 'Categories:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( '$categories' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$categories' ) ); ?>" type="text" value="<?php echo esc_attr( $categories ); ?>" />
            
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$show_filter' ) ); ?>" class="label100"><?php _e( 'Show category filter:', 'dp-theme' ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id( '$show_filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$show_filter' ) ); ?>">
				<option value="no" <?php if(esc_attr( $show_filter ) == 'no') : ?>selected="selected"<?php endif; ?>><?php _e( 'No categories fiter', 'dp-theme' ); ?></option>
				<option value="yes" <?php if(esc_attr( $show_filter ) == 'yes') : ?>selected="selected"<?php endif; ?>><?php _e( 'Display categories fiter', 'dp-theme' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$links' ) ); ?>" class="label100"><?php _e( 'Hover links:', 'dp-theme' ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id( '$links' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$links' ) ); ?>">
				<option value="no" <?php if(esc_attr( $links ) == 'no') : ?>selected="selected"<?php endif; ?>><?php _e( 'No links', 'dp-theme' ); ?></option>
				<option value="zoom" <?php if(esc_attr( $links ) == 'zoom') : ?>selected="selected"<?php endif; ?>><?php _e( 'Lightbox zoom link', 'dp-theme' ); ?></option>
				<option value="link" <?php if(esc_attr( $links ) == 'link') : ?>selected="selected"<?php endif; ?>><?php _e( 'Portfolio item link', 'dp-theme' ); ?></option>
				<option value="both" <?php if(esc_attr( $links ) == 'both') : ?>selected="selected"<?php endif; ?>><?php _e( 'Both', 'dp-theme' ); ?></option>
			</select>
		</p>
	<?php
	}
}
// EOF