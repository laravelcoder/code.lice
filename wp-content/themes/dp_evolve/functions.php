<?php
if(!function_exists('dynamo_file')) {
	/**
	 *
	 * Function used to get the file absolute path - useful when child theme is used
	 *
	 * @return file absolute path (in the original theme or in the child theme if file exists)
	 *
	 **/
	function dynamo_file($path) {
		if(is_child_theme()) {
			if($path == false) {
				return get_stylesheet_directory();
			} else {
				if(is_file(get_stylesheet_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path))) {
					return get_stylesheet_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
				} else {
					return get_template_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
				}
			}
		} else {
			if($path == false) {
				return get_template_directory();
			} else {
				return get_template_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
			}
		}
	}
}

if(!function_exists('dynamo_file_uri')) {
	/**
	 *
	 * Function used to get the file URI - useful when child theme is used
	 *
	 * @return file URI (in the original theme or in the child theme if file exists)
	 *
	 **/
	function dynamo_file_uri($path) {
		if(is_child_theme()) {
			if($path == false) {
				return get_stylesheet_directory_uri();
			} else {
				if(is_file(get_stylesheet_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path))) {
					return get_stylesheet_directory_uri() . '/' . $path;
				} else {
					return get_template_directory_uri() . '/' . $path;
				}
			}
		} else {
			if($path == false) {
				return get_template_directory_uri();
			} else {
				return get_template_directory_uri() . '/' . $path;
			}
		}
	}
}
//
if(!class_exists('DynamoWP')) {
	// include the framework base class
	require(dynamo_file('dynamo_framework/base.php'));
}
// load and parse template JSON file.
$dynamo_tpl_name = 'evolve';
// define constant to use with all __(), _e(), _n(), _x() and _xe() usage
define('DPTPLNAME', 'evolve');
// create the framework object
$dynamo_tpl = new DynamoWP();
// Including file with helper functions
require_once(dynamo_file('dynamo_framework/helpers/helpers.base.php'));
// Including file with template hooks
require_once(dynamo_file('dynamo_framework/hooks.php'));
// Including file with template functions
require_once(dynamo_file('dynamo_framework/functions.php'));
require_once(dynamo_file('dynamo_framework/user.functions.php'));
// Including file with woocommerce functions
if (isset($woocommerce)) : 
	require_once(dynamo_file('dynamo_framework/woocommerce-functions.php'));
endif;
// Including file with template filters
require_once(dynamo_file('dynamo_framework/filters.php'));
// Including file with template widgets
require_once(dynamo_file('dynamo_framework/widgets.comments.php'));
require_once(dynamo_file('dynamo_framework/widgets.nsp.php'));
require_once(dynamo_file('dynamo_framework/widgets.social.php'));
require_once(dynamo_file('dynamo_framework/widgets.tabs.php'));
require_once(dynamo_file('dynamo_framework/widgets.flickr.php'));
require_once(dynamo_file('dynamo_framework/widgets.recent_portfolio.php'));
require_once(dynamo_file('dynamo_framework/widgets.recent_portfolio_grid.php'));
require_once(dynamo_file('dynamo_framework/widgets.recent_posts.php'));
require_once(dynamo_file('dynamo_framework/widgets.newsflash.php'));
require_once(dynamo_file('dynamo_framework/widgets.tweets.php'));
// Including file with cutsom post type slide
require_once(dynamo_file('dynamo_framework/post_types/slide.php'));
// Including file with cutsom post type portfolio
require_once(dynamo_file('dynamo_framework/post_types/portfolio.php'));

// Including file with template admin features
require_once(dynamo_file('dynamo_framework/helpers/helpers.features.php'));
// Including file with template shortcodes
require_once(dynamo_file('dynamo_framework/helpers/helpers.shortcodes.php'));
// Including file with template layout functions
require_once(dynamo_file('dynamo_framework/helpers/helpers.layout.php'));
// Including file with template layout functions - connected with template fragments
require_once(dynamo_file('dynamo_framework/helpers/helpers.layout.fragments.php'));
// Including file with template branding functions
require_once(dynamo_file('dynamo_framework/helpers/helpers.branding.php'));
// Including file with template customize functions
require_once(dynamo_file('dynamo_framework/helpers/helpers.customizer.php'));
// Including file with custom sidebars functions
require_once(dynamo_file('dynamo_framework/post_types/sidebar.php'));
// Including file with like it functions
require_once(dynamo_file('dynamo_framework/helpers/helpers.likeit.php'));
// Including file with dynamic options based CSS 
require_once(dynamo_file('dynamo_framework/helpers/helpers.dynamic.css.php'));
// initialize the framework
$dynamo_tpl->init();
// add theme setup function
add_action('after_setup_theme', 'dynamo_theme_setup');
// Theme setup function
function dynamo_theme_setup(){
	// access to the global template object
	global $dynamo_tpl;
	// variable used for redirects
	global $pagenow;		
	// check if the themes.php address with goto variable has been used
	if ($pagenow == 'themes.php' && !empty($_GET['goto'])) {
		/**
		 *
		 * IMPORTANT FACT: if you're using few different redirects on a lot of subpages
		 * we recommend to define it as themes.php?goto=X, because if you want to
		 * change the URL for X, then you can change it on one place below :)
		 *
		 **/
		
		// check the goto value
		switch ($_GET['goto']) {
			// make proper redirect
			case 'dynamicpress':
				wp_redirect(get_option($dynamo_tpl->name . '_theme_author_link'));
				break;
			case 'documentation':
				wp_redirect(get_option($dynamo_tpl->name . '_theme_documentation_link'));
				break;
			// or use default redirect
			default:
				wp_safe_redirect('/wp-admin/');
				break;
		}
		exit;
	}
	// if the normal page was requested do following operations:
	
    // load and parse template JSON file.
    $json_data = $dynamo_tpl->get_json('config','template');
    // read the configuration
    $template_config = $json_data->template;
    // save the lowercase non-special characters template name				
    $template_name = strtolower(preg_replace("/[^A-Za-z0-9]/", "", $template_config->name));
    // load the template text_domain
	load_theme_textdomain( $template_name, get_stylesheet_directory() . '/languages' );
}
if (isset($woocommerce)) {
if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
	define( 'WOOCOMMERCE_USE_CSS', false );
}
}
// style enqueue function
    function dynamo_enqueue_css() {
	global $dynamo_tpl;
	wp_register_style('dp-css', get_template_directory_uri().'/css/dpcss_min.css');
	wp_enqueue_style('dp-css');

	if(get_option($dynamo_tpl->name . "_overridecss_state", 'Y') == 'Y') {
	wp_register_style('override-css', get_template_directory_uri().'/css/override.css');
	wp_enqueue_style('override-css');
	}
    if(is_rtl()) {
	wp_register_style('rtl-css', get_template_directory_uri().'/css/rtl.css');
	wp_enqueue_style('rtl-css');
 	}

	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
	if(count($matches)<2){
 	 preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
	}
	if (count($matches)>1){
	  //Then we're using IE
	  $version = $matches[1];
	  switch(true){
		case ($version<=8):
		wp_register_style('ie8-css', get_template_directory_uri().'/css/ie8.css');
		  break;
	
		case ($version==9):
		wp_register_style('ie9-css', get_template_directory_uri().'/css/ie9.css');
		  break;
	  }
	}
	}
	add_action('wp_enqueue_scripts', 'dynamo_enqueue_css');
// scripts enqueue function


    function dynamo_enqueue_js() {
		global $dynamo_tpl; 
	wp_enqueue_script('jquery','','','',true);
	// jQuery noconflict
	wp_register_script('jquery-noconflict-js', get_template_directory_uri().'/js/jquery.noconflict.js', array('jquery'),'',true);
	wp_enqueue_script('jquery-noconflict-js');
        //google
        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCFyqTpotZJBGzsULjGkjTN87tnTiT0isE&libraries=places&callback=initAutocomplete');
  
	// jQuery easing
	wp_register_script('easing-js', get_template_directory_uri().'/js/easing.js', array('jquery'),'',true);
	wp_enqueue_script('easing-js');
	// jQuery fitvid
	wp_register_script('jQuery-fitvid-js', get_template_directory_uri().'/js/jquery.fitvid.js', array('jquery'),'',true);
	wp_enqueue_script('jQuery-fitvid-js');
	// jQuery froogaloop
	wp_register_script('froogaloop-js', get_template_directory_uri().'/js/froogaloop.min.js', array('jquery'),'',true);
	wp_enqueue_script('froogaloop-js');
	//prettyphoto JS
	wp_register_script('prettyphoto-js', get_template_directory_uri().'/js/jquery.prettyPhoto.js', array('jquery'),'',true);
	wp_enqueue_script('prettyphoto-js');
	// Flex slider JS
	wp_register_script( 'flexslider-js',get_template_directory_uri().'/js/jquery.flexslider-min.js', array('jquery'), '2.1',true);
	// jQuery YTPlayer
	wp_register_script('mbYTP-js', get_template_directory_uri().'/js/jquery.mb.YTPlayer.min.js', array('jquery'),false,true);
	wp_enqueue_script('mbYTP-js');
	// jQuery appear plugin
	wp_register_script('appear-js', get_template_directory_uri().'/js/appear.js', array('jquery'),false,true);
	wp_enqueue_script('appear-js');
	// jQuery Easy Pie Chart
	wp_register_script('piechart-js', get_template_directory_uri().'/js/jquery-easypiechart-min.js', array('jquery'),false,true);
	wp_enqueue_script('piechart-js');
	// jQuery flickr stream JS
	wp_register_script( 'jQuery-flickr-js',get_template_directory_uri().'/js/jquery.zflickrfeed.min.js', array('jquery'),'',true);
	wp_enqueue_script( 'jQuery-flickr-js' );
	// jQuery tipsy JS
	wp_register_script( 'jQuery-tipsy-js',get_template_directory_uri().'/js/jquery.tipsy.js', array('jquery'),'',true);
	wp_enqueue_script( 'jQuery-tipsy-js' );
	// jQuery jPlayer JS
	wp_register_script( 'jQuery-jplayer-js',get_template_directory_uri().'/js/jquery.jplayer.min.js', array('jquery'),'',true);
	wp_enqueue_script( 'jQuery-jplayer-js' );
	// jQuery Waypoints
	wp_register_script('waypoints-js', get_template_directory_uri().'/js/waypoints.min.js', array('jquery'),false,true);
	wp_enqueue_script('waypoints-js');
	// jQuery nicescroll
	wp_register_script('custom-scrollbar-js', get_template_directory_uri().'/js/jquery.mCustomScrollbar.concat.min.js', array('jquery'));
	wp_enqueue_script('custom-scrollbar-js');
	// Owl carousel nicescroll
	wp_register_script('owl-js', get_template_directory_uri().'/js/owl.carousel.min.js', array('jquery'),false,true);
	wp_enqueue_script('owl-js');

	// jQuery nicescroll
	wp_register_script('selectfix-js', get_template_directory_uri().'/js/selectFix.js', array('jquery'));
	wp_enqueue_script('selectfix-js');
	// jQuery prallax
	wp_register_script('parallax-js', get_template_directory_uri().'/js/jquery.parallax.js', array('jquery'),false,true);
	wp_enqueue_script('parallax-js');
	// jQuery QTransform
	wp_register_script('qtransform-js', get_template_directory_uri().'/js/QTransform.js', array('jquery'),false,true);
	wp_enqueue_script('qtransform-js');
	// Modernizr
	wp_register_script('modernizr-js', get_template_directory_uri().'/js/modernizr.custom.js', array('jquery'));
	wp_enqueue_script('modernizr-js');
	//Shortcodes 
	wp_enqueue_script('shortcodes_js', get_template_directory_uri()."/js/shortcodes.js", array(), false,true);
	//theme JS
	wp_register_script('dp-js', get_template_directory_uri().'/js/dp.scripts.js', array(), false,true);
	wp_enqueue_script('dp-js');
        //Autocomplete
        wp_register_script('ust-auto-complete', get_template_directory_uri().'/js/ust-auto-complete.js', array(), false,true);
	wp_enqueue_script('ust-auto-complete');
	wp_register_script('jm_like_post', get_template_directory_uri().'/js/like.js', array('jquery'), '1.0', 1);
	wp_enqueue_script( 'jm_like_post');
	wp_localize_script( 'jm_like_post', 'ajax_var', array(
		'url' => network_admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' )
		)
		);
	wp_register_script('menu-js', get_template_directory_uri().'/js/dp.menu.js', array('jquery'),  false,true);
	wp_enqueue_script('menu-js');
	if(get_option($dynamo_tpl->name . '_prefixfree_state', 'N') == 'Y') { 
	wp_enqueue_script('dynamo-prefixfree', dynamo_file_uri('js/prefixfree.js'));
	}
	// jQuery isotope JS
	wp_register_script( 'jQuery-isotope-js',get_template_directory_uri().'/js/jquery.isotope.min.js', array('jquery'),'1.5',true);
	wp_enqueue_script( 'jQuery-isotope-js' );
	if ( ! is_admin() ) {
	wp_register_script('frontend-js', get_template_directory_uri().'/js/frontend.js', array('jquery'), false,true);
	wp_enqueue_script('frontend-js');

	}
	}


function wpse124773_jQueryFromCDN()
{
    $version = $GLOBALS['wp_scripts']->registered['jquery']->ver;

    wp_enqueue_script(
        'jquery',
        "//ajax.googleapis.com/ajax/libs/jquery/{$version}/jquery.min.js",
        $GLOBALS['wp_scripts']->registered['jquery']->deps,
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'dynamo_enqueue_js');

// scripts enqueue function
function dynamo_enqueue_admin_js_and_css() {
	// metaboxes scripts 
	wp_enqueue_script('dynamo.metabox.js', dynamo_file_uri('js/back-end/dynamo.metabox.js'), array('jquery'),'',true);
	wp_enqueue_media();
	// widget rules JS
	wp_register_script('widget-rules-js', dynamo_file_uri('js/back-end/widget.rules.js'), array('jquery'));
	wp_enqueue_script('widget-rules-js');
	// widget rules CSS
	wp_register_style('widget-rules-css', dynamo_file_uri('css/back-end/widget.rules.css'));
	wp_enqueue_style('widget-rules-css');
	// metaboxes CSS 
	wp_register_style('dynamo-metabox-css', dynamo_file_uri('css/back-end/metabox.css'));  
    wp_enqueue_style('dynamo-metabox-css');  
	//Color picker
	wp_register_script('dp-colorpicker', dynamo_file_uri('js/back-end/libraries/colorpicker/colorpicker.js'), array('jquery'));
	wp_enqueue_script('dp-colorpicker');
	wp_register_style('dp-colorpicker-css', dynamo_file_uri('js/back-end/libraries/colorpicker/colorpicker.css'));
	wp_enqueue_style('dp-colorpicker-css');
	// DP News Show Pro Widget back-end CSS
	wp_register_style('nsp-admin-css', dynamo_file_uri('css/back-end/nsp.css'));
	wp_enqueue_style('nsp-admin-css');
	// metaboxes CSS
	wp_register_style('metaboxes-css', get_template_directory_uri().'/dynamo_framework/metaboxes/meta.css');
	wp_enqueue_style('metaboxes-css');
	
	// shortcodes database
	if(
		get_locale() != '' && 
		is_dir(get_template_directory() . '/dynamo_framework/config/'. get_locale()) && 
		is_dir(get_template_directory() . '/dynamo_framework/options/'. get_locale())
	) {
		$language = 'en_US';	
	} else {
		$language = 'en_US';
	}
	
}
// this action enqueues scripts and styles: 
add_action('admin_enqueue_scripts', 'dynamo_enqueue_admin_js_and_css');
wp_oembed_add_provider( 'http://soundcloud.com/*', 'http://soundcloud.com/oembed' );
wp_oembed_add_provider( '#http://(www\.)?youtube\.com/watch.*#i', 'http://www.youtube.com/oembed', true );
wp_oembed_add_provider( '#http://(www\.)?vimeo\.com/.*#i', 'http://vimeo.com/api/oembed.{format}', true );

function dp_excerpt($text) {
   return str_replace('[&hellip;]', '<p><a class="dp-readon" href="'.get_permalink().'"><span>'.__( 'Read more', 'dp-theme' ).'</span></a></p>', $text); }
add_filter('the_excerpt', 'dp_excerpt');

function dp_excerpt_length($length) {
	global $dynamo_tpl;
 	return get_option($dynamo_tpl->name . "_excerpt_length"); }
add_filter('excerpt_length', 'dp_excerpt_length');

function dynamo_enqueue_fontawesome_styles() {
        global $wp_styles;
        wp_enqueue_style( 'dp-fontello', get_template_directory_uri().'/css/dp_fontello.css' );
    }
add_action('init', 'dynamo_enqueue_fontawesome_styles');


function dynamo_enqueue_woocommerce_css(){
	wp_register_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
	if ( class_exists( 'woocommerce' ) ) {
		wp_enqueue_style( 'woocommerce' );
	}
}
add_action( 'wp_enqueue_scripts', 'dynamo_enqueue_woocommerce_css' );

add_action( 'admin_init', 'dp_theme_check_clean_installation' );
function dp_theme_check_clean_installation(){
	add_action( 'admin_notices', 'dp_theme_options_reminder' );
}

if ( ! function_exists( 'dp_theme_options_reminder' ) ){
	function dp_theme_options_reminder(){
		global $dynamo_tpl; 

		if (get_option($dynamo_tpl->name . '_template_options_saved')!='Y'){
			printf( __('<div class="updated"><p>This is a fresh installation of %1$s theme. Don\'t forget to go to <a href="%2$s">Evolve -> Template Options panel</a> to set it up. This message will disappear once you have clicked the Save button within the <a href="%2$s">theme\'s options page</a>.</p></div>','Evolve'), wp_get_theme(), network_admin_url( 'admin.php?page=dynamo-menu' ) );
		}
	}
}
function change_mce_options( $init ) {
 $init['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
 $init['theme_advanced_disable'] = 'forecolor';
 return $init;
}
add_filter('tiny_mce_before_init', 'change_mce_options');

/***************************************************************************
* Function: Sets, Tracks and Displays the Count of Post Views (Post View Counter)
*********************************************************************************/
//Set the Post Custom Field in the WP dashboard as Name/Value pair 
function dp_PostViews($post_ID) {
 
    //Set the name of the Posts Custom Field.
    $count_key = 'post_views_count'; 
     
    //Returns values of the custom field with the specified key from the specified post.
    $count = get_post_meta($post_ID, $count_key, true);
     
    //If the the Post Custom Field value is empty. 
    if($count == ''){
        $count = 0; // set the counter to zero.
         
        //Delete all custom fields with the specified key from the specified post. 
        delete_post_meta($post_ID, $count_key);
         
        //Add a custom (meta) field (Name/value)to the specified post.
        add_post_meta($post_ID, $count_key, '0');
        return $count . ' View';
     
    //If the the Post Custom Field value is NOT empty.
    }else{
        $count++; //increment the counter by 1.
        //Update the value of an existing meta key (custom field) for the specified post.
        update_post_meta($post_ID, $count_key, $count);
         
        //If statement, is just to have the singular form 'View' for the value '1'
        if($count == '1'){
        $count = $count .' '. __('View', 'dp-theme');
        }
        //In all other cases return (count) Views
        else {
        $count = $count .' '. __('Views', 'dp-theme');
        }
		$output = '<a href="#" class="dp-tipsy1" data-tipcontent="'.$count.'" original-title=""><i class="icon-eye-outline"></i></a>';
		return $output;
    }
}
//Gets the  number of Post Views to be used later.
function get_PostViews($post_ID){
    $count_key = 'post_views_count';
    //Returns values of the custom field with the specified key from the specified post.
    $count = get_post_meta($post_ID, $count_key, true);
 
    return $count;
}
 
//Function that Adds a 'Views' Column to your Posts tab in WordPress Dashboard.
function post_column_views($newcolumn){
    //Retrieves the translated string, if translation exists, and assign it to the 'default' array.
    $newcolumn['post_views'] = __('Views', 'dp-theme');
    return $newcolumn;
}

//Function that Populates the 'Views' Column with the number of views count.
function post_custom_column_views($column_name, $id){
     
    if($column_name === 'post_views'){
        // Display the Post View Count of the current post.
        // get_the_ID() - Returns the numeric ID of the current post.
        echo get_PostViews(get_the_ID());
    }
}
//Hooks a function to a specific filter action.
//applied to the list of columns to print on the manage posts screen.
add_filter('manage_posts_columns', 'post_column_views');
 
//Hooks a function to a specific action. 
//allows you to add custom columns to the list post/custom post type pages.
//'10' default: specify the function's priority.
//and '2' is the number of the functions' arguments.
add_action('manage_posts_custom_column', 'post_custom_column_views',10,2);

/**
 * Load welcome message.
 */
require get_template_directory() . '/dynamo_framework/theme.welcome.php';


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/dynamo_framework/classes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'dp_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function dp_theme_register_required_plugins() {
    global $dynamo_tpl;
	
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin pre-packaged with a theme
		array(
			'name'     				=> 'Visual Composer', 
			'slug'     				=> 'js_composer', 
			'source'   				=> 'http://www.dynamicpress.pl/downloads/plugins/visual_composer/4_11_2_1/js_composer.zip',
			'required' 				=> true,
			'version' 				=> '', 
			'force_activation' 		=> false, 
			'force_deactivation' 	=> false, 
			'external_url' 			=> '', 
		),
		array(
			'name'     				=> 'Revolution Slider Plugin', 
			'slug'     				=> 'revslider', 
			'source'   				=> 'http://www.dynamicpress.pl/downloads/plugins/revslider/5_2_5/revslider.zip', 
			'required' 				=> true,
			'version' 				=> '', 
			'force_activation' 		=> false, 
			'force_deactivation' 	=> false, 
			'external_url' 			=> '', 
		),
		array(
			'name'     				=> 'Showbiz Plugin', 
			'slug'     				=> 'showbiz', 
			'source'   				=> 'http://www.dynamicpress.pl/downloads/plugins/showbiz/1_73/showbiz.zip',
			'required' 				=> true, 
			'version' 				=> '', 
			'force_activation' 		=> false, 
			'force_deactivation' 	=> false, 
			'external_url' 			=> '', 
		),
		array(
			'name' => 'Contact Form 7',
			'slug' => 'contact-form-7',
			'required' => false
		),
		array(
			'name' => 'Newsletter Sign-Up',
			'slug' => 'newsletter-sign-up',
			'required' => false
		),
		array(
			'name' => 'Widget Importer & Exporter',
			'slug' => 'widget-importer-exporter',
			'required' => false
		)
	);


	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'dp-theme';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
	);

	tgmpa( $plugins, $config );

}

function add_vc_plugin_js(){
 	wp_register_script('vc-plugin-js', get_template_directory_uri().'/js/vc.scripts.plugin.js', array(), false,true);
	wp_enqueue_script('vc-plugin-js');
}
add_action( 'wp_enqueue_scripts', 'add_vc_plugin_js' );

	//wp_register_script('vc-plugin-js', get_template_directory_uri().'/js/vc.scripts.plugin.js', array(), false,true);
	//wp_enqueue_script('vc-plugin-js');

// Initialising Shortcodes
if (class_exists('WPBakeryVisualComposerAbstract')) {
	function requireVcExtend(){
		require_once locate_template('dynamo_framework/vc_extend/dp-extend-vc.php');
	}
	add_action('init', 'requireVcExtend',2);

}
if (!function_exists('printIconSelect')){
	function printIconSelect($name) {
	$output = '<select name="'.$name.'" id="'.$name.'" class="width120">';
    $output .= '<option value=""></option>';
$output .= '<option value="icon-windy-rain-inv">icon-windy-rain-inv</option> ';   
$output .= '<option value="icon-duckduckgo">icon-duckduckgo</option> '; 
$output .= '<option value="icon-aim">icon-aim</option> ';   
$output .= '<option value="icon-snow-inv">icon-snow-inv</option> ';   
$output .= '<option value="icon-snow-heavy-inv">icon-snow-heavy-inv</option> ';   
$output .= '<option value="icon-hash">icon-hash</option> ';   
$output .= '<option value="icon-delicious">icon-delicious</option> ';   
$output .= '<option value="icon-paypal-1">icon-paypal-1</option> ';   
$output .= '<option value="icon-hail-inv">icon-hail-inv</option> ';  
$output .= '<option value="icon-money-1">icon-money-1</option> ';   
$output .= '<option value="icon-flattr-1">icon-flattr-1</option> ';  
$output .= '<option value="icon-clouds-inv">icon-clouds-inv</option> ';   
$output .= '<option value="icon-clouds-flash-inv">icon-clouds-flash-inv</option> ';   
$output .= '<option value="icon-android-1">icon-android-1</option> ';   
$output .= '<option value="icon-eventful">icon-eventful</option> ';   
$output .= '<option value="icon-temperature">icon-temperature</option> ';  
$output .= '<option value="icon-compass-4">icon-compass-4</option> ';  
$output .= '<option value="icon-na">icon-na</option> ';   
$output .= '<option value="icon-smashmag">icon-smashmag</option> ';   
$output .= '<option value="icon-celcius">icon-celcius</option> ';  
$output .= '<option value="icon-plus-4">icon-plus-4</option> ';   
$output .= '<option value="icon-plus-3">icon-plus-3</option> ';   
$output .= '<option value="icon-gplus-3">icon-gplus-3</option> ';   
$output .= '<option value="icon-plus-5">icon-plus-5</option> ';   
$output .= '<option value="icon-plus-1">icon-plus-1</option> ';   
$output .= '<option value="icon-fahrenheit">icon-fahrenheit</option> ';   
$output .= '<option value="icon-plus">icon-plus</option> ';   
$output .= '<option value="icon-wikipedia">icon-wikipedia</option> ';   
$output .= '<option value="icon-minus-1">icon-minus-1</option> ';   
$output .= '<option value="icon-lanyrd">icon-lanyrd</option> ';  
$output .= '<option value="icon-minus">icon-minus</option> ';   
$output .= '<option value="icon-minus-3">icon-minus-3</option> ';  
$output .= '<option value="icon-calendar-6">icon-calendar-6</option> ';  
$output .= '<option value="icon-stumbleupon-1">icon-stumbleupon-1</option> ';  
$output .= '<option value="icon-progress-4">icon-progress-4</option> ';   
$output .= '<option value="icon-clouds-flash-alt">icon-clouds-flash-alt</option> ';  
$output .= '<option value="icon-fivehundredpx">icon-fivehundredpx</option> ';  
$output .= '<option value="icon-sun-inv-1">icon-sun-inv-1</option> ';  
$output .= '<option value="icon-progress-5">icon-progress-5</option> '; 
$output .= '<option value="icon-progress-6">icon-progress-6</option> ';  
$output .= '<option value="icon-moon-inv-1">icon-moon-inv-1</option> ';  
$output .= '<option value="icon-bitcoin-1">icon-bitcoin-1</option> ';   
$output .= '<option value="icon-w3c">icon-w3c</option> ';   
$output .= '<option value="icon-progress-7">icon-progress-7</option> ';   
$output .= '<option value="icon-cloud-sun-inv">icon-cloud-sun-inv</option> ';   
$output .= '<option value="icon-progress-8">icon-progress-8</option> ';  
$output .= '<option value="icon-foursquare-1">icon-foursquare-1</option> ';  
$output .= '<option value="icon-cloud-moon-inv">icon-cloud-moon-inv</option> ';  
$output .= '<option value="icon-html5-1">icon-html5-1</option> ';  
$output .= '<option value="icon-progress-9">icon-progress-9</option> '; 
$output .= '<option value="icon-cloud-inv">icon-cloud-inv</option> ';   
$output .= '<option value="icon-ie-1">icon-ie-1</option> ';   
$output .= '<option value="icon-progress-10">icon-progress-10</option> ';   
$output .= '<option value="icon-cloud-flash-inv">icon-cloud-flash-inv</option> ';   
$output .= '<option value="icon-call">icon-call</option> ';   
$output .= '<option value="icon-progress-11">icon-progress-11</option> ';
$output .= '<option value="icon-drizzle-inv">icon-drizzle-inv</option> ';   
$output .= '<option value="icon-rain-inv">icon-rain-inv</option> ';  
$output .= '<option value="icon-grooveshark">icon-grooveshark</option> ';  
$output .= '<option value="icon-ninetyninedesigns">icon-ninetyninedesigns</option> ';  
$output .= '<option value="icon-windy-inv">icon-windy-inv</option> ';   
$output .= '<option value="icon-forrst">icon-forrst</option> ';  
$output .= '<option value="icon-colon">icon-colon</option> ';  
$output .= '<option value="icon-semicolon">icon-semicolon</option> ';   
$output .= '<option value="icon-digg">icon-digg</option> ';   
$output .= '<option value="icon-spotify-1">icon-spotify-1</option> ';   
$output .= '<option value="icon-info-4">icon-info-4</option> ';   
$output .= '<option value="icon-reddit">icon-reddit</option> ';   
$output .= '<option value="icon-guest">icon-guest</option> ';   
$output .= '<option value="icon-question">icon-question</option> ';
$output .= '<option value="icon-gowalla">icon-gowalla</option> ';   
$output .= '<option value="icon-at-2">icon-at-2</option> '; 
$output .= '<option value="icon-at-1">icon-at-1</option> '; 
$output .= '<option value="icon-at-3">icon-at-3</option> '; 
$output .= '<option value="icon-appstore">icon-appstore</option> ';  
$output .= '<option value="icon-sunrise">icon-sunrise</option> ';  
$output .= '<option value="icon-blogger">icon-blogger</option> ';  
$output .= '<option value="icon-sun-3">icon-sun-3</option> '; 
$output .= '<option value="icon-cc-1">icon-cc-1</option> ';  
$output .= '<option value="icon-moon-4">icon-moon-4</option> ';  
$output .= '<option value="icon-dribbble-4">icon-dribbble-4</option> ';  
$output .= '<option value="icon-eclipse">icon-eclipse</option> ';   
$output .= '<option value="icon-evernote-1">icon-evernote-1</option> '; 
$output .= '<option value="icon-mist">icon-mist</option> ';  
$output .= '<option value="icon-flickr-3">icon-flickr-3</option> ';  
$output .= '<option value="icon-wind-1">icon-wind-1</option> ';  
$output .= '<option value="icon-google">icon-google</option> ';   
$output .= '<option value="icon-snowflake">icon-snowflake</option> ';  
$output .= '<option value="icon-cloud-sun-1">icon-cloud-sun-1</option> ';  
$output .= '<option value="icon-viadeo">icon-viadeo</option> ';  
$output .= '<option value="icon-cloud-moon">icon-cloud-moon</option> ';  
$output .= '<option value="icon-instapaper">icon-instapaper</option> ';   
$output .= '<option value="icon-weibo-1">icon-weibo-1</option> ';   
$output .= '<option value="icon-fog-sun">icon-fog-sun</option> ';  
$output .= '<option value="icon-klout">icon-klout</option> ';  
$output .= '<option value="icon-fog-moon">icon-fog-moon</option> ';  
$output .= '<option value="icon-linkedin-4">icon-linkedin-4</option> ';  
$output .= '<option value="icon-fog-cloud">icon-fog-cloud</option> ';  
$output .= '<option value="icon-fog">icon-fog</option> ';   
$output .= '<option value="icon-meetup">icon-meetup</option> ';   
$output .= '<option value="icon-cloud-4">icon-cloud-4</option> ';  
$output .= '<option value="icon-vk">icon-vk</option> ';   
$output .= '<option value="icon-cloud-flash-1">icon-cloud-flash-1</option> '; 
$output .= '<option value="icon-cloud-flash-alt">icon-cloud-flash-alt</option> ';   
$output .= '<option value="icon-plancast">icon-plancast</option> ';  
$output .= '<option value="icon-drizzle-1">icon-drizzle-1</option> ';  
$output .= '<option value="icon-disqus">icon-disqus</option> ';   
$output .= '<option value="icon-rain-2">icon-rain-2</option> ';   
$output .= '<option value="icon-rss-5">icon-rss-5</option> ';   
$output .= '<option value="icon-skype-3">icon-skype-3</option> ';  
$output .= '<option value="icon-windy">icon-windy</option> ';  
$output .= '<option value="icon-twitter-5">icon-twitter-5</option> ';   
$output .= '<option value="icon-windy-rain">icon-windy-rain</option> ';  
$output .= '<option value="icon-snow-1">icon-snow-1</option> ';   
$output .= '<option value="icon-youtube-2">icon-youtube-2</option> ';   
$output .= '<option value="icon-snow-alt">icon-snow-alt</option> ';   
$output .= '<option value="icon-vimeo-2">icon-vimeo-2</option> ';  
$output .= '<option value="icon-snow-heavy">icon-snow-heavy</option> '; 
$output .= '<option value="icon-windows-1">icon-windows-1</option> ';  
$output .= '<option value="icon-hail">icon-hail</option> ';   
$output .= '<option value="icon-xing-1">icon-xing-1</option> ';  
$output .= '<option value="icon-yahoo">icon-yahoo</option> ';   
$output .= '<option value="icon-clouds">icon-clouds</option> '; 
$output .= '<option value="icon-clouds-flash">icon-clouds-flash</option> '; 
$output .= '<option value="icon-chrome-1">icon-chrome-1</option> ';  
$output .= '<option value="icon-email">icon-email</option> ';  
$output .= '<option value="icon-macstore">icon-macstore</option> ';  
$output .= '<option value="icon-myspace">icon-myspace</option> ';   
$output .= '<option value="icon-podcast">icon-podcast</option> ';  
$output .= '<option value="icon-amazon">icon-amazon</option> '; 
$output .= '<option value="icon-steam">icon-steam</option> ';  
$output .= '<option value="icon-cloudapp">icon-cloudapp</option> ';   
$output .= '<option value="icon-dropbox-2">icon-dropbox-2</option> ';  
$output .= '<option value="icon-ebay">icon-ebay</option> ';   
$output .= '<option value="icon-facebook-5">icon-facebook-5</option> ';   
$output .= '<option value="icon-github-4">icon-github-4</option> ';  
$output .= '<option value="icon-googleplay">icon-googleplay</option> ';   
$output .= '<option value="icon-itunes">icon-itunes</option> '; 
$output .= '<option value="icon-plurk">icon-plurk</option> ';   
$output .= '<option value="icon-songkick">icon-songkick</option> ';  
$output .= '<option value="icon-lastfm-2">icon-lastfm-2</option> '; 
$output .= '<option value="icon-gmail">icon-gmail</option> ';   
$output .= '<option value="icon-pinboard">icon-pinboard</option> ';  
$output .= '<option value="icon-openid">icon-openid</option> ';   
$output .= '<option value="icon-quora">icon-quora</option> ';   
$output .= '<option value="icon-soundcloud-2">icon-soundcloud-2</option> ';  
$output .= '<option value="icon-tumblr-2">icon-tumblr-2</option> ';  
$output .= '<option value="icon-eventasaurus">icon-eventasaurus</option> ';   
$output .= '<option value="icon-wordpress">icon-wordpress</option> ';   
$output .= '<option value="icon-yelp">icon-yelp</option> ';  
$output .= '<option value="icon-intensedebate">icon-intensedebate</option> ';   
$output .= '<option value="icon-eventbrite">icon-eventbrite</option> ';  
$output .= '<option value="icon-scribd">icon-scribd</option> ';   
$output .= '<option value="icon-posterous">icon-posterous</option> ';  
$output .= '<option value="icon-stripe">icon-stripe</option> ';  
$output .= '<option value="icon-pilcrow">icon-pilcrow</option> ';   
$output .= '<option value="icon-opentable">icon-opentable</option> ';  
$output .= '<option value="icon-cart">icon-cart</option> ';  
$output .= '<option value="icon-print-5">icon-print-5</option> ';  
$output .= '<option value="icon-angellist">icon-angellist</option> ';  
$output .= '<option value="icon-instagram-2">icon-instagram-2</option> ';  
$output .= '<option value="icon-dwolla">icon-dwolla</option> ';  
$output .= '<option value="icon-appnet">icon-appnet</option> ';  
$output .= '<option value="icon-statusnet">icon-statusnet</option> ';  
$output .= '<option value="icon-acrobat">icon-acrobat</option> '; 
$output .= '<option value="icon-drupal">icon-drupal</option> '; 
$output .= '<option value="icon-buffer">icon-buffer</option> '; 
$output .= '<option value="icon-pocket">icon-pocket</option> ';  
$output .= '<option value="icon-github-circled-4">icon-github-circled-4</option> ';  
$output .= '<option value="icon-bitbucket-1">icon-bitbucket-1</option> ';  
$output .= '<option value="icon-lego">icon-lego</option> ';  
$output .= '<option value="icon-login-3">icon-login-3</option> ';   
$output .= '<option value="icon-stackoverflow-1">icon-stackoverflow-1</option> ';  
$output .= '<option value="icon-hackernews">icon-hackernews</option> ';  
$output .= '<option value="icon-lkdto">icon-lkdto</option> ';  
$output .= '<option value="icon-info-1">icon-info-1</option> ';  
$output .= '<option value="icon-info-3">icon-info-3</option> ';  
$output .= '<option value="icon-left-thin">icon-left-thin</option> ';  
$output .= '<option value="icon-left-3">icon-left-3</option> ';  
$output .= '<option value="icon-left-big">icon-left-big</option> ';  
$output .= '<option value="icon-left-4">icon-left-4</option> ';  
$output .= '<option value="icon-up-thin">icon-up-thin</option> ';  
$output .= '<option value="icon-up-big">icon-up-big</option> ';  
$output .= '<option value="icon-up-5">icon-up-5</option> ';  
$output .= '<option value="icon-up-3">icon-up-3</option> ';  
$output .= '<option value="icon-up-4">icon-up-4</option> ';  
$output .= '<option value="icon-right-thin">icon-right-thin</option> ';  
$output .= '<option value="icon-right-4">icon-right-4</option> ';  
$output .= '<option value="icon-right-big">icon-right-big</option> ';  
$output .= '<option value="icon-right-3">icon-right-3</option> ';  
$output .= '<option value="icon-down-3">icon-down-3</option> ';  
$output .= '<option value="icon-down-big">icon-down-big</option> ';  
$output .= '<option value="icon-down-thin">icon-down-thin</option> ';  
$output .= '<option value="icon-down-4">icon-down-4</option> ';  
$output .= '<option value="icon-down-5">icon-down-5</option> ';   
$output .= '<option value="icon-level-up-1">icon-level-up-1</option> ';  
$output .= '<option value="icon-level-down-1">icon-level-down-1</option> ';   
$output .= '<option value="icon-undo">icon-undo</option> ';  
$output .= '<option value="icon-exchange-1">icon-exchange-1</option> ';  
$output .= '<option value="icon-switch">icon-switch</option> '; 
$output .= '<option value="icon-left-fat">icon-left-fat</option> ';  
$output .= '<option value="icon-up-fat">icon-up-fat</option> ';
$output .= '<option value="icon-right-fat">icon-right-fat</option> ';
$output .= '<option value="icon-down-fat">icon-down-fat</option> ';
$output .= '<option value="icon-left-bold-1">icon-left-bold-1</option> ';
$output .= '<option value="icon-up-bold-1">icon-up-bold-1</option> '; 
$output .= '<option value="icon-right-bold-1">icon-right-bold-1</option> ';  
$output .= '<option value="icon-down-bold-1">icon-down-bold-1</option> '; 
$output .= '<option value="icon-infinity">icon-infinity</option> ';  
$output .= '<option value="icon-infinity-1">icon-infinity-1</option> '; 
$output .= '<option value="icon-plus-squared-1">icon-plus-squared-1</option> '; 
$output .= '<option value="icon-minus-squared-1">icon-minus-squared-1</option> '; 
$output .= '<option value="icon-home">icon-home</option> ';
$output .= '<option value="icon-home-3">icon-home-3</option> ';  
$output .= '<option value="icon-home-5">icon-home-5</option> ';   
$output .= '<option value="icon-home-1">icon-home-1</option> ';  
$output .= '<option value="icon-home-4">icon-home-4</option> ';  
$output .= '<option value="icon-hourglass-1">icon-hourglass-1</option> ';  
$output .= '<option value="icon-keyboard-1">icon-keyboard-1</option> '; 
$output .= '<option value="icon-erase">icon-erase</option> ';  
$output .= '<option value="icon-split">icon-split</option> ';  
$output .= '<option value="icon-pause">icon-pause</option> ';  
$output .= '<option value="icon-pause-3">icon-pause-3</option> ';  
$output .= '<option value="icon-pause-1">icon-pause-1</option> ';  
$output .= '<option value="icon-eject-2">icon-eject-2</option> '; 
$output .= '<option value="icon-fast-fw">icon-fast-fw</option> '; 
$output .= '<option value="icon-fast-forward">icon-fast-forward</option> ';  
$output .= '<option value="icon-fast-backward">icon-fast-backward</option> ';  
$output .= '<option value="icon-fast-bw">icon-fast-bw</option> ';   
$output .= '<option value="icon-to-end-1">icon-to-end-1</option> ';  
$output .= '<option value="icon-to-end-2">icon-to-end-2</option> '; 
$output .= '<option value="icon-to-end">icon-to-end</option> ';   
$output .= '<option value="icon-to-start-2">icon-to-start-2</option> ';  
$output .= '<option value="icon-to-start">icon-to-start</option> ';   
$output .= '<option value="icon-to-start-1">icon-to-start-1</option> ';  
$output .= '<option value="icon-stopwatch-1">icon-stopwatch-1</option> '; 
$output .= '<option value="icon-clock-5">icon-clock-5</option> ';  
$output .= '<option value="icon-hourglass">icon-hourglass</option> ';   
$output .= '<option value="icon-stop-1">icon-stop-1</option> '; 
$output .= '<option value="icon-stop">icon-stop</option> ';  
$output .= '<option value="icon-stop-3">icon-stop-3</option> ';  
$output .= '<option value="icon-up-dir-1">icon-up-dir-1</option> ';  
$output .= '<option value="icon-up-dir">icon-up-dir</option> ';  
$output .= '<option value="icon-up-dir-2">icon-up-dir-2</option> '; 
$output .= '<option value="icon-play">icon-play</option> ';  
$output .= '<option value="icon-play-3">icon-play-3</option> ';  
$output .= '<option value="icon-play-1">icon-play-1</option> '; 
$output .= '<option value="icon-right-dir">icon-right-dir</option> ';  
$output .= '<option value="icon-right-dir-1">icon-right-dir-1</option> ';  
$output .= '<option value="icon-right-dir-3">icon-right-dir-3</option> ';  
$output .= '<option value="icon-right-dir-2">icon-right-dir-2</option> ';  
$output .= '<option value="icon-down-dir-3">icon-down-dir-3</option> ';  
$output .= '<option value="icon-down-dir-1">icon-down-dir-1</option> ';  
$output .= '<option value="icon-down-dir-2">icon-down-dir-2</option> ';  
$output .= '<option value="icon-down-dir">icon-down-dir</option> ';   
$output .= '<option value="icon-left-dir-2">icon-left-dir-2</option> ';  
$output .= '<option value="icon-left-dir-1">icon-left-dir-1</option> '; 
$output .= '<option value="icon-left-dir">icon-left-dir</option> ';  
$output .= '<option value="icon-adjust">icon-adjust</option> ';  
$output .= '<option value="icon-sun-inv">icon-sun-inv</option> ';  
$output .= '<option value="icon-cloud-8">icon-cloud-8</option> ';  
$output .= '<option value="icon-cloud-1">icon-cloud-1</option> ';  
$output .= '<option value="icon-cloud-5">icon-cloud-5</option> ';  
$output .= '<option value="icon-cloud">icon-cloud</option> ';  
$output .= '<option value="icon-cloud-3">icon-cloud-3</option> ';  
$output .= '<option value="icon-umbrella">icon-umbrella</option> ';  
$output .= '<option value="icon-umbrella-1">icon-umbrella-1</option> ';
$output .= '<option value="icon-star-8">icon-star-8</option> '; 
$output .= '<option value="icon-star-3">icon-star-3</option> '; 
$output .= '<option value="icon-star-5">icon-star-5</option> ';  
$output .= '<option value="icon-star">icon-star</option> ';  
$output .= '<option value="icon-star-4">icon-star-4</option> ';  
$output .= '<option value="icon-star-1">icon-star-1</option> ';  
$output .= '<option value="icon-star-empty-2">icon-star-empty-2</option> ';  
$output .= '<option value="icon-star-empty-1">icon-star-empty-1</option> ';  
$output .= '<option value="icon-star-empty">icon-star-empty</option> ';  
$output .= '<option value="icon-check">icon-check</option> ';  
$output .= '<option value="icon-cup">icon-cup</option> ';  
$output .= '<option value="icon-left-hand">icon-left-hand</option> ';  
$output .= '<option value="icon-up-hand">icon-up-hand</option> ';   
$output .= '<option value="icon-right-hand">icon-right-hand</option> ';  
$output .= '<option value="icon-down-hand">icon-down-hand</option> ';  
$output .= '<option value="icon-menu-1">icon-menu-1</option> ';  
$output .= '<option value="icon-th-list-2">icon-th-list-2</option> ';  
$output .= '<option value="icon-th-list-5">icon-th-list-5</option> '; 
$output .= '<option value="icon-th-list">icon-th-list</option> ';  
$output .= '<option value="icon-sun-1">icon-sun-1</option> ';  
$output .= '<option value="icon-sun-2">icon-sun-2</option> ';  
$output .= '<option value="icon-moon-1">icon-moon-1</option> '; 
$output .= '<option value="icon-moon-3">icon-moon-3</option> ';
$output .= '<option value="icon-female-1">icon-female-1</option> '; 
$output .= '<option value="icon-male-1">icon-male-1</option> '; 
$output .= '<option value="icon-king">icon-king</option> ';  
$output .= '<option value="icon-heart-empty-2">icon-heart-empty-2</option> ';   
$output .= '<option value="icon-heart-empty-1">icon-heart-empty-1</option> '; 
$output .= '<option value="icon-heart-empty">icon-heart-empty</option> ';  
$output .= '<option value="icon-heart-empty-4">icon-heart-empty-4</option> '; 
$output .= '<option value="icon-heart-3">icon-heart-3</option> ';  
$output .= '<option value="icon-heart-4">icon-heart-4</option> ';  
$output .= '<option value="icon-heart-8">icon-heart-8</option> '; 
$output .= '<option value="icon-heart-1">icon-heart-1</option> '; 
$output .= '<option value="icon-heart">icon-heart</option> ';  
$output .= '<option value="icon-heart-5">icon-heart-5</option> ';
$output .= '<option value="icon-note">icon-note</option> ';  
$output .= '<option value="icon-music">icon-music</option> '; 
$output .= '<option value="icon-note-beamed">icon-note-beamed</option> '; 
$output .= '<option value="icon-th">icon-th</option> ';  
$output .= '<option value="icon-th-4">icon-th-4</option> ';  
$output .= '<option value="icon-th-2">icon-th-2</option> ';  
$output .= '<option value="icon-layout">icon-layout</option> '; 
$output .= '<option value="icon-flag">icon-flag</option> ';  
$output .= '<option value="icon-flag-1">icon-flag-1</option> '; 
$output .= '<option value="icon-tools">icon-tools</option> ';  
$output .= '<option value="icon-anchor-2">icon-anchor-2</option> '; 
$output .= '<option value="icon-cog-1">icon-cog-1</option> ';  
$output .= '<option value="icon-cog-2">icon-cog-2</option> ';  
$output .= '<option value="icon-cog">icon-cog</option> ';  
$output .= '<option value="icon-cog-3">icon-cog-3</option> '; 
$output .= '<option value="icon-cog-7">icon-cog-7</option> '; 
$output .= '<option value="icon-attention">icon-attention</option> ';  
$output .= '<option value="icon-attention-5">icon-attention-5</option> ';  
$output .= '<option value="icon-attention-filled">icon-attention-filled</option> ';   
$output .= '<option value="icon-attention-1">icon-attention-1</option> ';  
$output .= '<option value="icon-attention-3">icon-attention-3</option> ';  
$output .= '<option value="icon-flash-3">icon-flash-3</option> ';   
$output .= '<option value="icon-flash-4">icon-flash-4</option> ';  
$output .= '<option value="icon-flash">icon-flash</option> ';  
$output .= '<option value="icon-flash-1">icon-flash-1</option> ';  
$output .= '<option value="icon-flash-2">icon-flash-2</option> '; 
$output .= '<option value="icon-record">icon-record</option> '; 
$output .= '<option value="icon-key-3">icon-key-3</option> ';  
$output .= '<option value="icon-rain-1">icon-rain-1</option> ';  
$output .= '<option value="icon-cloud-thunder">icon-cloud-thunder</option> ';   
$output .= '<option value="icon-cog-alt">icon-cog-alt</option> ';   
$output .= '<option value="icon-scissors-1">icon-scissors-1</option> ';   
$output .= '<option value="icon-scissors">icon-scissors</option> ';  
$output .= '<option value="icon-tape">icon-tape</option> ';  
$output .= '<option value="icon-flight">icon-flight</option> ';  
$output .= '<option value="icon-flight-1">icon-flight-1</option> ';  
$output .= '<option value="icon-mail-4">icon-mail-4</option> ';  
$output .= '<option value="icon-mail-3">icon-mail-3</option> ';  
$output .= '<option value="icon-mail">icon-mail</option> ';  
$output .= '<option value="icon-mail-1">icon-mail-1</option> ';  
$output .= '<option value="icon-mail-8">icon-mail-8</option> ';  
$output .= '<option value="icon-mail-5">icon-mail-5</option> '; 
$output .= '<option value="icon-edit-2">icon-edit-2</option> ';  
$output .= '<option value="icon-edit">icon-edit</option> ';  
$output .= '<option value="icon-pencil-4">icon-pencil-4</option> ';  
$output .= '<option value="icon-pencil-3">icon-pencil-3</option> '; 
$output .= '<option value="icon-pencil">icon-pencil</option> '; 
$output .= '<option value="icon-pencil-2">icon-pencil-2</option> ';  
$output .= '<option value="icon-pencil-1">icon-pencil-1</option> ';  
$output .= '<option value="icon-pencil-neg">icon-pencil-neg</option> ';  
$output .= '<option value="icon-pencil-5">icon-pencil-5</option> ';  
$output .= '<option value="icon-pencil-alt">icon-pencil-alt</option> ';   
$output .= '<option value="icon-pencil-alt-1">icon-pencil-alt-1</option> '; 
$output .= '<option value="icon-feather">icon-feather</option> ';
$output .= '<option value="icon-vector-pencil">icon-vector-pencil</option> '; 
$output .= '<option value="icon-ok">icon-ok</option> '; 
$output .= '<option value="icon-check-1">icon-check-1</option> '; 
$output .= '<option value="icon-ok-4">icon-ok-4</option> '; 
$output .= '<option value="icon-ok-6">icon-ok-6</option> ';  
$output .= '<option value="icon-ok-2">icon-ok-2</option> ';  
$output .= '<option value="icon-ok-3">icon-ok-3</option> ';  
$output .= '<option value="icon-ok-circle">icon-ok-circle</option> ';  
$output .= '<option value="icon-ok-circled">icon-ok-circled</option> ';  
$output .= '<option value="icon-ok-circle-1">icon-ok-circle-1</option> '; 
$output .= '<option value="icon-cancel-3">icon-cancel-3</option> '; 
$output .= '<option value="icon-cancel-7">icon-cancel-7</option> ';  
$output .= '<option value="icon-cancel-4">icon-cancel-4</option> ';  
$output .= '<option value="icon-cancel">icon-cancel</option> ';  
$output .= '<option value="icon-cancel-1">icon-cancel-1</option> ';  
$output .= '<option value="icon-cancel-circled-1">icon-cancel-circled-1</option> ';  
$output .= '<option value="icon-cancel-circled">icon-cancel-circled</option> ';   
$output .= '<option value="icon-cancel-circle-1">icon-cancel-circle-1</option> ';   
$output .= '<option value="icon-cancel-circle">icon-cancel-circle</option> ';   
$output .= '<option value="icon-cancel-circle-2">icon-cancel-circle-2</option> '; 
$output .= '<option value="icon-asterisk">icon-asterisk</option> ';  
$output .= '<option value="icon-cancel-5">icon-cancel-5</option> ';  
$output .= '<option value="icon-cancel-squared">icon-cancel-squared</option> ';  
$output .= '<option value="icon-help-circled-2">icon-help-circled-2</option> ';  
$output .= '<option value="icon-help-1">icon-help-1</option> ';   
$output .= '<option value="icon-help-2">icon-help-2</option> '; 
$output .= '<option value="icon-help-circled-alt">icon-help-circled-alt</option> ';  
$output .= '<option value="icon-attention-alt-1">icon-attention-alt-1</option> ';   
$output .= '<option value="icon-attention-circled">icon-attention-circled</option> ';  
$output .= '<option value="icon-attention-4">icon-attention-4</option> ';  
$output .= '<option value="icon-quote-left-alt">icon-quote-left-alt</option> '; 
$output .= '<option value="icon-quote-right-alt">icon-quote-right-alt</option> '; 
$output .= '<option value="icon-quote-left-1">icon-quote-left-1</option> ';  
$output .= '<option value="icon-quote">icon-quote</option> ';
$output .= '<option value="icon-quote-right-1">icon-quote-right-1</option> '; 
$output .= '<option value="icon-plus-circle">icon-plus-circle</option> '; 
$output .= '<option value="icon-plus-circled-1">icon-plus-circled-1</option> ';  
$output .= '<option value="icon-plus-circled">icon-plus-circled</option> '; 
$output .= '<option value="icon-plus-circle-1">icon-plus-circle-1</option> '; 
$output .= '<option value="icon-minus-circled">icon-minus-circled</option> ';  
$output .= '<option value="icon-minus-circled-1">icon-minus-circled-1</option> ';   
$output .= '<option value="icon-minus-circle-1">icon-minus-circle-1</option> ';  
$output .= '<option value="icon-minus-circle">icon-minus-circle</option> '; 
$output .= '<option value="icon-right-1">icon-right-1</option> '; 
$output .= '<option value="icon-direction-1">icon-direction-1</option> '; 
$output .= '<option value="icon-forward-1">icon-forward-1</option> ';  
$output .= '<option value="icon-forward">icon-forward</option> ';  
$output .= '<option value="icon-forward-4">icon-forward-4</option> '; 
$output .= '<option value="icon-ccw-1">icon-ccw-1</option> ';  
$output .= '<option value="icon-ccw">icon-ccw</option> ';  
$output .= '<option value="icon-cw-3">icon-cw-3</option> '; 
$output .= '<option value="icon-cw-2">icon-cw-2</option> '; 
$output .= '<option value="icon-cw-1">icon-cw-1</option> '; 
$output .= '<option value="icon-cw">icon-cw</option> ';  
$output .= '<option value="icon-cw-4">icon-cw-4</option> '; 
$output .= '<option value="icon-arrow-curved">icon-arrow-curved</option> ';  
$output .= '<option value="icon-squares">icon-squares</option> '; 
$output .= '<option value="icon-left-1">icon-left-1</option> ';  
$output .= '<option value="icon-up-1">icon-up-1</option> ';  
$output .= '<option value="icon-down-1">icon-down-1</option> ';  
$output .= '<option value="icon-resize-vertical">icon-resize-vertical</option> ';  
$output .= '<option value="icon-resize-vertical-1">icon-resize-vertical-1</option> ';  
$output .= '<option value="icon-resize-horizontal-1">icon-resize-horizontal-1</option> ';   
$output .= '<option value="icon-resize-horizontal">icon-resize-horizontal</option> ';  
$output .= '<option value="icon-eject">icon-eject</option> ';  
$output .= '<option value="icon-cog-4">icon-cog-4</option> '; 
$output .= '<option value="icon-zoom-out-4">icon-zoom-out-4</option> ';  
$output .= '<option value="icon-heart-7">icon-heart-7</option> ';  
$output .= '<option value="icon-sun-filled">icon-sun-filled</option> '; 
$output .= '<option value="icon-contrast">icon-contrast</option> '; 
$output .= '<option value="icon-cloud-7">icon-cloud-7</option> ';  
$output .= '<option value="icon-zoom-in-4">icon-zoom-in-4</option> '; 
$output .= '<option value="icon-star-7">icon-star-7</option> ';  
$output .= '<option value="icon-youtube-3">icon-youtube-3</option> ';  
$output .= '<option value="icon-anchor-outline">icon-anchor-outline</option> ';  
$output .= '<option value="icon-wrench-circled">icon-wrench-circled</option> ';  
$output .= '<option value="icon-list-add">icon-list-add</option> ';  
$output .= '<option value="icon-tv">icon-tv</option> ';   
$output .= '<option value="icon-anchor-1">icon-anchor-1</option> ';  
$output .= '<option value="icon-wrench-4">icon-wrench-4</option> ';  
$output .= '<option value="icon-sound-1">icon-sound-1</option> ';  
$output .= '<option value="icon-archive-1">icon-archive-1</option> ';  
$output .= '<option value="icon-wordpress-2">icon-wordpress-2</option> ';  
$output .= '<option value="icon-reply-outline">icon-reply-outline</option> ';  
$output .= '<option value="icon-videocam-5">icon-videocam-5</option> ';  
$output .= '<option value="icon-list-1">icon-list-1</option> ';  
$output .= '<option value="icon-accessibility">icon-accessibility</option> ';  
$output .= '<option value="icon-trash-8">icon-trash-8</option> ';  
$output .= '<option value="icon-reply-2">icon-reply-2</option> ';  
$output .= '<option value="icon-down-outline">icon-down-outline</option> ';   
$output .= '<option value="icon-website-circled">icon-website-circled</option> ';  
$output .= '<option value="icon-user-7">icon-user-7</option> ';  
$output .= '<option value="icon-down-2">icon-down-2</option> ';  
$output .= '<option value="icon-website">icon-website</option> '; 
$output .= '<option value="icon-key-5">icon-key-5</option> ';  
$output .= '<option value="icon-warning-1">icon-warning-1</option> '; 
$output .= '<option value="icon-search-7">icon-search-7</option> '; 
$output .= '<option value="icon-down-small">icon-down-small</option> '; 
$output .= '<option value="icon-forward-outline">icon-forward-outline</option> '; 
$output .= '<option value="icon-cog-6">icon-cog-6</option> ';  
$output .= '<option value="icon-w3c-1">icon-w3c-1</option> '; 
$output .= '<option value="icon-camera-7">icon-camera-7</option> ';  
$output .= '<option value="icon-forward-2">icon-forward-2</option> '; 
$output .= '<option value="icon-volume-up-3">icon-volume-up-3</option> ';  
$output .= '<option value="icon-tag-6">icon-tag-6</option> ';  
$output .= '<option value="icon-volume-off-4">icon-volume-off-4</option> ';
$output .= '<option value="icon-left-outline">icon-left-outline</option> '; 
$output .= '<option value="icon-lock-7">icon-lock-7</option> '; 
$output .= '<option value="icon-volume-down-2">icon-volume-down-2</option> '; 
$output .= '<option value="icon-left-2">icon-left-2</option> ';  
$output .= '<option value="icon-vimeo-4">icon-vimeo-4</option> '; 
$output .= '<option value="icon-left-small">icon-left-small</option> '; 
$output .= '<option value="icon-lightbulb-3">icon-lightbulb-3</option> '; 
$output .= '<option value="icon-view-mode">icon-view-mode</option> '; 
$output .= '<option value="icon-loop-alt-outline">icon-loop-alt-outline</option> '; 
$output .= '<option value="icon-pencil-7">icon-pencil-7</option> ';  
$output .= '<option value="icon-diamond">icon-diamond</option> ';  
$output .= '<option value="icon-loop-alt">icon-loop-alt</option> ';  
$output .= '<option value="icon-video-chat">icon-video-chat</option> '; 
$output .= '<option value="icon-video-circled">icon-video-circled</option> '; 
$output .= '<option value="icon-resize-full-outline">icon-resize-full-outline</option> ';
$output .= '<option value="icon-desktop-3">icon-desktop-3</option> '; 
$output .= '<option value="icon-video-4">icon-video-4</option> '; 
$output .= '<option value="icon-location-7">icon-location-7</option> '; 
$output .= '<option value="icon-resize-full-2">icon-resize-full-2<option>';  
$output .= '<option value="icon-resize-normal-outline">icon-resize-normal-outline<option>'; 
$output .= '<option value="icon-user-6">icon-user-6</option> ';  
$output .= '<option value="icon-eye-7">icon-eye-7</option> '; 
$output .= '<option value="icon-upload-5">icon-upload-5</option> ';  
$output .= '<option value="icon-comment-6">icon-comment-6</option> '; 
$output .= '<option value="icon-resize-normal">icon-resize-normal</option> ';  
$output .= '<option value="icon-inbox-4">icon-inbox-4</option> ';  
$output .= '<option value="icon-move-outline">icon-move-outline</option> '; 
$output .= '<option value="icon-lock-open-alt-2">icon-lock-open-alt-2</option> ';
$output .= '<option value="icon-cup-1">icon-cup-1</option> '; 
$output .= '<option value="icon-lock-open-6">icon-lock-open-6</option> '; 
$output .= '<option value="icon-move-1">icon-move-1</option> '; 
$output .= '<option value="icon-mobile-6">icon-mobile-6</option> ';  
$output .= '<option value="icon-loop-outline">icon-loop-outline</option> ';  
$output .= '<option value="icon-universal-access">icon-universal-access</option> '; 
$output .= '<option value="icon-doc-7">icon-doc-7</option> '; 
$output .= '<option value="icon-twitter-7">icon-twitter-7</option> '; 
$output .= '<option value="icon-tumblr-4">icon-tumblr-4</option> '; 
$output .= '<option value="icon-mail-7">icon-mail-7</option> '; 
$output .= '<option value="icon-right-outline">icon-right-outline</option> '; 
$output .= '<option value="icon-trash-circled">icon-trash-circled</option> '; 
$output .= '<option value="icon-thumbs-up-5">icon-thumbs-up-5</option> ';  
$output .= '<option value="icon-right-2">icon-right-2</option> '; 
$output .= '<option value="icon-right-small">icon-right-small</option> ';  
$output .= '<option value="icon-trash-7">icon-trash-7</option> '; 
$output .= '<option value="icon-photo-1">icon-photo-1</option> '; 
$output .= '<option value="icon-note-1">icon-note-1</option> '; 
$output .= '<option value="icon-torso">icon-torso</option> '; 
$output .= '<option value="icon-tint-1">icon-tint-1</option> '; 
$output .= '<option value="icon-clock-7">icon-clock-7</option> '; 
$output .= '<option value="icon-arrows-cw-outline">icon-arrows-cw-outline</option> '; 
$output .= '<option value="icon-arrows-cw-1">icon-arrows-cw-1</option> ';  
$output .= '<option value="icon-paper-plane-2">icon-paper-plane-2</option> '; 
$output .= '<option value="icon-clock-circled">icon-clock-circled</option> '; 
$output .= '<option value="icon-clock-6">icon-clock-6</option> '; 
$output .= '<option value="icon-params">icon-params</option> ';
$output .= '<option value="icon-up-outline">icon-up-outline</option> ';
$output .= '<option value="icon-thumbs-up-4">icon-thumbs-up-4</option> ';
$output .= '<option value="icon-money-2">icon-money-2</option> '; 
$output .= '<option value="icon-up-2">icon-up-2</option> ';
$output .= '<option value="icon-thumbs-down-4">icon-thumbs-down-4</option> '; 
$output .= '<option value="icon-database-2">icon-database-2</option> '; 
$output .= '<option value="icon-up-small">icon-up-small</option> '; 
$output .= '<option value="icon-at">icon-at</option> '; 
$output .= '<option value="icon-music-4">icon-music-4</option> '; 
$output .= '<option value="icon-th-list-4">icon-th-list-4</option> '; 
$output .= '<option value="icon-megaphone-3">icon-megaphone-3</option> '; 
$output .= '<option value="icon-th-large-2">icon-th-large-2</option> ';  
$output .= '<option value="icon-attach-outline">icon-attach-outline</option> ';  
$output .= '<option value="icon-attach-2">icon-attach-2</option> ';  
$output .= '<option value="icon-th-3">icon-th-3</option> '; 
$output .= '<option value="icon-graduation-cap-1">icon-graduation-cap-1</option> '; 
$output .= '<option value="icon-text-width-1">icon-text-width-1</option> ';  
$output .= '<option value="icon-cancel-alt">icon-cancel-alt</option> '; 
$output .= '<option value="icon-beaker-1">icon-beaker-1</option> '; 
$output .= '<option value="icon-text-height-1">icon-text-height-1</option> '; 
$output .= '<option value="icon-cancel-alt-filled">icon-cancel-alt-filled</option> '; 
$output .= '<option value="icon-food-1">icon-food-1</option> ';  
$output .= '<option value="icon-t-shirt">icon-t-shirt</option> '; 
$output .= '<option value="icon-tasks-1">icon-tasks-1</option> ';  
$output .= '<option value="icon-bat-charge">icon-bat-charge</option> ';
$output .= '<option value="icon-fire-3">icon-fire-3</option> '; 
$output .= '<option value="icon-bat4">icon-bat4</option> '; 
$output .= '<option value="icon-tags-2">icon-tags-2</option> '; 
$output .= '<option value="icon-attach-6">icon-attach-6</option> ';  
$output .= '<option value="icon-bat3">icon-bat3</option> ';  
$output .= '<option value="icon-tag-5">icon-tag-5</option> ';
$output .= '<option value="icon-stumbleupon-2">icon-stumbleupon-2</option> '; 
$output .= '<option value="icon-bat1">icon-bat1</option> ';
$output .= '<option value="icon-shop-1">icon-shop-1</option> ';
$output .= '<option value="icon-calendar-8">icon-calendar-8</option> '; 
$output .= '<option value="icon-bat2">icon-bat2</option> '; 
$output .= '<option value="icon-stop-circled">icon-stop-circled</option> ';
$output .= '<option value="icon-stop-6">icon-stop-6</option> ';
$output .= '<option value="icon-flask">icon-flask</option> '; 
$output .= '<option value="icon-wallet">icon-wallet</option> '; 
$output .= '<option value="icon-step-forward">icon-step-forward</option> ';
$output .= '<option value="icon-cd-3">icon-cd-3</option> '; 
$output .= '<option value="icon-beer-1">icon-beer-1</option> '; 
$output .= '<option value="icon-truck-1">icon-truck-1</option> '; 
$output .= '<option value="icon-step-backward">icon-step-backward</option> ';
$output .= '<option value="icon-bell-2">icon-bell-2</option> ';  
$output .= '<option value="icon-globe-6">icon-globe-6</option> ';  
$output .= '<option value="icon-star-empty-3">icon-star-empty-3</option> '; 
$output .= '<option value="icon-popup-2">icon-popup-2</option> ';
$output .= '<option value="icon-star-circled">icon-star-circled</option> '; 
$output .= '<option value="icon-star-6">icon-star-6</option> ';
$output .= '<option value="icon-briefcase-2">icon-briefcase-2</option> '; 
$output .= '<option value="icon-stackoverflow-2">icon-stackoverflow-2</option> ';
$output .= '<option value="icon-brush-1">icon-brush-1</option> ';  
$output .= '<option value="icon-volume-1">icon-volume-1</option> '; 
$output .= '<option value="icon-vcard-1">icon-vcard-1</option> '; 
$output .= '<option value="icon-calculator">icon-calculator</option> ';  
$output .= '<option value="icon-smiley-circled">icon-smiley-circled</option> '; 
$output .= '<option value="icon-smiley">icon-smiley</option> '; 
$output .= '<option value="icon-calendar-outlilne">icon-calendar-outlilne</option> '; 
$output .= '<option value="icon-slideshare">icon-slideshare</option> ';
$output .= '<option value="icon-calendar-2">icon-calendar-2</option> '; 
$output .= '<option value="icon-camera-outline">icon-camera-outline</option> '; 
$output .= '<option value="icon-skype-5">icon-skype-5</option> '; 
$output .= '<option value="icon-camera-2">icon-camera-2</option> ';  
$output .= '<option value="icon-signal-4">icon-signal-4</option> '; 
$output .= '<option value="icon-basket-circled">icon-basket-circled</option> ';
$output .= '<option value="icon-block-outline">icon-block-outline</option> '; 
$output .= '<option value="icon-basket-4">icon-basket-4</option> ';  
$output .= '<option value="icon-share-2">icon-share-2</option> '; 
$output .= '<option value="icon-chart-alt-outline">icon-chart-alt-outline</option> ';
$output .= '<option value="icon-export-5">icon-export-5</option> ';
$output .= '<option value="icon-chart-alt">icon-chart-alt</option> '; 
$output .= '<option value="icon-chart-bar-outline">icon-chart-bar-outline</option> '; 
$output .= '<option value="icon-search-circled">icon-search-circled</option> '; 
$output .= '<option value="icon-search-6">icon-search-6</option> ';  
$output .= '<option value="icon-chart-outline">icon-chart-outline</option> '; 
$output .= '<option value="icon-target-4">icon-target-4</option> '; 
$output .= '<option value="icon-desktop-circled">icon-desktop-circled</option> '; 
$output .= '<option value="icon-desktop-2">icon-desktop-2</option> '; 
$output .= '<option value="icon-chart-pie-outline">icon-chart-pie-outline</option> '; 
$output .= '<option value="icon-rss-6">icon-rss-6</option> ';
$output .= '<option value="icon-chart-pie-1">icon-chart-pie-1</option> ';
$output .= '<option value="icon-left-open-outline">icon-left-open-outline</option> ';
$output .= '<option value="icon-road-1">icon-road-1</option> '; 
$output .= '<option value="icon-backward-circled">icon-backward-circled</option> ';
$output .= '<option value="icon-left-open-2">icon-left-open-2</option> ';
$output .= '<option value="icon-right-open-outline">icon-right-open-outline</option> ';
$output .= '<option value="icon-retweet-3">icon-retweet-3</option> '; 
$output .= '<option value="icon-right-open-2">icon-right-open-2</option> ';
$output .= '<option value="icon-resize-vertical-2">icon-resize-vertical-2</option> ';
$output .= '<option value="icon-resize-small-4">icon-resize-small-4</option> ';
$output .= '<option value="icon-clipboard-1">icon-clipboard-1</option> '; 
$output .= '<option value="icon-play-circle2">icon-play-circle2</option> ';
$output .= '<option value="icon-resize-horizontal-2">icon-resize-horizontal-2</option> '; 
$output .= '<option value="icon-upload-cloud-2">icon-upload-cloud-2</option> '; 
$output .= '<option value="icon-resize-full-5">icon-resize-full-5</option> ';
$output .= '<option value="icon-code-outline">icon-code-outline</option> '; 
$output .= '<option value="icon-code-2">icon-code-2</option> '; 
$output .= '<option value="icon-cw-circled">icon-cw-circled</option> '; 
$output .= '<option value="icon-coffee-1">icon-coffee-1</option> '; 
$output .= '<option value="icon-cw-5">icon-cw-5</option> '; 
$output .= '<option value="icon-cog-outline">icon-cog-outline</option> ';
$output .= '<option value="icon-cancel-circled-4">icon-cancel-circled-4</option> '; 
$output .= '<option value="icon-cancel-circled2-1">icon-cancel-circled2-1</option> '; 
$output .= '<option value="icon-compass-2">icon-compass-2</option> '; 
$output .= '<option value="icon-cancel-6">icon-cancel-6</option> '; 
$output .= '<option value="icon-contacts">icon-contacts</option> '; 
$output .= '<option value="icon-arrows-cw-2">icon-arrows-cw-2</option> ';
$output .= '<option value="icon-credit-card-2">icon-credit-card-2</option> ';  
$output .= '<option value="icon-reddit-1">icon-reddit-1</option> ';
$output .= '<option value="icon-record-2">icon-record-2</option> '; 
$output .= '<option value="icon-upload-cloud-outline">icon-upload-cloud-outline</option> ';
$output .= '<option value="icon-database-1">icon-database-1</option> ';
$output .= '<option value="icon-shuffle-4">icon-shuffle-4</option> '; 
$output .= '<option value="icon-quote-circled">icon-quote-circled</option> ';
$output .= '<option value="icon-cancel-circled-outline">icon-cancel-circled-outline</option> '; 
$output .= '<option value="icon-cancel-circled-2">icon-cancel-circled-2</option> ';
$output .= '<option value="icon-quote-1">icon-quote-1</option> '; 
$output .= '<option value="icon-help-circled-3">icon-help-circled-3</option> ';
$output .= '<option value="icon-desktop-1">icon-desktop-1</option> ';
$output .= '<option value="icon-laptop-1">icon-laptop-1</option> '; 
$output .= '<option value="icon-help-3">icon-help-3</option> ';
$output .= '<option value="icon-qrcode-1">icon-qrcode-1</option> ';
$output .= '<option value="icon-tablet-1">icon-tablet-1</option> ';
$output .= '<option value="icon-print-6">icon-print-6</option> ';
$output .= '<option value="icon-address-1">icon-address-1</option> ';
$output .= '<option value="icon-plus-circled-2">icon-plus-circled-2</option> ';
$output .= '<option value="icon-divide-outline">icon-divide-outline</option> ';
$output .= '<option value="icon-plus-6">icon-plus-6</option> '; 
$output .= '<option value="icon-divide">icon-divide</option> ';  
$output .= '<option value="icon-play-circled2-1">icon-play-circled2-1</option> ';
$output .= '<option value="icon-play-circled-1">icon-play-circled-1</option> ';
$output .= '<option value="icon-doc-add">icon-doc-add</option> ';  
$output .= '<option value="icon-doc-remove">icon-doc-remove</option> '; 
$output .= '<option value="icon-play-5">icon-play-5</option> '; 
$output .= '<option value="icon-doc-text-2">icon-doc-text-2</option> ';
$output .= '<option value="icon-flight-2">icon-flight-2</option> ';
$output .= '<option value="icon-doc-2">icon-doc-2</option> ';
$output .= '<option value="icon-pinterest-3">icon-pinterest-3</option> ';
$output .= '<option value="icon-picture-4">icon-picture-4</option> '; 
$output .= '<option value="icon-download-outline">icon-download-outline</option> ';
$output .= '<option value="icon-picasa-2">icon-picasa-2</option> '; 
$output .= '<option value="icon-edit-1">icon-edit-1</option> ';
$output .= '<option value="icon-photo-circled">icon-photo-circled</option> '; 
$output .= '<option value="icon-eject-outline">icon-eject-outline</option> ';
$output .= '<option value="icon-photo">icon-photo</option> '; 
$output .= '<option value="icon-phone-circled">icon-phone-circled</option> '; 
$output .= '<option value="icon-eject-1">icon-eject-1</option> ';
$output .= '<option value="icon-phone-3">icon-phone-3</option> '; 
$output .= '<option value="icon-eq-outline">icon-eq-outline</option> '; 
$output .= '<option value="icon-person">icon-person</option> '; 
$output .= '<option value="icon-eq">icon-eq</option> ';
$output .= '<option value="icon-pencil-circled">icon-pencil-circled</option> '; 
$output .= '<option value="icon-export-outline">icon-export-outline</option> '; 
$output .= '<option value="icon-export-2">icon-export-2</option> ';
$output .= '<option value="icon-pencil-6">icon-pencil-6</option> '; 
$output .= '<option value="icon-eye-outline">icon-eye-outline</option> ';  
$output .= '<option value="icon-pause-circled">icon-pause-circled</option> '; 
$output .= '<option value="icon-pause-5">icon-pause-5</option> '; 
$output .= '<option value="icon-eye-2">icon-eye-2</option> ';
$output .= '<option value="icon-feather-1">icon-feather-1</option> ';
$output .= '<option value="icon-path">icon-path</option> ';
$output .= '<option value="icon-video-2">icon-video-2</option> '; 
$output .= '<option value="icon-attach-circled">icon-attach-circled</option> ';
$output .= '<option value="icon-flag-2">icon-flag-2</option> ';
$output .= '<option value="icon-attach-5">icon-attach-5</option> ';
$output .= '<option value="icon-flag-filled">icon-flag-filled</option> ';
$output .= '<option value="icon-ok-circled-2">icon-ok-circled-2</option> ';
$output .= '<option value="icon-ok-circled2-1">icon-ok-circled2-1</option> ';
$output .= '<option value="icon-flash-outline">icon-flash-outline</option> '; 
$output .= '<option value="icon-ok-5">icon-ok-5</option> ';
$output .= '<option value="icon-off-1">icon-off-1</option> ';
$output .= '<option value="icon-flow-split">icon-flow-split</option> '; 
$output .= '<option value="icon-network-1">icon-network-1</option> ';
$output .= '<option value="icon-flow-merge">icon-flow-merge</option> ';
$output .= '<option value="icon-music-3">icon-music-3</option> '; 
$output .= '<option value="icon-flow-parallel-1">icon-flow-parallel-1</option> ';  
$output .= '<option value="icon-move-3">icon-move-3</option> ';
$output .= '<option value="icon-flow-cross">icon-flow-cross</option> ';
$output .= '<option value="icon-folder-add">icon-folder-add</option> ';
$output .= '<option value="icon-minus-circled-2">icon-minus-circled-2</option> ';
$output .= '<option value="icon-folder-delete">icon-folder-delete</option> ';
$output .= '<option value="icon-minus-4">icon-minus-4</option> '; 
$output .= '<option value="icon-folder-2">icon-folder-2</option> '; 
$output .= '<option value="icon-mic-circled">icon-mic-circled</option> ';
$output .= '<option value="icon-gift-1">icon-gift-1</option> ';
$output .= '<option value="icon-mic-5">icon-mic-5</option> '; 
$output .= '<option value="icon-location-circled">icon-location-circled</option> ';
$output .= '<option value="icon-globe-alt-outline">icon-globe-alt-outline</option> ';
$output .= '<option value="icon-location-6">icon-location-6</option> '; 
$output .= '<option value="icon-male-2">icon-male-2</option> ';
$output .= '<option value="icon-users-outline">icon-users-outline</option> ';
$output .= '<option value="icon-users-2">icon-users-2</option> ';
$output .= '<option value="icon-magnet-2">icon-magnet-2</option> ';
$output .= '<option value="icon-headphones-1">icon-headphones-1</option> '; 
$output .= '<option value="icon-lock-circled">icon-lock-circled</option> '; 
$output .= '<option value="icon-lock-6">icon-lock-6</option> '; 
$output .= '<option value="icon-heart-2">icon-heart-2</option> ';
$output .= '<option value="icon-clipboard-2">icon-clipboard-2</option> ';
$output .= '<option value="icon-heart-filled">icon-heart-filled</option> ';
$output .= '<option value="icon-home-outline">icon-home-outline</option> ';
$output .= '<option value="icon-list-3">icon-list-3</option> ';
$output .= '<option value="icon-linkedin-6">icon-linkedin-6</option> ';
$output .= '<option value="icon-home-2">icon-home-2</option> ';
$output .= '<option value="icon-picture-outline">icon-picture-outline</option> ';
$output .= '<option value="icon-leaf-3">icon-leaf-3</option> '; 
$output .= '<option value="icon-picture-2">icon-picture-2</option> ';
$output .= '<option value="icon-laptop-circled">icon-laptop-circled</option> '; 
$output .= '<option value="icon-infinity-outline">icon-infinity-outline</option> '; 
$output .= '<option value="icon-laptop-2">icon-laptop-2</option> ';
$output .= '<option value="icon-key-4">icon-key-4</option> ';
$output .= '<option value="icon-info-outline">icon-info-outline</option> ';
$output .= '<option value="icon-italic-1">icon-italic-1</option> '; 
$output .= '<option value="icon-info-2">icon-info-2</option> ';
$output .= '<option value="icon-iphone-home">icon-iphone-home</option> '; 
$output .= '<option value="icon-attention-2">icon-attention-2</option> ';
$output .= '<option value="icon-instagram-4">icon-instagram-4</option> ';
$output .= '<option value="icon-info-circled-3">icon-info-circled-3</option> ';
$output .= '<option value="icon-indent-right-1">icon-indent-right-1</option> '; 
$output .= '<option value="icon-check-outline">icon-check-outline</option> '; 
$output .= '<option value="icon-check-2">icon-check-2</option> ';
$output .= '<option value="icon-indent-left-1">icon-indent-left-1</option> ';
$output .= '<option value="icon-right-hand-1">icon-right-hand-1</option> ';
$output .= '<option value="icon-key-outline">icon-key-outline</option> ';
$output .= '<option value="icon-left-hand-1">icon-left-hand-1</option> '; 
$output .= '<option value="icon-leaf-2">icon-leaf-2</option> ';  
$output .= '<option value="icon-down-hand-1">icon-down-hand-1</option> ';  
$output .= '<option value="icon-guidedog">icon-guidedog</option> ';
$output .= '<option value="icon-lightbulb-1">icon-lightbulb-1</option> ';  
$output .= '<option value="icon-group-circled">icon-group-circled</option> ';
$output .= '<option value="icon-link-outline">icon-link-outline</option> '; 
$output .= '<option value="icon-link-2">icon-link-2</option> '; 
$output .= '<option value="icon-group">icon-group</option> '; 
$output .= '<option value="icon-direction-outline">icon-direction-outline</option> ';
$output .= '<option value="icon-forward-circled">icon-forward-circled</option> '; 
$output .= '<option value="icon-forward-3">icon-forward-3</option> '; 
$output .= '<option value="icon-direction-2">icon-direction-2</option> '; 
$output .= '<option value="icon-location-outline">icon-location-outline</option> ';
$output .= '<option value="icon-fontsize-1">icon-fontsize-1</option> '; 
$output .= '<option value="icon-font-1">icon-font-1</option> '; 
$output .= '<option value="icon-location-2">icon-location-2</option> '; 
$output .= '<option value="icon-folder-circled">icon-folder-circled</option> ';
$output .= '<option value="icon-lock-2">icon-lock-2</option> ';
$output .= '<option value="icon-lock-filled">icon-lock-filled</option> ';
$output .= '<option value="icon-folder-open-2">icon-folder-open-2</option> ';
$output .= '<option value="icon-female-2">icon-female-2</option> ';
$output .= '<option value="icon-lock-open-2">icon-lock-open-2</option> ';
$output .= '<option value="icon-fast-forward-2">icon-fast-forward-2</option> ';
$output .= '<option value="icon-lock-open-filled">icon-lock-open-filled</option> '; 
$output .= '<option value="icon-mail-2">icon-mail-2</option> '; 
$output .= '<option value="icon-fast-backward-2">icon-fast-backward-2</option> ';
$output .= '<option value="icon-map-1">icon-map-1</option> ';
$output .= '<option value="icon-videocam-4">icon-videocam-4</option> ';
$output .= '<option value="icon-facebook-7">icon-facebook-7</option> ';
$output .= '<option value="icon-eject-alt-outline">icon-eject-alt-outline</option> ';
$output .= '<option value="icon-eject-alt">icon-eject-alt</option> ';
$output .= '<option value="icon-eye-6">icon-eye-6</option> '; 
$output .= '<option value="icon-gauge-2">icon-gauge-2</option> '; 
$output .= '<option value="icon-fast-fw-outline">icon-fast-fw-outline</option> ';  
$output .= '<option value="icon-fast-fw-1">icon-fast-fw-1</option> '; 
$output .= '<option value="icon-css">icon-css</option> '; 
$output .= '<option value="icon-credit-card-4">icon-credit-card-4</option> ';
$output .= '<option value="icon-pause-outline">icon-pause-outline</option> ';
$output .= '<option value="icon-pause-2">icon-pause-2</option> ';
$output .= '<option value="icon-compass-circled">icon-compass-circled</option> '; 
$output .= '<option value="icon-play-outline">icon-play-outline</option> '; 
$output .= '<option value="icon-compass-5">icon-compass-5</option> ';  
$output .= '<option value="icon-play-2">icon-play-2</option> ';  
$output .= '<option value="icon-comment-alt-1">icon-comment-alt-1</option> '; 
$output .= '<option value="icon-record-outline">icon-record-outline</option> ';  
$output .= '<option value="icon-down-open-3">icon-down-open-3</option> ';  
$output .= '<option value="icon-check-empty-1">icon-check-empty-1</option> '; 
$output .= '<option value="icon-record-1">icon-record-1</option> ';
$output .= '<option value="icon-check-3">icon-check-3</option> '; 
$output .= '<option value="icon-rewind-outline">icon-rewind-outline</option> '; 
$output .= '<option value="icon-certificate-2">icon-certificate-2</option> '; 
$output .= '<option value="icon-rewind">icon-rewind</option> ';
$output .= '<option value="icon-cc-2">icon-cc-2</option> ';
$output .= '<option value="icon-stop-outline">icon-stop-outline</option> '; 
$output .= '<option value="icon-camera-6">icon-camera-6</option> ';
$output .= '<option value="icon-stop-2">icon-stop-2</option> '; 
$output .= '<option value="icon-chat-2">icon-chat-2</option> ';
$output .= '<option value="icon-block-4">icon-block-4</option> ';
$output .= '<option value="icon-comment-2">icon-comment-2</option> ';
$output .= '<option value="icon-backward">icon-backward</option> '; 
$output .= '<option value="icon-asterisk-1">icon-asterisk-1</option> ';
$output .= '<option value="icon-chat-alt">icon-chat-alt</option> ';
$output .= '<option value="icon-mic-outline">icon-mic-outline</option> ';
$output .= '<option value="icon-asl">icon-asl</option> ';
$output .= '<option value="icon-up-6">icon-up-6</option> ';
$output .= '<option value="icon-minus-outline">icon-minus-outline</option> ';
$output .= '<option value="icon-right-5">icon-right-5</option> '; 
$output .= '<option value="icon-heart-circled">icon-heart-circled</option> ';
$output .= '<option value="icon-minus-2">icon-minus-2</option> '; 
$output .= '<option value="icon-news">icon-news</option> ';
$output .= '<option value="icon-heart-6">icon-heart-6</option> '; 
$output .= '<option value="icon-music-outline">icon-music-outline</option> ';
$output .= '<option value="icon-hearing-impaired">icon-hearing-impaired</option> '; 
$output .= '<option value="icon-headphones-3">icon-headphones-3</option> '; 
$output .= '<option value="icon-music-2">icon-music-2</option> ';
$output .= '<option value="icon-hdd-2">icon-hdd-2</option> ';  
$output .= '<option value="icon-pen">icon-pen</option> ';
$output .= '<option value="icon-up-hand-1">icon-up-hand-1</option> ';
$output .= '<option value="icon-phone-outline">icon-phone-outline</option> '; 
$output .= '<option value="icon-github-6">icon-github-6</option> ';
$output .= '<option value="icon-gift-2">icon-gift-2</option> '; 
$output .= '<option value="icon-resize-full-alt-2">icon-resize-full-alt-2</option> '; 
$output .= '<option value="icon-pi-outline">icon-pi-outline</option> ';
$output .= '<option value="icon-friendfeed-rect-1">icon-friendfeed-rect-1</option> ';
$output .= '<option value="icon-pi">icon-pi</option> '; 
$output .= '<option value="icon-friendfeed-1">icon-friendfeed-1</option> '; 
$output .= '<option value="icon-pin-outline">icon-pin-outline</option> '; 
$output .= '<option value="icon-pin-1">icon-pin-1</option> '; 
$output .= '<option value="icon-foursquare-2">icon-foursquare-2</option> '; 
$output .= '<option value="icon-doc-new-circled">icon-doc-new-circled</option> ';
$output .= '<option value="icon-pipette">icon-pipette</option> '; 
$output .= '<option value="icon-plane-outline">icon-plane-outline</option> '; 
$output .= '<option value="icon-doc-new">icon-doc-new</option> ';
$output .= '<option value="icon-plane">icon-plane</option> '; 
$output .= '<option value="icon-edit-circled">icon-edit-circled</option> ';
$output .= '<option value="icon-edit-3">icon-edit-3</option> '; 
$output .= '<option value="icon-plug">icon-plug</option> ';
$output .= '<option value="icon-plus-outline">icon-plus-outline</option> '; 
$output .= '<option value="icon-doc-circled">icon-doc-circled</option> ';
$output .= '<option value="icon-doc-6">icon-doc-6</option> '; 
$output .= '<option value="icon-plus-2">icon-plus-2</option> '; 
$output .= '<option value="icon-dribbble-5">icon-dribbble-5</option> ';
$output .= '<option value="icon-looped-square-outline">icon-looped-square-outline</option> '; 
$output .= '<option value="icon-looped-square-interest">icon-looped-square-interest</option> ';

$output .= '<option value="icon-download-alt">icon-download-alt</option> ';
$output .= '<option value="icon-download-6">icon-download-6</option> ';  
$output .= '<option value="icon-power-outline">icon-power-outline</option> '; 
$output .= '<option value="icon-power">icon-power</option> ';
$output .= '<option value="icon-digg-1">icon-digg-1</option> ';
$output .= '<option value="icon-deviantart-1">icon-deviantart-1</option> '; 
$output .= '<option value="icon-print-2">icon-print-2</option> '; 
$output .= '<option value="icon-puzzle-outline">icon-puzzle-outline</option> ';  
$output .= '<option value="icon-delicious-1">icon-delicious-1</option> ';  
$output .= '<option value="icon-left-circled-2">icon-left-circled-2</option> ';  
$output .= '<option value="icon-puzzle-1">icon-puzzle-1</option> ';
$output .= '<option value="icon-down-circled-2">icon-down-circled-2</option> '; 
$output .= '<option value="icon-target-outline">icon-target-outline</option> '; 
$output .= '<option value="icon-child">icon-child</option> ';
$output .= '<option value="icon-up-open-3">icon-up-open-3</option> ';
$output .= '<option value="icon-cw-outline">icon-cw-outline</option> ';  
$output .= '<option value="icon-right-open-4">icon-right-open-4</option> '; 
$output .= '<option value="icon-left-open-4">icon-left-open-4</option> '; 
$output .= '<option value="icon-rss-outline">icon-rss-outline</option> ';
$output .= '<option value="icon-bold-1">icon-bold-1</option> '; 
$output .= '<option value="icon-rss-2">icon-rss-2</option> '; 
$output .= '<option value="icon-scissors-outline">icon-scissors-outline</option> ';
$output .= '<option value="icon-blogger-2">icon-blogger-2</option> '; 
$output .= '<option value="icon-blind">icon-blind</option> '; 
$output .= '<option value="icon-box-2">icon-box-2</option> '; 
$output .= '<option value="icon-bell-5">icon-bell-5</option> '; 
$output .= '<option value="icon-basket-2">icon-basket-2</option> '; 
$output .= '<option value="icon-behance-1">icon-behance-1</option> '; 
$output .= '<option value="icon-at-circled">icon-at-circled</option> '; 
$output .= '<option value="icon-barcode-1">icon-barcode-1</option> ';
$output .= '<option value="icon-dribbble-circled-1">icon-dribbble-circled-1</option> '; 
$output .= '<option value="icon-left-5">icon-left-5</option> '; 
$output .= '<option value="icon-dribbble-2">icon-dribbble-2</option> ';
$output .= '<option value="icon-down-6">icon-down-6</option> '; 
$output .= '<option value="icon-facebook-circled-1">icon-facebook-circled-1</option> ';
$output .= '<option value="icon-align-right-1">icon-align-right-1</option> ';
$output .= '<option value="icon-align-left-1">icon-align-left-1</option> ';
$output .= '<option value="icon-facebook-2">icon-facebook-2</option> ';
$output .= '<option value="icon-flickr-circled-1">icon-flickr-circled-1</option> ';
$output .= '<option value="icon-align-justify-1">icon-align-justify-1</option> ';
$output .= '<option value="icon-align-center-1">icon-align-center-1</option> ';
$output .= '<option value="icon-flickr-2">icon-flickr-2</option> '; 
$output .= '<option value="icon-adult">icon-adult</option> ';
$output .= '<option value="icon-github-circled-2">icon-github-circled-2</option> ';
$output .= '<option value="icon-github-2">icon-github-2</option> ';
$output .= '<option value="icon-adjust-1">icon-adjust-1</option> '; 
$output .= '<option value="icon-lastfm-circled-1">icon-lastfm-circled-1</option> '; 
$output .= '<option value="icon-address-book-alt">icon-address-book-alt</option> '; 
$output .= '<option value="icon-lastfm-1">icon-lastfm-1</option> '; 
$output .= '<option value="icon-address-book">icon-address-book</option> ';
$output .= '<option value="icon-lightbulb-2">icon-lightbulb-2</option> ';
$output .= '<option value="icon-linkedin-circled-1">icon-linkedin-circled-1</option> '; 
$output .= '<option value="icon-linkedin-2">icon-linkedin-2</option> ';
$output .= '<option value="icon-home-circled">icon-home-circled</option> '; 
$output .= '<option value="icon-home-6">icon-home-6</option> '; 
$output .= '<option value="icon-pinterest-circled-2">icon-pinterest-circled-2</option> '; 
$output .= '<option value="icon-heart-empty-3">icon-heart-empty-3</option> '; 
$output .= '<option value="icon-pinterest-1">icon-pinterest-1</option> '; 
$output .= '<option value="icon-skype-outline">icon-skype-outline</option> '; 
$output .= '<option value="icon-globe-5">icon-globe-5</option> ';
$output .= '<option value="icon-skype-2">icon-skype-2</option> ';  
$output .= '<option value="icon-glasses">icon-glasses</option> '; 
$output .= '<option value="icon-glass-1">icon-glass-1</option> '; 
$output .= '<option value="icon-tumbler-circled">icon-tumbler-circled</option> '; 
$output .= '<option value="icon-tumbler">icon-tumbler</option> '; 
$output .= '<option value="icon-github-text-1">icon-github-text-1</option> '; 
$output .= '<option value="icon-twitter-circled-1">icon-twitter-circled-1</option> ';
$output .= '<option value="icon-flag-3">icon-flag-3</option> ';  
$output .= '<option value="icon-twitter-2">icon-twitter-2</option> '; 
$output .= '<option value="icon-fire-2">icon-fire-2</option> '; 
$output .= '<option value="icon-filter-1">icon-filter-1</option> ';
$output .= '<option value="icon-vimeo-circled-1">icon-vimeo-circled-1</option> '; 
$output .= '<option value="icon-vimeo-1">icon-vimeo-1</option> '; 
$output .= '<option value="icon-video-alt-1">icon-video-alt-1</option> ';
$output .= '<option value="icon-sort-alphabet-outline">icon-sort-alphabet-outline</option> ';
$output .= '<option value="icon-mail-circled">icon-mail-circled</option> '; 
$output .= '<option value="icon-sort-alphabet">icon-sort-alphabet</option> '; 
$output .= '<option value="icon-mail-6">icon-mail-6</option> ';
$output .= '<option value="icon-sort-numeric-outline">icon-sort-numeric-outline</option> ';
$output .= '<option value="icon-eject-3">icon-eject-3</option> ';
$output .= '<option value="icon-edit-alt">icon-edit-alt</option> '; 
$output .= '<option value="icon-sort-numeric">icon-sort-numeric</option> '; 
$output .= '<option value="icon-cloud-circled">icon-cloud-circled</option> '; 
$output .= '<option value="icon-wrench-outline">icon-wrench-outline</option> ';
$output .= '<option value="icon-cloud-6">icon-cloud-6</option> '; 
$output .= '<option value="icon-up-circled-2">icon-up-circled-2</option> '; 
$output .= '<option value="icon-star-2">icon-star-2</option> '; 
$output .= '<option value="icon-right-circled-2">icon-right-circled-2</option> ';
$output .= '<option value="icon-star-filled">icon-star-filled</option> ';
$output .= '<option value="icon-braille">icon-braille</option> '; 
$output .= '<option value="icon-certificate-outline">icon-certificate-outline</option> ';
$output .= '<option value="icon-certificate-1">icon-certificate-1</option> '; 
$output .= '<option value="icon-bookmark-empty-1">icon-bookmark-empty-1</option> ';
$output .= '<option value="icon-bookmark-3">icon-bookmark-3</option> '; 
$output .= '<option value="icon-stopwatch">icon-stopwatch</option> '; 
$output .= '<option value="icon-book-4">icon-book-4</option> ';
$output .= '<option value="icon-lifebuoy-1">icon-lifebuoy-1</option> '; 
$output .= '<option value="icon-popup-1">icon-popup-1</option> ';
$output .= '<option value="icon-inbox-alt">icon-inbox-alt</option> ';
$output .= '<option value="icon-inbox-circled">icon-inbox-circled</option> ';
$output .= '<option value="icon-tag-2">icon-tag-2</option> '; 
$output .= '<option value="icon-tags-1">icon-tags-1</option> '; 
$output .= '<option value="icon-inbox-3">icon-inbox-3</option> ';  
$output .= '<option value="icon-lightbulb-alt">icon-lightbulb-alt</option> '; 
$output .= '<option value="icon-th-large-outline">icon-th-large-outline</option> '; 
$output .= '<option value="icon-chart-circled">icon-chart-circled</option> ';
$output .= '<option value="icon-th-large-1">icon-th-large-1</option> '; 
$output .= '<option value="icon-th-list-outline">icon-th-list-outline</option> ';  
$output .= '<option value="icon-chart-2">icon-chart-2</option> ';
$output .= '<option value="icon-th-list-1">icon-th-list-1</option> '; 
$output .= '<option value="icon-googleplus">icon-googleplus</option> '; 
$output .= '<option value="icon-menu-outline">icon-menu-outline</option> '; 
$output .= '<option value="icon-globe-alt-1">icon-globe-alt-1</option> '; 
$output .= '<option value="icon-menu-2">icon-menu-2</option> ';
$output .= '<option value="icon-folder-close">icon-folder-close</option> '; 
$output .= '<option value="icon-th-outline">icon-th-outline</option> '; 
$output .= '<option value="icon-folder-5">icon-folder-5</option> ';
$output .= '<option value="icon-th-1">icon-th-1</option> ';
$output .= '<option value="icon-flickr-4">icon-flickr-4</option> ';
$output .= '<option value="icon-flag-circled">icon-flag-circled</option> ';
$output .= '<option value="icon-temperatire">icon-temperatire</option> ';
$output .= '<option value="icon-eye-off-1">icon-eye-off-1</option> ';
$output .= '<option value="icon-exclamation">icon-exclamation</option> '; 
$output .= '<option value="icon-error-alt">icon-error-alt</option> ';
$output .= '<option value="icon-ok-outline">icon-ok-outline</option> ';
$output .= '<option value="icon-error">icon-error</option> ';
$output .= '<option value="icon-ok-1">icon-ok-1</option> '; 
$output .= '<option value="icon-ticket-2">icon-ticket-2</option> ';
$output .= '<option value="icon-comment-5">icon-comment-5</option> '; 
$output .= '<option value="icon-cogs">icon-cogs</option> '; 
$output .= '<option value="icon-cog-circled">icon-cog-circled</option> '; 
$output .= '<option value="icon-cancel-outline">icon-cancel-outline</option> ';
$output .= '<option value="icon-cancel-2">icon-cancel-2</option> ';
$output .= '<option value="icon-cog-5">icon-cog-5</option> '; 
$output .= '<option value="icon-trash-2">icon-trash-2</option> '; 
$output .= '<option value="icon-calendar-circled">icon-calendar-circled</option> ';  
$output .= '<option value="icon-calendar-7">icon-calendar-7</option> '; 
$output .= '<option value="icon-tree">icon-tree</option> ';  
$output .= '<option value="icon-upload-outline">icon-upload-outline</option> ';
$output .= '<option value="icon-megaphone-2">icon-megaphone-2</option> '; 
$output .= '<option value="icon-briefcase-3">icon-briefcase-3</option> ';
$output .= '<option value="icon-upload-2">icon-upload-2</option> '; 
$output .= '<option value="icon-vkontakte-2">icon-vkontakte-2</option> '; 
$output .= '<option value="icon-user-add-outline">icon-user-add-outline</option> ';
$output .= '<option value="icon-user-add-1">icon-user-add-1</option> '; 
$output .= '<option value="icon-user-delete-outline">icon-user-delete-outline</option> ';
$output .= '<option value="icon-user-delete">icon-user-delete</option> '; 
$output .= '<option value="icon-user-outline">icon-user-outline</option> '; 
$output .= '<option value="icon-user-2">icon-user-2</option> ';
$output .= '<option value="icon-videocam-outline">icon-videocam-outline</option> '; 
$output .= '<option value="icon-videocam-1">icon-videocam-1</option> '; 
$output .= '<option value="icon-volume-middle">icon-volume-middle</option> ';
$output .= '<option value="icon-volume-off-1">icon-volume-off-1</option> '; 
$output .= '<option value="icon-volume-high">icon-volume-high</option> '; 
$output .= '<option value="icon-volume-low">icon-volume-low</option> '; 
$output .= '<option value="icon-warning-empty">icon-warning-empty</option> ';
$output .= '<option value="icon-warning">icon-warning</option> '; 
$output .= '<option value="icon-wristwatch">icon-wristwatch</option> ';
$output .= '<option value="icon-waves-outline">icon-waves-outline</option> ';
$output .= '<option value="icon-waves">icon-waves</option> '; 
$output .= '<option value="icon-cloud-2">icon-cloud-2</option> '; 
$output .= '<option value="icon-rain">icon-rain</option> '; 
$output .= '<option value="icon-moon-2">icon-moon-2</option> '; 
$output .= '<option value="icon-cloud-sun">icon-cloud-sun</option> ';
$output .= '<option value="icon-drizzle">icon-drizzle</option> '; 
$output .= '<option value="icon-snow">icon-snow</option> ';
$output .= '<option value="icon-cloud-flash">icon-cloud-flash</option> '; 
$output .= '<option value="icon-cloud-wind">icon-cloud-wind</option> '; 
$output .= '<option value="icon-wind">icon-wind</option> ';
$output .= '<option value="icon-wifi-outline">icon-wifi-outline</option> ';
$output .= '<option value="icon-wifi">icon-wifi</option> ';
$output .= '<option value="icon-wine">icon-wine</option> ';
$output .= '<option value="icon-globe-outline">icon-globe-outline</option> ';
$output .= '<option value="icon-zoom-in-outline">icon-zoom-in-outline</option> '; 
$output .= '<option value="icon-zoom-in-1">icon-zoom-in-1</option> ';
$output .= '<option value="icon-zoom-out-outline">icon-zoom-out-outline</option> '; 
$output .= '<option value="icon-zoom-out-1">icon-zoom-out-1</option> ';
$output .= '<option value="icon-search-outline">icon-search-outline</option> ';
$output .= '<option value="icon-search-2">icon-search-2</option> '; 
$output .= '<option value="icon-left-circle">icon-left-circle</option> '; 
$output .= '<option value="icon-left-circle-1">icon-left-circle-1</option> '; 
$output .= '<option value="icon-right-circle-1">icon-right-circle-1</option> ';
$output .= '<option value="icon-right-circle">icon-right-circle</option> ';
$output .= '<option value="icon-up-circle">icon-up-circle</option> '; 
$output .= '<option value="icon-up-circle-1">icon-up-circle-1</option> '; 
$output .= '<option value="icon-down-circle">icon-down-circle</option> '; 
$output .= '<option value="icon-down-circle-1">icon-down-circle-1</option> '; 
$output .= '<option value="icon-left-bold">icon-left-bold</option> ';  
$output .= '<option value="icon-right-bold">icon-right-bold</option> ';
$output .= '<option value="icon-up-bold">icon-up-bold</option> '; 
$output .= '<option value="icon-down-bold">icon-down-bold</option> ';
$output .= '<option value="icon-user-add">icon-user-add</option> ';  
$output .= '<option value="icon-star-half">icon-star-half</option> '; 
$output .= '<option value="icon-ok-circled2">icon-ok-circled2</option> ';
$output .= '<option value="icon-cancel-circled2">icon-cancel-circled2</option> ';  
$output .= '<option value="icon-help-circled-1">icon-help-circled-1</option> ';
$output .= '<option value="icon-help-circled">icon-help-circled</option> '; 
$output .= '<option value="icon-info-circled-1">icon-info-circled-1</option> '; 
$output .= '<option value="icon-info-circled">icon-info-circled</option> '; 
$output .= '<option value="icon-th-large-3">icon-th-large-3</option> ';
$output .= '<option value="icon-th-large">icon-th-large</option> ';
$output .= '<option value="icon-lock-empty">icon-lock-empty</option> '; 
$output .= '<option value="icon-lock-open-empty">icon-lock-open-empty</option> ';
$output .= '<option value="icon-eye-3">icon-eye-3</option> '; 
$output .= '<option value="icon-eye">icon-eye</option> ';  
$output .= '<option value="icon-eye-1">icon-eye-1</option> '; 
$output .= '<option value="icon-eye-4">icon-eye-4</option> ';
$output .= '<option value="icon-eye-off">icon-eye-off</option> '; 
$output .= '<option value="icon-tag-1">icon-tag-1</option> '; 
$output .= '<option value="icon-tag-3">icon-tag-3</option> ';
$output .= '<option value="icon-tag">icon-tag</option> ';  
$output .= '<option value="icon-tag-7">icon-tag-7</option> ';  
$output .= '<option value="icon-tag-4">icon-tag-4</option> ';
$output .= '<option value="icon-tags">icon-tags</option> '; 
$output .= '<option value="icon-tag-empty">icon-tag-empty</option> ';
$output .= '<option value="icon-camera-alt">icon-camera-alt</option> ';  
$output .= '<option value="icon-download-cloud-1">icon-download-cloud-1</option> ';   
$output .= '<option value="icon-upload-cloud-1">icon-upload-cloud-1</option> '; 
$output .= '<option value="icon-upload-cloud-3">icon-upload-cloud-3</option> '; 
$output .= '<option value="icon-reply-4">icon-reply-4</option> ';  
$output .= '<option value="icon-reply-1">icon-reply-1</option> ';  
$output .= '<option value="icon-reply-all-2">icon-reply-all-2</option> '; 
$output .= '<option value="icon-reply-all-1">icon-reply-all-1</option> ';
$output .= '<option value="icon-code-3">icon-code-3</option> '; 
$output .= '<option value="icon-code-1">icon-code-1</option> '; 
$output .= '<option value="icon-export-3">icon-export-3</option> ';
$output .= '<option value="icon-export">icon-export</option> '; 
$output .= '<option value="icon-export-1">icon-export-1</option> '; 
$output .= '<option value="icon-print">icon-print</option> ';  
$output .= '<option value="icon-print-1">icon-print-1</option> ';  
$output .= '<option value="icon-print-3">icon-print-3</option> '; 
$output .= '<option value="icon-retweet">icon-retweet</option> '; 
$output .= '<option value="icon-retweet-4">icon-retweet-4</option> ';  
$output .= '<option value="icon-retweet-1">icon-retweet-1</option> ';  
$output .= '<option value="icon-comment-7">icon-comment-7</option> ';  
$output .= '<option value="icon-comment-1">icon-comment-1</option> '; 
$output .= '<option value="icon-comment">icon-comment</option> '; 
$output .= '<option value="icon-comment-3">icon-comment-3</option> '; 
$output .= '<option value="icon-comment-inv">icon-comment-inv</option> '; 
$output .= '<option value="icon-comment-alt-2">icon-comment-alt-2</option> '; 
$output .= '<option value="icon-comment-alt">icon-comment-alt</option> '; 
$output .= '<option value="icon-comment-inv-alt">icon-comment-inv-alt</option> ';  
$output .= '<option value="icon-comment-alt2">icon-comment-alt2</option> ';  
$output .= '<option value="icon-comment-inv-alt2">icon-comment-inv-alt2</option> '; 
$output .= '<option value="icon-chat-3">icon-chat-3</option> '; 
$output .= '<option value="icon-chat">icon-chat</option> ';  
$output .= '<option value="icon-chat-6">icon-chat-6</option> '; 
$output .= '<option value="icon-chat-1">icon-chat-1</option> ';  
$output .= '<option value="icon-chat-4">icon-chat-4</option> '; 
$output .= '<option value="icon-chat-inv">icon-chat-inv</option> ';  
$output .= '<option value="icon-vcard">icon-vcard</option> '; 
$output .= '<option value="icon-address">icon-address</option> '; 
$output .= '<option value="icon-location-3">icon-location-3</option> '; 
$output .= '<option value="icon-location-8">icon-location-8</option> ';
$output .= '<option value="icon-location-1">icon-location-1</option> ';
$output .= '<option value="icon-location-4">icon-location-4</option> '; 
$output .= '<option value="icon-location">icon-location</option> '; 
$output .= '<option value="icon-location-inv">icon-location-inv</option> ';
$output .= '<option value="icon-location-alt">icon-location-alt</option> '; 
$output .= '<option value="icon-map">icon-map</option> '; 
$output .= '<option value="icon-compass-1">icon-compass-1</option> ';
$output .= '<option value="icon-compass-3">icon-compass-3</option> ';  
$output .= '<option value="icon-trash-1">icon-trash-1</option> ';  
$output .= '<option value="icon-trash">icon-trash</option> '; 
$output .= '<option value="icon-trash-3">icon-trash-3</option> '; 
$output .= '<option value="icon-trash-4">icon-trash-4</option> ';
$output .= '<option value="icon-trash-empty">icon-trash-empty</option> '; 
$output .= '<option value="icon-doc-8">icon-doc-8</option> '; 
$output .= '<option value="icon-doc-1">icon-doc-1</option> ';
$output .= '<option value="icon-doc-3">icon-doc-3</option> ';
$output .= '<option value="icon-doc-inv-1">icon-doc-inv-1</option> '; 
$output .= '<option value="icon-doc-text-inv-1">icon-doc-text-inv-1</option> ';  
$output .= '<option value="icon-doc-alt">icon-doc-alt</option> '; 
$output .= '<option value="icon-doc-inv-alt">icon-doc-inv-alt</option> ';  
$output .= '<option value="icon-article-1">icon-article-1</option> ';
$output .= '<option value="icon-article">icon-article</option> '; 
$output .= '<option value="icon-article-alt">icon-article-alt</option> ';
$output .= '<option value="icon-article-alt-1">icon-article-alt-1</option> ';
$output .= '<option value="icon-docs-1">icon-docs-1</option> ';
$output .= '<option value="icon-docs-landscape">icon-docs-landscape</option> ';
$output .= '<option value="icon-doc-landscape">icon-doc-landscape</option> '; 
$output .= '<option value="icon-archive">icon-archive</option> '; 
$output .= '<option value="icon-archive-2">icon-archive-2</option> '; 
$output .= '<option value="icon-rss-3">icon-rss-3</option> '; 
$output .= '<option value="icon-rss-1">icon-rss-1</option> '; 
$output .= '<option value="icon-rss-7">icon-rss-7</option> '; 
$output .= '<option value="icon-rss-4">icon-rss-4</option> ';
$output .= '<option value="icon-rss-alt-1">icon-rss-alt-1</option> ';
$output .= '<option value="icon-rss-alt">icon-rss-alt</option> ';
$output .= '<option value="icon-share">icon-share</option> '; 
$output .= '<option value="icon-share-1">icon-share-1</option> ';
$output .= '<option value="icon-basket">icon-basket</option> '; 
$output .= '<option value="icon-basket-3">icon-basket-3</option> ';
$output .= '<option value="icon-basket-1">icon-basket-1</option> '; 
$output .= '<option value="icon-calendar-inv">icon-calendar-inv</option> '; 
$output .= '<option value="icon-calendar-alt-1">icon-calendar-alt-1</option> '; 
$output .= '<option value="icon-shareable">icon-shareable</option> '; 
$output .= '<option value="icon-login">icon-login</option> '; 
$output .= '<option value="icon-login-1">icon-login-1</option> ';
$output .= '<option value="icon-logout-3">icon-logout-3</option> '; 
$output .= '<option value="icon-logout-1">icon-logout-1</option> '; 
$output .= '<option value="icon-logout">icon-logout</option> ';  
$output .= '<option value="icon-volume">icon-volume</option> '; 
$output .= '<option value="icon-resize-full-4">icon-resize-full-4</option> ';
$output .= '<option value="icon-resize-full">icon-resize-full</option> '; 
$output .= '<option value="icon-resize-full-1">icon-resize-full-1</option> ';  
$output .= '<option value="icon-resize-full-6">icon-resize-full-6</option> '; 
$output .= '<option value="icon-resize-full-3">icon-resize-full-3</option> '; 
$output .= '<option value="icon-resize-full-alt-1">icon-resize-full-alt-1</option> '; 
$output .= '<option value="icon-resize-small-1">icon-resize-small-1</option> ';
$output .= '<option value="icon-resize-small-2">icon-resize-small-2</option> '; 
$output .= '<option value="icon-resize-small-3">icon-resize-small-3</option> '; 
$output .= '<option value="icon-resize-small">icon-resize-small</option> '; 
$output .= '<option value="icon-resize-small-alt">icon-resize-small-alt</option> '; 
$output .= '<option value="icon-move-2">icon-move-2</option> '; 
$output .= '<option value="icon-popup-4">icon-popup-4</option> '; 
$output .= '<option value="icon-popup-3">icon-popup-3</option> '; 
$output .= '<option value="icon-popup">icon-popup</option> '; 
$output .= '<option value="icon-popup-5">icon-popup-5</option> '; 
$output .= '<option value="icon-publish">icon-publish</option> ';  
$output .= '<option value="icon-window">icon-window</option> '; 
$output .= '<option value="icon-arrow-combo">icon-arrow-combo</option>'; 
$output .= '<option value="icon-zoom-in-2">icon-zoom-in-2</option> '; 
$output .= '<option value="icon-zoom-in">icon-zoom-in</option> ';  
$output .= '<option value="icon-zoom-out">icon-zoom-out</option> ';  
$output .= '<option value="icon-zoom-out-2">icon-zoom-out-2</option> ';
$output .= '<option value="icon-chart-pie">icon-chart-pie</option> '; 
$output .= '<option value="icon-language">icon-language</option> ';  
$output .= '<option value="icon-air">icon-air</option> ';  
$output .= '<option value="icon-database">icon-database</option> ';  
$output .= '<option value="icon-drive">icon-drive</option> '; 
$output .= '<option value="icon-bucket">icon-bucket</option> '; 
$output .= '<option value="icon-thermometer">icon-thermometer</option> ';  
$output .= '<option value="icon-down-circled-1">icon-down-circled-1</option> ';  
$output .= '<option value="icon-down-circled2">icon-down-circled2</option> '; 
$output .= '<option value="icon-left-circled-1">icon-left-circled-1</option> ';  
$output .= '<option value="icon-right-circled-1">icon-right-circled-1</option> '; 
$output .= '<option value="icon-up-circled2">icon-up-circled2</option> '; 
$output .= '<option value="icon-up-circled-1">icon-up-circled-1</option> '; 
$output .= '<option value="icon-down-open">icon-down-open</option> '; 
$output .= '<option value="icon-down-open-1">icon-down-open-1</option> ';   
$output .= '<option value="icon-left-open">icon-left-open</option> '; 
$output .= '<option value="icon-left-open-1">icon-left-open-1</option> '; 
$output .= '<option value="icon-left-open-5">icon-left-open-5</option> '; 
$output .= '<option value="icon-right-open-1">icon-right-open-1</option> '; 
$output .= '<option value="icon-right-open">icon-right-open</option> ';  
$output .= '<option value="icon-right-open-5">icon-right-open-5</option> ';
$output .= '<option value="icon-up-open-1">icon-up-open-1</option> ';
$output .= '<option value="icon-up-open">icon-up-open</option> '; 
$output .= '<option value="icon-arrows-cw">icon-arrows-cw</option> ';  
$output .= '<option value="icon-arrows-cw-3">icon-arrows-cw-3</option> ';  
$output .= '<option value="icon-down-open-mini">icon-down-open-mini</option> '; 
$output .= '<option value="icon-play-circled2">icon-play-circled2</option> ';  
$output .= '<option value="icon-left-open-mini">icon-left-open-mini</option> ';  
$output .= '<option value="icon-to-end-alt">icon-to-end-alt</option> ';  
$output .= '<option value="icon-right-open-mini">icon-right-open-mini</option> '; 
$output .= '<option value="icon-up-open-mini">icon-up-open-mini</option> '; 
$output .= '<option value="icon-to-start-alt">icon-to-start-alt</option> '; 
$output .= '<option value="icon-down-open-big">icon-down-open-big</option> '; 
$output .= '<option value="icon-award-empty">icon-award-empty</option> '; 
$output .= '<option value="icon-list-2">icon-list-2</option> '; 
$output .= '<option value="icon-left-open-big">icon-left-open-big</option> ';  
$output .= '<option value="icon-list-nested">icon-list-nested</option> '; 
$output .= '<option value="icon-right-open-big">icon-right-open-big</option> ';  
$output .= '<option value="icon-up-open-big">icon-up-open-big</option> ';  
$output .= '<option value="icon-progress-0">icon-progress-0</option> '; 
$output .= '<option value="icon-progress-1">icon-progress-1</option> ';  
$output .= '<option value="icon-progress-2">icon-progress-2</option> '; 
$output .= '<option value="icon-progress-3">icon-progress-3</option> '; 
$output .= '<option value="icon-signal-3">icon-signal-3</option> ';
$output .= '<option value="icon-back-in-time">icon-back-in-time</option> '; 
$output .= '<option value="icon-bat-empty">icon-bat-empty</option> ';  
$output .= '<option value="icon-bat-half">icon-bat-half</option> '; 
$output .= '<option value="icon-bat-full">icon-bat-full</option> ';
$output .= '<option value="icon-bat-charge-1">icon-bat-charge-1</option> '; 
$output .= '<option value="icon-network">icon-network</option> '; 
$output .= '<option value="icon-inbox">icon-inbox</option> '; 
$output .= '<option value="icon-inbox-1">icon-inbox-1</option> ';
$output .= '<option value="icon-install">icon-install</option> '; 
$output .= '<option value="icon-font">icon-font</option> '; 
$output .= '<option value="icon-font-2">icon-font-2</option> ';  
$output .= '<option value="icon-bold">icon-bold</option> ';  
$output .= '<option value="icon-italic">icon-italic</option> ';  
$output .= '<option value="icon-text-height">icon-text-height</option> '; 
$output .= '<option value="icon-text-width">icon-text-width</option> ';  
$output .= '<option value="icon-align-left">icon-align-left</option> '; 
$output .= '<option value="icon-align-center">icon-align-center</option> ';
$output .= '<option value="icon-align-right">icon-align-right</option> '; 
$output .= '<option value="icon-align-justify">icon-align-justify</option> ';
$output .= '<option value="icon-list">icon-list</option> ';
$output .= '<option value="icon-list-4">icon-list-4</option> '; 
$output .= '<option value="icon-indent-left-2">icon-indent-left-2</option> '; 
$output .= '<option value="icon-indent-left">icon-indent-left</option> ';  
$output .= '<option value="icon-indent-right">icon-indent-right</option> ';  
$output .= '<option value="icon-indent-right-2">icon-indent-right-2</option> '; 
$output .= '<option value="icon-lifebuoy">icon-lifebuoy</option> '; 
$output .= '<option value="icon-mouse">icon-mouse</option> '; 
$output .= '<option value="icon-dot">icon-dot</option> ';  
$output .= '<option value="icon-dot-2">icon-dot-2</option> ';  
$output .= '<option value="icon-dot-3">icon-dot-3</option> ';  
$output .= '<option value="icon-off">icon-off</option> '; 
$output .= '<option value="icon-suitcase-1">icon-suitcase-1</option> '; 
$output .= '<option value="icon-road">icon-road</option> ';  
$output .= '<option value="icon-list-alt">icon-list-alt</option> '; 
$output .= '<option value="icon-flow-cascade">icon-flow-cascade</option> ';  
$output .= '<option value="icon-qrcode">icon-qrcode</option> '; 
$output .= '<option value="icon-flow-branch">icon-flow-branch</option> '; 
$output .= '<option value="icon-flow-tree">icon-flow-tree</option> '; 
$output .= '<option value="icon-barcode">icon-barcode</option> ';
$output .= '<option value="icon-ajust">icon-ajust</option> '; 
$output .= '<option value="icon-flow-line">icon-flow-line</option> ';  
$output .= '<option value="icon-flow-parallel">icon-flow-parallel</option> ';
$output .= '<option value="icon-tint">icon-tint</option> ';
$output .= '<option value="icon-equalizer">icon-equalizer</option> '; 
$output .= '<option value="icon-cursor">icon-cursor</option> ';  
$output .= '<option value="icon-aperture">icon-aperture</option> ';  
$output .= '<option value="icon-aperture-alt">icon-aperture-alt</option> ';  
$output .= '<option value="icon-steering-wheel">icon-steering-wheel</option> ';
$output .= '<option value="icon-brush">icon-brush</option> '; 
$output .= '<option value="icon-brush-2">icon-brush-2</option> ';
$output .= '<option value="icon-brush-alt">icon-brush-alt</option> '; 
$output .= '<option value="icon-paper-plane">icon-paper-plane</option> ';
$output .= '<option value="icon-eyedropper">icon-eyedropper</option> '; 
$output .= '<option value="icon-layers">icon-layers</option> ';
$output .= '<option value="icon-layers-alt">icon-layers-alt</option> ';
$output .= '<option value="icon-moon-inv">icon-moon-inv</option> ';
$output .= '<option value="icon-magnet-1">icon-magnet-1</option> ';
$output .= '<option value="icon-magnet">icon-magnet</option> ';
$output .= '<option value="icon-chart-pie-3">icon-chart-pie-3</option> ';
$output .= '<option value="icon-chart-pie-2">icon-chart-pie-2</option> '; 
$output .= '<option value="icon-gauge-1">icon-gauge-1</option> '; 
$output .= '<option value="icon-traffic-cone">icon-traffic-cone</option> '; 
$output .= '<option value="icon-chart-pie-alt">icon-chart-pie-alt</option> '; 
$output .= '<option value="icon-dial">icon-dial</option> ';  
$output .= '<option value="icon-cc">icon-cc</option> ';  
$output .= '<option value="icon-cc-by">icon-cc-by</option> '; 
$output .= '<option value="icon-resize-full-circle">icon-resize-full-circle</option> '; 
$output .= '<option value="icon-cc-nc">icon-cc-nc</option> ';   
$output .= '<option value="icon-down-micro">icon-down-micro</option> '; 
$output .= '<option value="icon-cc-nc-eu">icon-cc-nc-eu</option> ';  
$output .= '<option value="icon-up-micro">icon-up-micro</option> ';  
$output .= '<option value="icon-cw-circle">icon-cw-circle</option> ';  
$output .= '<option value="icon-cc-nc-jp">icon-cc-nc-jp</option> '; 
$output .= '<option value="icon-cc-sa">icon-cc-sa</option> '; 
$output .= '<option value="icon-updown-circle">icon-updown-circle</option> ';
$output .= '<option value="icon-cc-nd">icon-cc-nd</option> '; 
$output .= '<option value="icon-cc-pd">icon-cc-pd</option> ';  
$output .= '<option value="icon-terminal-1">icon-terminal-1</option> ';
$output .= '<option value="icon-list-numbered-1">icon-list-numbered-1</option> '; 
$output .= '<option value="icon-cc-zero">icon-cc-zero</option> ';
$output .= '<option value="icon-basket-alt">icon-basket-alt</option> '; 
$output .= '<option value="icon-cc-share">icon-cc-share</option> '; 
$output .= '<option value="icon-cc-remix">icon-cc-remix</option> '; 
$output .= '<option value="icon-mobile-alt">icon-mobile-alt</option> ';
$output .= '<option value="icon-tablet-2">icon-tablet-2</option> '; 
$output .= '<option value="icon-ipod">icon-ipod</option> '; 
$output .= '<option value="icon-stop-4">icon-stop-4</option> '; 
$output .= '<option value="icon-grid">icon-grid</option> '; 
$output .= '<option value="icon-easel">icon-easel</option> '; 
$output .= '<option value="icon-emo-happy">icon-emo-happy</option> ';
$output .= '<option value="icon-aboveground-rail">icon-aboveground-rail</option> ';
$output .= '<option value="icon-emo-wink">icon-emo-wink</option> '; 
$output .= '<option value="icon-airfield">icon-airfield</option> '; 
$output .= '<option value="icon-airport">icon-airport</option> ';
$output .= '<option value="icon-emo-unhappy">icon-emo-unhappy</option> ';
$output .= '<option value="icon-emo-sleep">icon-emo-sleep</option> '; 
$output .= '<option value="icon-art-gallery">icon-art-gallery</option> '; 
$output .= '<option value="icon-bar">icon-bar</option> ';
$output .= '<option value="icon-emo-thumbsup">icon-emo-thumbsup</option> '; 
$output .= '<option value="icon-emo-devil">icon-emo-devil</option> '; 
$output .= '<option value="icon-baseball">icon-baseball</option> '; 
$output .= '<option value="icon-emo-surprised">icon-emo-surprised</option> '; 
$output .= '<option value="icon-basketball">icon-basketball</option> '; 
$output .= '<option value="icon-emo-tongue">icon-emo-tongue</option> '; 
$output .= '<option value="icon-beer-2">icon-beer-2</option> '; 
$output .= '<option value="icon-emo-coffee">icon-emo-coffee</option> ';  
$output .= '<option value="icon-emo-sunglasses">icon-emo-sunglasses</option> '; 
$output .= '<option value="icon-belowground-rail">icon-belowground-rail</option> ';  
$output .= '<option value="icon-emo-displeased">icon-emo-displeased</option> '; 
$output .= '<option value="icon-bicycle">icon-bicycle</option> ';
$output .= '<option value="icon-bus">icon-bus</option> '; 
$output .= '<option value="icon-emo-beer">icon-emo-beer</option> '; 
$output .= '<option value="icon-emo-grin">icon-emo-grin</option> ';  
$output .= '<option value="icon-cafe">icon-cafe</option> '; 
$output .= '<option value="icon-emo-angry">icon-emo-angry</option> ';
$output .= '<option value="icon-campsite">icon-campsite</option> '; 
$output .= '<option value="icon-cemetery">icon-cemetery</option> ';  
$output .= '<option value="icon-emo-saint">icon-emo-saint</option> '; 
$output .= '<option value="icon-cinema">icon-cinema</option> '; 
$output .= '<option value="icon-emo-cry">icon-emo-cry</option> '; 
$output .= '<option value="icon-college">icon-college</option> '; 
$output .= '<option value="icon-emo-shoot">icon-emo-shoot</option> '; 
$output .= '<option value="icon-emo-squint">icon-emo-squint</option> '; 
$output .= '<option value="icon-commerical-building">icon-commerical-building</option> ';  
$output .= '<option value="icon-emo-laugh">icon-emo-laugh</option> '; 
$output .= '<option value="icon-credit-card-3">icon-credit-card-3</option> '; 
$output .= '<option value="icon-emo-wink2">icon-emo-wink2</option> '; 
$output .= '<option value="icon-cricket">icon-cricket</option> '; 
$output .= '<option value="icon-embassy">icon-embassy</option> '; 
$output .= '<option value="icon-fast-food">icon-fast-food</option> '; 
$output .= '<option value="icon-ferry">icon-ferry</option> '; 
$output .= '<option value="icon-fire-station">icon-fire-station</option> ';
$output .= '<option value="icon-football">icon-football</option> '; 
$output .= '<option value="icon-fuel">icon-fuel</option> '; 
$output .= '<option value="icon-garden">icon-garden</option> ';
$output .= '<option value="icon-giraffe">icon-giraffe</option> ';
$output .= '<option value="icon-golf">icon-golf</option> '; 
$output .= '<option value="icon-grocery-store">icon-grocery-store</option> '; 
$output .= '<option value="icon-harbor">icon-harbor</option> ';
$output .= '<option value="icon-heliport">icon-heliport</option> ';
$output .= '<option value="icon-hospital-1">icon-hospital-1</option> '; 
$output .= '<option value="icon-industrial-building">icon-industrial-building</option> ';
$output .= '<option value="icon-library">icon-library</option> ';
$output .= '<option value="icon-lodging">icon-lodging</option> ';
$output .= '<option value="icon-london-underground">icon-london-underground</option> '; 
$output .= '<option value="icon-minefield">icon-minefield</option> '; 
$output .= '<option value="icon-monument">icon-monument</option> ';  
$output .= '<option value="icon-museum">icon-museum</option> '; 
$output .= '<option value="icon-pharmacy">icon-pharmacy</option> '; 
$output .= '<option value="icon-pitch">icon-pitch</option> ';
$output .= '<option value="icon-police">icon-police</option>'; 
$output .= '<option value="icon-post">icon-post</option> '; 
$output .= '<option value="icon-prison">icon-prison</option> '; 
$output .= '<option value="icon-rail">icon-rail</option> '; 
$output .= '<option value="icon-religious-christian">icon-religious-christian</option> '; 
$output .= '<option value="icon-religious-islam">icon-religious-islam</option> '; 
$output .= '<option value="icon-spin1">icon-spin1</option> '; 
$output .= '<option value="icon-spin2">icon-spin2</option> ';
$output .= '<option value="icon-religious-jewish">icon-religious-jewish</option> ';
$output .= '<option value="icon-spin3">icon-spin3</option> ';
$output .= '<option value="icon-restaurant">icon-restaurant</option> '; 
$output .= '<option value="icon-roadblock">icon-roadblock</option> '; 
$output .= '<option value="icon-spin4">icon-spin4</option> '; 
$output .= '<option value="icon-school">icon-school</option> '; 
$output .= '<option value="icon-shop">icon-shop</option> '; 
$output .= '<option value="icon-skiing">icon-skiing</option> '; 
$output .= '<option value="icon-soccer">icon-soccer</option> ';
$output .= '<option value="icon-spin5">icon-spin5</option> '; 
$output .= '<option value="icon-swimming">icon-swimming</option> '; 
$output .= '<option value="icon-spin6">icon-spin6</option> '; 
$output .= '<option value="icon-tennis">icon-tennis</option> '; 
$output .= '<option value="icon-theatre">icon-theatre</option> '; 
$output .= '<option value="icon-toilet">icon-toilet</option> ';
$output .= '<option value="icon-town-hall">icon-town-hall</option> ';
$output .= '<option value="icon-trash-6">icon-trash-6</option> ';
$output .= '<option value="icon-tree-1">icon-tree-1</option> ';
$output .= '<option value="icon-tree-2">icon-tree-2</option> ';  
$output .= '<option value="icon-warehouse">icon-warehouse</option> '; 
$output .= '<option value="icon-firefox">icon-firefox</option> '; 
$output .= '<option value="icon-chrome">icon-chrome</option> ';  
$output .= '<option value="icon-opera">icon-opera</option> '; 
$output .= '<option value="icon-ie">icon-ie</option> '; 
$output .= '<option value="icon-crown">icon-crown</option> '; 
$output .= '<option value="icon-crown-plus">icon-crown-plus</option> ';
$output .= '<option value="icon-crown-minus">icon-crown-minus</option> '; 
$output .= '<option value="icon-marquee">icon-marquee</option> '; 
$output .= '<option value="icon-down-open-2">icon-down-open-2</option> '; 
$output .= '<option value="icon-up-open-2">icon-up-open-2</option> '; 
$output .= '<option value="icon-right-open-3">icon-right-open-3</option> '; 
$output .= '<option value="icon-left-open-3">icon-left-open-3</option> ';
$output .= '<option value="icon-menu-3">icon-menu-3</option> ';
$output .= '<option value="icon-th-list-3">icon-th-list-3</option> ';  
$output .= '<option value="icon-th-thumb">icon-th-thumb</option> ';
$output .= '<option value="icon-th-thumb-empty">icon-th-thumb-empty</option> '; 
$output .= '<option value="icon-coverflow">icon-coverflow</option> ';
$output .= '<option value="icon-coverflow-empty">icon-coverflow-empty</option> '; 
$output .= '<option value="icon-pause-4">icon-pause-4</option> '; 
$output .= '<option value="icon-play-4">icon-play-4</option> ';
$output .= '<option value="icon-to-end-3">icon-to-end-3</option> '; 
$output .= '<option value="icon-to-start-3">icon-to-start-3</option> '; 
$output .= '<option value="icon-fast-forward-1">icon-fast-forward-1</option> '; 
$output .= '<option value="icon-fast-backward-1">icon-fast-backward-1</option> '; 
$output .= '<option value="icon-upload-cloud-4">icon-upload-cloud-4</option> ';
$output .= '<option value="icon-download-cloud-2">icon-download-cloud-2</option> ';
$output .= '<option value="icon-data-science">icon-data-science</option> ';   
$output .= '<option value="icon-data-science-inv">icon-data-science-inv</option> ';   
$output .= '<option value="icon-globe-4">icon-globe-4</option> ';   
$output .= '<option value="icon-globe-inv">icon-globe-inv</option> ';   
$output .= '<option value="icon-math">icon-math</option> ';   
$output .= '<option value="icon-math-circled-empty">icon-math-circled-empty</option> ';   
$output .= '<option value="icon-math-circled">icon-math-circled</option> ';   
$output .= '<option value="icon-paper-plane-1">icon-paper-plane-1</option> ';   
$output .= '<option value="icon-paper-plane-alt2">icon-paper-plane-alt2</option> ';   
$output .= '<option value="icon-paper-plane-alt">icon-paper-plane-alt</option> ';   
$output .= '<option value="icon-color-adjust">icon-color-adjust</option> ';   
$output .= '<option value="icon-star-half-1">icon-star-half-1</option> ';   
$output .= '<option value="icon-star-half_empty">icon-star-half_empty</option> ';   
$output .= '<option value="icon-ccw-2">icon-ccw-2</option> ';   
$output .= '<option value="icon-heart-broken">icon-heart-broken</option> ';  
$output .= '<option value="icon-hash-1">icon-hash-1</option> ';   
$output .= '<option value="icon-reply-3">icon-reply-3</option> ';   
$output .= '<option value="icon-retweet-2">icon-retweet-2</option> ';   
$output .= '<option value="icon-login-2">icon-login-2</option> ';   
$output .= '<option value="icon-logout-2">icon-logout-2</option> ';   
$output .= '<option value="icon-download-5">icon-download-5</option> ';   
$output .= '<option value="icon-upload-4">icon-upload-4</option> ';   
$output .= '<option value="icon-location-5">icon-location-5</option> ';   
$output .= '<option value="icon-monitor-1">icon-monitor-1</option> ';   
$output .= '<option value="icon-tablet-3">icon-tablet-3</option> ';   
$output .= '<option value="icon-mobile-5">icon-mobile-5</option> ';   
$output .= '<option value="icon-connected-object">icon-connected-object</option> ';   
$output .= '<option value="icon-isight">icon-isight</option> ';   
$output .= '<option value="icon-videocam-3">icon-videocam-3</option> ';   
$output .= '<option value="icon-shuffle-3">icon-shuffle-3</option> ';   
$output .= '<option value="icon-chat-5">icon-chat-5</option> ';   
$output .= '<option value="icon-bell-4">icon-bell-4</option> ';   
$output .= '<option value="icon-movie">icon-movie</option> ';   
$output .= '<option value="icon-ruler">icon-ruler</option> ';   
$output .= '<option value="icon-vector">icon-vector</option> ';   
$output .= '<option value="icon-move">icon-move</option> ';   
$output .= '<option value="icon-mic-off">icon-mic-off</option> ';   
$output .= '<option value="icon-mic-4">icon-mic-4</option> ';   
$output .= '<option value="icon-doc-5">icon-doc-5</option> ';   
$output .= '<option value="icon-dribbble-circled-2">icon-dribbble-circled-2</option> ';   
$output .= '<option value="icon-dribbble-3">icon-dribbble-3</option> ';   
$output .= '<option value="icon-facebook-circled-2">icon-facebook-circled-2</option> ';   
$output .= '<option value="icon-facebook-4">icon-facebook-4</option> ';   
$output .= '<option value="icon-github-circled-alt">icon-github-circled-alt</option> ';   
$output .= '<option value="icon-github-circled-3">icon-github-circled-3</option> ';   
$output .= '<option value="icon-github-3">icon-github-3</option> ';   
$output .= '<option value="icon-github-circled-alt2">icon-github-circled-alt2</option> ';   
$output .= '<option value="icon-twitter-circled-2">icon-twitter-circled-2</option> ';   
$output .= '<option value="icon-twitter-4">icon-twitter-4</option> ';   
$output .= '<option value="icon-gplus-circled-1">icon-gplus-circled-1</option> ';   
$output .= '<option value="icon-gplus-2">icon-gplus-2</option> ';   
$output .= '<option value="icon-linkedin-circled-2">icon-linkedin-circled-2</option> ';   
$output .= '<option value="icon-linkedin-3">icon-linkedin-3</option> ';   
$output .= '<option value="icon-instagram-1">icon-instagram-1</option> ';   
$output .= '<option value="icon-instagram-circled">icon-instagram-circled</option>';   
$output .= '<option value="icon-mfg-logo">icon-mfg-logo</option> ';   
$output .= '<option value="icon-mfg-logo-circled">icon-mfg-logo-circled</option> ';   
$output .= '<option value="icon-user-5">icon-user-5</option> ';   
$output .= '<option value="icon-user-male">icon-user-male</option> ';   
$output .= '<option value="icon-user-female">icon-user-female</option> ';   
$output .= '<option value="icon-users-3">icon-users-3</option> ';   
$output .= '<option value="icon-folder-4">icon-folder-4</option> ';   
$output .= '<option value="icon-folder-open-1">icon-folder-open-1</option> ';  
$output .= '<option value="icon-folder-empty-2">icon-folder-empty-2</option> ';   
$output .= '<option value="icon-attach-4">icon-attach-4</option> ';   
$output .= '<option value="icon-ok-circled-1">icon-ok-circled-1</option> ';   
$output .= '<option value="icon-cancel-circled-3">icon-cancel-circled-3</option> ';   
$output .= '<option value="icon-inbox-2">icon-inbox-2</option> ';  
$output .= '<option value="icon-trophy-1">icon-trophy-1</option> ';   
$output .= '<option value="icon-lock-open-alt-1">icon-lock-open-alt-1</option> ';   
$output .= '<option value="icon-link-4">icon-link-4</option> ';   
$output .= '<option value="icon-zoom-out-3">icon-zoom-out-3</option> ';   
$output .= '<option value="icon-stop-5">icon-stop-5</option> ';   
$output .= '<option value="icon-export-4">icon-export-4</option> ';   
$output .= '<option value="icon-eye-5">icon-eye-5</option> ';   
$output .= '<option value="icon-trash-5">icon-trash-5</option> ';   
$output .= '<option value="icon-hdd-1">icon-hdd-1</option> ';   
$output .= '<option value="icon-info-circled-2">icon-info-circled-2</option> ';   
$output .= '<option value="icon-info-circled-alt">icon-info-circled-alt</option> ';   
$output .= '<option value="icon-print-4">icon-print-4</option> ';   
$output .= '<option value="icon-fontsize">icon-fontsize</option> ';   
$output .= '<option value="icon-soundcloud-1">icon-soundcloud-1</option> ';   
$output .= '<option value="icon-soundcloud-circled">icon-soundcloud-circled</option> ';   
$output .= '<option value="icon-link-ext">icon-link-ext</option> ';   
$output .= '<option value="icon-check-empty">icon-check-empty</option> ';   
$output .= '<option value="icon-bookmark-empty">icon-bookmark-empty</option> ';   
$output .= '<option value="icon-phone-squared">icon-phone-squared</option> ';   
$output .= '<option value="icon-twitter">icon-twitter</option> ';   
$output .= '<option value="icon-facebook">icon-facebook</option> ';   
$output .= '<option value="icon-github-circled">icon-github-circled</option> ';   
$output .= '<option value="icon-rss">icon-rss</option> ';   
$output .= '<option value="icon-hdd">icon-hdd</option> ';   
$output .= '<option value="icon-certificate">icon-certificate</option> ';   
$output .= '<option value="icon-left-circled">icon-left-circled</option> ';   
$output .= '<option value="icon-right-circled">icon-right-circled</option> ';   
$output .= '<option value="icon-up-circled">icon-up-circled</option> ';   
$output .= '<option value="icon-down-circled">icon-down-circled</option> ';   
$output .= '<option value="icon-tasks">icon-tasks</option> ';   
$output .= '<option value="icon-filter">icon-filter</option> ';   
$output .= '<option value="icon-resize-full-alt">icon-resize-full-alt</option> ';   
$output .= '<option value="icon-beaker">icon-beaker</option> ';   
$output .= '<option value="icon-docs">icon-docs</option> ';   
$output .= '<option value="icon-blank">icon-blank</option> ';   
$output .= '<option value="icon-menu">icon-menu</option> ';  
$output .= '<option value="icon-list-bullet">icon-list-bullet</option> ';   
$output .= '<option value="icon-list-numbered">icon-list-numbered</option> ';   
$output .= '<option value="icon-strike">icon-strike</option> ';   
$output .= '<option value="icon-underline">icon-underline</option> ';   
$output .= '<option value="icon-table">icon-table</option> ';   
$output .= '<option value="icon-magic">icon-magic</option> ';   
$output .= '<option value="icon-pinterest-circled">icon-pinterest-circled</option> ';   
$output .= '<option value="icon-pinterest-squared">icon-pinterest-squared</option> ';   
$output .= '<option value="icon-gplus-squared">icon-gplus-squared</option> ';   
$output .= '<option value="icon-gplus">icon-gplus</option> ';   
$output .= '<option value="icon-money">icon-money</option> ';   
$output .= '<option value="icon-columns">icon-columns</option> ';   
$output .= '<option value="icon-sort">icon-sort</option> ';   
$output .= '<option value="icon-sort-down">icon-sort-down</option> ';   
$output .= '<option value="icon-sort-up">icon-sort-up</option> ';   
$output .= '<option value="icon-mail-alt">icon-mail-alt</option> ';   
$output .= '<option value="icon-linkedin">icon-linkedin</option> ';   
$output .= '<option value="icon-gauge">icon-gauge</option> ';   
$output .= '<option value="icon-comment-empty">icon-comment-empty</option> ';   
$output .= '<option value="icon-chat-empty">icon-chat-empty</option> ';   
$output .= '<option value="icon-sitemap">icon-sitemap</option> ';   
$output .= '<option value="icon-paste">icon-paste</option> ';   
$output .= '<option value="icon-lightbulb">icon-lightbulb</option> ';   
$output .= '<option value="icon-exchange">icon-exchange</option> ';   
$output .= '<option value="icon-download-cloud">icon-download-cloud</option> ';  
$output .= '<option value="icon-upload-cloud">icon-upload-cloud</option> ';   
$output .= '<option value="icon-user-md">icon-user-md</option> ';   
$output .= '<option value="icon-stethoscope">icon-stethoscope</option> ';  
$output .= '<option value="icon-suitcase">icon-suitcase</option> ';   
$output .= '<option value="icon-bell-alt">icon-bell-alt</option> ';   
$output .= '<option value="icon-coffee">icon-coffee</option> ';   
$output .= '<option value="icon-food">icon-food</option> ';   
$output .= '<option value="icon-doc-text">icon-doc-text</option> ';   
$output .= '<option value="icon-building">icon-building</option> ';   
$output .= '<option value="icon-hospital">icon-hospital</option> ';   
$output .= '<option value="icon-ambulance">icon-ambulance</option> ';   
$output .= '<option value="icon-medkit">icon-medkit</option> ';   
$output .= '<option value="icon-fighter-jet">icon-fighter-jet</option> ';   
$output .= '<option value="icon-beer">icon-beer</option> ';   
$output .= '<option value="icon-h-sigh">icon-h-sigh</option> ';   
$output .= '<option value="icon-plus-squared">icon-plus-squared</option> ';   
$output .= '<option value="icon-angle-double-left">icon-angle-double-left</option> ';   
$output .= '<option value="icon-angle-double-right">icon-angle-double-right</option> ';   
$output .= '<option value="icon-angle-double-up">icon-angle-double-up</option> ';   
$output .= '<option value="icon-angle-double-down">icon-angle-double-down</option> ';   
$output .= '<option value="icon-angle-left">icon-angle-left</option> ';   
$output .= '<option value="icon-angle-right">icon-angle-right</option> ';   
$output .= '<option value="icon-angle-up">icon-angle-up</option> ';   
$output .= '<option value="icon-angle-down">icon-angle-down</option> ';   
$output .= '<option value="icon-desktop">icon-desktop</option> ';   
$output .= '<option value="icon-laptop">icon-laptop</option> ';   
$output .= '<option value="icon-tablet">icon-tablet</option> ';   
$output .= '<option value="icon-mobile">icon-mobile</option> ';   
$output .= '<option value="icon-circle-empty">icon-circle-empty</option> ';   
$output .= '<option value="icon-quote-left">icon-quote-left</option> ';   
$output .= '<option value="icon-quote-right">icon-quote-right</option> ';   
$output .= '<option value="icon-spinner">icon-spinner</option> ';   
$output .= '<option value="icon-circle">icon-circle</option> ';   
$output .= '<option value="icon-reply">icon-reply</option> ';   
$output .= '<option value="icon-github">icon-github</option> ';   
$output .= '<option value="icon-folder-empty">icon-folder-empty</option> ';   
$output .= '<option value="icon-folder-open-empty">icon-folder-open-empty</option> ';   
$output .= '<option value="icon-plus-squared-small">icon-plus-squared-small</option> ';   
$output .= '<option value="icon-minus-squared-small">icon-minus-squared-small</option> ';   
$output .= '<option value="icon-smile">icon-smile</option> ';   
$output .= '<option value="icon-frown">icon-frown</option> ';   
$output .= '<option value="icon-meh">icon-meh</option> ';   
$output .= '<option value="icon-gamepad">icon-gamepad</option> ';   
$output .= '<option value="icon-keyboard">icon-keyboard</option> ';   
$output .= '<option value="icon-flag-empty">icon-flag-empty</option> ';   
$output .= '<option value="icon-flag-checkered">icon-flag-checkered</option> ';   
$output .= '<option value="icon-terminal">icon-terminal</option> ';   
$output .= '<option value="icon-code">icon-code</option> ';   
$output .= '<option value="icon-reply-all">icon-reply-all</option> ';   
$output .= '<option value="icon-star-half-alt">icon-star-half-alt</option> ';   
$output .= '<option value="icon-direction">icon-direction</option> ';   
$output .= '<option value="icon-crop">icon-crop</option> ';   
$output .= '<option value="icon-fork">icon-fork</option> ';   
$output .= '<option value="icon-unlink">icon-unlink</option> ';   
$output .= '<option value="icon-help">icon-help</option> ';   
$output .= '<option value="icon-info">icon-info</option> ';   
$output .= '<option value="icon-attention-alt">icon-attention-alt</option> ';   
$output .= '<option value="icon-superscript">icon-superscript</option> ';   
$output .= '<option value="icon-subscript">icon-subscript</option> ';   
$output .= '<option value="icon-eraser">icon-eraser</option> ';   
$output .= '<option value="icon-puzzle">icon-puzzle</option> ';   
$output .= '<option value="icon-mic">icon-mic</option> ';   
$output .= '<option value="icon-mute">icon-mute</option> ';   
$output .= '<option value="icon-shield">icon-shield</option> ';   
$output .= '<option value="icon-calendar-empty">icon-calendar-empty</option> ';   
$output .= '<option value="icon-extinguisher">icon-extinguisher</option> ';   
$output .= '<option value="icon-rocket">icon-rocket</option> ';   
$output .= '<option value="icon-maxcdn">icon-maxcdn</option> ';   
$output .= '<option value="icon-angle-circled-left">icon-angle-circled-left</option> ';   
$output .= '<option value="icon-angle-circled-right">icon-angle-circled-right</option> ';   
$output .= '<option value="icon-angle-circled-up">icon-angle-circled-up</option> ';   
$output .= '<option value="icon-angle-circled-down">icon-angle-circled-down</option> ';   
$output .= '<option value="icon-html5">icon-html5</option> ';   
$output .= '<option value="icon-css3">icon-css3</option> ';   
$output .= '<option value="icon-anchor">icon-anchor</option> ';   
$output .= '<option value="icon-lock-open-alt">icon-lock-open-alt</option> ';   
$output .= '<option value="icon-bullseye">icon-bullseye</option> ';   
$output .= '<option value="icon-ellipsis">icon-ellipsis</option> ';   
$output .= '<option value="icon-ellipsis-vert">icon-ellipsis-vert</option> ';   
$output .= '<option value="icon-rss-squared">icon-rss-squared</option> ';   
$output .= '<option value="icon-play-circled">icon-play-circled</option> ';   
$output .= '<option value="icon-ticket">icon-ticket</option> ';   
$output .= '<option value="icon-minus-squared">icon-minus-squared</option> ';   
$output .= '<option value="icon-minus-squared-alt">icon-minus-squared-alt</option> ';   
$output .= '<option value="icon-level-up">icon-level-up</option> ';   
$output .= '<option value="icon-level-down">icon-level-down</option> ';   
$output .= '<option value="icon-ok-squared">icon-ok-squared</option> ';   
$output .= '<option value="icon-pencil-squared">icon-pencil-squared</option> ';   
$output .= '<option value="icon-link-ext-alt">icon-link-ext-alt</option> ';   
$output .= '<option value="icon-export-alt">icon-export-alt</option> ';   
$output .= '<option value="icon-compass">icon-compass</option> ';   
$output .= '<option value="icon-collapse">icon-collapse</option> ';   
$output .= '<option value="icon-collapse-top">icon-collapse-top</option> ';   
$output .= '<option value="icon-expand">icon-expand</option> ';   
$output .= '<option value="icon-euro">icon-euro</option> ';   
$output .= '<option value="icon-pound">icon-pound</option> ';   
$output .= '<option value="icon-dollar">icon-dollar</option> ';   
$output .= '<option value="icon-rupee">icon-rupee</option> ';   
$output .= '<option value="icon-yen">icon-yen</option> ';   
$output .= '<option value="icon-renminbi">icon-renminbi</option> ';   
$output .= '<option value="icon-won">icon-won</option> ';   
$output .= '<option value="icon-bitcoin">icon-bitcoin</option> ';   
$output .= '<option value="icon-doc-inv">icon-doc-inv</option> ';   
$output .= '<option value="icon-doc-text-inv">icon-doc-text-inv</option> ';   
$output .= '<option value="icon-sort-name-up">icon-sort-name-up</option> ';   
$output .= '<option value="icon-sort-name-down">icon-sort-name-down</option> ';   
$output .= '<option value="icon-sort-alt-up">icon-sort-alt-up</option> ';   
$output .= '<option value="icon-sort-alt-down">icon-sort-alt-down</option> ';   
$output .= '<option value="icon-sort-number-up">icon-sort-number-up</option> ';   
$output .= '<option value="icon-sort-number-down">icon-sort-number-down</option> ';   
$output .= '<option value="icon-thumbs-up-alt">icon-thumbs-up-alt</option> ';   
$output .= '<option value="icon-thumbs-down-alt">icon-thumbs-down-alt</option> ';   
$output .= '<option value="icon-youtube-squared">icon-youtube-squared</option> ';   
$output .= '<option value="icon-youtube">icon-youtube</option> ';   
$output .= '<option value="icon-xing">icon-xing</option> ';   
$output .= '<option value="icon-xing-squared">icon-xing-squared</option> ';   
$output .= '<option value="icon-youtube-play">icon-youtube-play</option> ';   
$output .= '<option value="icon-dropbox">icon-dropbox</option> ';   
$output .= '<option value="icon-stackoverflow">icon-stackoverflow</option> ';   
$output .= '<option value="icon-instagramm">icon-instagramm</option> ';   
$output .= '<option value="icon-flickr">icon-flickr</option> ';   
$output .= '<option value="icon-adn">icon-adn</option> ';   
$output .= '<option value="icon-bitbucket">icon-bitbucket</option> ';   
$output .= '<option value="icon-bitbucket-squared">icon-bitbucket-squared</option> ';   
$output .= '<option value="icon-tumblr">icon-tumblr</option> ';   
$output .= '<option value="icon-tumblr-squared">icon-tumblr-squared</option> ';  
$output .= '<option value="icon-down">icon-down</option> ';   
$output .= '<option value="icon-up">icon-up</option> ';   
$output .= '<option value="icon-right">icon-right</option> ';   
$output .= '<option value="icon-left">icon-left</option> ';   
$output .= '<option value="icon-apple">icon-apple</option> ';   
$output .= '<option value="icon-windows">icon-windows</option> ';   
$output .= '<option value="icon-android">icon-android</option> ';   
$output .= '<option value="icon-linux">icon-linux</option> ';   
$output .= '<option value="icon-dribbble">icon-dribbble</option> ';   
$output .= '<option value="icon-skype">icon-skype</option> ';   
$output .= '<option value="icon-foursquare">icon-foursquare</option> ';   
$output .= '<option value="icon-trello">icon-trello</option> ';   
$output .= '<option value="icon-female">icon-female</option> ';   
$output .= '<option value="icon-male">icon-male</option> ';   
$output .= '<option value="icon-gittip">icon-gittip</option> ';   
$output .= '<option value="icon-sun">icon-sun</option> ';   
$output .= '<option value="icon-moon">icon-moon</option> ';   
$output .= '<option value="icon-box">icon-box</option> ';   
$output .= '<option value="icon-bug">icon-bug</option> ';   
$output .= '<option value="icon-vkontakte">icon-vkontakte</option> ';   
$output .= '<option value="icon-weibo">icon-weibo</option> ';   
$output .= '<option value="icon-renren">icon-renren</option> ';   
$output .= '<option value="icon-facebook-6">icon-facebook-6</option> ';   
$output .= '<option value="icon-github-1">icon-github-1</option> ';   
$output .= '<option value="icon-facebook-3">icon-facebook-3</option> ';   
$output .= '<option value="icon-github-squared">icon-github-squared</option> ';   
$output .= '<option value="icon-facebook-rect">icon-facebook-rect</option> ';   
$output .= '<option value="icon-github-circled-1">icon-github-circled-1</option> ';   
$output .= '<option value="icon-facebook-rect-2">icon-facebook-rect-2</option> ';   
$output .= '<option value="icon-facebook-rect-1">icon-facebook-rect-1</option> ';   
$output .= '<option value="icon-twitter-3">icon-twitter-3</option> ';   
$output .= '<option value="icon-twitter-6">icon-twitter-6</option> ';   
$output .= '<option value="icon-flickr-1">icon-flickr-1</option> ';   
$output .= '<option value="icon-twitter-bird-2">icon-twitter-bird-2</option> ';   
$output .= '<option value="icon-twitter-bird">icon-twitter-bird</option> ';   
$output .= '<option value="icon-twitter-bird-1">icon-twitter-bird-1</option> ';   
$output .= '<option value="icon-twitter-squared">icon-twitter-squared</option> ';   
$output .= '<option value="icon-flickr-circled">icon-flickr-circled</option> ';   
$output .= '<option value="icon-icq">icon-icq</option> ';   
$output .= '<option value="icon-yandex">icon-yandex</option> ';   
$output .= '<option value="icon-vimeo">icon-vimeo</option> ';   
$output .= '<option value="icon-yandex-rect">icon-yandex-rect</option> ';   
$output .= '<option value="icon-github-text">icon-github-text</option> ';   
$output .= '<option value="icon-vimeo-circled">icon-vimeo-circled</option> ';   
$output .= '<option value="icon-facebook-squared">icon-facebook-squared</option> ';   
$output .= '<option value="icon-github-5">icon-github-5</option> ';   
$output .= '<option value="icon-googleplus-rect-1">icon-googleplus-rect-1</option> ';   
$output .= '<option value="icon-twitter-1">icon-twitter-1</option> ';   
$output .= '<option value="icon-googleplus-rect">icon-googleplus-rect</option> ';   
$output .= '<option value="icon-twitter-circled">icon-twitter-circled</option> ';   
$output .= '<option value="icon-vkontakte-rect">icon-vkontakte-rect</option> ';   
$output .= '<option value="icon-vkontakte-rect-1">icon-vkontakte-rect-1</option> ';   
$output .= '<option value="icon-skype-6">icon-skype-6</option> ';   
$output .= '<option value="icon-skype-4">icon-skype-4</option> ';   
$output .= '<option value="icon-linkedin-squared">icon-linkedin-squared</option> ';   
$output .= '<option value="icon-odnoklassniki">icon-odnoklassniki</option> ';   
$output .= '<option value="icon-facebook-1">icon-facebook-1</option> ';   
$output .= '<option value="icon-odnoklassniki-rect">icon-odnoklassniki-rect</option> ';   
$output .= '<option value="icon-odnoklassniki-rect-1">icon-odnoklassniki-rect-1</option> ';   
$output .= '<option value="icon-facebook-circled">icon-facebook-circled</option> ';   
$output .= '<option value="icon-facebook-squared-1">icon-facebook-squared-1</option> ';   
$output .= '<option value="icon-vimeo-rect">icon-vimeo-rect</option> ';   
$output .= '<option value="icon-vimeo-rect-1">icon-vimeo-rect-1</option> ';   
$output .= '<option value="icon-gplus-1">icon-gplus-1</option> ';   
$output .= '<option value="icon-vimeo-3">icon-vimeo-3</option>';   
$output .= '<option value="icon-tumblr-rect">icon-tumblr-rect</option> ';   
$output .= '<option value="icon-tumblr-rect-1">icon-tumblr-rect-1</option> ';   
$output .= '<option value="icon-gplus-circled">icon-gplus-circled</option> ';   
$output .= '<option value="icon-tumblr-3">icon-tumblr-3</option> ';   
$output .= '<option value="icon-pinterest">icon-pinterest</option> ';   
$output .= '<option value="icon-linkedin-rect-1">icon-linkedin-rect-1</option> ';   
$output .= '<option value="icon-friendfeed">icon-friendfeed</option> ';   
$output .= '<option value="icon-youtube-4">icon-youtube-4</option> ';   
$output .= '<option value="icon-friendfeed-rect">icon-friendfeed-rect</option> ';   
$output .= '<option value="icon-pinterest-circled-1">icon-pinterest-circled-1</option> ';   
$output .= '<option value="icon-twitter-rect">icon-twitter-rect</option> ';   
$output .= '<option value="icon-blogger-1">icon-blogger-1</option> ';   
$output .= '<option value="icon-tumblr-1">icon-tumblr-1</option> ';   
$output .= '<option value="icon-blogger-rect">icon-blogger-rect</option> ';   
$output .= '<option value="icon-youtube-1">icon-youtube-1</option> ';   
$output .= '<option value="icon-tumblr-circled">icon-tumblr-circled</option> ';   
$output .= '<option value="icon-deviantart">icon-deviantart</option> ';   
$output .= '<option value="icon-jabber">icon-jabber</option> ';   
$output .= '<option value="icon-lastfm-3">icon-lastfm-3</option> ';   
$output .= '<option value="icon-linkedin-1">icon-linkedin-1</option> ';   
$output .= '<option value="icon-linkedin-circled">icon-linkedin-circled</option> ';   
$output .= '<option value="icon-lastfm-rect">icon-lastfm-rect</option> ';   
$output .= '<option value="icon-linkedin-5">icon-linkedin-5</option> ';   
$output .= '<option value="icon-linkedin-rect">icon-linkedin-rect</option> ';   
$output .= '<option value="icon-dribbble-1">icon-dribbble-1</option> ';   
$output .= '<option value="icon-picasa-1">icon-picasa-1</option> ';   
$output .= '<option value="icon-dribbble-circled">icon-dribbble-circled</option> ';   
$output .= '<option value="icon-wordpress-1">icon-wordpress-1</option> ';   
$output .= '<option value="icon-instagram-3">icon-instagram-3</option> ';   
$output .= '<option value="icon-stumbleupon">icon-stumbleupon</option> ';   
$output .= '<option value="icon-instagram-filled">icon-instagram-filled</option> ';   
$output .= '<option value="icon-stumbleupon-circled">icon-stumbleupon-circled</option> ';   
$output .= '<option value="icon-diigo">icon-diigo</option> ';   
$output .= '<option value="icon-lastfm">icon-lastfm</option> ';   
$output .= '<option value="icon-box-4">icon-box-4</option> ';   
$output .= '<option value="icon-lastfm-circled">icon-lastfm-circled</option> ';   
$output .= '<option value="icon-box-rect">icon-box-rect</option> ';   
$output .= '<option value="icon-tudou">icon-tudou</option> ';   
$output .= '<option value="icon-rdio">icon-rdio</option> ';   
$output .= '<option value="icon-youku">icon-youku</option> ';   
$output .= '<option value="icon-win8">icon-win8</option> ';   
$output .= '<option value="icon-rdio-circled">icon-rdio-circled</option> ';   
$output .= '<option value="icon-spotify">icon-spotify</option> ';   
$output .= '<option value="icon-spotify-circled">icon-spotify-circled</option> ';   
$output .= '<option value="icon-qq">icon-qq</option> ';   
$output .= '<option value="icon-instagram">icon-instagram</option> ';   
$output .= '<option value="icon-dropbox-1">icon-dropbox-1</option> ';   
$output .= '<option value="icon-evernote">icon-evernote</option> ';   
$output .= '<option value="icon-flattr">icon-flattr</option> ';   
$output .= '<option value="icon-skype-1">icon-skype-1</option> ';   
$output .= '<option value="icon-skype-circled">icon-skype-circled</option> ';   
$output .= '<option value="icon-renren-1">icon-renren-1</option> ';   
$output .= '<option value="icon-sina-weibo">icon-sina-weibo</option> ';   
$output .= '<option value="icon-paypal">icon-paypal</option> ';   
$output .= '<option value="icon-picasa">icon-picasa</option> ';   
$output .= '<option value="icon-soundcloud">icon-soundcloud</option> ';   
$output .= '<option value="icon-mixi">icon-mixi</option> ';   
$output .= '<option value="icon-behance">icon-behance</option> ';   
$output .= '<option value="icon-google-circles">icon-google-circles</option> ';   
$output .= '<option value="icon-vkontakte-1">icon-vkontakte-1</option> ';   
$output .= '<option value="icon-smashing">icon-smashing</option> ';   
$output .= '<option value="icon-comment-4">icon-comment-4</option> ';   
$output .= '<option value="icon-folder-open-empty-1">icon-folder-open-empty-1</option> ';   
$output .= '<option value="icon-calendar-5">icon-calendar-5</option> ';   
$output .= '<option value="icon-newspaper-1">icon-newspaper-1</option> ';   
$output .= '<option value="icon-camera-5">icon-camera-5</option> ';   
$output .= '<option value="icon-search-5">icon-search-5</option> ';   
$output .= '<option value="icon-lock-alt">icon-lock-alt</option> ';   
$output .= '<option value="icon-lock-5">icon-lock-5</option> ';   
$output .= '<option value="icon-lock-open-5">icon-lock-open-5</option> ';   
$output .= '<option value="icon-joystick">icon-joystick</option> ';   
$output .= '<option value="icon-fire-1">icon-fire-1</option> ';   
$output .= '<option value="icon-chart-bar-5">icon-chart-bar-5</option> ';   
$output .= '<option value="icon-spread">icon-spread</option> ';   
$output .= '<option value="icon-spinner1">icon-spinner1</option> ';   
$output .= '<option value="icon-spinner2">icon-spinner2</option> ';   
$output .= '<option value="icon-db-shape">icon-db-shape</option> ';   
$output .= '<option value="icon-sweden">icon-sweden</option> ';   
$output .= '<option value="icon-logo-db">icon-logo-db</option> ';   
$output .= '<option value="icon-globe-2">icon-globe-2</option> ';   
$output .= '<option value="icon-picture-1">icon-picture-1</option> ';   
$output .= '<option value="icon-picture">icon-picture</option> ';   
$output .= '<option value="icon-picture-5">icon-picture-5</option> ';   
$output .= '<option value="icon-picture-3">icon-picture-3</option> ';   
$output .= '<option value="icon-globe-1">icon-globe-1</option> ';   
$output .= '<option value="icon-globe">icon-globe</option> ';   
$output .= '<option value="icon-globe-alt">icon-globe-alt</option> ';   
$output .= '<option value="icon-globe-3">icon-globe-3</option> ';   
$output .= '<option value="icon-leaf">icon-leaf</option> ';   
$output .= '<option value="icon-leaf-1">icon-leaf-1</option> ';   
$output .= '<option value="icon-lemon">icon-lemon</option> ';   
$output .= '<option value="icon-glass">icon-glass</option> ';   
$output .= '<option value="icon-gift">icon-gift</option> ';   
$output .= '<option value="icon-graduation-cap">icon-graduation-cap</option> ';   
$output .= '<option value="icon-mic-3">icon-mic-3</option> ';  
$output .= '<option value="icon-mic-1">icon-mic-1</option> ';   
$output .= '<option value="icon-mic-2">icon-mic-2</option> ';   
$output .= '<option value="icon-videocam">icon-videocam</option> ';   
$output .= '<option value="icon-videocam-2">icon-videocam-2</option> ';   
$output .= '<option value="icon-video-alt">icon-video-alt</option> ';   
$output .= '<option value="icon-headphones-2">icon-headphones-2</option> ';   
$output .= '<option value="icon-headphones">icon-headphones</option> ';   
$output .= '<option value="icon-palette">icon-palette</option> ';   
$output .= '<option value="icon-ticket-1">icon-ticket-1</option> ';   
$output .= '<option value="icon-video-5">icon-video-5</option> ';   
$output .= '<option value="icon-video-3">icon-video-3</option> ';   
$output .= '<option value="icon-video-1">icon-video-1</option> ';   
$output .= '<option value="icon-video">icon-video</option> ';   
$output .= '<option value="icon-target-5">icon-target-5</option> ';   
$output .= '<option value="icon-target-2">icon-target-2</option> ';   
$output .= '<option value="icon-target-3">icon-target-3</option> ';   
$output .= '<option value="icon-target">icon-target</option> ';   
$output .= '<option value="icon-target-1">icon-target-1</option> ';   
$output .= '<option value="icon-music-1">icon-music-1</option> ';   
$output .= '<option value="icon-trophy">icon-trophy</option> ';   
$output .= '<option value="icon-award-2">icon-award-2</option> ';   
$output .= '<option value="icon-award-1">icon-award-1</option> ';   
$output .= '<option value="icon-award">icon-award</option> ';   
$output .= '<option value="icon-videocam-alt">icon-videocam-alt</option> ';   
$output .= '<option value="icon-thumbs-up-2">icon-thumbs-up-2</option> ';   
$output .= '<option value="icon-thumbs-up-3">icon-thumbs-up-3</option> ';   
$output .= '<option value="icon-thumbs-up-1">icon-thumbs-up-1</option> ';   
$output .= '<option value="icon-thumbs-up">icon-thumbs-up</option> ';   
$output .= '<option value="icon-thumbs-down-2">icon-thumbs-down-2</option> ';   
$output .= '<option value="icon-thumbs-down-3">icon-thumbs-down-3</option> ';   
$output .= '<option value="icon-thumbs-down">icon-thumbs-down</option> ';   
$output .= '<option value="icon-thumbs-down-1">icon-thumbs-down-1</option> ';   
$output .= '<option value="icon-bag">icon-bag</option> ';   
$output .= '<option value="icon-user-1">icon-user-1</option> ';   
$output .= '<option value="icon-user-4">icon-user-4</option> ';   
$output .= '<option value="icon-user-3">icon-user-3</option> ';   
$output .= '<option value="icon-user-8">icon-user-8</option> ';   
$output .= '<option value="icon-user">icon-user</option> ';   
$output .= '<option value="icon-users-1">icon-users-1</option> ';   
$output .= '<option value="icon-users">icon-users</option> ';   
$output .= '<option value="icon-user-woman">icon-user-woman</option> ';   
$output .= '<option value="icon-user-pair">icon-user-pair</option> ';  
$output .= '<option value="icon-lamp">icon-lamp</option> ';   
$output .= '<option value="icon-lamp-1">icon-lamp-1</option> ';   
$output .= '<option value="icon-alert">icon-alert</option> ';   
$output .= '<option value="icon-water">icon-water</option> ';   
$output .= '<option value="icon-droplet">icon-droplet</option> ';   
$output .= '<option value="icon-credit-card">icon-credit-card</option> ';   
$output .= '<option value="icon-credit-card-1">icon-credit-card-1</option> ';   
$output .= '<option value="icon-dollar-1">icon-dollar-1</option> ';   
$output .= '<option value="icon-monitor">icon-monitor</option> ';   
$output .= '<option value="icon-briefcase">icon-briefcase</option> ';   
$output .= '<option value="icon-briefcase-1">icon-briefcase-1</option> ';   
$output .= '<option value="icon-floppy">icon-floppy</option> ';   
$output .= '<option value="icon-floppy-1">icon-floppy-1</option> ';   
$output .= '<option value="icon-cd">icon-cd</option> ';   
$output .= '<option value="icon-cd-2">icon-cd-2</option> ';   
$output .= '<option value="icon-cd-1">icon-cd-1</option> ';   
$output .= '<option value="icon-folder">icon-folder</option> ';   
$output .= '<option value="icon-folder-3">icon-folder-3</option> ';   
$output .= '<option value="icon-folder-1">icon-folder-1</option> ';   
$output .= '<option value="icon-folder-6">icon-folder-6</option> ';   
$output .= '<option value="icon-folder-empty-1">icon-folder-empty-1</option> ';   
$output .= '<option value="icon-folder-open">icon-folder-open</option> ';   
$output .= '<option value="icon-doc">icon-doc</option> ';   
$output .= '<option value="icon-doc-text-1">icon-doc-text-1</option> ';   
$output .= '<option value="icon-doc-4">icon-doc-4</option> ';   
$output .= '<option value="icon-calendar-1">icon-calendar-1</option> ';   
$output .= '<option value="icon-calendar-4">icon-calendar-4</option> ';   
$output .= '<option value="icon-calendar-3">icon-calendar-3</option> ';   
$output .= '<option value="icon-calendar">icon-calendar</option> ';   
$output .= '<option value="icon-calendar-alt">icon-calendar-alt</option> ';   
$output .= '<option value="icon-chart-1">icon-chart-1</option> ';   
$output .= '<option value="icon-chart">icon-chart</option> ';   
$output .= '<option value="icon-chart-line">icon-chart-line</option> ';   
$output .= '<option value="icon-chart-bar-4">icon-chart-bar-4</option> ';   
$output .= '<option value="icon-chart-bar">icon-chart-bar</option> ';   
$output .= '<option value="icon-chart-bar-3">icon-chart-bar-3</option> ';   
$output .= '<option value="icon-chart-bar-1">icon-chart-bar-1</option> ';   
$output .= '<option value="icon-chart-bar-2">icon-chart-bar-2</option> ';   
$output .= '<option value="icon-clipboard">icon-clipboard</option> ';   
$output .= '<option value="icon-pin-2">icon-pin-2</option> ';   
$output .= '<option value="icon-pin">icon-pin</option> ';   
$output .= '<option value="icon-attach-3">icon-attach-3</option> ';   
$output .= '<option value="icon-attach">icon-attach</option> ';   
$output .= '<option value="icon-attach-1">icon-attach-1</option> ';   
$output .= '<option value="icon-attach-7">icon-attach-7</option> ';   
$output .= '<option value="icon-bookmarks">icon-bookmarks</option> ';   
$output .= '<option value="icon-book-alt">icon-book-alt</option> ';   
$output .= '<option value="icon-book">icon-book</option> ';   
$output .= '<option value="icon-book-2">icon-book-2</option> ';   
$output .= '<option value="icon-book-3">icon-book-3</option> ';   
$output .= '<option value="icon-book-1">icon-book-1</option> ';   
$output .= '<option value="icon-book-open">icon-book-open</option> ';   
$output .= '<option value="icon-book-open-1">icon-book-open-1</option> ';   
$output .= '<option value="icon-phone-1">icon-phone-1</option> ';   
$output .= '<option value="icon-phone">icon-phone</option> ';   
$output .= '<option value="icon-phone-2">icon-phone-2</option> ';   
$output .= '<option value="icon-bullhorn">icon-bullhorn</option> ';   
$output .= '<option value="icon-megaphone-1">icon-megaphone-1</option> ';   
$output .= '<option value="icon-megaphone">icon-megaphone</option> ';   
$output .= '<option value="icon-upload">icon-upload</option> ';   
$output .= '<option value="icon-upload-3">icon-upload-3</option> ';   
$output .= '<option value="icon-upload-1">icon-upload-1</option> ';   
$output .= '<option value="icon-download-2">icon-download-2</option> ';   
$output .= '<option value="icon-download-1">icon-download-1</option> ';   
$output .= '<option value="icon-download-4">icon-download-4</option> ';   
$output .= '<option value="icon-download">icon-download</option> ';   
$output .= '<option value="icon-download-3">icon-download-3</option> ';   
$output .= '<option value="icon-box-1">icon-box-1</option> ';   
$output .= '<option value="icon-box-3">icon-box-3</option> ';   
$output .= '<option value="icon-newspaper">icon-newspaper</option> ';   
$output .= '<option value="icon-mobile-3">icon-mobile-3</option> ';   
$output .= '<option value="icon-mobile-1">icon-mobile-1</option> ';   
$output .= '<option value="icon-mobile-2">icon-mobile-2</option> ';   
$output .= '<option value="icon-mobile-4">icon-mobile-4</option> ';   
$output .= '<option value="icon-signal-1">icon-signal-1</option> ';   
$output .= '<option value="icon-signal">icon-signal</option> ';   
$output .= '<option value="icon-signal-2">icon-signal-2</option> ';   
$output .= '<option value="icon-signal-5">icon-signal-5</option> ';   
$output .= '<option value="icon-camera">icon-camera</option> ';   
$output .= '<option value="icon-camera-3">icon-camera-3</option> ';   
$output .= '<option value="icon-camera-1">icon-camera-1</option> ';   
$output .= '<option value="icon-camera-4">icon-camera-4</option> ';   
$output .= '<option value="icon-shuffle">icon-shuffle</option> ';   
$output .= '<option value="icon-shuffle-1">icon-shuffle-1</option> ';   
$output .= '<option value="icon-shuffle-2">icon-shuffle-2</option> ';   
$output .= '<option value="icon-loop">icon-loop</option> ';  
$output .= '<option value="icon-loop-1">icon-loop-1</option> ';   
$output .= '<option value="icon-loop-alt-1">icon-loop-alt-1</option> ';   
$output .= '<option value="icon-loop-2">icon-loop-2</option> ';   
$output .= '<option value="icon-arrows-ccw">icon-arrows-ccw</option> ';   
$output .= '<option value="icon-light-down">icon-light-down</option> ';   
$output .= '<option value="icon-light-up">icon-light-up</option> ';   
$output .= '<option value="icon-volume-off">icon-volume-off</option> ';   
$output .= '<option value="icon-volume-off-2">icon-volume-off-2</option> ';   
$output .= '<option value="icon-mute-1">icon-mute-1</option> ';   
$output .= '<option value="icon-volume-off-3">icon-volume-off-3</option> ';   
$output .= '<option value="icon-volume-down-1">icon-volume-down-1</option> ';   
$output .= '<option value="icon-volume-down">icon-volume-down</option> ';   
$output .= '<option value="icon-volume-up-2">icon-volume-up-2</option> ';   
$output .= '<option value="icon-sound">icon-sound</option> ';   
$output .= '<option value="icon-volume-up">icon-volume-up</option> ';   
$output .= '<option value="icon-volume-up-1">icon-volume-up-1</option> ';   
$output .= '<option value="icon-battery">icon-battery</option> ';   
$output .= '<option value="icon-search-8">icon-search-8</option> ';   
$output .= '<option value="icon-search-1">icon-search-1</option> ';   
$output .= '<option value="icon-search">icon-search</option> ';   
$output .= '<option value="icon-search-4">icon-search-4</option> ';   
$output .= '<option value="icon-search-3">icon-search-3</option> ';   
$output .= '<option value="icon-key-inv">icon-key-inv</option> ';   
$output .= '<option value="icon-key">icon-key</option> ';   
$output .= '<option value="icon-key-2">icon-key-2</option> ';   
$output .= '<option value="icon-key-1">icon-key-1</option> ';   
$output .= '<option value="icon-lock-1">icon-lock-1</option> ';   
$output .= '<option value="icon-lock-4">icon-lock-4</option> ';   
$output .= '<option value="icon-lock">icon-lock</option> ';   
$output .= '<option value="icon-lock-8">icon-lock-8</option> ';   
$output .= '<option value="icon-lock-3">icon-lock-3</option> ';   
$output .= '<option value="icon-lock-open-4">icon-lock-open-4</option> ';   
$output .= '<option value="icon-lock-open-1">icon-lock-open-1</option> ';   
$output .= '<option value="icon-lock-open">icon-lock-open</option> ';   
$output .= '<option value="icon-lock-open-3">icon-lock-open-3</option> ';   
$output .= '<option value="icon-lock-open-7">icon-lock-open-7</option> ';   
$output .= '<option value="icon-bell-3">icon-bell-3</option> ';   
$output .= '<option value="icon-bell">icon-bell</option> ';   
$output .= '<option value="icon-bell-1">icon-bell-1</option> ';   
$output .= '<option value="icon-bookmark-2">icon-bookmark-2</option> ';   
$output .= '<option value="icon-bookmark-1">icon-bookmark-1</option> ';   
$output .= '<option value="icon-bookmark">icon-bookmark</option> ';   
$output .= '<option value="icon-link-3">icon-link-3</option> ';   
$output .= '<option value="icon-link-5">icon-link-5</option> ';   
$output .= '<option value="icon-link">icon-link</option> ';   
$output .= '<option value="icon-link-1">icon-link-1</option> ';   
$output .= '<option value="icon-back">icon-back</option> ';   
$output .= '<option value="icon-fire">icon-fire</option> ';   
$output .= '<option value="icon-flashlight">icon-flashlight</option> ';   
$output .= '<option value="icon-wrench-1">icon-wrench-1</option> ';   
$output .= '<option value="icon-wrench-3">icon-wrench-3</option> ';   
$output .= '<option value="icon-wrench">icon-wrench</option> ';   
$output .= '<option value="icon-wrench-2">icon-wrench-2</option> ';   
$output .= '<option value="icon-hammer">icon-hammer</option> ';   
$output .= '<option value="icon-chart-area">icon-chart-area</option> ';   
$output .= '<option value="icon-clock-alt">icon-clock-alt</option> ';  
$output .= '<option value="icon-clock-2">icon-clock-2</option> ';   
$output .= '<option value="icon-clock-4">icon-clock-4</option> ';   
$output .= '<option value="icon-clock-8">icon-clock-8</option> ';   
$output .= '<option value="icon-clock-3">icon-clock-3</option> ';   
$output .= '<option value="icon-clock">icon-clock</option> ';   
$output .= '<option value="icon-clock-1">icon-clock-1</option> ';   
$output .= '<option value="icon-rocket-1">icon-rocket-1</option> ';   
$output .= '<option value="icon-truck">icon-truck</option> ';   
$output .= '<option value="icon-block-5">icon-block-5</option> ';   
$output .= '<option value="icon-block-3">icon-block-3</option> ';   
$output .= '<option value="icon-block-1">icon-block-1</option> ';   
$output .= '<option value="icon-block-2">icon-block-2</option> ';   
$output .= '<option value="icon-block">icon-block</option> ';
$output .= '<option value="ss-cursor">ss-cursor</option> ';
$output .= '<option value="ss-crosshair">ss-crosshair</option> ';
$output .= '<option value="ss-search">ss-search</option> ';
$output .= '<option value="ss-zoomin">ss-zoomin</option> ';
$output .= '<option value="ss-zoomout">ss-zoomout</option> ';
$output .= '<option value="ss-view">ss-view</option> ';
$output .= '<option value="ss-attach">ss-attach</option> ';
$output .= '<option value="ss-link">ss-link</option> ';
$output .= '<option value="ss-unlink">ss-unlink</option> ';
$output .= '<option value="ss-move">ss-move</option> ';
$output .= '<option value="ss-write">ss-write</option> ';
$output .= '<option value="ss-writingdisabled">ss-writingdisabled</option> ';
$output .= '<option value="ss-erase">ss-erase</option> ';
$output .= '<option value="ss-compose">ss-compose</option> ';
$output .= '<option value="ss-lock">ss-lock</option> ';
$output .= '<option value="ss-unlock">ss-unlock</option> ';
$output .= '<option value="ss-key">ss-key</option> ';
$output .= '<option value="ss-backspace">ss-backspace</option> ';
$output .= '<option value="ss-ban">ss-ban</option> ';
$output .= '<option value="ss-smoking">ss-smoking</option> ';
$output .= '<option value="ss-nosmoking">ss-nosmoking</option> ';
$output .= '<option value="ss-trash">ss-trash</option> ';
$output .= '<option value="ss-target">ss-target</option> ';
$output .= '<option value="ss-tag">ss-tag</option> ';
$output .= '<option value="ss-bookmark">ss-bookmark</option> ';
$output .= '<option value="ss-flag">ss-flag</option> ';
$output .= '<option value="ss-like">ss-like</option> ';
$output .= '<option value="ss-dislike">ss-dislike</option> ';
$output .= '<option value="ss-heart">ss-heart</option> ';
$output .= '<option value="ss-star">ss-star</option> ';
$output .= '<option value="ss-sample">ss-sample</option> ';
$output .= '<option value="ss-crop">ss-crop</option> ';
$output .= '<option value="ss-layers">ss-layers</option> ';
$output .= '<option value="ss-layergroup">ss-layergroup</option> ';
$output .= '<option value="ss-pen">ss-pen</option> ';
$output .= '<option value="ss-bezier">ss-bezier</option> ';
$output .= '<option value="ss-pixels">ss-pixels</option> ';
$output .= '<option value="ss-phone">ss-phone</option> ';
$output .= '<option value="ss-phonedisabled">ss-phonedisabled</option> ';
$output .= '<option value="ss-touchtonephone">ss-touchtonephone</option> ';
$output .= '<option value="ss-mail">ss-mail</option> ';
$output .= '<option value="ss-inbox">ss-inbox</option> ';
$output .= '<option value="ss-outbox">ss-outbox</option> ';
$output .= '<option value="ss-chat">ss-chat</option> ';
$output .= '<option value="ss-user">ss-user</option> ';
$output .= '<option value="ss-users">ss-users</option> ';
$output .= '<option value="ss-usergroup">ss-usergroup</option> ';
$output .= '<option value="ss-businessuser">ss-businessuser</option> ';
$output .= '<option value="ss-man">ss-man</option> ';
$output .= '<option value="ss-male">ss-male</option> ';
$output .= '<option value="ss-woman">ss-woman</option> ';
$output .= '<option value="ss-female">ss-female</option> ';
$output .= '<option value="ss-raisedhand">ss-raisedhand</option> ';
$output .= '<option value="ss-hand">ss-hand</option> ';
$output .= '<option value="ss-pointup">ss-pointup</option> ';
$output .= '<option value="ss-pointupright">ss-pointupright</option> ';
$output .= '<option value="ss-pointright">ss-pointright</option> ';
$output .= '<option value="ss-pointdownright">ss-pointdownright</option> ';
$output .= '<option value="ss-pointdown">ss-pointdown</option> ';
$output .= '<option value="ss-pointdownleft">ss-pointdownleft</option> ';
$output .= '<option value="ss-pointleft">ss-pointleft</option> ';
$output .= '<option value="ss-pointupleft">ss-pointupleft</option> ';
$output .= '<option value="ss-cart">ss-cart</option> ';
$output .= '<option value="ss-creditcard">ss-creditcard</option> ';
$output .= '<option value="ss-calculator">ss-calculator</option> ';
$output .= '<option value="ss-barchart">ss-barchart</option> ';
$output .= '<option value="ss-piechart">ss-piechart</option> ';
$output .= '<option value="ss-box">ss-box</option> ';
$output .= '<option value="ss-home">ss-home</option> ';
$output .= '<option value="ss-globe">ss-globe</option> ';
$output .= '<option value="ss-navigate">ss-navigate</option> ';
$output .= '<option value="ss-compass">ss-compass</option> ';
$output .= '<option value="ss-signpost">ss-signpost</option> ';
$output .= '<option value="ss-location">ss-location</option> ';
$output .= '<option value="ss-floppydisk">ss-floppydisk</option> ';
$output .= '<option value="ss-database">ss-database</option> ';
$output .= '<option value="ss-hdd">ss-hdd</option> ';
$output .= '<option value="ss-microchip">ss-microchip</option> ';
$output .= '<option value="ss-music">ss-music</option> ';
$output .= '<option value="ss-headphones">ss-headphones</option> ';
$output .= '<option value="ss-discdrive">ss-discdrive</option> ';
$output .= '<option value="ss-volume">ss-volume</option> ';
$output .= '<option value="ss-lowvolume">ss-lowvolume</option> ';
$output .= '<option value="ss-mediumvolume">ss-mediumvolume</option> ';
$output .= '<option value="ss-highvolume">ss-highvolume</option> ';
$output .= '<option value="ss-airplay">ss-airplay</option> ';
$output .= '<option value="ss-camera">ss-camera</option> ';
$output .= '<option value="ss-picture">ss-picture</option> ';
$output .= '<option value="ss-video">ss-video</option> ';
$output .= '<option value="ss-webcam">ss-webcam</option> ';
$output .= '<option value="ss-film">ss-film</option> ';
$output .= '<option value="ss-playvideo">ss-playvideo</option> ';
$output .= '<option value="ss-videogame">ss-videogame</option> ';
$output .= '<option value="ss-play">ss-play</option> ';
$output .= '<option value="ss-pause">ss-pause</option> ';
$output .= '<option value="ss-stop">ss-stop</option> ';
$output .= '<option value="ss-record">ss-record</option> ';
$output .= '<option value="ss-rewind">ss-rewind</option> ';
$output .= '<option value="ss-fastforward">ss-fastforward</option> ';
$output .= '<option value="ss-skipback">ss-skipback</option> ';
$output .= '<option value="ss-skipforward">ss-skipforward</option> ';
$output .= '<option value="ss-eject">ss-eject</option> ';
$output .= '<option value="ss-repeat">ss-repeat</option> ';
$output .= '<option value="ss-replay">ss-replay</option> ';
$output .= '<option value="ss-shuffle">ss-shuffle</option> ';
$output .= '<option value="ss-index">ss-index</option> ';
$output .= '<option value="ss-storagebox">ss-storagebox</option> ';
$output .= '<option value="ss-book">ss-book</option> ';
$output .= '<option value="ss-notebook">ss-notebook</option> ';
$output .= '<option value="ss-newspaper">ss-newspaper</option> ';
$output .= '<option value="ss-gridlines">ss-gridlines</option> ';
$output .= '<option value="ss-rows">ss-rows</option> ';
$output .= '<option value="ss-columns">ss-columns</option> ';
$output .= '<option value="ss-thumbnails">ss-thumbnails</option> ';
$output .= '<option value="ss-mouse">ss-mouse</option> ';
$output .= '<option value="ss-usb">ss-usb</option> ';
$output .= '<option value="ss-desktop">ss-desktop</option> ';
$output .= '<option value="ss-laptop">ss-laptop</option> ';
$output .= '<option value="ss-tablet">ss-tablet</option> ';
$output .= '<option value="ss-smartphone">ss-smartphone</option> ';
$output .= '<option value="ss-cell">ss-cell</option> ';
$output .= '<option value="ss-battery">ss-battery</option> ';
$output .= '<option value="ss-highbattery">ss-highbattery</option> ';
$output .= '<option value="ss-mediumbattery">ss-mediumbattery</option> ';
$output .= '<option value="ss-lowbattery">ss-lowbattery</option> ';
$output .= '<option value="ss-chargingbattery">ss-chargingbattery</option> ';
$output .= '<option value="ss-lightbulb">ss-lightbulb</option> ';
$output .= '<option value="ss-washer">ss-washer</option> ';
$output .= '<option value="ss-downloadcloud">ss-downloadcloud</option> ';
$output .= '<option value="ss-download">ss-download</option> ';
$output .= '<option value="ss-downloadbox">ss-downloadbox</option> ';
$output .= '<option value="ss-uploadcloud">ss-uploadcloud</option> ';
$output .= '<option value="ss-upload">ss-upload</option> ';
$output .= '<option value="ss-uploadbox">ss-uploadbox</option> ';
$output .= '<option value="ss-fork">ss-fork</option> ';
$output .= '<option value="ss-merge">ss-merge</option> ';
$output .= '<option value="ss-refresh">ss-refresh</option> ';
$output .= '<option value="ss-sync">ss-sync</option> ';
$output .= '<option value="ss-loading">ss-loading</option> ';
$output .= '<option value="ss-file">ss-file</option> ';
$output .= '<option value="ss-files">ss-files</option> ';
$output .= '<option value="ss-addfile">ss-addfile</option> ';
$output .= '<option value="ss-removefile">ss-removefile</option> ';
$output .= '<option value="ss-checkfile">ss-checkfile</option> ';
$output .= '<option value="ss-deletefile">ss-deletefile</option> ';
$output .= '<option value="ss-exe">ss-exe</option> ';
$output .= '<option value="ss-zip">ss-zip</option> ';
$output .= '<option value="ss-doc">ss-doc</option> ';
$output .= '<option value="ss-pdf">ss-pdf</option> ';
$output .= '<option value="ss-jpg">ss-jpg</option> ';
$output .= '<option value="ss-png">ss-png</option> ';
$output .= '<option value="ss-mp3">ss-mp3</option> ';
$output .= '<option value="ss-rar">ss-rar</option> ';
$output .= '<option value="ss-gif">ss-gif</option> ';
$output .= '<option value="ss-folder">ss-folder</option> ';
$output .= '<option value="ss-openfolder">ss-openfolder</option> ';
$output .= '<option value="ss-downloadfolder">ss-downloadfolder</option> ';
$output .= '<option value="ss-uploadfolder">ss-uploadfolder</option> ';
$output .= '<option value="ss-quote">ss-quote</option> ';
$output .= '<option value="ss-unquote">ss-unquote</option> ';
$output .= '<option value="ss-print">ss-print</option> ';
$output .= '<option value="ss-copier">ss-copier</option> ';
$output .= '<option value="ss-fax">ss-fax</option> ';
$output .= '<option value="ss-scanner">ss-scanner</option> ';
$output .= '<option value="ss-printregistration">ss-printregistration</option> ';
$output .= '<option value="ss-shredder">ss-shredder</option> ';
$output .= '<option value="ss-expand">ss-expand</option> ';
$output .= '<option value="ss-contract">ss-contract</option> ';
$output .= '<option value="ss-help">ss-help</option> ';
$output .= '<option value="ss-info">ss-info</option> ';
$output .= '<option value="ss-alert">ss-alert</option> ';
$output .= '<option value="ss-caution">ss-caution</option> ';
$output .= '<option value="ss-logout">ss-logout</option> ';
$output .= '<option value="ss-login">ss-login</option> ';
$output .= '<option value="ss-scaleup">ss-scaleup</option> ';
$output .= '<option value="ss-scaledown">ss-scaledown</option> ';
$output .= '<option value="ss-plus">ss-plus</option> ';
$output .= '<option value="ss-hyphen">ss-hyphen</option> ';
$output .= '<option value="ss-check">ss-check</option> ';
$output .= '<option value="ss-delete">ss-delete</option> ';
$output .= '<option value="ss-notifications">ss-notifications</option> ';
$output .= '<option value="ss-notificationsdisabled">ss-notificationsdisabled</option> ';
$output .= '<option value="ss-clock">ss-clock</option> ';
$output .= '<option value="ss-stopwatch">ss-stopwatch</option> ';
$output .= '<option value="ss-alarmclock">ss-alarmclock</option> ';
$output .= '<option value="ss-egg">ss-egg</option> ';
$output .= '<option value="ss-eggs">ss-eggs</option> ';
$output .= '<option value="ss-cheese">ss-cheese</option> ';
$output .= '<option value="ss-chickenleg">ss-chickenleg</option> ';
$output .= '<option value="ss-pizzapie">ss-pizzapie</option> ';
$output .= '<option value="ss-pizza">ss-pizza</option> ';
$output .= '<option value="ss-cheesepizza">ss-cheesepizza</option> ';
$output .= '<option value="ss-frenchfries">ss-frenchfries</option> ';
$output .= '<option value="ss-apple">ss-apple</option> ';
$output .= '<option value="ss-carrot">ss-carrot</option> ';
$output .= '<option value="ss-broccoli">ss-broccoli</option> ';
$output .= '<option value="ss-cucumber">ss-cucumber</option> ';
$output .= '<option value="ss-orange">ss-orange</option> ';
$output .= '<option value="ss-lemon">ss-lemon</option> ';
$output .= '<option value="ss-onion">ss-onion</option> ';
$output .= '<option value="ss-bellpepper">ss-bellpepper</option> ';
$output .= '<option value="ss-peas">ss-peas</option> ';
$output .= '<option value="ss-grapes">ss-grapes</option> ';
$output .= '<option value="ss-strawberry">ss-strawberry</option> ';
$output .= '<option value="ss-bread">ss-bread</option> ';
$output .= '<option value="ss-mug">ss-mug</option> ';
$output .= '<option value="ss-mugs">ss-mugs</option> ';
$output .= '<option value="ss-espresso">ss-espresso</option> ';
$output .= '<option value="ss-macchiato">ss-macchiato</option> ';
$output .= '<option value="ss-cappucino">ss-cappucino</option> ';
$output .= '<option value="ss-latte">ss-latte</option> ';
$output .= '<option value="ss-icedcoffee">ss-icedcoffee</option> ';
$output .= '<option value="ss-coffeebean">ss-coffeebean</option> ';
$output .= '<option value="ss-coffeemilk">ss-coffeemilk</option> ';
$output .= '<option value="ss-coffeefoam">ss-coffeefoam</option> ';
$output .= '<option value="ss-coffeesugar">ss-coffeesugar</option> ';
$output .= '<option value="ss-sugarpackets">ss-sugarpackets</option> ';
$output .= '<option value="ss-capsule">ss-capsule</option> ';
$output .= '<option value="ss-capsulerecycling">ss-capsulerecycling</option> ';
$output .= '<option value="ss-insertcapsule">ss-insertcapsule</option> ';
$output .= '<option value="ss-tea">ss-tea</option> ';
$output .= '<option value="ss-teabag">ss-teabag</option> ';
$output .= '<option value="ss-jug">ss-jug</option> ';
$output .= '<option value="ss-pitcher">ss-pitcher</option> ';
$output .= '<option value="ss-kettle">ss-kettle</option> ';
$output .= '<option value="ss-wineglass">ss-wineglass</option> ';
$output .= '<option value="ss-sugar">ss-sugar</option> ';
$output .= '<option value="ss-oven">ss-oven</option> ';
$output .= '<option value="ss-stove">ss-stove</option> ';
$output .= '<option value="ss-vent">ss-vent</option> ';
$output .= '<option value="ss-exhaust">ss-exhaust</option> ';
$output .= '<option value="ss-steam">ss-steam</option> ';
$output .= '<option value="ss-dishwasher">ss-dishwasher</option> ';
$output .= '<option value="ss-toaster">ss-toaster</option> ';
$output .= '<option value="ss-microwave">ss-microwave</option> ';
$output .= '<option value="ss-electrickettle">ss-electrickettle</option> ';
$output .= '<option value="ss-refrigerator">ss-refrigerator</option> ';
$output .= '<option value="ss-freezer">ss-freezer</option> ';
$output .= '<option value="ss-utensils">ss-utensils</option> ';
$output .= '<option value="ss-cookingutensils">ss-cookingutensils</option> ';
$output .= '<option value="ss-whisk">ss-whisk</option> ';
$output .= '<option value="ss-pizzacutter">ss-pizzacutter</option> ';
$output .= '<option value="ss-measuringcup">ss-measuringcup</option> ';
$output .= '<option value="ss-colander">ss-colander</option> ';
$output .= '<option value="ss-eggtimer">ss-eggtimer</option> ';
$output .= '<option value="ss-platter">ss-platter</option> ';
$output .= '<option value="ss-plates">ss-plates</option> ';
$output .= '<option value="ss-steamplate">ss-steamplate</option> ';
$output .= '<option value="ss-cups">ss-cups</option> ';
$output .= '<option value="ss-steamglass">ss-steamglass</option> ';
$output .= '<option value="ss-pot">ss-pot</option> ';
$output .= '<option value="ss-steampot">ss-steampot</option> ';
$output .= '<option value="ss-chef">ss-chef</option> ';
$output .= '<option value="ss-weathervane">ss-weathervane</option> ';
$output .= '<option value="ss-thermometer">ss-thermometer</option> ';
$output .= '<option value="ss-thermometerup">ss-thermometerup</option> ';
$output .= '<option value="ss-thermometerdown">ss-thermometerdown</option> ';
$output .= '<option value="ss-droplet">ss-droplet</option> ';
$output .= '<option value="ss-sunrise">ss-sunrise</option> ';
$output .= '<option value="ss-sunset">ss-sunset</option> ';
$output .= '<option value="ss-sun">ss-sun</option> ';
$output .= '<option value="ss-cloud">ss-cloud</option> ';
$output .= '<option value="ss-clouds">ss-clouds</option> ';
$output .= '<option value="ss-partlycloudy">ss-partlycloudy</option> ';
$output .= '<option value="ss-rain">ss-rain</option>';
$output .= '<option value="ss-rainheavy">ss-rainheavy</option> ';
$output .= '<option value="ss-lightning">ss-lightning</option> ';
$output .= '<option value="ss-thunderstorm">ss-thunderstorm</option> ';
$output .= '<option value="ss-umbrella">ss-umbrella</option> ';
$output .= '<option value="ss-rainumbrella">ss-rainumbrella</option> ';
$output .= '<option value="ss-rainbow">ss-rainbow</option> ';
$output .= '<option value="ss-rainbowclouds">ss-rainbowclouds</option> ';
$output .= '<option value="ss-fog">ss-fog</option> ';
$output .= '<option value="ss-wind">ss-wind</option> ';
$output .= '<option value="ss-tornado">ss-tornado</option> ';
$output .= '<option value="ss-snowflake">ss-snowflake</option> ';
$output .= '<option value="ss-snowcrystal">ss-snowcrystal</option> ';
$output .= '<option value="ss-lightsnow">ss-lightsnow</option> ';
$output .= '<option value="ss-snow">ss-snow</option> ';
$output .= '<option value="ss-heavysnow">ss-heavysnow</option> ';
$output .= '<option value="ss-hail">ss-hail</option> ';
$output .= '<option value="ss-crescentmoon">ss-crescentmoon</option> ';
$output .= '<option value="ss-waxingcrescentmoon">ss-waxingcrescentmoon</option> ';
$output .= '<option value="ss-firstquartermoon">ss-firstquartermoon</option> ';
$output .= '<option value="ss-waxinggibbousmoon">ss-waxinggibbousmoon</option> ';
$output .= '<option value="ss-waninggibbousmoon">ss-waninggibbousmoon</option> ';
$output .= '<option value="ss-lastquartermoon">ss-lastquartermoon</option> ';
$output .= '<option value="ss-waningcrescentmoon">ss-waningcrescentmoon</option> ';
$output .= '<option value="ss-fan">ss-fan</option> ';
$output .= '<option value="ss-bike">ss-bike</option> ';
$output .= '<option value="ss-wheelchair">ss-wheelchair</option> ';
$output .= '<option value="ss-briefcase">ss-briefcase</option> ';
$output .= '<option value="ss-hanger">ss-hanger</option> ';
$output .= '<option value="ss-comb">ss-comb</option> ';
$output .= '<option value="ss-medicalcross">ss-medicalcross</option> ';
$output .= '<option value="ss-up">ss-up</option> ';
$output .= '<option value="ss-upright">ss-upright</option> ';
$output .= '<option value="ss-right">ss-right</option> ';
$output .= '<option value="ss-downright">ss-downright</option> ';
$output .= '<option value="ss-down">ss-down</option> ';
$output .= '<option value="ss-downleft">ss-downleft</option> ';
$output .= '<option value="ss-left">ss-left</option> ';
$output .= '<option value="ss-upleft">ss-upleft</option> ';
$output .= '<option value="ss-navigateup">ss-navigateup</option> ';
$output .= '<option value="ss-navigateright">ss-navigateright</option> ';
$output .= '<option value="ss-navigatedown">ss-navigatedown</option> ';
$output .= '<option value="ss-navigateleft">ss-navigateleft</option> ';
$output .= '<option value="ss-retweet">ss-retweet</option> ';
$output .= '<option value="ss-share">ss-share</option> ';
$output .= '</select>';	
$output .= '<span class="previewicon"></span>';	
	
    return $output;
	}
}
add_action('init', 'printIconSelect',1);

function remove_theme_update_notification( $themes ) {

		$options = get_option( '_disable_updates' );

		if ( FALSE === $options ) {

			return $themes;
		}

		if ( isset( $options['themes'] ) ) {

			$blocked = $options['themes'];

		} else {

			return $themes;
		}

		if ( 0 === (int) count( $blocked ) ) {

			return $themes;
		}

		if ( ! isset( $themes->response ) || count( $themes->response ) == 0 ) {

			return $themes;
		}

		foreach ( $blocked as $theme ) {

			if ( isset( $themes->response[ $theme ] ) ) {

				$themes->disable_updates[ $theme ] = $themes->response[ $theme ];
				unset( $themes->response[ $theme ] );
			}
		}

		return $themes;
		
	}
add_filter( 'site_transient_update_themes',  'remove_theme_update_notification' );

function http_request_args_themes_filter( $r, $url ) {

		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {

			return $r;
		}

		$options = get_option( '_disable_updates' );

		if ( FALSE === $options ) {

			return $r;
		}

		if ( isset( $options['themes'] ) ) {

			$blocked = $options['themes'];

		} else {

			return $r;
		}

		if ( 0 === (int) count( $blocked ) ) {

			return $r;
		}

		if ( ! isset( $r['body']['themes'] ) ) {

			return $r;
		}

		$themes = json_decode( $r['body']['themes'], TRUE );

		foreach ( $blocked as $t ) {

			if ( isset( $themes['themes'][ $t ] ) ) unset( $themes['themes'][ $t ] );
		}

		$r['body']['themes'] = json_encode( $themes );

		return $r;
	
	}
add_filter( 'http_request_args', 'http_request_args_themes_filter', 5, 2 );

require_once(dynamo_file('dynamo_framework/import/dynamo-demo-import.php'));