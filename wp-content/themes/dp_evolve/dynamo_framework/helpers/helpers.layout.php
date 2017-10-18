<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Layout functions
 *
 * Group of functions used in the layout - help to create the page structure
 *
 **/
 
/**
 *
 * Function used to load specific layout parts
 *
 * @return null
 *
 **/
function dp_load($part_name, $assets = null, $args = null) {	
	$assets_output = '';
	
	if($assets !== null) {
		foreach($assets as $key => $value) {
			if($key == 'css') {
				$assets_output .= '<link rel="stylesheet" type="text/css" href="'.$value.'" />' . "\n";
			} elseif($key == 'js') {
				$assets_output .= '<script type="text/javascript" src="'.$value.'"></script>' . "\n";
			}
		}
	}

	include(dynamo_file('layouts/' . $part_name . '.php'));

	if ($part_name = 'header') {
	    do_action( 'get_header', $part_name );
	}

}
 
/**
 *
 * Function used to generate the template full title
 *
 * @return null
 *
 **/
function dp_title() {
	// The $paged global variable contains the page number of a listing of posts.
	// The $page global variable contains the page number of a single post that is paged.
	// We'll display whichever one applies, if we're not looking at the first page.
	global $paged, $page;
	// access to the template object
	global $dynamo_tpl;
	// check if the page is a search result
	if ( is_search() ) {
		// If we're a search, let's start over:
		$title = sprintf( __( 'Search results for %s', 'dp-theme' ), '"' . get_search_query() . '"' );
		// Add a page number if we're on page 2 or more:
		if ( $paged >= 2 ) {
			$title .= " " . sprintf( __( 'Page %s', 'dp-theme' ), $paged );
		}
		// return the title			
		echo $title;
	}
	// if user enabled our SEO override
	if(get_option($dynamo_tpl->name . '_seo_use_dp_seo_settings') == 'Y') {
		// get values from panel if enabled
		$desc = get_option($dynamo_tpl->name . '_seo_description');
		$blogname = get_option($dynamo_tpl->name . '_seo_blogname');
		// create the first part of the title
		$prepared = str_replace_once(get_bloginfo( 'name', 'Display' ), '', wp_title('', false));
		$title = is_front_page() ? $blogname : $prepared;
		// return first part with site name without space characters at beginning
		if(get_option($dynamo_tpl->name . '_seo_use_blogname_in_title') == 'Y') {
			echo $blogname;
			echo ' ' . get_option($dynamo_tpl->name . '_seo_separator_in_title') . ' '; } 
		
			echo $desc;
		
	} else { // in other case
		// return the standard title
		if(is_home()) { 
			bloginfo('name');
			echo ' &raquo; '; 
			bloginfo('description');
		} else { 
			wp_title( '|', true, 'right' );
		}
	}
}

/**
 *
 * Function used to generate the template blog name
 *
 * @return string
 *
 **/
function dp_blog_name() {
	// access to the template object
	global $dynamo_tpl;
	// if user enabled our SEO override
	if(get_option($dynamo_tpl->name . '_seo_use_dp_seo_settings') == 'Y') {
		// blog name from template SEO options
		return get_option($dynamo_tpl->name . '_seo_description');
	} else { // in other case
		// output standard blog name
		return get_bloginfo( 'name' );
	}
}

/**
 *
 * Function used to generate the template description
 *
 * @return string
 *
 **/
function dp_blog_desc() {
	// access to the template object
	global $dynamo_tpl;
	// if user enabled our SEO override
	if(get_option($dynamo_tpl->name . '_seo_use_dp_seo_settings') == 'Y') {
		// description from template SEO options
		return get_option($dynamo_tpl->name . '_seo_blogname');
	} else { // in other case
		// output standard blog description
		return get_bloginfo( 'description' );
	}
}

/**
 *
 * Function used to generate a Logo image based on the branding options
 *
 * @return null
 *
 **/
function dp_blog_logo() {
	// access to the template object
	global $dynamo_tpl;
	// variable for the logo text
	$logo_text = '';
	// check the logo image type:
	if(get_option($dynamo_tpl->name . "_branding_logo_type", 'css') == 'image') {
		// check the logo text type
			$logo_text = get_option($dynamo_tpl->name . "_branding_logo_text_value", '') . ' - ' . get_option($dynamo_tpl->name . "_branding_logo_slogan_value", '');
		// return the logo output
		echo '<img src="'.get_option($dynamo_tpl->name . "_branding_logo_image", '').'" alt="' . $logo_text . '" />';
	} else { // text logo
		// get the logo text type
			$logo_text = get_option($dynamo_tpl->name . "_branding_logo_text_value", '') . '<small>' . get_option($dynamo_tpl->name . "_branding_logo_slogan_value", '') . '</small>';
		// return the logo output
		echo $logo_text;
	}
}

/**
 *
 * Function used to generate the template metatags
 *
 * @return null 
 *
 **/
function dp_metatags() {
	// access to the template object
	global $dynamo_tpl;
	// check if the SEO settings are enabled
	if(get_option($dynamo_tpl->name . '_seo_use_dp_seo_settings') == 'Y') {
		if(is_home() || is_front_page()) {
			if(get_option($dynamo_tpl->name . '_seo_homepage_desc') == 'custom') {
				echo '<meta name="description" content="'.get_option($dynamo_tpl->name . '_seo_homepage_desc_value').'" />';
			}
			
			if(get_option($dynamo_tpl->name . '_seo_homepage_keywords') == 'custom') {
				echo '<meta name="keywords" content="'.get_option($dynamo_tpl->name . '_seo_homepage_keywords_value').'" />';
			}
		}
		
		if(is_singular()) {
			global $wp_query;
			$postID = $wp_query->post->ID;
		
			if(get_post_meta($postID, 'dynamo-post-desc', true) != '') {
				if(get_option($dynamo_tpl->name . '_seo_post_desc') == 'custom') {
					echo '<meta name="description" content="'.get_post_meta($postID, 'dynamo-post-desc',true).'" />';
				}
			}
			 			
			if(get_post_meta($postID, 'dynamo-post-keywords', true) != '') {
				if(get_option($dynamo_tpl->name . '_seo_post_keywords') == 'custom') {
					echo '<meta name="keywords" content="'.get_post_meta($postID, 'dynamo-post-keywords',true).'" />';
				}
			}
		}
	}
}

/**
 *
 * Function used to generate the template Open Graph tags
 *
 * @return null
 *
 **/
function dp_opengraph_metatags() {
	// access to the template object
	global $dynamo_tpl;
	// check if the Open Graph is enabled
	if(get_option($dynamo_tpl->name . '_opengraph_use_opengraph') == 'Y') {
		if(is_single() || is_page()) {
			global $wp_query;
			//
			$postID = $wp_query->post->ID;
			//
			$title = get_post_meta($postID, 'dynamo_opengraph_title', true);
			$type = get_post_meta($postID, 'dynamo_opengraph_type', true);
			$image = wp_get_attachment_url(get_post_meta($postID, 'dynamo_opengraph_image', true));


			if($image == '') {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'single-post-thumbnail' );
				$image = $image[0];
			}


			$desc = get_post_meta($postID, 'dynamo_opengraph_desc', true);
			$other = get_post_meta($postID, 'dynamo_opengraph_other', true);
			//
			echo apply_filters('dynamo_og_title', '<meta name="og:title" content="'.(($title == '') ? esc_html($wp_query->post->post_title) : $title).'" />' . "\n");
			//
			if($image != '') {
				echo apply_filters('dynamo_og_image', '<meta name="og:image" content="'.$image.'" />' . "\n");
			} elseif(get_option($dynamo_tpl->name . '_og_default_image', '') != '') {
				echo apply_filters('dynamo_og_image', '<meta name="og:image" content="'.get_option($dynamo_tpl->name . '_og_default_image', '').'" />' . "\n");
			}
			//
			echo apply_filters('dynamo_og_type', '<meta name="og:type" content="'.(($type == '') ? 'article' : $type).'" />' . "\n");
			//
			echo apply_filters('dynamo_og_description', '<meta name="og:description" content="'.(($desc == '') ? substr(str_replace("\"", '', strip_tags($wp_query->post->post_content)), 0, 200) : $desc).'" />' . "\n");
			//
			echo apply_filters('dynamo_og_url', '<meta name="og:url" content="'.get_permalink($postID).'" />' . "\n");
			//
			if($other != '') {
				$other = preg_split('/\r\n|\r|\n/', $other);
				//
				foreach($other as $item) {
					//
					$item = explode('=', $item);
					//	
					if(count($item) >= 2) {
						echo apply_filters('dynamo_og_custom', '<meta name="'.$item[0].'" content="'.$item[1].'" />' . "\n");
					}
				}
			}
		}
	}
}
/**
 *
 * Function used to generate the TwitterCards tags
 *
 * @return null
 *
 **/
function dp_twitter_metatags() {
        // access to the template object
        global $dynamo_tpl;
        // check if the Twitter Cards option is enabled
        if(get_option($dynamo_tpl->name . '_twitter_cards') == 'Y') {
                if(is_single() || is_page()) {
                        global $wp_query;
                        //
                        $postID = $wp_query->post->ID;
                        //
                        $title = get_post_meta($postID, 'dynamo_twitter_title', true);
                        $image = wp_get_attachment_url(get_post_meta($postID, 'dynamo_twitter_image', true));
                        
                        if($image == '') {
                                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'single-post-thumbnail' );
                                $image = $image[0];
                        }
                        
                        $desc = get_post_meta($postID, 'dynamo_twitter_desc', true);
                        
                        $site_default = get_option($dynamo_tpl->name . '_twitter_site');
                        $creator_default = get_option($dynamo_tpl->name . '_twitter_creator');
                        $site = get_post_meta($postID, 'dynamo_twitter_site', true);
                        $creator = get_post_meta($postID, 'dynamo_twitter_creator', true);
                        
                        if($site_default != '') {
                                $site = $site_default;
                        }
                        
                        if($creator_default != '') {
                                $creator = $creator_default;
                        }
                        
                        echo apply_filters('dynamo_twitter_card', '<meta name="twitter:card" content="summary" />' . "\n");        
                        //
                        echo apply_filters('dynamo_twitter_url', '<meta name="twitter:url" content="'.get_permalink($postID).'" />' . "\n");
                        //                
                        echo apply_filters('dynamo_twitter_title', '<meta name="twitter:title" content="'.(($title == '') ? $wp_query->post->post_title : $title).'" />' . "\n");
                        //
                        if($image != '') {
                                echo apply_filters('dynamo_twitter_image', '<meta name="twitter:image" content="'.$image.'" />' . "\n");
                        }
                        echo apply_filters('dynamo_twitter_description', '<meta name="twitter:description" content="'.(($desc == '') ? substr(str_replace("\"", '', strip_tags($wp_query->post->post_content)), 0, 200) : $desc).'" />' . "\n");
                        //
                        echo apply_filters('dynamo_twitter_site', '<meta name="twitter:site" content="' . $site . '" />' . "\n");
                        //
                        echo apply_filters('dynamo_twitter_creator', '<meta name="twitter:creator" content="' . $creator . '" />' . "\n");
                }
        }
}
/**
 *
 * Function used to check if menu should be displayed
 *
 * @param name - name of the menu to check
 *
 * @return bool
 *
 **/
function dp_show_menu($name) {
	global $dynamo_tpl;
	
	// check if specific theme_location has assigned menu
	if (has_nav_menu($name)) {
		// if yes - please check menu confition function
		$conditional_function = false;
		
		if($dynamo_tpl->menu[$name]['state_rule'] != '') {
			$conditional_function = create_function('', 'return '.$dynamo_tpl->menu[$name]['state_rule'].';');
		}
		
		if(
			$dynamo_tpl->menu[$name]['state'] == 'Y' ||
			(
				$dynamo_tpl->menu[$name]['state'] == 'rule' && $conditional_function()
			)
		) {
			return true;
		} else {
			return false;
		}
	} else {
		// if there is no assigned menu for specific theme_location
		return false;
	}
}

/**
 *
 * Function used to generate some template settings
 *
 * @return null
 *
 **/
function dp_head_config() {
	// access the main template object
	global $dynamo_tpl;
	// output the start script tag
	echo "<script type=\"text/javascript\">\n";
	echo "           \$DP_PAGE_URL = '".home_url()."';\n";
	echo "           \$DP_TMPL_URL = '".dynamo_file_uri(false)."';\n";
	echo "           \$DP_TMPL_NAME = '".$dynamo_tpl->name."';\n";
	echo "           \$DP_MENU = [];\n";
	// output the menu config
	foreach($dynamo_tpl->menu as $menuname => $settings) {
		echo "           \$DP_MENU[\"".$menuname."\"] = [];\n";
		echo "           \$DP_MENU[\"".$menuname."\"][\"animation\"] = \"".$settings['animation']."\";\n";
		echo "           \$DP_MENU[\"".$menuname."\"][\"animation_speed\"] = \"".$settings['animation_speed']."\";\n";
	}
	echo "           \$DP_TABLET_WIDTH = '". get_option($dynamo_tpl->name . '_tablet_width', 800)."';\n";
	echo "           \$DP_LAYOUT = '". get_option($dynamo_tpl->name . '_page_wrap_state')."';\n";
	echo "           \$DP_STICKY_HEADER = '". get_option($dynamo_tpl->name . '_sticky_header__state')."';\n";
	// output the finish script tag
	echo "        </script>\n";
}

/**
 *
 * Function used to check if breadcrumbs should be displayed
 *
 * @return bool
 *
 **/
function dp_show_breadcrumbs() {
	// access the template object
	global $dynamo_tpl;
	
	$conditional_function = false;
	
	if(get_option($dynamo_tpl->name . '_breadcrumbs_state', 'Y') == 'rule') {
		$state_rule = str_replace('\&#039;', "'", get_option($dynamo_tpl->name . '_breadcrumbs_staterule', ''));
		$is_correct = preg_match("@^[a-zA-Z0-9\_\s\(\)'\"\-&|!]+$@ms", $state_rule);

		if($is_correct) {
			$conditional_function = create_function('', 'return '. $state_rule .';');
		} else {
			$conditional_function = create_function('', 'return FALSE;');
		}
	}
	
	return (get_option($dynamo_tpl->name . '_breadcrumbs_state', 'Y') == 'Y' || 
		(get_option($dynamo_tpl->name . '_breadcrumbs_state', 'Y') == 'rule' && $conditional_function()));
}

/**
 *
 * Function used to generate the breadcrumbs output
 *
 * @return null
 *
 **/
function dp_breadcrumbs_output() {
	global $post;
	// open the breadcrumbs tag
	$output = '<div class="dp-breadcrumbs">';
	// check if we are on the post or normal page
	if (!is_home()) {
		// return the Home link
		$output .= '<a href="' . home_url() . '" class="dp-home">' . apply_filters('dynamo_breadcrumb_home', get_bloginfo('name')) . "</a>";
		// if page is category or post
		if (is_category() || is_singular()) {
			// return the category link
			$output .= get_the_category_list(' ');
			// if it is a subpage
            if (is_page() && $post->post_parent ) {
            $output .= '<a href="' . get_permalink($post->post_parent) . '">' . get_the_title($post->post_parent) . '</a>';        
            }
			// if it is a post page
			if (is_singular()) {
				// return link the name of current post
				$output .= '<span class="dp-current">' . get_the_title() . '</span>';
			}			
		// if it is a normal page
		} elseif (is_page()) { 
			// output the page name
			$output .= get_the_title('<span class="dp-current">', '</span>');
		} elseif (is_tag() && isset($_GET['tag'])) {
			// output the tag name
			$output .= '<span class="dp-current">' . __('Tag: ', 'dp-theme') . strip_tags($_GET['tag']) . '</span>';
		} elseif (is_author() && isset($_GET['author'])) {
			// get the author name
			$id = strip_tags($_GET['author']);
			if(is_numeric($id)) {
				// output the author name
				$output .= '<span class="dp-current">' . __('Published by: ', 'dp-theme') . get_the_author_meta('display_name', $id) . '</span>';
			}
		} elseif(is_404()) {
			$output .= '<span class="dp-current">' . __('Page not found', 'dp-theme') . '</span>';
		} elseif(is_archive()) {
			$output .= '<span class="dp-current">' . __('Archives', 'dp-theme') . '</span>';
		} elseif(is_search() && isset($_GET['s'])) {
			// output the author name
			$output .= '<span class="dp-current">' . __('Searching for: ', 'dp-theme') . strip_tags($_GET['s']) . '</span>';
		}
	// if the page is a home
	} else {
		// output the home link only
		$output .= '<a href="' . home_url() . '" class="dp-home">' . get_bloginfo('name') . "</a>";
	}
	// close the breadcrumbs container
	$output .= '</div>';


	echo apply_filters('dynamo_breadcrumb', $output);
}

/**
 *
 * Function used to create url to the template style CSS files
 *
 * @return null
 *
 **/
function dp_head_style_css() {
	// get access to the template object
	global $dynamo_tpl;
	// get access to the WP Customizer
	global $wp_customize;
	// iterate through template styles
	for($i = 0; $i < count($dynamo_tpl->styles); $i++) {
		// get the value for the specific style
		$stylevalue = get_option($dynamo_tpl->name . '_template_style_' . $dynamo_tpl->styles[$i], '');
		// find an url for the founded style
		if ($stylevalue!='') { 
		$url = $dynamo_tpl->style_colors[$dynamo_tpl->styles[$i]][$stylevalue];
		// if the customizer is enabled - not use the Cookies to set the styles
		// if the cookies works then the style switcher in the back-end won't work
		if(isset($wp_customize) && $wp_customize->is_preview()) {
			$url = esc_attr($url);
		} else { // when the page isn't previewed
		
			$url = esc_attr(isset($_COOKIE[$dynamo_tpl->name.'_style']) ? $_COOKIE[$dynamo_tpl->name.'_style'] : $url);
		}
		
		// output the LINK element
		wp_enqueue_style('dynamo-style', dynamo_file_uri('css/' . $url));
		}
	}
}
add_action('wp_head', 'dp_head_style_css', 7);


/**
 *
 * Function used to create font CSS rules
 *
 * @return HTML output
 *
 **/
function dp_head_fonts() {
	global $dynamo_tpl;

	$output = "<style type=\"text/css\">\n";

	for($i = 0; $i < count($dynamo_tpl->fonts); $i++) {
		$selectors = get_option($dynamo_tpl->name . '_fonts_selectors_' . $dynamo_tpl->fonts[$i]['short_name'], '');
		$type = get_option($dynamo_tpl->name . '_fonts_type_' . $dynamo_tpl->fonts[$i]['short_name'], 'normal');
		$normal = get_option($dynamo_tpl->name . '_fonts_normal_' . $dynamo_tpl->fonts[$i]['short_name'], '');
		$squirrel = get_option($dynamo_tpl->name . '_fonts_squirrel_' . $dynamo_tpl->fonts[$i]['short_name'], '');
		$google = get_option($dynamo_tpl->name . '_fonts_google_' . $dynamo_tpl->fonts[$i]['short_name'], '');
		$edgefonts = get_option($dynamo_tpl->name . '_fonts_edgefonts_' . $dynamo_tpl->fonts[$i]['short_name'], '');
		
		if(trim($selectors) != '') {
			$font_family = "";
			
			if($type == 'normal') {
				$normal = str_replace(
				                    array(
				                        "Times New Roman",
				                        "Trebuchet MS",
				                        "Arial Black",
				                        "Palatino Linotype",
				                        "Book Antiqua",
				                        "Lucida Sans Unicode",
				                        "Lucida Grande",
				                        "MS Serif",
				                        "New York",
				                        "Comic Sans MS",
				                        "Courier New",
				                        "Lucida Console",
				                    ),
				                    array(
				                        "'Times New Roman'",
				                        "'Trebuchet MS'",
				                        "'Arial Black'",
				                        "'Palatino Linotype'",
				                        "'Book Antiqua'",
				                        "'Lucida Sans Unicode'",
				                        "'Lucida Grande'",
				                        "'MS Serif'",
				                        "'New York'",
				                        "'Comic Sans MS'",
				                        "'Courier New'",
				                        "'Lucida Console'",
				                    ),
				                    $normal
				                );
			
				$font_family = str_replace('\&#039;', "'", $normal);
			} else if($type == 'squirrel') {				
				wp_enqueue_style('dynamo-fonts-' . $i, dynamo_file_uri('fonts/' . $squirrel . '/stylesheet.css'));
				$font_family = "'" . $squirrel . "'";
			} else if($type == 'google'){
				$fname = array();
				preg_match('@family=(.+)$@is', $google, $fname);
				if(!count($fname)) {
					preg_match('@family=(.+):.+@is', $google, $fname);
				} 
				
				if(!count($fname)) {
					preg_match('@family(.+)\|.+@is', $google, $fname);
				}
				
				$font_family = "'" . str_replace('+', ' ', preg_replace('@:.+@', '', preg_replace('@&.+@', '', $fname[1]))) . "'";
				// We are providing the protocol to avoid duplicated downloads on IE7/8
				$google = ($dynamo_tpl->isSSL) ? str_replace('http://', 'https://', $google) : $google;
				
				echo '<link href="'.$google.'" rel="stylesheet" type="text/css" />';
			} else {
				$fname = array();
				preg_match('@use.edgefonts.net/(.+)(\.js|:(.+)\.js)$@is', $edgefonts, $fname);
				
				$font_family = $fname[1];
				// We are providing the protocol to avoid duplicated downloads on IE7/8
				$edgefonts = ($dynamo_tpl->isSSL) ? str_replace('http://', 'https://', $edgefonts) : $edgefonts;
				
				echo '<script src="'.$edgefonts.'"></script>';
			}
			
			$output .= str_replace(array('\\', '&quot;', '&apos;', '&gt;'), array('', '"', '\'', '>'), $selectors) . " { font-family: " . $font_family . "; }\n\n";
		}
	}
	
	$output .= "</style>\n";
	
	echo $output;
}

/**
 *
 * Function used to create links to stylesheets and script files for specific pages
 *
 * @return HTML output
 *
 **/
function dp_head_style_pages() {
	// get access to the template object
	global $dynamo_tpl;
	// scripts for the contact page
	if( is_page_template('contact.php') ){ 		
		wp_enqueue_script('dynamo-contact-validate', dynamo_file_uri('js/jquery.validate.min.js'), array('jquery', 'dynamo-scripts'), false, true);
		wp_enqueue_script('dynamo-contact-main', dynamo_file_uri('js/contact.js'), array('jquery', 'dynamo-scripts'), false, true);
	}
}

/**
 *
 * Function used to create conditional string
 *
 * @param mode - mode of the condition - exclude, all, include
 * @param input - input data separated by commas, look into example inside the function
 * @param users - the value of the user access
 *
 * @return HTML output
 *
 **/
function dp_condition($mode, $input, $users) {
	// Example input:
	// homepage,page:12,post:10,category:test,tag:test
	
	$output = ' (';
	if($mode == 'all') {
		$output = '';
	} else if($mode == 'exclude') {
		$output = ' !(';
	}
	
	if($mode != 'all') {
		$input = preg_replace('@[^a-zA-Z0-9\-_,;\:\.\s]@mis', '', $input); 
		$input = substr($input, 1);
		$input = explode(',', $input);
		
		for($i = 0; $i < count($input); $i++) {
			if($i > 0) {
				$output .= '||'; 
			}
			
                        if(stripos($input[$i], 'homepage') !== FALSE) {
                            $output .= ' is_home() ';
                        } else if(stripos($input[$i], 'page:') !== FALSE) {
                            $output .= ' is_page(\'' . substr($input[$i], 5) . '\') ';
                        } else if(stripos($input[$i], 'post:') !== FALSE) {
                            $output .= ' is_single(\'' . substr($input[$i], 5) . '\') ';
                        } else if(stripos($input[$i], 'category:') !== FALSE) {
                            $output .= ' (is_category(\'' . substr($input[$i], 9) . '\') || (in_category(\'' . substr($input[$i], 9) . '\') && is_single())) ';
						} else if(stripos($input[$i], 'category_descendant:') !== FALSE) {
                                $output .= ' (is_category(\'' . substr($input[$i], 20) . '\') || (in_category(\'' . substr($input[$i], 20) . '\') || post_is_in_descendant_category( \'' . substr($input[$i], 20) . '\' ) && !is_home())) ';
                        } else if(stripos($input[$i], 'tag:') !== FALSE) {
                            $output .= ' (is_tag(\'' . substr($input[$i], 4) . '\') || (has_tag(\'' . substr($input[$i], 4) . '\') && is_single())) ';
                        } else if(stripos($input[$i], 'archive') !== FALSE) {
                            $output .= ' is_archive() ';
                        } else if(stripos($input[$i], 'author:') !== FALSE) {
                            $output .= ' (is_author(\'' . substr($input[$i], 7) . '\') && is_single()) ';
                    } else if(stripos($input[$i], 'template:') !== FALSE) {
                        if(substr($input[$i], 9) != '') {
                                       $output .= ' (is_page_template(\'' . substr($input[$i], 9) . '.php\') && is_singular()) ';
                               } else {
                                       $output .= ' (is_page_template() && is_singular()) ';
                               }
                } else if(stripos($input[$i], 'format:') !== FALSE) {
                        if(substr($input[$i], 7 != '')) {
                            $output .= ' (has_term( \'post_format\', \'post-format-' . substr($input[$i], 7) . '\') && is_single()) ';
                    } else {
                            $output .= ' (has_term( \'post_format\') && is_single()) ';
                    }
                        } else if(stripos($input[$i], 'taxonomy:') !== FALSE) {
                            if(substr($input[$i], 9) != '') {
                                    $taxonomy = substr($input[$i], 9);
                                    $taxonomy = explode(';', $taxonomy);
                                    // check amount of taxonomies
                                    if(count($taxonomy) == 1) {
                                                   $output .= ' (is_tax(\'' . $taxonomy[0] . '\'))';
                                           } else if(count($taxonomy) == 2) {
                                                   $output .= ' (is_tax(\'' . $taxonomy[0] . '\', \'' . $taxonomy[1] . '\')) ';
                                           }
                                   }
                        } else if(stripos($input[$i], 'posttype:') !== FALSE) {
                            if(substr($input[$i], 9) != '') {
                                    $type = substr($input[$i], 9);
                                    // check for post types
                                    if($type != '') {
                                                   $output .= ' (get_post_type() == \'' . $type . '\' && is_single()) ';
                                           }
                                   }
                        } else if(stripos($input[$i], 'search') !== FALSE) {
                            $output .= ' is_search() ';
                        } else if(stripos($input[$i], 'page404') !== FALSE) {
                            $output .= ' is_404() ';
                        }
                }


                $output .= ')';
        }

	if($users != 'all') {
		if($users == 'guests') {
			$output .= (($output == '') ? '' : ' && ') . ' !is_user_logged_in()';
		} else if($users == 'registered') {
			$output .= (($output == '') ? '' : ' && ') . ' is_user_logged_in()';
		} else if($users == 'administrator') {
			$output .= (($output == '') ? '' : ' && ') . ' current_user_can(\'manage_options\')';
		}
	}
	
	if($output == '' || trim($output) == '()' || trim($output) == '!()') {
		$output = ' TRUE';
	}
	
	return $output;
}

/**
 *
 * Function used to check if given sidebar is active
 *
 * @param index - index of the sidebar
 *
 * @return bool/int
 * 
 **/
function dp_is_active_sidebar( $index ) {
	// get access to the template object
	global $dynamo_tpl;
	// get access to registered widgets
	global $wp_registered_widgets;
	// sidebar flag
	$sidebar_flag = false;
	//
	if($GLOBALS['pagenow'] !== 'wp-signup.php' && $GLOBALS['pagenow'] !== 'wp-activate.php') {
		// generate sidebar index
		$index = ( is_int($index) ) ? "sidebar-$index" : sanitize_title($index);
		// getting the widgets
		$sidebars_widgets = wp_get_sidebars_widgets();
		// get the widget showing rules
		$options_type = get_option($dynamo_tpl->name . '_widget_rules_type');
		$options = get_option($dynamo_tpl->name . '_widget_rules');
		$users = get_option($dynamo_tpl->name . '_widget_users');
		// if some widget exists
		if ( !empty($sidebars_widgets[$index]) ) {
			$widget_counter = 0;
			foreach ( (array) $sidebars_widgets[$index] as $id ) {
				// if widget doesn't exists - skip this iteration
				if ( !isset($wp_registered_widgets[$id]) ) continue;
				// if the widget rules are enabled
				if(get_option($dynamo_tpl->name . '_widget_rules_state') == 'Y') {
					// check the widget rules
					$conditional_result = false;
					// create conditional function based on rules
					if( isset($options[$id]) && $options[$id] != '' ) {
						// create function
						$conditional_function = create_function('', 'return '.dp_condition($options_type[$id], $options[$id], $users[$id]).';');
						// generate the result of function
						$conditional_result = $conditional_function();
					}
					// if condition for widget isn't set or is TRUE
					if( !isset($options[$id]) || $options[$id] == '' || $conditional_result === TRUE ) {
						// return TRUE, because at lease one widget exists in the specific sidebar
						$sidebar_flag = true;
						$widget_counter++;
					}
					// set the state of the widget
					$wp_registered_widgets[$id]['dpstate'] = $conditional_result;
				} else {
					$widget_counter++;
					$sidebar_flag = true;
					$wp_registered_widgets[$id]['dpstate'] = true;
				}
			}
			// change num 
			foreach ( (array) $sidebars_widgets[$index] as $id ) {
				// if widget doesn't exists - skip this iteration
				if ( !isset($wp_registered_widgets[$id]) ) continue;
				// save the class
				$wp_registered_widgets[$id]['dpcount'] = $widget_counter;
			}
		}
	}
	// if there is no widgets in the sidebar
	return $sidebar_flag;
}

/**
 *
 * Function used to generate the template sidebars
 *
 * @param index - index of the sidebar
 *
 * @return bool
 *
 **/
function dp_dynamic_sidebar($index) {
	// get access to the template object
	global $dynamo_tpl;
	// get access to registered sidebars and widgets
	global $wp_registered_sidebars;
	global $wp_registered_widgets;
	// prepare index of the sidebar
	$index = sanitize_title($index);
	// get the widget showing rules
	$options_type = get_option($dynamo_tpl->name . '_widget_rules_type');
	$options = get_option($dynamo_tpl->name . '_widget_rules');
	$styles = get_option($dynamo_tpl->name . '_widget_style');
	$styles_css = get_option($dynamo_tpl->name . '_widget_style_css');
	$responsive = get_option($dynamo_tpl->name . '_widget_responsive');
	// find sidebar with specific name
	foreach ( (array) $wp_registered_sidebars as $key => $value ) {
		if ( sanitize_title($value['name']) == $index ) {
			$index = $key;
			break;
		}
	}
	// get sidebars widgets list
	$sidebars_widgets = wp_get_sidebars_widgets();
	// if the list is empty - finish the function
	if ( empty( $sidebars_widgets ) ) {
		return false;
	}
	// if specified sidebar doesn't exist - finish the function
	if ( empty($wp_registered_sidebars[$index]) || 
		!array_key_exists($index, $sidebars_widgets) || 
		!is_array($sidebars_widgets[$index]) || 
		empty($sidebars_widgets[$index]) ) {
		return false;
	}
	// if the sidebar exists - get it
	$sidebar = $wp_registered_sidebars[$index];
	// widget counter
	$counter = 0;
	// run hook
	do_action('dynamowp_before_sidebar');
	// iterate through specified sidebar widget
	foreach ( (array) $sidebars_widgets[$index] as $id ) {
		// if widget doesn't exists - skip this iteration
		if ( !isset($wp_registered_widgets[$id]) ) continue;
		// if condition for widget isn't set or is TRUE
		if( !isset($options[$id]) || $options[$id] == '' || $wp_registered_widgets[$id]['dpstate'] == TRUE ) {
			$counter++;
			// get the widget params and merge with sidebar data, widget ID and name
			$params = array_merge(
				array( 
					array_merge( 
						$sidebar, 
						array(
							'widget_id' => $id, 
							'widget_name' => $wp_registered_widgets[$id]['name']
						) 
					) 
				),
				
				(array) $wp_registered_widgets[$id]['params']
			);
			// Substitute HTML id and class attributes into before_widget
			$classname_ = '';
			foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
				if ( is_string($cn) ) $classname_ .= '_' . $cn;
				elseif ( is_object($cn) ) $classname_ .= '_' . get_class($cn);
			}
			// only for the widget areas where the amount of widgets is bigger than 1			
			if(isset($dynamo_tpl->widgets[$index]) && $dynamo_tpl->widgets[$index] > 1) {
				$widget_class = '';
				$widget_amount = $wp_registered_widgets[$id]['dpcount'];
				// set the col* classes
				$widget_class = ' col' . $dynamo_tpl->widgets[$index];
				// set the nth* classes
				if($counter % $dynamo_tpl->widgets[$index] == 0) {
					$widget_class .= ' nth' . $dynamo_tpl->widgets[$index];
				} else {
					$widget_class .= ' nth' . $counter % $dynamo_tpl->widgets[$index];
				}
				// set the last classes
				$last_amount = $widget_amount % $dynamo_tpl->widgets[$index];
				if(
					$last_amount > 0 && 
					$counter > $widget_amount - $last_amount
				) {
					$widget_class .= ' last' . $last_amount; 
				}
				//
				$classname_ .= $widget_class;
			}
			// trim the class name
			$classname_ = ltrim($classname_, '_');
			// define the code before widget
			if( (isset($styles[$id]) && $styles[$id] != '') || (isset($responsive[$id]) && $responsive[$id] != '' && $responsive[$id] != 'all')) {
				$css_class = '';
				
				if($styles[$id] == 'dpcustom') {
					$css_class = $styles_css[$id];
				} else {
					$css_class = $styles[$id];
				}
				$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, ' ' . $css_class . ' ' . $responsive[$id] . ' ' . $classname_);
			} else {
				$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, ' ' . $classname_);
			}
			// apply params
			$params = apply_filters( 'dynamic_sidebar_params', $params );
			// get the widget callback function
			$callback = $wp_registered_widgets[$id]['callback'];
			// generate the widget
			do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );
			// use the widget callback function if exists
			if ( is_callable($callback) ) {
				call_user_func_array($callback, $params);
			}
		}
	}
	// run hook
	do_action('dynamowp_after_sidebar');
}

/**
 *
 * Code used to implement icons in the widget titles
 *
 * @return null
 * 
 **/
function dp_title_icons($title) {
	if($title == '&nbsp;' || trim($title) == '' || strlen($title) == 0) {
		return false;
	} else {
		$icons = array();	
		preg_match('(icon([\-a-zA-Z0-9]){1,})', $title, $icons);
		// icon text (if exists)
		$icon = '';
		//
		if(count($icons) > 0) {
			$icon = '<i class="'.$icons[0].'"></i>';
		}
		//
		$title = preg_replace('@(\[icon([\-a-zA-Z0-9]){1,}\])@', '', $title);
		//
		return $icon.' '.$title;
	}
}

add_filter('widget_title', 'dp_title_icons');

/**
 *
 * Code used to implement thickbox in the page
 *
 * @return null
 * 
 **/
function dp_thickbox_load() {
	//
	global $dynamo_tpl;
	//
	if(get_option($dynamo_tpl->name . '_thickbox_state') == 'Y') : 
	?>
	<script type="text/javascript">
		var thickboxL10n = {
			"next":"<?php __('Next >', 'dp-theme'); ?>",
			"prev":"<?php __('< Prev', 'dp-theme'); ?>",
			"image":"<?php __('Image', 'dp-theme'); ?>",
			"of":"<?php __('of', 'dp-theme'); ?>",
			"close":"<?php __('Close', 'dp-theme'); ?>",
			"noiframes":"<?php __('This feature requires inline frames. You have iframes disabled or your browser does not support them.', 'dp-theme'); ?>",
			"loadingAnimation":"<?php echo home_url(); ?>/wp-includes/js/thickbox/loadingAnimation.gif",
			"closeImage":"<?php echo home_url(); ?>/wp-includes/js/thickbox/tb-close.png"
		};
	</script>
 		<?php wp_enqueue_script('dynamo-thickbox', home_url() . '/wp-includes/js/thickbox/thickbox.js', array('jquery', 'dynamo-scripts'), false, true);	
		wp_enqueue_style('dynamo-thickbox', home_url() . '/wp-includes/js/thickbox/thickbox.css', array('dynamo-extensions'));
	endif;
}


// EOF
