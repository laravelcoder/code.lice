<?php
/**
 * 
 * DP Social Widget class
 *
 **/

class DP_NewsFlash_Widget extends WP_Widget {
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function DP_NewsFlash_Widget() {
		parent::__construct(
			'widget_dp_newsflash', 
			__( 'DP NewsFlash', 'dp-theme' ), 
			array( 
				'classname' => 'widget_dp_newsflash', 
				'description' => __( 'Use this widget to show news flash', 'dp-theme') 
			)
		);
		
		$this->alt_option_name = 'widget_dp_newsflash';
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
		$cache = wp_cache_get('widget_dp_newsflash', 'widget');
		
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
		// get settings
		$this->config['style'] = empty($instance['style']) ? 'light' : $instance['style'];
		$this->config['catid'] = empty($instance['catid']) ? 'uncategorized' : $instance['catid'];
		$this->config['article_count'] = empty($instance['article_count']) ? '4' : $instance['article_count'];
		$this->config['itemsOrdering'] = empty($instance['itemsOrdering']) ? 'date' : $instance['itemsOrdering'];
		$this->config['usetitle'] = empty($instance['usetitle']) ? 'titel' : $instance['usetitle'];
		$this->config['content_type'] = empty($instance['content_type']) ? 'content' : $instance['content_type'];
		$this->config['pretext'] = empty($instance['pretext']) ? 'Breaking News' : $instance['pretext'];
		$this->config['controls'] = empty($instance['controls']) ? 'on' : $instance['controls'];
		$this->config['animation'] = empty($instance['animation']) ? 'slide' : $instance['animation'];
		$this->config['direction'] = empty($instance['direction']) ? 'horizontal' : $instance['direction'];
		$this->config['duration'] = empty($instance['duration']) ? '600' : $instance['duration'];
		$this->config['delay'] = empty($instance['delay']) ? '2500' : $instance['delay'];
		$this->config['news_indent'] = empty($instance['news_indent']) ? '75' : $instance['news_indent'];
		$this->config['preview_count'] = empty($instance['preview_count']) ? '75' : $instance['preview_count'];
		
		//
			echo $before_widget;
			echo $before_title;
			echo $title;
			echo $after_title;
		// render the layout
		 // Query Init
 		$catslug = get_category_by_slug($this->config['catid']);
		$dpnewsflash = new WP_Query('cat='.$catslug->term_id.'&posts_per_page='.$this->config['article_count'].'&orderby='.$this->config['itemsOrdering']);
		$wid = "flexslider_".mt_rand();
		echo '<script type="text/javascript">'."\n";
		echo "   jQuery(window).load(function() {"."\n"; 
		echo  "jQuery('#".$wid."').flexslider({"."\n";
		echo  '    animation: "'.$this->config['animation'].'",'."\n";
		echo  '    direction: "'.$this->config['direction'].'",'."\n";
		echo  '    slideshowSpeed:"'.$this->config['delay'].'",'."\n";
		echo  '    animationSpeed:"'.$this->config['duration'].'",'."\n";
		echo  '    controlNav: false,'."\n";
		echo  '    directionNav:false,'."\n";
        echo  '	   start: function(slider){'."\n";
		echo  '	   jQuery("#prev").click(function(){'."\n";   
		echo  '    slider.flexAnimate(slider.getTarget("prev"), true);'."\n"; 
		echo  '    });'."\n"; 
		echo  '    jQuery("#next").click(function(){'."\n";  
		echo  '    slider.flexAnimate(slider.getTarget("next"), true);'."\n"; 
		echo  '  });'."\n"; 
        echo  '  }'."\n"; 
		echo  "  });"."\n";
		echo "   });"."\n";
		echo "</script>"."\n";
		echo '<div id="dp-newsflash" class="'.$this->config['style'].'">';
    	echo '<span class="leading-text">'.$this->config['pretext'].'</span>';
		if ($this->config['controls'] == true) {echo '<div class="controls"><a id="prev"></a><a id="next"></a></div>';}
		echo '<div class="newsline">';
		echo '<div class="flexslider" id="'.$wid.'" style="margin-left:'.$this->config['news_indent'].'px;margin-right:40px;"><ul class="slides" >';

		if($dpnewsflash->have_posts()) : while($dpnewsflash->have_posts()) : $dpnewsflash->the_post();
		echo '<li>';
		echo '<a href="'.get_permalink().'">';
		    if ($this->config['usetitle'] == 'title') {
		        the_title();
		    } else {
		    	if($this->config['content_type'] == 'content') :
			        echo $this->prepareDPContent(get_the_content(false), $this->config['preview_count']).'&hellip;';
		    	else :
		    		echo $this->prepareDPContent(get_the_excerpt(), $this->config['preview_count']).'&hellip;';
		    	endif;
		    }
  		 echo '</a>';
		echo '</li>';
 		endwhile; endif; 

		echo '</ul></div></div>';
   		echo '';
   		echo '</div>';
		wp_reset_query();
			// 
			echo $after_widget;
		// save the cache results
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_dp_newsflash', $cache, 'widget');
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
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['catid'] = strip_tags($new_instance['catid']);
		$instance['article_count'] = strip_tags($new_instance['article_count']);
		$instance['itemsOrdering'] = strip_tags($new_instance['itemsOrdering']);
		$instance['usetitle'] = strip_tags($new_instance['usetitle']);
		$instance['content_type'] = strip_tags($new_instance['content_type']);
		$instance['pretext'] = strip_tags($new_instance['pretext']);
		$instance['controls'] = strip_tags($new_instance['controls']);
		$instance['animation'] = strip_tags($new_instance['animation']);
		$instance['direction'] = strip_tags($new_instance['direction']);
		$instance['duration'] = strip_tags($new_instance['duration']);
		$instance['delay'] = strip_tags($new_instance['delay']);
		$instance['news_indent'] = strip_tags($new_instance['news_indent']);
		$instance['preview_count'] = strip_tags($new_instance['preview_count']);
		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_dp_newsflash'])) {
			delete_option( 'widget_dp_newsflash' );
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
		wp_cache_delete( 'widget_dp_newsflash', 'widget' );
	}
    function prepareDPContent($text, $length = 200) {
		// strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		$text = preg_replace( '/{.+?}/', '', $text);
		// replace line breaking tags with whitespace
		$text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );
		$text = substr(strip_tags( $text ), 0, $length) ;
		return $text;
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
		$style = isset($instance['style']) ? esc_attr($instance['style']) : 'light';
		$catid = isset($instance['catid']) ? esc_attr($instance['catid']) : 'uncategorized';
		$article_count = isset($instance['article_count']) ? esc_attr($instance['article_count']) : '4';
		$itemsOrdering = isset($instance['itemsOrdering']) ? esc_attr($instance['itemsOrdering']) : 'date';
		$usetitle = isset($instance['usetitle']) ? esc_attr($instance['usetitle']) : 'title';
		$content_type = isset($instance['content_type']) ? esc_attr($instance['content_type']) : 'content';
		$pretext = isset($instance['pretext']) ? esc_attr($instance['pretext']) : 'Breaking News';
		$controls = isset($instance['controls']) ? esc_attr($instance['controls']) : 'on';
		$animation = isset($instance['animation']) ? esc_attr($instance['animation']) : 'slide';
		$direction = isset($instance['direction']) ? esc_attr($instance['direction']) : 'horizontal';
		$duration = isset($instance['duration']) ? esc_attr($instance['duration']) : '600';
		$delay = isset($instance['delay']) ? esc_attr($instance['delay']) : '2500';
		$news_indent = isset($instance['news_indent']) ? esc_attr($instance['news_indent']) : '75';
		$preview_count = isset($instance['preview_count']) ? esc_attr($instance['preview_count']) : '75';
        $categories = get_terms('category', 'hide_empty=0&orderby=name');
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php _e('Style:', 'dp-theme'); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('style')); ?>" name="<?php echo esc_attr( $this->get_field_name('style')); ?>">
				<option value="light"<?php echo (esc_attr($style) == 'light') ? ' selected="selected"' : ''; ?>>
					<?php _e('Light', 'dp-theme'); ?>
				</option>
				<option value="dark"<?php echo (esc_attr($style) == 'dark') ? ' selected="selected"' : ''; ?>>
					<?php _e('Dark', 'dp-theme'); ?>
				</option>
			</select>
		</p>
	    <p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'catid' ) ); ?>"><?php _e('Posts Category:', 'dp-theme'); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('catid')); ?>" name="<?php echo esc_attr( $this->get_field_name('catid')); ?>">
					<?php foreach ($categories as $cat) { ?>
					<option value="<?php echo $cat->slug; ?>"<?php if($instance['catid'] == $cat->slug) : echo ' selected="selected"'; endif; ?>><?php echo $cat->name; ?></option>
					<?php } ?>
			</select>
		</p>
         <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'article_count' ) ); ?>"><?php _e( 'Max Number of Posts:', 'dp-theme' ); ?></label>
			<input class="smallinputbox" id="<?php echo esc_attr( $this->get_field_id( 'article_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_count' ) ); ?>" type="text" value="<?php echo esc_attr( $article_count ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('itemsOrdering')); ?>"><?php _e('Order:', 'dp-theme'); ?></label>
		    <select class="widefat" id="<?php echo esc_attr($this->get_field_id('itemsOrdering')); ?>" name="<?php echo esc_attr($this->get_field_name('itemsOrdering')); ?>">
		      	<option value="author"<?php selected($instance['itemsOrdering'], 'author'); ?>><?php _e('Author', 'dp-theme'); ?></option>
		      	<option value="date"<?php selected($instance['itemsOrdering'], 'date'); ?>><?php _e('Date', 'dp-theme'); ?></option>
		      	<option value="title"<?php selected($instance['itemsOrdering'], 'title'); ?>><?php _e('Title', 'dp-theme'); ?></option>
		      	<option value="modified"<?php selected($instance['itemsOrdering'], 'modified'); ?>><?php _e('Modified', 'dp-theme'); ?></option>
		      	<option value="menu_order"<?php selected($instance['itemsOrdering'], 'menu_order'); ?>><?php _e('Menu Order', 'dp-theme'); ?></option>
		      	<option value="parent"<?php selected($instance['itemsOrdering'], 'parent'); ?>><?php _e('Parent', 'dp-theme'); ?></option>
		      	<option value="id"<?php selected($instance['itemsOrdering'], 'id'); ?>>ID</option>
		     </select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'usetitle' ) ); ?>"><?php _e('Use Title or Content:', 'dp-theme'); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('usetitle')); ?>" name="<?php echo esc_attr( $this->get_field_name('usetitle')); ?>">
				<option value="title"<?php echo (esc_attr($usetitle) == 'title') ? ' selected="selected"' : ''; ?>>
					<?php _e('Title', 'dp-theme'); ?>
				</option>
				<option value="content"<?php echo (esc_attr($usetitle) == 'content') ? ' selected="selected"' : ''; ?>>
					<?php _e('Content', 'dp-theme'); ?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'content_type' ) ); ?>"><?php _e('Content Type:', 'dp-theme'); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('content_type')); ?>" name="<?php echo esc_attr( $this->get_field_name('content_type')); ?>">
				<option value="excerpt"<?php echo (esc_attr($content_type) == 'excerpt') ? ' selected="selected"' : ''; ?>>
					<?php _e('Excerpt', 'dp-theme'); ?>
				</option>
				<option value="content"<?php echo (esc_attr($content_type) == 'content') ? ' selected="selected"' : ''; ?>>
					<?php _e('Content', 'dp-theme'); ?>
				</option>
			</select>
		</p>
         <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'pretext' ) ); ?>"><?php _e( 'PreText Label:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pretext' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pretext' ) ); ?>" type="text" value="<?php echo esc_attr( $pretext ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'controls' ) ); ?>"><?php _e('Show Controls:', 'dp-theme'); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('controls')); ?>" name="<?php echo esc_attr( $this->get_field_name('controls')); ?>">
				<option value="true"<?php echo (esc_attr($controls) == 'true') ? ' selected="selected"' : ''; ?>>
					<?php _e('On', 'dp-theme'); ?>
				</option>
				<option value="false"<?php echo (esc_attr($controls) == 'false') ? ' selected="selected"' : ''; ?>>
					<?php _e('Off', 'dp-theme'); ?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'animation' ) ); ?>"><?php _e('Animation:', 'dp-theme'); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('animation')); ?>" name="<?php echo esc_attr( $this->get_field_name('animation')); ?>">
				<option value="slide"<?php echo (esc_attr($animation) == 'slide') ? ' selected="selected"' : ''; ?>>
					<?php _e('Slide', 'dp-theme'); ?>
				</option>
				<option value="fade"<?php echo (esc_attr($content_type) == 'fade') ? ' selected="selected"' : ''; ?>>
					<?php _e('Fade', 'dp-theme'); ?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>"><?php _e('Animation direction:', 'dp-theme'); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id('direction')); ?>" name="<?php echo esc_attr( $this->get_field_name('direction')); ?>">
				<option value="horizontal"<?php echo (esc_attr($direction) == 'hotizontal') ? ' selected="selected"' : ''; ?>>
					<?php _e('Horizontal', 'dp-theme'); ?>
				</option>
				<option value="vertical"<?php echo (esc_attr($direction) == 'vertical') ? ' selected="selected"' : ''; ?>>
					<?php _e('Vertical', 'dp-theme'); ?>
				</option>
			</select>
		</p>
         <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'duration' ) ); ?>"><?php _e( 'Transition duration (ms):', 'dp-theme' ); ?></label>
			<input class="smallinputbox" id="<?php echo esc_attr( $this->get_field_id( 'duration' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'duration' ) ); ?>" type="text" value="<?php echo esc_attr( $duration ); ?>" />
		</p>
         <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'delay' ) ); ?>"><?php _e( 'Transition delay (ms):', 'dp-theme' ); ?></label>
			<input class="smallinputbox" id="<?php echo esc_attr( $this->get_field_id( 'delay' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'delay' ) ); ?>" type="text" value="<?php echo esc_attr( $delay ); ?>" />
		</p>
         <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'news_indent' ) ); ?>"><?php _e( 'News Indent (px):', 'dp-theme' ); ?></label>
			<input class="smallinputbox" id="<?php echo esc_attr( $this->get_field_id( 'news_indent' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'news_indent' ) ); ?>" type="text" value="<?php echo esc_attr( $news_indent ); ?>" />
		</p>
         <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'preview_count' ) ); ?>"><?php _e( 'Preview Length (characters):', 'dp-theme' ); ?></label>
			<input class="smallinputbox" id="<?php echo esc_attr( $this->get_field_id( 'preview_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'preview_count' ) ); ?>" type="text" value="<?php echo esc_attr( $preview_count ); ?>" />
		</p>
	<?php
	}
}


// EOF