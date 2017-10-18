<?php
/**
 * 
 * DP Recent Post Widget class
 *
 **/

class DP_Recent_Post_Widget extends WP_Widget {
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function DP_Recent_Post_Widget() {
		parent::__construct(
			'widget_dp_recent_post', 
			__( 'DP Recent Posts', 'dp-theme' ), 
			array( 
				'classname' => 'widget_dp_recent_post', 
				'description' => __( 'Use this widget to show recent posts', 'dp-theme') 
			)
		);
		
		$this->alt_option_name = 'widget_dp_recent_post';
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
		$cache = wp_cache_get('widget_dp_recent_post', 'widget');
		
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
		$post_cat = empty($instance['post_cat']) ? '' : $instance['post_cat'];
		$show_num = empty($instance['$show_num']) ? '' : $instance['$show_num'];
		$word_limit = empty($instance['$word_limit']) ? '' : $instance['$word_limit'];
		$thumb_width = empty($instance['$thumb_width']) ? '' : $instance['$thumb_width'];

		//
		
		
			echo $before_widget;
			echo $before_title;
			echo $title;
			echo $after_title;
			//
			$custom_posts = get_posts('showposts='.$show_num.'&cat='.get_cat_ID($post_cat));
			if( !empty($custom_posts) ){ 
			echo "<div class='dp-recent-post-widget'>";
			foreach($custom_posts as $custom_post) { 
				?>
				<div class="recent-post-widget">
				<?php
								$thumbnail_id = get_post_thumbnail_id( $custom_post->ID );				
								$thumbnail = wp_get_attachment_image_src( $thumbnail_id);
								$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);	
								if( !empty($thumbnail) ){
									?>
					<div class="thumbnail">
						<a href="<?php echo get_permalink( $custom_post->ID ); ?>">
							
									<?php echo '<img class="pic2" width="'.$thumb_width.'" src="' . $thumbnail[0] . '" alt="'. $alt_text .'"/>'; ?>
							
						</a>
					</div>
					<?php	}
							?>
					<div class="content">
                        <div class="excerpt"><a href="<?php echo get_permalink( $custom_post->ID ); ?>"> 
						<?php  $excerpt =  get_post_field('post_content', $custom_post->ID); 
						$excerpt = strip_tags(do_shortcode($excerpt));
						$excerpt = preg_replace('/<img[^>]+./','',$excerpt); 
						echo string_limit_words($excerpt,$word_limit);?>&hellip;</a></div>
						<div class="date">
                        <?php echo mysql2date('j M Y',$custom_post->post_date); ?> 
						</div>
					</div>
					<div class="clearboth"></div>
				</div>						
				<?php 
				
			}
			echo "</div>";
		}
			// 
			echo $after_widget;
		// save the cache results
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_dp_recent_post', $cache, 'widget');
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
		$instance['post_cat'] = strip_tags($new_instance['post_cat']);
		$instance['$show_num'] = strip_tags($new_instance['$show_num']);
		$instance['$word_limit'] = strip_tags($new_instance['$word_limit']);
		$instance['$thumb_width'] = strip_tags($new_instance['$thumb_width']);
		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_dp_recent_post'])) {
			delete_option( 'widget_dp_recent_post' );
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
		wp_cache_delete( 'widget_dp_recent_post', 'widget' );
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
		$post_cat = isset($instance['post_cat']) ? esc_attr($instance['post_cat']) : '';
		$show_num = isset($instance['$show_num']) ? esc_attr($instance['$show_num']) : '';
		$word_limit = isset($instance['$word_limit']) ? esc_attr($instance['$word_limit']) : '';
		$thumb_width = isset($instance['$thumb_width']) ? esc_attr($instance['$thumb_width']) : '';
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) ); ?>"><?php _e( 'Posts category:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_cat' ) ); ?>" type="text" value="<?php echo esc_attr( $post_cat ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$show_num' ) ); ?>"><?php _e( 'Item count:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( '$show_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$show_num' ) ); ?>" type="text" value="<?php echo esc_attr( $show_num ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$word_limit' ) ); ?>"><?php _e( 'Word limit in excerpt:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( '$word_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$word_limit' ) ); ?>" type="text" value="<?php echo esc_attr( $word_limit ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( '$thumb_width' ) ); ?>"><?php _e( 'Thumnail width (px):', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( '$thumb_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( '$thumb_width' ) ); ?>" type="text" value="<?php echo esc_attr( $thumb_width ); ?>" />
		</p>
	<?php
	}
}
// EOF