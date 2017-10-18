<?php
/**
 * 
 * DP Social Widget class
 *
 **/

class DP_Social_Widget extends WP_Widget {
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function DP_Social_Widget() {
		parent::__construct(
			'widget_dp_social_icons', 
			__( 'DP Social Icons', 'dp-theme' ), 
			array( 
				'classname' => 'widget_dp_social_icons', 
				'description' => __( 'Use this widget to show social links', 'dp-theme') 
			)
		);
		
		$this->alt_option_name = 'widget_dp_social_icons';
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
		$cache = wp_cache_get('widget_dp_social_icons', 'widget');
		
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
		$fb_link = empty($instance['fb_link']) ? '' : $instance['fb_link'];
		$twitter_link = empty($instance['twitter_link']) ? '' : $instance['twitter_link'];
		$gplus_link = empty($instance['gplus_link']) ? '' : $instance['gplus_link'];
		$rss_link = empty($instance['rss_link']) ? '' : $instance['rss_link'];
		$linkedin_link = empty($instance['linkedin_link']) ? '' : $instance['linkedin_link'];
		$dribble_link = empty($instance['dribble_link']) ? '' : $instance['dribble_link'];
		$flickr_link = empty($instance['flickr_link']) ? '' : $instance['flickr_link'];
		$forst_link = empty($instance['forst_link']) ? '' : $instance['forst_link'];
		$pinterest_link = empty($instance['pinterest_link']) ? '' : $instance['pinterest_link'];
		$sharethis_link = empty($instance['sharethis_link']) ? '' : $instance['sharethis_link'];
		$skype_link = empty($instance['skype_link']) ? '' : $instance['skype_link'];
		$vimeo_link = empty($instance['vimeo_link']) ? '' : $instance['vimeo_link'];
		$youtube_link = empty($instance['youtube_link']) ? '' : $instance['youtube_link'];
		//
		if ($fb_link !== '' || $twitter_link !== '' || $gplus_link !== '' || $rss_link !== '' || $linkedin_link !== '' || $dribble_link !== '' || $flickr_link !== '' || $forst_link !== ''  || $pinterest_link !== '' || $sharethis_link !== '' || $skype_link !== '' || $vimeo_link !== '' || $youtube_link !== '') {
			echo $before_widget;
			echo $before_title;
			echo $title;
			echo $after_title;
			//
			if($fb_link !== '') echo '<a href="'.str_replace('&', '&amp;', $fb_link).'" target="_blank" class="dp-facebook-icon" data-tipcontent="Facebook">Facebook</a>';
			if($twitter_link !== '') echo '<a href="'.str_replace('&', '&amp;', $twitter_link).'" target="_blank" class="dp-twitter-icon" data-tipcontent="Twitter">Twitter</a>';
			if($gplus_link !== '') echo '<a href="'.str_replace('&', '&amp;', $gplus_link).'" target="_blank" class="dp-gplus-icon" data-tipcontent="Google+">Google+</a>';
			if($rss_link !== '') echo '<a href="'.str_replace('&', '&amp;', $rss_link).'" target="_blank" class="dp-rss-icon" data-tipcontent="RSS">RSS</a>';
			if($linkedin_link !== '') echo '<a href="'.str_replace('&', '&amp;', $linkedin_link).'" target="_blank" class="dp-linkedin-icon" data-tipcontent="Linkedin">Linkedin</a>';
			if($dribble_link !== '') echo '<a href="'.str_replace('&', '&amp;', $dribble_link).'" target="_blank" class="dp-dribble-icon" data-tipcontent="Dribble">Dribble</a>';
			if($flickr_link !== '') echo '<a href="'.str_replace('&', '&amp;', $flickr_link).'" target="_blank" class="dp-flickr-icon" data-tipcontent="Flickr">Flickr</a>';
			if($forst_link !== '') echo '<a href="'.str_replace('&', '&amp;', $forst_link).'" target="_blank" class="dp-forst-icon" data-tipcontent="Forst">Forst</a>';
			if($pinterest_link !== '') echo '<a href="'.str_replace('&', '&amp;', $pinterest_link).'" target="_blank" class="dp-pinterest-icon" data-tipcontent="Pinterest">Pinterest</a>';
			if($sharethis_link !== '') echo '<a href="'.str_replace('&', '&amp;', $sharethis_link).'" target="_blank" class="dp-sharethis-icon" data-tipcontent="Sharethis">Sharethis</a>';
			if($skype_link !== '') echo '<a href="'.str_replace('&', '&amp;', $skype_link).'" target="_blank" class="dp-skype-icon" data-tipcontent="Skype">Skype</a>';
			if($vimeo_link !== '') echo '<a href="'.str_replace('&', '&amp;', $vimeo_link).'" target="_blank" class="dp-vimeo-icon" data-tipcontent="Vimeo">Vimeo</a>';
			if($youtube_link !== '') echo '<a href="'.str_replace('&', '&amp;', $youtube_link).'" target="_blank" class="dp-youtube-icon" data-tipcontent="YouTube">YouTube</a>';
			// 
			echo $after_widget;
		}
		// save the cache results
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_dp_social_icons', $cache, 'widget');
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
		$instance['fb_link'] = strip_tags($new_instance['fb_link']);
		$instance['twitter_link'] = strip_tags($new_instance['twitter_link']);
		$instance['gplus_link'] = strip_tags($new_instance['gplus_link']);
		$instance['rss_link'] = strip_tags($new_instance['rss_link']);
		$instance['linkedin_link'] = strip_tags($new_instance['linkedin_link']);
		$instance['dribble_link'] = strip_tags($new_instance['dribble_link']);
		$instance['flickr_link'] = strip_tags($new_instance['flickr_link']);
		$instance['forst_link'] = strip_tags($new_instance['forst_link']);
		$instance['pinterest_link'] = strip_tags($new_instance['pinterest_link']);
		$instance['sharethis_link'] = strip_tags($new_instance['sharethis_link']);
		$instance['skype_link'] = strip_tags($new_instance['skype_link']);
		$instance['vimeo_link'] = strip_tags($new_instance['vimeo_link']);
		$instance['youtube_link'] = strip_tags($new_instance['youtube_link']);
		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_dp_social_icons'])) {
			delete_option( 'widget_dp_social_icons' );
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
		wp_cache_delete( 'widget_dp_social_icons', 'widget' );
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
		$fb_link = isset($instance['fb_link']) ? esc_attr($instance['fb_link']) : '';
		$twitter_link = isset($instance['twitter_link']) ? esc_attr($instance['twitter_link']) : '';
		$gplus_link = isset($instance['gplus_link']) ? esc_attr($instance['gplus_link']) : '';
		$rss_link = isset($instance['rss_link']) ? esc_attr($instance['rss_link']) : '';
		$linkedin_link = isset($instance['linkedin_link']) ? esc_attr($instance['linkedin_link']) : '';
		$dribble_link = isset($instance['dribble_link']) ? esc_attr($instance['dribble_link']) : '';
		$flickr_link = isset($instance['flickr_link']) ? esc_attr($instance['flickr_link']) : '';
		$forst_link = isset($instance['forst_link']) ? esc_attr($instance['forst_link']) : '';
		$pinterest_link = isset($instance['pinterest_link']) ? esc_attr($instance['pinterest_link']) : '';
		$sharethis_link = isset($instance['sharethis_link']) ? esc_attr($instance['sharethis_link']) : '';
		$skype_link = isset($instance['skype_link']) ? esc_attr($instance['skype_link']) : '';
		$vimeo_link = isset($instance['vimeo_link']) ? esc_attr($instance['vimeo_link']) : '';
		$youtube_link = isset($instance['youtube_link']) ? esc_attr($instance['youtube_link']) : '';
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fb_link' ) ); ?>"><?php _e( 'Facebook link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fb_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fb_link' ) ); ?>" type="text" value="<?php echo esc_attr( $fb_link ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_link' ) ); ?>"><?php _e( 'Twitter link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_link' ) ); ?>" type="text" value="<?php echo esc_attr( $twitter_link ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'gplus_link' ) ); ?>"><?php _e( 'Google+ link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'gplus_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'gplus_link' ) ); ?>" type="text" value="<?php echo esc_attr( $gplus_link ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'rss_link' ) ); ?>"><?php _e( 'RSS link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rss_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rss_link' ) ); ?>" type="text" value="<?php echo esc_attr( $rss_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'linkedin_link' ) ); ?>"><?php _e( 'LinkedIn link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin_link' ) ); ?>" type="text" value="<?php echo esc_attr( $linkedin_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'dribble_link' ) ); ?>"><?php _e( 'Dribble link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dribble_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dribble_link' ) ); ?>" type="text" value="<?php echo esc_attr( $dribble_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'flickr_link' ) ); ?>"><?php _e( 'Flickr link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'flickr_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flickr_link' ) ); ?>" type="text" value="<?php echo esc_attr( $flickr_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'forst_link' ) ); ?>"><?php _e( 'Forst link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'forst_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'forst_link' ) ); ?>" type="text" value="<?php echo esc_attr( $forst_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest_link' ) ); ?>"><?php _e( 'Pinterest link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest_link' ) ); ?>" type="text" value="<?php echo esc_attr( $pinterest_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sharethis_link' ) ); ?>"><?php _e( 'Sharethis link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'sharethis_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sharethis_link' ) ); ?>" type="text" value="<?php echo esc_attr( $sharethis_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'skype_link' ) ); ?>"><?php _e( 'Skype link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'skype_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'skype_link' ) ); ?>" type="text" value="<?php echo esc_attr( $skype_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'vimeo_link' ) ); ?>"><?php _e( 'Vimeo link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vimeo_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vimeo_link' ) ); ?>" type="text" value="<?php echo esc_attr( $vimeo_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'youtube_link' ) ); ?>"><?php _e( 'YouTube link:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube_link' ) ); ?>" type="text" value="<?php echo esc_attr( $youtube_link ); ?>" />
		</p>
	<?php
	}
}


// EOF