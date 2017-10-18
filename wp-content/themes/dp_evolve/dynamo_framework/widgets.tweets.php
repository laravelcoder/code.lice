<?php

/**
 * 
 * DP Tweets Widget class
 *
 **/

class DP_Tweets_Widget extends WP_Widget {
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function DP_Tweets_Widget() {
		parent::__construct(
			'widget_dp_tweets', 
			__( 'DP Tweets Widget', 'dp-theme' ), 
			array( 
				'classname' => 'widget_dp_tweets', 
				'description' => __( 'Use this widget to show recent tweets for specific query', 'dp-theme') 
			)
		);
		
		$this->alt_option_name = 'widget_dp_tweets';
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
		$cache = get_transient(md5($this->id));
		
		// the part with the title and widget wrappers cannot be cached! 
		// in order to avoid problems with the calculating columns
		//
		extract($args, EXTR_SKIP);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? _e( 'DP Tweets', 'dp-theme' ) : $instance['title'], $instance, $this->id_base);
		
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;
		
		if($cache) {

			echo $cache;
			echo $after_widget;
			return;
		}

		ob_start();
		$consumerkey = empty($instance['consumerkey']) ? '' : $instance['consumerkey'];
		$consumersecret = empty($instance['consumersecret']) ? '' : $instance['consumersecret'];
		$accesstoken = empty($instance['accesstoken']) ? '' : $instance['accesstoken'];
		$accesstokensecret = empty($instance['accesstokensecret']) ? '' : $instance['accesstokensecret'];
		$cachetime = empty($instance['cachetime']) ? '' : $instance['cachetime'];
		$username = empty($instance['username']) ? '' : $instance['username'];
		$tweetstoshow = empty($instance['tweetstoshow']) ? '' : $instance['tweetstoshow'];
		$show_avatar = empty($instance['show_avatar']) ? 'true' : $instance['show_avatar'];
		$excludereplies = empty($instance['excludereplies']) ? 'true' : $instance['excludereplies'];
		$show_date = empty($instance['show_date']) ? 'true' : $instance['show_date'];

		//check settings and die if not set
								if(empty($instance['consumerkey']) || empty($instance['consumersecret']) || empty($instance['accesstoken']) || empty($instance['accesstokensecret']) || empty($instance['cachetime']) || empty($instance['username'])){
									_e( 'Please fill all widget settings!', 'dp-theme' );
									echo $after_widget;
									return;
								}		
		//
		require_once(dynamo_file('dynamo_framework/classes/class.twitteroauth.php'));								
							function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
							  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
							  return $connection;
							}
							  
							  							  
							$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
							$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=10&exclude_replies=".$excludereplies) or die('Couldn\'t retrieve tweets! Wrong username?');

if(!empty($tweets->errors)){
								if($tweets->errors[0]->message == 'Invalid or expired token'){
									echo '<strong>'.$tweets->errors[0]->message.'!</strong><br />You\'ll need to regenerate it <a href="https://dev.twitter.com/apps" target="_blank">here</a>!' . $after_widget;
								}else{
									echo '<strong>'.$tweets->errors[0]->message.'</strong>' . $after_widget;
								}
								return;
							}
							$tweets_array = array();
							for($i = 0;$i <= count($tweets); $i++){
								if(!empty($tweets[$i])){
									$tweets_array[$i]['created_at'] = $tweets[$i]->created_at;
									$tweets_array[$i]['avatar'] = $tweets[$i]->user->profile_image_url;
									$tweets_array[$i]['author'] = $tweets[$i]->user->screen_name;
										//clean tweet text
										$tweets_array[$i]['text'] = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $tweets[$i]->text);
									
									if(!empty($tweets[$i]->id_str)){
										$tweets_array[$i]['status_id'] = $tweets[$i]->id_str;			
									}
								}	
							}							
					
		if(!empty($tweets)){					
		echo '<div class="dp_recent_tweets"><ul>' . "\n";
		$fctr = '1';
							foreach($tweets_array as $tweet){
								if(!empty($tweet['text'])){
									if(empty($tweet['status_id'])){ $tweet['status_id'] = ''; }
									if(empty($tweet['created_at'])){ $tweet['created_at'] = ''; }
								
									print '<li>';
									if ($show_avatar == 'true') print '<img class="twitter_awatar" src="'.$tweet['avatar'].'">';
									print '<span class="twitter_author">'.dp_convert_links('@'.$tweet['author']).'</span><div class="space5"></div><span>'.dp_convert_links($tweet['text']).'</span>';
									if ($show_date == 'true') print '<br /><a class="twitter_time" target="_blank" href="http://twitter.com/'.$instance['username'].'/statuses/'.$tweet['status_id'].'">'.dp_relative_time($tweet['created_at']).'</a></li>';
									if($fctr == $instance['tweetstoshow']){ break; }
									$fctr++;
								}
							}
		
		echo '</ul></div>' . "\n";
		}
		// save the cache results
		$cache_output = ob_get_flush();		
		set_transient(md5($this->id) , $cache_output, 10 * 60);
		
		echo $after_widget;
				
		
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
		$instance['consumerkey'] = strip_tags($new_instance['consumerkey']);
		$instance['consumersecret'] = strip_tags($new_instance['consumersecret']);
		$instance['accesstoken'] = strip_tags($new_instance['accesstoken']);
		$instance['accesstokensecret'] = strip_tags($new_instance['accesstokensecret']);
		$instance['cachetime'] = strip_tags($new_instance['cachetime']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['tweetstoshow'] = strip_tags($new_instance['tweetstoshow']);
		$instance['show_avatar'] = strip_tags($new_instance['show_avatar']);
		$instance['excludereplies'] = strip_tags($new_instance['excludereplies']);
		$instance['show_date'] = strip_tags($new_instance['show_date']);

		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_dp_tweets'])) {
			delete_option( 'widget_dp_tweets' );
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
		delete_transient(md5($this->id));
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
		$consumerkey = isset($instance['consumerkey']) ? esc_attr($instance['consumerkey']) : '';
		$consumersecret = isset($instance['consumersecret']) ? esc_attr($instance['consumersecret']) : '';
		$accesstoken = isset($instance['accesstoken']) ? esc_attr($instance['accesstoken']) : '';
		$accesstokensecret = isset($instance['accesstokensecret']) ? esc_attr($instance['accesstokensecret']) : '';
		$cachetime = isset($instance['cachetime']) ? esc_attr($instance['cachetime']) : '3';
		$username = isset($instance['username']) ? esc_attr($instance['username']) : '';
		$tweetstoshow = isset($instance['tweetstoshow']) ? esc_attr($instance['tweetstoshow']) : '3';
		$show_avatar = isset($instance['show_avatar']) ? esc_attr($instance['show_avatar']) : 'true';
		$excludereplies = isset($instance['excludereplies']) ? esc_attr($instance['excludereplies']) : 'true';
		$show_date = isset($instance['show_date']) ? esc_attr($instance['show_date']) : 'true';
	
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'consumerkey' ) ); ?>"><?php _e( 'Consumer Key:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'consumerkey' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumerkey' ) ); ?>" type="text" value="<?php echo esc_attr( $consumerkey ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'consumersecret' ) ); ?>"><?php _e( 'Consumer Secret:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'consumersecret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumersecret' ) ); ?>" type="text" value="<?php echo esc_attr( $consumersecret ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'accesstoken' ) ); ?>"><?php _e( 'Access Token:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'accesstoken' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'accesstoken' ) ); ?>" type="text" value="<?php echo esc_attr( $accesstoken ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'accesstokensecret' ) ); ?>"><?php _e( 'Access Token Secret:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'accesstokensecret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'accesstokensecret' ) ); ?>" type="text" value="<?php echo esc_attr( $accesstokensecret ); ?>" />
        </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cachetime' ) ); ?>"><?php _e( 'Cache Tweets in every:', 'dp-theme' ); ?></label>
			<input class="small-text" id="<?php echo esc_attr( $this->get_field_id( 'cachetime' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cachetime' ) ); ?>" type="text" value="<?php echo esc_attr( $cachetime ); ?>" /> <?php _e( 'hours', 'dp-theme' ); ?>
        </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php _e( 'Twitter Username:', 'dp-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>" />
        </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tweetstoshow' ) ); ?>"><?php _e( 'Amount of tweets:', 'dp-theme' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'tweetstoshow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tweetstoshow' ) ); ?>">
           <?php $i = 1;
					for(i; $i <= 10; $i++){
						echo '<option value="'.$i.'"'; if($instance['tweetstoshow'] == $i){ echo ' selected="selected"'; } echo '>'.$i.'</option>';						
					}?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_avatar' ) ); ?>" class="label100"><?php _e( 'Show avatar:', 'dp-theme' ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id( 'show_avatar' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_avatar' ) ); ?>">
				<option value="true" <?php if(esc_attr( $show_avatar ) == 'true') : ?>selected="selected"<?php endif; ?>><?php _e('Enabled', 'dp-theme'); ?></option>
				<option value="false" <?php if(esc_attr( $show_avatar ) == 'false') : ?>selected="selected"<?php endif; ?>><?php _e('Disabled', 'dp-theme'); ?></option>
			</select>
		</p>

		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" class="label100"><?php _e( 'Show date:', 'dp-theme' ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
				<option value="true" <?php if(esc_attr( $show_date ) == 'true') : ?>selected="selected"<?php endif; ?>><?php _e('Enabled', 'dp-theme'); ?></option>
				<option value="false" <?php if(esc_attr( $show_date ) == 'false') : ?>selected="selected"<?php endif; ?>><?php _e('Disabled', 'dp-theme'); ?></option>
			</select>
		</p>
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excludereplies' ) ); ?>" class="label100"><?php _e( 'Exclude replies:', 'dp-theme' ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id( 'excludereplies' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excludereplies' ) ); ?>">
				<option value="false" <?php if(esc_attr( $excludereplies ) == 'false') : ?>selected="selected"<?php endif; ?>><?php _e('No', 'dp-theme'); ?></option>
				<option value="true" <?php if(esc_attr( $excludereplies ) == 'true') : ?>selected="selected"<?php endif; ?>><?php _e('Yes', 'dp-theme'); ?></option>
			</select>
		</p>

	<?php
	}
}
	function dp_convert_links($status,$targetBlank=true,$linkMaxLen=250){
							 
								// the target
									$target=$targetBlank ? " target=\"_blank\" " : "";
								 
$status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);
//$status = preg_replace_callback('/((http:\/\/|https:\/\/)[^ )]+)/', function($matches) {
   // return '<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=250 ? substr('$1',0,250).'...':'$1')).'</a>';
//}, $status);															 
								// convert @ to follow
									$status = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>",$status);
								 
								// convert # to search
									$status = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>",$status);
								 
								// return the status
									return $status;
							}
function dp_relative_time($a) {
							//get current timestampt
							$b = strtotime("now"); 
							//get timestamp when tweet created
							$c = strtotime($a);
							//get difference
							$d = $b - $c;
							//calculate different time values
							$minute = 60;
							$hour = $minute * 60;
							$day = $hour * 24;
							$week = $day * 7;
								
							if(is_numeric($d) && $d > 0) {
								//if less then 3 seconds
								if($d < 3) return "right now";
								//if less then minute
								if($d < $minute) return floor($d) . " seconds ago";
								//if less then 2 minutes
								if($d < $minute * 2) return "about 1 minute ago";
								//if less then hour
								if($d < $hour) return floor($d / $minute) . " minutes ago";
								//if less then 2 hours
								if($d < $hour * 2) return "about 1 hour ago";
								//if less then day
								if($d < $day) return floor($d / $hour) . " hours ago";
								//if more then day, but less then 2 days
								if($d > $day && $d < $day * 2) return "yesterday";
								//if less then year
								if($d < $day * 365) return floor($d / $day) . " days ago";
								//else return more than a year
								return "over a year ago";
							}
						}	
						// EOF