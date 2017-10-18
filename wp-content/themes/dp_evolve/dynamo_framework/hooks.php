<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Full hooks reference:
 * 
 * Hooks connected with the document:
 *
 * dynamowp_doctype
 * dynamowp_html_attributes
 * dynamowp_title
 * dynamowp_metatags
 * dynamowp_fonts
 * dynamowp_ie_scripts
 * dynamowp_head
 * dynamowp_body_attributes
 * dynamowp_footer
 * dynamowp_ga_code
 *
 * Hooks connected with the content:
 *
 * dynamowp_before_mainbody
 * dynamowp_after_mainbody
 * dynamowp_before_loop
 * dynamowp_after_loop
 * dynamowp_before_nav
 * dynamowp_after_nav
 * dynamowp_before_post_content
 * dynamowp_after_post_content
 * dynamowp_before_column
 * dynamowp_after_column
 * dynamowp_before_sidebar
 * dynamowp_after_sidebar
 *
 * Hooks connected with comments:
 * 
 * dynamowp_before_comments_count
 * dynamowp_after_comments_count
 * dynamowp_before_comments_list
 * dynamowp_after_comments_list
 * dynamowp_before_comment
 * dynamowp_after_comment
 * dynamowp_before_comments_form
 * dynamowp_after_comments_form
 *
 **/

/**
 *
 * Function used to generate the DOCTYPE of the document
 *
 **/

function dynamowp_doctype_hook() {
	// generate the HTML5 doctype
	echo '<!DOCTYPE html>' . "\n";
	
 	// YOUR HOOK CODE HERE
}

add_action('dynamowp_doctype', 'dynamowp_doctype_hook');

/**
 *
 * Function used to generate the DOCTYPE of the document
 *
 **/

function dynamowp_html_attributes_hook() {
	// generate the <html> language attributes
	global $dynamo_tpl;
	language_attributes();
	// generate the prefix attribute
	if(get_option($dynamo_tpl->name . '_opengraph_use_opengraph') == 'Y') {
                echo ' prefix="og: http://ogp.me/ns#"';
        }

	// generate the cache manifest attribute
	if(trim(get_option($dynamo_tpl->name . '_cache_manifest', '')) != '') {
		echo ' manifest="'.trim(get_option($dynamo_tpl->name . '_cache_manifest', '')).'"';
	}

 	// YOUR HOOK CODE HERE
}

add_action('dynamowp_html_attributes', 'dynamowp_html_attributes_hook');

/**
 *
 * Function used to generate the title content
 *
 **/

function dynamowp_title_hook() {
	// standard function used to generate the page title
	dp_title();
	
 	// YOUR HOOK CODE HERE
}

add_action('dynamowp_title', 'dynamowp_title_hook');

/**
 *
 * Function used to generate the metatags in the <head> section
 *
 **/

function dynamowp_metatags_hook() {
	global $dynamo_tpl; 
	
	// only for IE
	if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])) {
		echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge" />' . "\n"; 
	}
	echo '<meta charset="'.get_bloginfo('charset').'" />' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />' . "\n";
	
	// generates Dynamo SEO metatags
	dp_metatags();
	// generates Dynamo Open Graph metatags
	dp_opengraph_metatags();
	// generates Twitter Cards metatags
    dp_twitter_metatags();
 	// YOUR HOOK CODE HERE
}

add_action('dynamowp_metatags', 'dynamowp_metatags_hook');

/**
 *
 * Function used to generate the font rules in the <head> section
 *
 **/

function dynamowp_fonts_hook() {
	// generates Dynamo fonts
	dp_head_fonts();
	
 	// YOUR HOOK CODE HERE
}

add_action('dynamowp_fonts', 'dynamowp_fonts_hook');

/**
 *
 * Function used to generate scripts connected with the IE browser in the <head> section
 *
 **/

function dynamowp_ie_scripts_hook() {
	// generate scripts connected with IE9
	echo '<!--[if lt IE 9]>' . "\n";
	echo '<script src="'.dynamo_file_uri('js/html5shiv.js').'"></script>' . "\n";
	echo '<script src="'.dynamo_file_uri('js/respond.js').'"></script>' . "\n";
	echo '<![endif]-->' . "\n";
	
 	// YOUR HOOK CODE HERE
}

add_action('dynamowp_ie_scripts', 'dynamowp_ie_scripts_hook');

/**
 *
 * Function used to generate the code at the end of the <head> section
 * By default wp_head() should be called at the end od header section.
 * Sometimes, however, it is necessary to perform some action after the call wp_head (). 
 * For example, add custom css  overriding plugins css that have been not enqueued
 * Use this hook only when necessary 
 *
 **/
 
function dynamowp_plugin_css_override() {
		wp_register_style('dynamo-plugins-css-override', dynamo_file_uri('css/override-plugins.css')); 
    	wp_enqueue_style('dynamo-plugins-css-override');
}
add_action('wp_head', 'dynamowp_plugin_css_override', 6);

function dynamowp_responsive_css() { 
	global $dynamo_tpl;
	wp_register_style('desktop-small-css', get_template_directory_uri().'/css/desktop.small.css','','','(max-width:'.get_option($dynamo_tpl->name . '_theme_width', '1230').'px)');
	wp_enqueue_style('desktop-small-css');
	wp_register_style('tablet-css', get_template_directory_uri().'/css/tablet.css','','','(max-width:'.get_option($dynamo_tpl->name . '_tablet_width', '1030').'px)');
	wp_enqueue_style('tablet-css');
	wp_register_style('tablet-small-css', get_template_directory_uri().'/css/tablet.small.css','','','(max-width:'.get_option($dynamo_tpl->name . '_small_tablet_width', '820').'px)');
	wp_enqueue_style('tablet-small-css');
	wp_register_style('mobile-css', get_template_directory_uri().'/css/mobile.css','','','(max-width:'.get_option($dynamo_tpl->name . '_mobile_width', '580').'px)');
	wp_enqueue_style('mobile-css');
	}

add_action('wp_head', 'dynamowp_responsive_css', 5);

function dynamowp_dynamic_css() {
		$paths = wp_upload_dir();
		wp_register_style('dynamo-dynamic-css', $paths['baseurl'].'/dp-evolve-dynamic.css');  
    	wp_enqueue_style('dynamo-dynamic-css');   
}
add_action('wp_head', 'dynamowp_dynamic_css', 7);

/**
 *
 * Function used to generate the <body> element attributes
 *
 **/
 
function dynamowp_body_attributes_hook() {
 	global $dynamo_tpl;
 	
 	// generate the standard body class
 	body_class();
 	// generate the tablet attribute
 	if($dynamo_tpl->browser->get("tablet") == true) {
 		echo ' data-tablet="true"';
 	}
 	// generate the mobile attribute
 	if($dynamo_tpl->browser->get("mobile") == true) {
 		echo ' data-mobile="true"';
 	} 
 	// generate the table-width attribute
 	echo ' data-tablet-width="'. get_option($dynamo_tpl->name . '_tablet_width', 800) .'"';
 	
 	// YOUR HOOK CODE HERE
}

add_action('dynamowp_body_attributes', 'dynamowp_body_attributes_hook');
 
/**
 *
 * Function used to generate the code before the closing <body> tag
 *
 **/

function dynamowp_footer_hook() {
	// YOUR HOOK CODE HERE
}
  
add_action('dynamowp_footer', 'dynamowp_footer_hook');

/**
 *
 * Function used to generate the Google Analytics before the closing <body> tag
 *
 **/

function dynamowp_ga_code_hook() {
	global $dynamo_tpl;
	// check if the Tracking ID is specified
	if(get_option($dynamo_tpl->name . '_ga_ua_id', '') != '') {
		?>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '<?php echo get_option($dynamo_tpl->name . '_ga_ua_id', ''); ?>']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		<?php
	}
}
  
add_action('dynamowp_ga_code', 'dynamowp_ga_code_hook');

add_action( 'wp', 'dynamo_check_category' );
function dynamo_check_category() {
    if ( is_category() )
        add_action( 'wp_enqueue_scripts', 'dynamo_add_flex' );
}

function dynamo_add_flex() {
	wp_enqueue_script( 'flexslider-js' );
}

function extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="slideshow"><?php __('Display slideshow?', 'dp-theme'); ?></label></th>
<td>
<select id="Cat_meta[slideshow]" name="Cat_meta[slideshow]">
					<option value="yes"<?php echo ($cat_meta['slideshow']=='yes') ? ' selected="selected"' : ''; ?>>
						<?php __('Yes', 'dp-theme'); ?>
					</option>
					<option value="no"<?php echo ($cat_meta['slideshow']=='no') ? ' selected="selected"' : ''; ?>>
						<?php __('No', 'dp-theme'); ?>
					</option>
				</select>
            <span class="description"><?php __('By default bellow category title is displayed featured images slideshow. Here can be disabled for this category.', 'dp-theme'); ?></span>
        </td>
</tr>
<?php
}
add_action ( 'edit_category_form_fields', 'extra_category_fields');

function save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}
add_action ( 'edited_category', 'save_extra_category_fileds'); 
/**
 * 
 * 
 * 
 * 
 * WP Core actions 
 *
 *
 *
 *
 **/


/**
 *
 * Function used to generate the custom RSS feed link
 *
 **/


function dynamowp_custom_rss_feed_url( $output, $feed ) {
    global $dynamo_tpl;
    // get the new RSS URL
    $feed_link = get_option($dynamo_tpl->name . '_custom_rss_feed', '');
    // check the URL
    if(trim($feed_link) !== '') {
	    if (strpos($output, 'comments')) {
	        return $output;
	    }


	    return esc_url($feed_link);
    } else {
    	return $output;
    }
}


add_action( 'feed_link', 'dynamowp_custom_rss_feed_url', 10, 2 ); 
// EOF