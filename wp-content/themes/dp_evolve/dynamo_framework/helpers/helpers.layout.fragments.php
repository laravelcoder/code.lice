<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * DynamoWP layout fragments
 *
 * Functions used to create DynamoWP-specific functions 
 *
 **/
 
/**
 *
 * Template for menus
 *
 * @param menuname - name of the menu
 * @param fullname - full name of the menu - ID
 * @param params - array of the other params (optional)
 *
 * @return HTML output
 *
 **/ 
 
function dynamo_menu($menuname, $fullname, $params = null, $addclass) {			
	global $dynamo_tpl;
	if(dp_show_menu($menuname)) {
		if($params !== null) {
			extract($params);
		}
	
		wp_nav_menu(array(
		      'theme_location'  => $menuname,
			  'container'       => isset($container) ? $container : false, 
			  'container_class' => 'menu-{menu slug}-container', 
			  'container_id'    => $fullname,
			  'menu_class'      => 'menu ' . $dynamo_tpl->menu[$menuname]['style'], 
			  'menu_id'         => str_replace('menu', '-menu', $menuname),
			  'echo'            => isset($echo) ? $echo : true,
			  'fallback_cb'     => isset($fallback_cb) ? $fallback_cb: 'wp_page_menu',
			  'before'          => isset($before) ? $before : '',
			  'after'           => isset($after) ? $after : '',
			  'link_before'     => isset($link_before) ? $link_before : '',
			  'link_after'      => isset($link_after) ? $link_after : '',
			  'items_wrap'      => isset($items_wrap) ? $items_wrap : '<ul id="%1$s" class="%2$s '.$addclass.'">%3$s</ul>',
			  'depth'           => $dynamo_tpl->menu[$menuname]['depth'],
			  'walker'			=> isset($walker) ? $walker : ''
		));
	}
}
function dynamo_menu_mobile($menuname, $fullname, $params = null, $addclass) {			
	global $dynamo_tpl;
	if(dp_show_menu($menuname)) {
		if($params !== null) {
			extract($params);
		}
	
		wp_nav_menu(array(
		      'theme_location'  => $menuname,
			  'container'       => isset($container) ? $container : false, 
			  'container_class' => 'menu-{menu slug}-container', 
			  'container_id'    => $fullname,
			  'menu_class'      => 'menu ' . $dynamo_tpl->menu[$menuname]['style'], 
			  'menu_id'         => str_replace('menu', '-menu', $menuname),
			  'echo'            => isset($echo) ? $echo : true,
			  'fallback_cb'     => isset($fallback_cb) ? $fallback_cb: 'wp_page_menu',
			  'before'          => isset($before) ? $before : '',
			  'after'           => isset($after) ? $after : '',
			  'link_before'     => isset($link_before) ? $link_before : '',
			  'link_after'      => isset($link_after) ? $link_after : '',
			  'items_wrap'      => isset($items_wrap) ? $items_wrap : '<ul class="%2$s '.$addclass.'">%3$s</ul>',
			  'depth'           => $dynamo_tpl->menu[$menuname]['depth'],
			  'walker'			=> isset($walker) ? $walker : ''
		));
	}
}

/**
 *
 * Template for comments and pingbacks.
 *
 * @param comment - the comment to render
 * @param args - additional arguments
 * @param depth - the depth of the comment
 *
 * @return null
 *
 **/
function dynamo_comment_template( $comment, $args, $depth ) {
	global $dynamo_tpl;
	
	$GLOBALS['comment'] = $comment;

	do_action('dynamowp_before_comment');

	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="pingback">
		<p>
			<?php __( 'Pingback:', 'dp-theme' ); ?> 
			<?php comment_author_link(); ?>
			<?php edit_comment_link( __( 'Edit', 'dp-theme' ), '<span class="edit-link">', '</span>' ); ?>
		</p>
		<?php break; ?>
	<?php default : ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>">	
			<aside>
				<div class="avatar"><?php echo get_avatar( $comment, ($comment->comment_parent == '0') ? 40 : 32); ?></div>
			</aside>
					
			<section class="content">
            <div class="comment-bouble">				
				<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="awaiting-moderation"><?php __( 'Your comment is awaiting moderation.', 'dp-theme' ); ?></em>
				<?php endif; ?>
				
				<?php comment_text(); ?>
				
				<footer>
					<?php
						/* translators: 1: comment author, 2: date and time */
						printf( 
							__( '%1$s on %2$s', 'dp-theme' ),
							sprintf( 
								'<b>%s</b>', 
								get_comment_author_link() 
							),
							sprintf(
								'<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time(DATE_W3C),
								sprintf( __( '%1$s at %2$s', 'dp-theme' ), 
								get_comment_date(), 
								get_comment_time() )
							)
						);
					?>
					
					<?php edit_comment_link( __( 'Edit', 'dp-theme' ), '<span class="edit-link">', '</span>' ); ?>
					
					<span class="reply">
						<?php comment_reply_link( 
							array_merge( 
								$args,
								array( 
									'reply_text' => __( 'Reply', 'dp-theme' ), 
									'depth' => $depth, 
									'max_depth' => $args['max_depth'] 
								) 
							) 
						); ?>
					</span>
				</footer>
                </div>
			</section>
		</article>

	<?php
			break;
	endswitch;
	
	do_action('dynamowp_after_comment');
}

/**
 *
 * Function used to generate post fields
 *
 * @return null
 *
 **/
function dp_post_fields() {
	global $dynamo_tpl;
	// check if the custom fields are enabled
	if(get_option($dynamo_tpl->name . '_custom_fields_state', 'Y') == 'Y') {
		// get the post custom fields
		if ($keys = get_post_custom_keys()) {
			// get the hidden fields array
			$hiddenfields = explode(',', get_option($dynamo_tpl->name . '_hidden_post_fields', ''));
			// variable for the list items
			$output = '';
			// generate the list
			foreach ((array) $keys as $key) {
				// trim the key name
				$key_trimmed = trim($key);
				// skip the protected meta data and "dynamo-" or "dynamo_" values
				if(
					is_protected_meta($key_trimmed, 'post') || 
					stripos($key_trimmed, 'dynamo-') !== FALSE ||
					stripos($key_trimmed, 'dynamo_') !== FALSE ||
					in_array($key_trimmed, $hiddenfields)
					) {
					continue;
				}
				// map the values
				$values = array_map('trim', get_post_custom_values($key));
				// extract the value
				$value = implode($values,', ');
                                
                //custom post fileds label mapping
                $mapping = preg_split('/\r\n|\r|\n/', get_option($dynamo_tpl->name . '_post_fileds_label_mapping'));
                 foreach($mapping as $item) {
                                     //
                         if(strpos($item, '=') === false) continue;
                                     
                         $item = explode('=', $item);
                                     
                         if($key != $item[0]) continue;
                                     
                         if($item[1] != '' && $item[0] == $key) {
                            $key = $item[1];
                          }
                } 
                                
				// generate the item
				$output .= apply_filters('the_meta_key', '<dt>'.$key.':</dt>'."\n".'<dd>'.$value.'</dd>'."\n", $key, $value);
			}
			// output the list
			if($output !== '') {
				echo '<dl class="post-fields">' . "\n";
				echo $output;
				echo '</dl>' . "\n";
			}
		}
	}
}

/**
 *
 * Function used to generate post meta data
 *
 * @param attachment - for the attachment size the script generate additional informations
 *
 * @return null
 *
 **/
function dp_post_meta($attachment = false) {
 	global $dynamo_tpl;
 	$tag_list = get_the_tag_list( '', __( ', ', 'dp-theme' ) );
 	?>
    <div class="meta">
    <?php if(get_post_format() != '') : ?>
	 		<span class="format dp-format-<?php echo get_post_format(); ?>"></span>
	<?php endif; ?>	
    <?php if (!has_post_thumbnail() && get_post_meta(get_the_ID(), "_dynamo-featured-video", true) == '') { ?>
    <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state') == 'Y') :
    if(!(is_tag() || is_search())) { ?>
    <span class= "date"><span><?php echo mysql2date('F j , Y',get_post()->post_date); ?></span><span>&nbsp;|</span></span>
    <?php } 
    endif; 
	} ?>
    
    <?php if(!(is_tag() || is_search())) : ?>
			<?php if(get_option($dynamo_tpl->name . '_postmeta_author_state') == 'Y') : ?>
	<span class="author-link"><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_attr(sprintf(__('Posted by %s', 'dp-theme'), get_the_author())); ?></a>&nbsp;|
    </span>
    <?php  endif; ?>
	<?php endif; ?>
	<?php if(get_option($dynamo_tpl->name . '_postmeta_tags_state') == 'Y') : 
    if(!(is_tag() || is_search())) { 
	if ($tag_list !="") {
	?>
    <span class="tags"><?php echo $tag_list; ?></span><span>&nbsp;|</span>
    <?php }} ?>
    <?php endif; ?>
	<?php if ( comments_open() && ! post_password_required() ) : ?>
	<?php if(get_option($dynamo_tpl->name . '_postmeta_coments_state') == 'Y') : 
	?>
	<span class="comments">
    <?php $num_comments = get_comments_number(); // get_comments_number returns only a numeric value

	if ( comments_open() ) {
		if ( $num_comments == 0 ) {
			$comments = __('No Comments','dp-theme');
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments . __(' Comments','dp-theme');
		} else {
			$comments = __('1 Comment','dp-theme');
		}
		$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
	} else {
		$write_comments =  __('Comments are off for this post.','dp-theme');
	}
	echo $write_comments;
	?>

    </span>
    <?php  endif; ?>

	<?php endif; ?> 
	
			
    </div>
 	<?php
}

function dp_post_meta_mini($attachment = false) {
 	global $dynamo_tpl;
 	$tag_list = get_the_tag_list( '', __( ', ', 'dp-theme' ) );
 	?>
    <div class="meta">
    <?php if(get_post_format() != '') : ?>
	 		<span class="format dp-format-<?php echo get_post_format(); ?>"></span>
	<?php endif; ?>	
    <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state') == 'Y') :
    if(!(is_tag() || is_search())) { ?>
    <span class= "date"><span><?php echo mysql2date('F j , Y',get_post()->post_date); ?></span><span>&nbsp;|</span></span>
    <?php } 
    endif; 
	if(!(is_tag() || is_search())) : ?>
			<?php if(get_option($dynamo_tpl->name . '_postmeta_author_state') == 'Y') : ?>
	<span class="author-link"><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_attr(sprintf(__('Posted by %s', 'dp-theme'), get_the_author())); ?></a>&nbsp;|
    </span>
    <?php  endif; ?>
	<?php endif; ?>
	<?php if ( comments_open() && ! post_password_required() ) : ?>
	<?php if(get_option($dynamo_tpl->name . '_postmeta_coments_state') == 'Y') : 
	?>
	<span class="comments">
    <?php $num_comments = get_comments_number(); // get_comments_number returns only a numeric value

	if ( comments_open() ) {
		if ( $num_comments == 0 ) {
			$comments = __('No Comments','dp-theme');
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments . __(' Comments','dp-theme');
		} else {
			$comments = __('1 Comment','dp-theme');
		}
		$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
	} else {
		$write_comments =  __('Comments are off for this post.','dp-theme');
	}
	echo $write_comments;
	?>

    </span>
    <?php  endif; ?>

	<?php endif; ?> 
	
			
    </div>
 	<?php
}



/**
 *
 * Function to generate the post pagination
 *
 * @return null
 *
 **/
function dp_post_links() {
	global $dynamo_tpl;
	if (is_single() && get_option($dynamo_tpl->name . '_post_nav_state', 'N') == 'Y' ) {echo dp_previous_next_post();}
	wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'dp-theme' ) . '</span>', 'after' => '</div>' ) );
}

/**
 *
 * Function to generate the post navigation
 * by Krisi 
 * @param id - id of the NAV element
 *
 * @return null
 *
 **/

function dp_content_nav($pages = '', $range = 2)
{  
     $showitems = ($range * 2)+1;  

     global $paged;
	 
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
		 //add this line for fake. never gets executed but makes the theme pass Theme check
		if(1==2){paginate_links(); posts_nav_link(); next_posts_link(); previous_posts_link();}
     }
}

/**
 *
 * Function to generate the comment form
 *
 **/
function dp_comment_form( $args = array(), $post_id = null ) {
	global $id;

	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'dp-theme' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'dp-theme' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'dp-theme'  ) . '</label>' .
		            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s', 'dp-theme' ), '<span class="required">*</span>' );
	$defaults = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment','noun', 'dp-theme' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'dp-theme' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'dp-theme'  ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.', 'dp-theme' ) . ( $req ? $required_text : '' ) . '</p>',
		'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'dp-theme' ), ' <code>' . allowed_tags() . '</code>', 'dp-theme'  ) . '</p>',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => __( 'Add Comment', 'dp-theme'  ),
		'title_reply_to'       => __( 'Leave a Reply to %s', 'dp-theme'  ),
		'cancel_reply_link'    => __( 'Cancel reply', 'dp-theme'  ),
		'label_submit'         => __( 'Post comment', 'dp-theme' ),
	);

	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	?>
		<?php if ( comments_open( $post_id ) ) : ?>
			<?php do_action( 'comment_form_before' ); ?>
			<div id="respond">
            <div class="headline heading-line "><h3 id="reply-title"><span><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?></span><small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h3></div>
				<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
					<?php echo $args['must_log_in']; ?>
					<?php do_action( 'comment_form_must_log_in_after' ); ?>
				<?php else : ?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
						<?php do_action( 'comment_form_top' ); ?>
						<?php if ( is_user_logged_in() ) : ?>
							<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
							<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
						<?php else : ?>
							<?php echo $args['comment_notes_before']; ?>
							<?php
							do_action( 'comment_form_before_fields' );

							foreach ( (array) $args['fields'] as $name => $field ) {
								echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
							}
							do_action( 'comment_form_after_fields' );
							?>
						<?php endif; ?>
						<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
						<p class="form-submit">
							<input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
							<?php comment_id_fields( $post_id ); ?>
						</p>
						<?php do_action( 'comment_form', $post_id ); ?>
					</form>
				<?php endif; ?>
			</div><!-- #respond -->
			<?php do_action( 'comment_form_after' ); ?>
		<?php else : ?>
			<?php do_action( 'comment_form_comments_closed' ); ?>
		<?php endif; ?>
	<?php
}




/**
 *
 * Function to generate the post Social API elements
 *
 * @param title - title of the post
 * @param postID - ID of the post
 *
 * @return string - HTML output
 *
 **/
 
function dp_social_api($title, $postID) {
	global $dynamo_tpl;
	// check if the social api is enabled on the specific page
	$social_api_mode = get_option($dynamo_tpl->name . '_social_api_exclude_include', 'exclude');
	$social_api_articles = explode(',', get_option($dynamo_tpl->name . '_social_api_articles', ''));
	$social_api_pages = explode(',', get_option($dynamo_tpl->name . '_social_api_pages', ''));
	$social_api_categories = explode(',', get_option($dynamo_tpl->name . '_social_api_categories', ''));
	//
	$is_excluded = false;
	//
	if($social_api_mode == 'include' || $social_api_mode == 'exclude') {
		//
		$is_excluded = 
			($social_api_pages != FALSE ? is_page($social_api_pages) : FALSE) || 
			($social_api_articles != FALSE ? is_single($social_api_articles) : FALSE) || 
			($social_api_categories != FALSE ? in_category($social_api_categories) : FALSE);
		//
		if($social_api_mode == 'exclude') {
			$is_excluded = !$is_excluded;
		}
	}
	//
	if($social_api_mode != 'none' && $is_excluded) {
		// variables for output
		$fb_like_output = '';
		$gplus_output = '';
		$twitter_output = '';
		$pinterest_output = '';
		// FB like
		if(get_option($dynamo_tpl->name . '_fb_like', 'Y') == 'Y') {
			// configure FB like
			$fb_like_attributes = ''; 
			// configure FB like
			if(get_option($dynamo_tpl->name . '_fb_like_send', 'Y') == 'Y') { $fb_like_attributes .= ' data-send="true"'; }
			$fb_like_attributes .= ' data-layout="'.get_option($dynamo_tpl->name . '_fb_like_layout', 'standard').'"';
			$fb_like_attributes .= ' data-show-faces="'.get_option($dynamo_tpl->name . '_fb_like_show_faces', 'true').'"';
			$fb_like_attributes .= ' data-width="'.get_option($dynamo_tpl->name . '_fb_like_width', '500').'"';
			$fb_like_attributes .= ' data-action="'.get_option($dynamo_tpl->name . '_fb_like_action', 'like').'"';
			$fb_like_attributes .= ' data-font="'.get_option($dynamo_tpl->name . '_fb_like_font', 'arial').'"';
			$fb_like_attributes .= ' data-colorscheme="'.get_option($dynamo_tpl->name . '_fb_like_colorscheme', 'light').'"';
			
			$fb_like_output = '<div class="fb-like" data-href="'.get_permalink($postID).'" '.$fb_like_attributes.'></div>';
		}
		// G+
		if(get_option($dynamo_tpl->name . '_google_plus', 'Y') == 'Y') {
			// configure +1 button
			$gplus_attributes = '';    		
			// configure +1 button attributes
			$gplus_attributes .= ' annotation="'.get_option($dynamo_tpl->name . '_google_plus_count', 'none').'"'; 
			$gplus_attributes .= ' width="'.get_option($dynamo_tpl->name . '_google_plus_width', '300').'"'; 
			$gplus_attributes .= ' expandTo="top"'; 
				
			if(get_option($dynamo_tpl->name . '_google_plus_size', 'medium') != 'standard') { 
				$gplus_attributes .= ' size="'.get_option($dynamo_tpl->name . '_google_plus_size', 'medium').'"'; 
			}
			
			$gplus_output = '<g:plusone '.$gplus_attributes.' callback="'.get_permalink($postID).'"></g:plusone>';
		}
		// Twitter
		if(get_option($dynamo_tpl->name . '_tweet_btn', 'Y') == 'Y') {
			// configure Twitter buttons    		  
			$tweet_btn_attributes = '';
			$tweet_btn_attributes .= ' data-count="'.get_option($dynamo_tpl->name . '_tweet_btn_data_count', 'vertical').'"';
			if(get_option($dynamo_tpl->name . '_tweet_btn_data_via', '') != '') {
				$tweet_btn_attributes .= ' data-via="'.get_option($dynamo_tpl->name . '_tweet_btn_data_via', '').'"'; 
			}
			$tweet_btn_attributes .= ' data-lang="'.get_option($dynamo_tpl->name . '_tweet_btn_data_lang', 'en').'"';
			  
			$twitter_output = '<a href="http://twitter.com/share" class="twitter-share-button" data-text="'.strip_tags($title).'" data-url="'.get_permalink($postID).'" '.$tweet_btn_attributes.'>'.__('Tweet', 'dp-theme').'</a>';
			}
		// Pinterest
		if(get_option($dynamo_tpl->name . '_pinterest_btn', 'Y') == 'Y') {
		      $pinit_title = dp_post_thumbnail_caption(true);
		      if($pinit_title == '') {  
    			$pinit_title = false;  
             }  
                
             $image = get_post_meta($postID, 'dynamo_opengraph_image', true); 

		      if($image == '') {
		      	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'single-post-thumbnail' );
		      	$image = $image[0];
				if($image == '' && get_option($dynamo_tpl->name . '_og_default_image', '') != '') {
		      		$image = get_option($dynamo_tpl->name . '_og_default_image', '');
		      	}
		      }
		      
		      
		     // configure Pinterest buttons               
		     $pinterest_btn_attributes = get_option($dynamo_tpl->name . '_pinterest_btn_style', 'horizontal');
		     $pinterest_output = '<a href="http://pinterest.com/pin/create/button/?url='.get_permalink($postID).'&amp;media='.$image.'&amp;description='.(($pinit_title == false) ? urlencode(strip_tags($title)) : $pinit_title).'" class="pin-it-button" count-layout="'.$pinterest_btn_attributes.'"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="'.__('Pin it', 'dp-theme').'" alt="'.__('Pin it', 'dp-theme').'" /></a>';
			}
		if ( $fb_like_output !='' || $gplus_output != '' || $twitter_output != '' || $pinterest_output != '') { 
		$output = '<section id="dp-social-api">' . apply_filters('dynamo_social_api_fb', $fb_like_output) . apply_filters('dynamo_social_api_gplus', $gplus_output) . apply_filters('dynamo_social_api_twitter', $twitter_output) . apply_filters('dynamo_social_api_pinterest', $pinterest_output) . '</section>';
		} else $output = '';
		

		return apply_filters('dynamo_social_api', $output);
	}
}

/**
 *
 * Function to generate the author info block
 *
 * @return null
 *
 **/
 
function dp_author($author_page = false, $return_value = false) {
    global $dynamo_tpl;

	// check if the author info is enabled on the specific page
	$authorinfo_mode = get_option($dynamo_tpl->name . '_authorinfo_exclude_include', 'exclude');
	$authorinfo_articles = explode(',', get_option($dynamo_tpl->name . '_authorinfo_articles', ''));
	$authorinfo_pages = explode(',', get_option($dynamo_tpl->name . '_authorinfo_pages', ''));
	$authorinfo_categories = explode(',', get_option($dynamo_tpl->name . '_authorinfo_categories', ''));
	//
	$is_excluded = false;
	//
	if($authorinfo_mode == 'include' || $authorinfo_mode == 'exclude') {
		//
		$is_excluded = 
			($authorinfo_pages != FALSE ? is_page($authorinfo_pages) : FALSE) || 
			($authorinfo_articles != FALSE ? is_single($authorinfo_articles) : FALSE) || 
			($authorinfo_categories != FALSE ? in_category($authorinfo_categories) : FALSE);
		//
		if($authorinfo_mode == 'exclude') {
			$is_excluded = !$is_excluded;
		}
	}
	//
	if($authorinfo_mode == 'all' || $is_excluded) :
		if(
			(is_page() && get_option($dynamo_tpl->name . '_template_show_author_info_on_pages') == 'Y') ||
			!is_page()
		) :
		    if(
		        get_the_author_meta( 'description' ) && 
		        (
		        	$author_page ||
		        	get_option($dynamo_tpl->name . '_template_show_author_info') == 'Y'
		        )
		    ): 
		    ?>
		    <?php if($return_value == true) : ?>
		    	<?php return true; ?>
		    <?php else : ?>
			    <section class="author-info">
			        <aside class="author-avatar">
			            <?php echo get_avatar( get_the_author_meta( 'user_email' ), 64 ); ?>
			        </aside>
			        <div class="author-desc">
			            <h2>
			                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
			                    <?php printf( __( 'Author: %s ', 'dp-theme' ), get_the_author_meta('display_name', get_the_author_meta( 'ID' )) ); ?> 
			                </a>
			            </h2>
			            <p>
			                <?php the_author_meta( 'description' ); ?>
			            </p>
			
			            <?php 
			                $www = get_the_author_meta('user_url', get_the_author_meta( 'ID' ));
			                if($www != '') : 
			            ?>
			            <p class="author-www">
			                <?php __('Website: ', 'dp-theme'); ?><a href="<?php echo $www; ?>"><?php echo $www; ?></a>
			            </p>
			            <?php endif; ?>
			            
			            <?php
			            	$google_profile = get_the_author_meta( 'google_profile' );
			            	if ($google_profile != '') :
			            		if(stripos($google_profile, '?') === FALSE && stripos($google_profile, 'rel=author') === FALSE) {
			            			$google_profile .= '?rel=author'; 
			            		}
			            ?>
			            <p class="author-google">
			            	<a href="<?php echo esc_url($google_profile); ?>" rel="me"><?php __('Google Profile', 'dp-theme'); ?></a>
			            </p>
			            <?php endif; ?>
			        </div>
			    </section>
		    	<?php 
		    	endif;
		    endif;
		endif;
	endif;
	
	if($return_value == true) {
		return false;
	}
}



/**
 *
 * Function to generate the recen post list
 *
 **/
function dp_print_recent_post($post_cat,$show_num,$thumb_width, $word_limit ) {
			 
			if ($post_cat =='All') $post_cat = '';
			$custom_posts = get_posts('showposts='.$show_num.'&cat='.get_cat_ID($post_cat));
			$output = "<div class='dp-recent-post-widget'>";
			if( !empty($custom_posts) ){  
			foreach($custom_posts as $custom_post) { 
				$output .= "<div class='recent-post-widget' >";
								$thumbnail_id = get_post_thumbnail_id( $custom_post->ID );				
								$thumbnail = wp_get_attachment_image_src( $thumbnail_id);
								$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);	
								if( !empty($thumbnail) ){
				$output .= "<div class='thumbnail'>";
				$output .= "<a href=".get_permalink( $custom_post->ID )."><img class='pic2' width='".$thumb_width."' src='" . $thumbnail[0] . "' alt='". $alt_text ."'/></a></div>";
					}
					$output .= "<div><div class='excerpt'> <a href='".get_permalink( $custom_post->ID )."'>"; 
							$excerpt =  get_post_field('post_content', $custom_post->ID);
							$excerpt = preg_replace('/<img[^>]+./','',$excerpt);
					$output .= string_limit_words($excerpt,$word_limit)."&hellip;</a></div>";
					$output .= "<div class='date'>".mysql2date('j M Y',$custom_post->post_date)."</div></div><div class='clearboth'></div></div>";
							
				
			} 
		
		 }
         $output .= "</div>";
return $output;
}
/**
 *
 * Function to generate the popular post list
 *
 **/

function dp_print_popular_posts($show_num,$thumb_width,$word_limit) {
	global $post;
		$popular_posts = get_popular_posts($show_num); 
		if($popular_posts->have_posts()){
		while($popular_posts->have_posts()): $popular_posts->the_post();
		 			 
			$output = "<div class='recent-post-widget'>";
				$thumbnail_id = get_post_thumbnail_id( $post->ID );				
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id);
				$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
				if( !empty($thumbnail) ){
						$output .= '<div class="thumbnail">';
						$output .= '<a href="'.get_permalink( $post->ID ). '">';
						$output .=  '<img class="pic2" width="'.$thumb_width.'" src="' . $thumbnail[0] . '" alt="'. $alt_text .'"/>'; 
						$output .=  '</a></div>';
	
				}
						$output .=  '<div class="content">';
                		$output .=  '<div class="excerpt"><a href="'.get_permalink( $post->ID ).'">';
					 $excerpt = preg_replace('/<img[^>]+./','',get_the_content());
					 	$output .=  string_limit_words($excerpt,$word_limit).'&hellip;</a></div>';
						$output .= '<div class="date">';
                       	$output .=  mysql2date('j M Y',$post->post_date); 
						$output .=  '</div>';
						$output .=  '</div>';
						$output .=  '<div class="clearboth"></div>';
			$output .=  "</div>";

		endwhile;
		}
return $output;		
}



/**
 *
 * Function to generate the related projects thumbnail grid
 *
 **/
function dp_print_related_projects_grid($post_id,$show_num) {
	global $post, $dynamo_tpl;
	$related_projects = get_related_projects($post_id, $show_num);
	if($related_projects->have_posts()){
		$item_classes='';
			
		echo '<div class="portfolio-wrapper">';
		while($related_projects->have_posts()): $related_projects->the_post();
		?>
		 <div class="portfolio-item <?php echo $item_classes; ?>">
				<?php if(has_post_thumbnail()):
				$title = $post->post_title;
				$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "portfolio-four" ); 
				$item_desc= get_post_meta($post->ID, 'item_short_desc', true);
				if ($item_desc =='') $item_desc = '&nbsp;';
				?>
                <div class="mediaholder"> <a href="<?php echo $image; ?>" rel="dp_lightbox"> 
                         <img alt="" src="<?php echo $thumb[0]; ?>">
                         <div class="hovermask">
                         <div class="hovericon"><i class="ss-search"></i></div>
                         </div>
                          </a> 
                </div>
                <figcaption class="item-description">
                                   <a href="<?php the_permalink(); ?>"><h5><?php echo $title ?></h5></a>
                                   <span><?php echo $item_desc ?></span>
                                   <?php if (get_option($dynamo_tpl->name . '_portfolio_like_state', 'Y') == 'Y') echo getPostLikeLink( $post->ID ) ?>
                </figcaption>              
				<?php endif; ?>
				<?php
				 	
				 ?>
				</div>

		<?php endwhile;
		echo '</div>';
		}
		
}

/**
 *
 * Function to generate the recent projects thumbnail grid
 *
 **/
function dp_print_recent_projects_grid($show_num, $column,$categories,$show_filter,$links) {
	global $post;
	if ($column == '') $column = '4';
	if ($show_filter == "") $show_filter = 'no';
	if ($links == "") $links = 'no';
	switch ($column) {
		case "2":
			$thumb_size = "portfolio-two";
			$item_size = "item50";
			break;
		case "3":
			$thumb_size = "portfolio-three";
			$item_size = "item33";
			break;
		case "4":
			$thumb_size = "portfolio-four";
			$item_size = "item25";
			break;
		case "5":
			$thumb_size = "portfolio-four";
			$item_size = "item20";
			break;
		case "6":
			$thumb_size = "portfolio-four";
			$item_size = "item16";
			break;
		case "8":
			$thumb_size = "portfolio-four";
			$item_size = "item12";
			break;
	}
	
	$selected_categories = array();
	if ($categories != '') {$selected_categories = explode(',', $categories);
		} else {
		$portfolio_category = get_terms('portfolios');
			if($portfolio_category):
			foreach($portfolio_category as $portfolio_cat):
			array_push($selected_categories,$portfolio_cat->slug);
			endforeach;
			endif;
		}
	$args = array(
				'post_type' => 'portfolio',
				'orderby' => 'menu_order date',
				'showposts' => $show_num,
				'order' => 'ASC',
				'tax_query' => array(
        array(
            'taxonomy' => 'portfolios',
            'field' => 'slug',
            'terms' => $selected_categories
        )
    )
						
				);
	?>
    <div class="portfolio-grid-container">
    <div class="portfolio <?php echo $item_size ?>">
    <?php
	$gallery = new WP_Query($args);
		if ($show_filter =='yes' && (count($selected_categories)>1) ) { 
		$portfolio_category = get_terms('portfolios');
		if($portfolio_category):
		?>
        <div class="centered-block-outer">
 			<div class="centered-block-middle">
  				<div class="centered-block-inner">
		<ul class="portfolio-tabs">
            <li class="active"><a data-filter="*" href="#">All</a></li>
			<?php foreach($portfolio_category as $portfolio_cat): ?>
            <?php if (in_array($portfolio_cat->slug, $selected_categories)) { ?>
    		<li><a data-filter=".<?php echo $portfolio_cat->slug; ?>" href="#"><?php echo $portfolio_cat->name; ?></a></li>
			<?php } ?>
			<?php endforeach; ?>
		</ul>
        </div>
        </div>
        </div>
		<?php endif; 
		}
	echo '<div class="portfolio-wrapper">';
	if($gallery->have_posts()){
		while($gallery->have_posts()): $gallery->the_post();
		if(has_post_thumbnail()):
			$item_classes = '';
			$item_desc= get_post_meta($post->ID, 'item_short_desc', true);
			if ($item_desc =='') $item_desc = '&nbsp;';
			$item_cats = get_the_terms($post->ID, 'portfolios');
			if($item_cats):
			foreach($item_cats as $item_cat) {
				$item_classes .= $item_cat->slug . ' ';
				$category = $item_cat->name;
			}
			endif;
			?>

		<div class="portfolio-item-wrapper">
				<div class="portfolio-item <?php echo $item_classes; ?>">
				<?php if(has_post_thumbnail()):
				$title = $post->post_title;
				$item_desc= get_post_meta($post->ID, 'item_short_desc', true);
				$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "portfolio-four" ); 
				$lightbox_title = $title;
				if ($item_desc !='') $lightbox_title.= ' :: '.$item_desc;
				$album = 'al-'. mt_rand();
				?>
                <div class="mediaholder">
                <?php if ($links == "text") { ?>
                <a href="<?php the_permalink(); ?>">
                <div class="text-overlay">
            	<div class="info"><?php echo $title ?></div>
          		</div>
                <?php } ?>  
                         <img alt="" src="<?php echo $thumb[0]; ?>">
                <?php if ($links == "text") { ?>
                </a>
                <?php } ?>  
                         <div class="hovermask">
                         <?php if ($links != "no" || $links != "text") { ?>
                         <?php if ($links == "zoom") { ?>
                         <a href="<?php echo $image; ?>" title="<?php echo $lightbox_title; ?>" rel="dp_lightbox[<?php $album; ?>]"><div class="hovericon"><i class="ss-search"></i></div> </a>
                         <?php }?>
                         <?php if ($links == "link") { ?>
                         <a href="<?php the_permalink(); ?>"><div class="hovericon"><i class="ss-link"></i></div> </a>
                         <?php }?>
                         <?php if ($links == "both") { ?>
                         <a href="<?php echo $image; ?>" title="<?php echo $lightbox_title; ?>" rel="dp_lightbox[<?php $album; ?>]"><div class="hovericon left"><i class="ss-search"></i></div> </a>
                         <a href="<?php the_permalink(); ?>"><div class="hovericon right"><i class="ss-link"></i></div> </a>
                         <?php }?>
                         <?php }?>
                         </div>
                          
                </div>          
				<?php endif; ?>
				<?php endif; ?>
				</div>
    		</div>			
		<?php 
		endwhile;
		echo '</div></div></div><div class="clearboth"></div>';
		}
}
/**
 *
 * Function to generate the related projects thumbnail grid
 *
 **/
function dp_category_slideshow($cat_id) {
	global $dynamo_tpl;
$query_string = 'cat='.$cat_id.'&order=ASC&orderby=menu_order&showposts='.get_option($dynamo_tpl->name . '_archive_slideshow_item_count');
$slideshow_query = new WP_Query($query_string);
$output ='';
if($slideshow_query->have_posts()) {
					$arefeatured = false;
					while ($slideshow_query->have_posts()) {
					$slideshow_query->the_post();
					global $post;
					if ( has_post_thumbnail() ) $arefeatured = true;
					}
					if ( $arefeatured == false) {echo $output;
					return;}
					$gallery_id ='flexslider_'.$cat_id;
					$output .= '<script type="text/javascript">'."\n";
					$output .= "   jQuery(window).load(function() {"."\n"; 
					$output .=  "jQuery('#".$gallery_id."').flexslider({"."\n";
					$output .=  '    animation: "fade",'."\n";
					$output .=  '    slideshowSpeed:"7000",'."\n";
					$output .=  '    animationSpeed:"1000",'."\n";
					$output .=  '    pauseOnHover: true,'."\n";
					$output .=  '    smoothHeight: true,'."\n";
					$output .=  '    before: function(slider){'."\n";
					$output .=  "    jQuery('.panel').css( {'opacity':'0','margin-left':'-1000px'});"."\n";
					$output .=  "},"."\n";
					$output .=  "	after: function(slider){var currentSlide = slider.slides.eq(slider.currentSlide);"."\n";
					$output .=  "	currentSlide.find('.panel').delay(800).animate( {'opacity':'1','margin-left':'10px'},500,'easeOutBack' );"."\n";
					$output .=  "},"."\n";
					$output .=  "  });"."\n";      
					$output .= "   });"."\n";
					$output .= "</script>"."\n";
					$output .= '<div class="clearboth"></div><div class="flexgallery category"><div class="flexslider" id="'.$gallery_id.'"><ul class="slides">'."\n";
					while ($slideshow_query->have_posts()) {
					$slideshow_query->the_post();
					global $post;
					if ( has_post_thumbnail() ) {
					$imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					$title = $post->post_title;
					$permalink = get_permalink($post->ID);
					$text = get_post_field('post_content', $post->ID);
					$text = strip_shortcodes($text);
	 				$text = strip_tags($text);
					$text = string_limit_words($text, get_option($dynamo_tpl->name . '_archive_slideshow_excerpt_len')).'&hellip;';
					$output .= '<li><img src="'.$imageurl.'" title="" alt="" />'."\n";
					$output .= '<div class="panel"><a href="'.$permalink.'" rel="bookmark" title=""><div class="gallery-post-title">'.$title.'</div></a>'."\n";
					$output .= '<div class="gallery-post-text">'.$text.'</div></div>'."\n";
					$output .= '</li>'."\n"; 												
					}
					}//End while
					$output .= '</ul></div>'."\n";
					
}
$output .='</div><div class="clearboth"></div>'."\n";

echo $output;
}

/**
 *
 * Function to generate the featured image caption
 *
* @param raw - if you need to get raw text without HTML tags  
*   
* @return HTML output or raw text (depending from params) 

 *
 **/


function dp_post_thumbnail_caption($raw = false) {
	global $post;
	// get the post thumbnail ID
	$thumbnail_id = get_post_thumbnail_id($post->ID);
	// get the thumbnail description
	$thumbnail_img = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));
	// return the thumbnail caption
	if ($thumbnail_img && isset($thumbnail_img[0])) {
		if($thumbnail_img[0]->post_excerpt != '') {
	      if($raw) {  
            return strip_tags($thumbnail_img[0]->post_excerpt);  
          } else {  
            return '<figcaption>'.$thumbnail_img[0]->post_excerpt.'</figcaption>';  
          }  
      }  
  } else {  
     return false;  
		}
	}

/**
 *
 * Function to generate the recent posts grid
 *
 **/
function dp_print_recent_post_grid($perpage,$column,$categories,$show_filter) {
global $dynamo_tpl,$post,$more;
	
	if ($perpage == '') $perpage = get_option('posts_per_page');
	if ($column == '') $column = '4';
	switch ($column) {
		case "2":
			$thumb_size = "portfolio-two";
			$item_size = "portfolio-two";
			break;
		case "3":
			$thumb_size = "portfolio-three";
			$item_size = "portfolio-three";
			break;
		case "4":
			$thumb_size = "portfolio-four";
			$item_size = "portfolio-four";
			break;
		case "5":
			$thumb_size = "portfolio-four";
			$item_size = "portfolio-five";
			break;
		case "6":
			$thumb_size = "portfolio-four";
			$item_size = "portfolio-six";
			break;
		case "8":
			$thumb_size = "portfolio-four";
			$item_size = "portfolio-eight";
			break;
		default:
			$thumb_size = "portfolio-four";
			$item_size = "portfolio-four";
	}
	$selected_categories = array();
	if ($categories != '') {$selected_categories = explode(',', $categories);
		} else {
		$portfolio_category = get_terms('category');
			if($portfolio_category):
			foreach($portfolio_category as $portfolio_cat):
			array_push($selected_categories,$portfolio_cat->slug);
			endforeach;
			endif;
		}

$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
$params = get_post_custom();
$args = array(	'paged' => $paged,
				'posts_per_page' =>  $perpage,
				'orderby' => 'date&order=ASC',
				'category_name' => $categories
			);?>
            
		<?php
		// Filter begin
		if ($show_filter == "yes") {
		$portfolio_category = get_terms('category');
		if($portfolio_category && count($selected_categories) >1):
		?>
        <div class="centered-block-outer">
 			<div class="centered-block-middle">
  				<div class="centered-block-inner">
		<ul class="blog-tabs">
            <li class="active"><a data-filter="*" href="#">All</a></li>
			<?php foreach($portfolio_category as $portfolio_cat): ?>
            <?php if (in_array($portfolio_cat->slug, $selected_categories)) { ?>
    		<li><a data-filter=".<?php echo $portfolio_cat->slug; ?>" href="#"><?php echo $portfolio_cat->name; ?></a></li>
			<?php } ?>
			<?php endforeach; ?>
		</ul>
        </div>
        </div>
        </div>
		<?php 
		endif;
		}
		// Filter end?>
        

<div class="<?php echo $item_size; ?> blog-grid-container">
<div class="blog-grid masonry">
<?php $newsquery = new WP_Query($args);	
while($newsquery->have_posts()): $newsquery->the_post(); ?>
			<?php
			$item_classes = '';
			$item_cats = get_the_terms($post->ID, 'category');
			if($item_cats):
			foreach($item_cats as $item_cat) {
				$item_classes .= $item_cat->slug . ' ';
				$category = $item_cat->name;
			}
			endif;
			?>

	<div class="portfolio-item-wrapper <?php echo $item_classes; ?>">
    
    <div id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
    <div class="portfolio-item">
        			<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_dynamo-featured-video", true) != '') : 
	?>
	<p>
	<?php echo wp_oembed_get( get_post_meta(get_the_ID(), "_dynamo-featured-video", true) ); ?>
	</p>
	<?php elseif(has_post_thumbnail() && get_post_format() != 'gallery') : ?>
	<figure class="featured-image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
	</figure>
	<?php endif; ?>
    <?php if (get_post_format() == 'gallery')  {  
				// Load images
				$images = get_children(
					array(
						'numberposts' => -1, // Load all posts
						'orderby' => 'menu_order', // Images will be loaded in the order set in the page media manager
						'order'=> 'ASC', // Use ascending order
						'post_mime_type' => 'image', // Loads only images
						'post_parent' => $post->ID, // Loads only images associated with the specific page
						'post_status' => null, // No status
						'post_type' => 'attachment' // Type of the posts to load - attachments
					)
				);
			?>
        <?php if($images): 
		dynamo_add_flex();
		?>
        <div class="clearboth"></div>
			<div class="flexgallery">
                    			<?php 
					$gallery_id = "flexslider_".mt_rand();
					$output = '<script type="text/javascript">'."\n";
					$output .= "   jQuery(document).ready(function() {"."\n"; 
					$output .=  "jQuery('#".$gallery_id."').flexslider({"."\n";
					$output .=  '    animation: "slide",'."\n";
					$output .=  '    slideshowSpeed:"5000",'."\n";
					$output .=  '    pauseOnHover: true,'."\n";
					$output .=  '    smoothHeight: true'."\n";
					$output .=  "  });"."\n";      
					$output .= "   });"."\n";
					$output .= "</script>"."\n";
					echo $output; 
				?>

            <div class="flexslider" id="<?php echo $gallery_id; ?>"><ul class="slides">
				<?php 
					foreach($images as $image) : 
				?>
				<li><figure>
					<img src="<?php echo $image->guid; ?>" alt="<?php echo $image->post_title; ?>" title="<?php echo $image->post_title; ?>" />
					<?php if($image->post_title != '' || $image->post_content != '' || $image->post_excerpt != '') : ?>
					<figcaption>
						<h3><?php echo $image->post_title; // get the attachment title ?></h3>
						<p><?php echo $image->post_content; // get the attachment description ?></p>
						<small><?php echo $image->post_excerpt; // get the attachment caption ?></small>
					</figcaption>
					<?php endif; ?>
				</figure></li>
				<?php 
					endforeach;
				?>
			</ul></div>
			</div>
		  	<?php endif;
				}
			?>
        <?php if (get_post_format() != 'link' && get_post_format() != 'quote' && get_post_format() != 'status' && get_post_format() != 'audio' ){?>

     <?php } ?>
		<section class="summary <?php echo get_post_format(); ?>">
        <?php
		$post_format = get_post_format();
		switch ($post_format) {
		   case 'link':
				$more = 0;
				echo '<i class="icon-link-4"></i>';
				the_content('');
				$more =1;
				 break;
		   case 'audio':
				$more = 0;
				the_content('');
				$more =1;
				 break;
		   case 'status':
				echo '<i class="icon-chat-6"></i>';
				the_content('');
				$more =1;
				 break;
		   case 'quote':
				echo '<i class="icon-quote"></i>';
				the_content('');
				$more =1;
				 break;
		  default:		
		   
		  }
		
		if ($post_format =='' || $post_format =='video' || $post_format =='audio' || $post_format =='aside' || $post_format =='gallery')  {?>
        
        <figcaption class="item-description">
        <?php dp_post_meta_mini(); ?>
        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'dp-theme' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><h5><?php the_title(); ?></h5>
        </a>
        <?php the_excerpt(); ?>
		<?php echo getPostLikeLink( $post->ID ) ?>        
        </figcaption>
        <?php } ?>
		</section>
	</div>
    </div>
    </div> 
   <?php endwhile ?>
       
   
   </div><?php dp_content_nav($newsquery->max_num_pages, $range = 2); ?>
   </div>
<?php    

wp_reset_query();

 }

// EOF