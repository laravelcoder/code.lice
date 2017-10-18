<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Branding functions
 *
 * Functions used in page branding
 *
 **/
 
 
/**
 *
 * Function used to create custom page favicon
 *
 **/ 
 
function dynamo_favicon() {
	global $dynamo_tpl;
	
	if(get_option($dynamo_tpl->name . '_branding_favicon', '') != '') {
		echo '<link rel="shortcut icon" href="' . get_option($dynamo_tpl->name . '_branding_favicon', '') . '" type="image/x-icon" />' . "\n";
	}
} 

add_action('wp_head', 'dynamo_favicon');

/**
 *
 * Function used to create custom login page elements
 *
 **/ 

if(!function_exists('dynamo_loginpage_url')) {
	function dynamo_loginpage_url() {
	 	return home_url();
	}
}

add_filter('login_headerurl', 'dynamo_loginpage_url');

if(!function_exists('dynamo_loginpage_title')) {
	function dynamo_loginpage_title() {
		return esc_attr(get_bloginfo('name'));
	}
} 

add_filter('login_headertitle', 'dynamo_loginpage_title');
 
/**
 *
 * Function used to create custom login page logo
 *
 **/ 

if(!function_exists('dynamo_branding_custom_login_logo')) {

	function dynamo_branding_custom_login_logo() {
	    // access to the template object
	    global $dynamo_tpl;
	    // get logo path
	    $logo_path = get_option($dynamo_tpl->name . "_branding_login_page_image");
	    // if logo path isn't blank
	    if($logo_path !== '') {
		    echo '<style type="text/css">
		        h1 a { 
		        	background-image: url(' . $logo_path . ')!important;
		        	background-size: contain!important;
		        	height: ' . get_option($dynamo_tpl->name . "_branding_login_page_image_height") . 'px!important;
		        	margin: 0 auto 10px auto!important;
		        	width: ' . get_option($dynamo_tpl->name . "_branding_login_page_image_width") . 'px!important; 
		        }
		    </style>';
	    }
	}

}

add_action('login_head', 'dynamo_branding_custom_login_logo');

/**
 *
 * Function used to create custom dashboard logo
 *
 **/

if(!function_exists('dynamo_branding_custom_admin_logo')) {

	function dynamo_branding_custom_admin_logo() {
	   	// access to the template object
	   	global $dynamo_tpl;
	   	// get logo path
	   	$logo_path = get_option($dynamo_tpl->name . "_branding_admin_page_image");
	   	// if logo path isn't blank
	   	if($logo_path !== '') {
		   echo '<style type="text/css">
	       		.wp-menu-image a[href="admin.php?page=dynamo-menu"] img {  
	         		height: ' . get_option($dynamo_tpl->name . "_branding_admin_page_image_height") . 'px!important;
	         		width: ' . get_option($dynamo_tpl->name . "_branding_admin_page_image_width") . 'px!important; 
	         	}
	       </style>';
       	}
	}

}

add_action('admin_head', 'dynamo_branding_custom_admin_logo');

// EOF