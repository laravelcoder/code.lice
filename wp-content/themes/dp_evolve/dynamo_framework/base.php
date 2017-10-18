<?php

/**
 *
 * Main framework class
 *
 **/

define('DYNAMO_WP', 1);

// Including file with template object class
include_once(dynamo_file('dynamo_framework/classes/class.dpobject.php'));
// Including file with template browser class
include_once(dynamo_file('dynamo_framework/classes/class.dpbrowser.php'));
// Including file with menu walker class
include_once(dynamo_file('dynamo_framework/classes/class.menu.walker.setup.php'));
// Including file with menu walker class
include_once(dynamo_file('dynamo_framework/classes/class.menu.walker.edit.php'));
// Including file with menu walker class
include_once(dynamo_file('dynamo_framework/classes/class.menu.walker.php'));
// Including file with mobile menu walker class
include_once(dynamo_file('dynamo_framework/classes/class.menu.walker.mobile.php'));
// Including file used for WPML support
if (defined('ICL_SITEPRESS_VERSION')) {
	include_once(dynamo_file('dynamo_framework/wpml.php'));
}

class DynamoWP {
	
	/**
	 *
	 * Array with the template configuration.
	 * 
	 * This configuration can be used in all other functions
	 * connected with the template. Structure of the configuration
	 * depend from the specific template.
	 *
	 **/
	
	public $config;
	
	/**
	 *
	 * Variable which contains the page language
	 *
	 * This variable is responsible for the language detection used in getting proper
	 * JSON files for specific language
	 *
	 **/
	 
	public $language;
	
	/**
	 *
	 * Array with the detected problems
	 *
	 * This list is used to show detected problems with
	 * the template and server configuration.
	 *
	 **/
	
	public $problems;
	
	/**
	 *
	 * Template name contains only selected characters
	 *
	 * Characters are [A-Za-z0-9]
	 *
	 **/
	
	public $name;
	
	
	/**
	 *
	 * Template name from template JSON file
	 *
	 **/
	
	public $full_name;
	
	/**
	 *
	 * Template menus
	 *
	 **/
	
	public $menu;
	
	/**
	 *
	 * Template fonts
	 *
	 **/
	
	public $fonts;
	
	/**
	 *
	 * Template widgets counts
	 *
	 **/
	
	public $widgets;
	
	/**
	 *
	 * Template styles
	 *
	 **/
	 
	public $styles;
	public $style_colors;
	
	/**
	 *
	 * Browser object
	 *
	 **/
	
	public $browser;
	
	/**
	 *
	 * Flag to detect SSL
	 *
	 **/
	 
	public $isSSL; 
	
	/**
	 *
	 * Class constructor
	 *
	 **/
	
	public function __construct() {
		// set the default language
		$this->language = 'en_US';
		// set the problems array
		$this->problems = array();
		// check if the get_locale returns a language and if
		// the directories for this language exists
		if(
			get_locale() != '' && 
			is_dir(get_template_directory() . '/dynamo_framework/config/'. get_locale()) && 
			is_dir(get_template_directory() . '/dynamo_framework/options/'. get_locale())
		) {
			$this->language = 'en_US';	
		} else {
			// if the locale doesn't exists or the dir's doesn't exist - save problems
			if(get_locale() != '' && !is_dir(get_template_directory() . '/dynamo_framework/config/'. get_locale())) {
				array_push($this->problems, 'The dynamo_framework/config/'.get_locale().' directory doesn\'t exist');
			}
			// 
			if(get_locale() != '' && !is_dir(get_template_directory() . '/dynamo_framework/options/'. get_locale())) {
				array_push($this->problems, 'The dynamo_framework/options/'.get_locale().' directory doesn\'t exist');
			}
		}
		// read the JSON file with template basic settings
		$this->read_config();
		// set the browser object
		$browser = new DPBrowser();
		$this->browser = $browser->getResult();
		// set the isSSL flag
		$this->isSSL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? true : false;
	}

	/**
	 *
	 * Function to initialize the framework
	 *
	 * @return null
	 *
	 **/
	
	public function init() {
		// add actions connected with the template
		$this->add_actions();
		// add filters connected with the template
		$this->add_filters();
		// add admin panel features
		$this->add_features();
		// set the $content_width variable
		if(!isset($content_width)) { 
			$content_width = get_option($this->name . '_content_width_variable', 1024);
		}
	}
	
	/**
	 *
	 * Function to read template JSON file
	 * 
	 * This JSON file contains:
	 * - template name, 
	 * - template version,
	 * - template description,
	 * - template settings,
	 * - developer settings
	 *
	 * @return null
	 *
	 **/
	
	private function read_config() {
		// load and parse template JSON file.
		$json_data = $this->get_json('config','template');
		// read the configuration
		$this->config = array(
							'template' => $json_data->template,
							'developer_config' => $json_data->developer_config
						);
		// save the full name
		$this->full_name = $this->config['template']->name;				
		// save the lowercase non-special characters template name				
		$this->name = strtolower(preg_replace("/[^A-Za-z0-9]/", "", $this->config['template']->name));
	}
	
	/**
	 *
	 * Function to add template actions connected with
	 * the functions located in dynamo_framework/functions.php file
	 *
	 * @return null
	 *
	 **/
	 
	 private function add_actions() {
	 	add_action( 'admin_menu', 'dynamo_admin_menu' );
	 	add_action( 'widgets_init', 'dynamo_widgets_init' );
	 }

	/**
	 *
	 * Function to add template filters connected with
	 * the functions located in dynamo_framework/filters.php file
	 *
	 * @return null
	 *
	 **/
	 
	 private function add_filters() {
	 	// adds the custom die handler
	 	add_filter('wp_die_handler', 'dynamo_die_handler');
	 }
	 
	 /**
	  *
	  * Function to add admin panel features
	  * like typography button in the editor
	  *
	  * @return null
	  *
	  **/
	  
	 private function add_features() {
	 	// add support for the post formats
	 	add_theme_support( 'post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat') ); 
	 	// theme will style the visual editor using the editor-style.css file.
	 	add_editor_style();
	 	// add support for post thumbnails
	 	add_theme_support( 'post-thumbnails' );
		add_image_size('portfolio-two', 596, 448, true);
		add_image_size('portfolio-three', 398, 298, true);
		add_image_size('portfolio-four', 298, 224, true);
		add_image_size('portfolio-recent', 298, 224, true);
	 	// add support for default posts and comments RSS feed links in the head
	 	add_theme_support( 'automatic-feed-links' );
       // add support for widgets in Theme Customizer
		add_theme_support('widget-customizer');
		// add support for Woocommerce 2.0 plugin
		add_theme_support( 'woocommerce' );
	 	// register menus
	 	$this->register_menus();
	 	// register styles
	 	$this->register_styles();
	 	// register fonts
	 	$this->register_fonts();
	 	// add metaboxes
	 	add_action('add_meta_boxes', 'add_dynamo_metaboxes' );
	 	add_action('save_post', 'dynamo_metaboxes_save' ); 
	 	// add DP Shortcode buttons to the TinyMCE editor
	 	add_action('admin_init', 'add_dp_shortcode_buttons');
	 	// register template built-in widgets
	 	register_widget('DP_Comments_Widget');
	 	register_widget('DP_Social_Widget');
	 	register_widget('DP_NSP_Widget');
	 	register_widget('DP_Tabs_Widget');
		register_widget('DP_Flickr_Widget');
		register_widget('DP_Recent_Port_Widget');
		register_widget('DP_Recent_Post_Widget');
		register_widget('DP_Recent_Port_Grid_Widget');
		register_widget('DP_NewsFlash_Widget');
		register_widget('DP_Tweets_Widget');
	 }	
	 
	 /**
	  *
	  * Function to register all menus from menus.json file
	  * and store useful informations in the $menu property
	  * 
	  * @return null
	  * 
	  **/
	  
	 private function register_menus() {
	 	// load and parse JSON file.
	 	$json_data = $this->get_json('config','menus');
	 	// iterate through all menus in the file
	 	foreach ($json_data as $menu) {
	 		// register menu
	 		register_nav_menu( (string)$menu->location, (string)$menu->description );	
	 		// menus
	 		$this->menu[(string)$menu->location] = array(
	 			"mainmenu" => (string) $menu->main,
	 			"state" => get_option($this->name . '_navigation_menu_state_'.(string) $menu->location, 'Y'),
	 			"state_rule" => get_option($this->name . '_navigation_menu_staterule_'.(string) $menu->location, ''),
	 			"depth" => get_option($this->name . '_navigation_menu_depth_'.(string) $menu->location, '0'),
	 			"style" => ((string) $menu->main == 'true') ? get_option($this->name . '_navigation_menu_style_'.(string) $menu->location, 'dp_extra') : 'dp_normal',
	 			"animation" => get_option($this->name . '_navigation_menu_animation_'.(string) $menu->location, 'none'),
	 			"animation_speed" => get_option($this->name . '_navigation_menu_animationspeed_'.(string) $menu->location, 'normal')
	 		);
	 	}
	 }
	 
	 /**
	  *
	  * Function to register all template styles from styles.json file
	  * and store useful informations in the $styles property
	  * 
	  * @return null
	  * 
	  **/
	 
	 private function register_styles() {
	 	// load and parse JSON file.
	 	$json_data = $this->get_json('config','styles');
	 	// init array
	 	$this->styles = array();
	 	$this->style_colors = array();
	 	// iterate through all menus in the file
	 	foreach ($json_data as $styles_family) {
	 		// push the styles family name to the $styles property
	 		array_push($this->styles, (string) $styles_family->family);
	 		$this->style_colors[(string) $styles_family->family] = array();
	 		// creating connections - style value => style file
	 		foreach($styles_family->styles as $style) {
	 			$this->style_colors[(string) $styles_family->family][(string) $style->value] = (string) $style->file;
	 		}
	 	}
	 }
	 
	 /**
	  *
	  * Function to register all template font groups from fonts.json file
	  * and store useful informations in the $fonts property
	  * 
	  * @return null
	  * 
	  **/
	 
	 private function register_fonts() {
	 	// load and parse JSON file.
	 	$json_data = $this->get_json('config','fonts');
	 	// init array
	 	$this->fonts = array();
	 	// iterate through all menus in the file
	 	foreach ($json_data as $fonts_family) {
	 		// push the fonts family name to the $fonts property
	 		array_push($this->fonts, array(
	 			"short_name" => (string) $fonts_family->short_name,
	 			"full_name" => (string) $fonts_family->full_name
	 		));
	 	}
	 }
	 
	/**
	*
	* Function to load specific JSON file
	* 
	* @param dir - the directory with JSON files
	* @param filename - name of the file to load - without ".json" extension
	* @param lang - if the directory supports multilanguage support
	*
	* @return JSON object from the loaded file
	* 
	**/ 
	  
	public function get_json($dir, $filename, $lang = true) {		
		// lang dir
		$lang = ($lang) ? ($this->language) . '/' : '';
		if (defined('ICL_SITEPRESS_VERSION')) {
			$lang = apply_filters('dynamo-get-json',$dir,$lang);
		}
		if(is_dir(get_stylesheet_directory().'/dynamo_framework/' . $dir . '/' . $lang)) {
		$path = dynamo_file('dynamo_framework/' . $dir . '/' . $lang . $filename . '.json');
		} else {
		$path = dynamo_file('dynamo_framework/' . $dir . '/en_US/'. $filename . '.json');
		}
		// check if the specified file exists
		if(file_exists($path)) {
			// decode data from the JSON file
			$json_data = json_decode(wp_remote_fopen($path));
			// check for the older PHP versions
			if(function_exists('json_last_error')) {
				// get the errors
				switch(json_last_error()) {
					case JSON_ERROR_DEPTH:
					    array_push($this->problems, 'JSON ERROR: Maximum stack depth exceeded in ' . $path);
					    return array();
					break;
					case JSON_ERROR_CTRL_CHAR:
					    array_push($this->problems, 'JSON ERROR: Unexpected control character found in ' . $path);
					    return array();
					break;
					case JSON_ERROR_SYNTAX:
					    array_push($this->problems, 'JSON ERROR: Syntax error, malformed JSON in ' . $path);
					    return array();
					break;
					case JSON_ERROR_NONE:
					    // No errors
					    return json_decode(file_get_contents($path));
					break;
				}
			} else {
				return json_decode(file_get_contents($path));
			}
		} else {
			// if the file doesn't exist - push the error
			array_push($this->problems, 'JSON ERROR: file '.$path.' doesn\'t exist');
			return array();
		}
	}

}	

// EOF