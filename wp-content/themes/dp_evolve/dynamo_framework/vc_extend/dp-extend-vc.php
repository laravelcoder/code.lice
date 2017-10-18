<?php
vc_set_as_theme($disable_updater = true);

// Removing shortcodes
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_teaser_grid");
vc_remove_element("vc_button");
vc_remove_element("vc_cta_button");
vc_remove_element("vc_message");
vc_remove_element("vc_pie");
vc_remove_element("vc_posts_slider");
$remove_depreciate = array (
  'deprecated' => ''
);
vc_map_update( 'vc_tab', $remove_depreciate );
vc_map_update( 'vc_tour', $remove_depreciate );
vc_map_update( 'vc_accordion_tab', $remove_depreciate );

// Add VC admin CSS styles
function load_custom_vc_admin_style() {
        wp_register_style( 'custom_vc_admin_css', get_template_directory_uri() . '/dynamo_framework/vc_extend/dp_vc_admin.css', false, '' );
        wp_enqueue_style( 'custom_vc_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_vc_admin_style' );

//Add VC frontend styles
function load_custom_vc_frontend_style() {
		 wp_register_style( 'custom_vc_frontend_css', get_template_directory_uri().'/dynamo_framework/vc_extend/dp_vc_frontend.css', array('js_composer_front'), '', 'screen' );
		 wp_enqueue_style( 'custom_vc_frontend_css' );
    }
add_action('wp_head', 'load_custom_vc_frontend_style', 6);

// Add custom functions and arrays
function icon_settings_field($settings, $value) {
   //$dependency = vc_generate_dependencies_attributes($settings);
   $icon_id =uniqid("icon_");
   return '<div class="icon-selector-block">'
             .'<input data-preview ="previewicon-'.$icon_id.'" id="'.$icon_id.'" name="'.$settings['param_name']
             .'" class="wpb_vc_param_value wpb-textinput '
             .$settings['param_name'].' '.$settings['type'].'_field" type="text" value="'
             .$value.'"/>'
         .'</div>'
         .'<a href="'. get_template_directory_uri().'/dynamo_framework/fontello/icons.php?parentid='.$icon_id.' &parentpreviewid=previewicon-'.$icon_id.'
&TB_iframe=true&width=500&height=550" class="thickbox button">Select icon</a><span class="previewicon" id="previewicon-'.$icon_id.'"></span>';
}
vc_add_shortcode_param('icon_selector', 'icon_settings_field', get_template_directory_uri().'/dynamo_framework/vc_extend/dp_vc_js.js');

if (!function_exists('getSliderArray')){
	function getSliderArray() {
    $terms = get_terms('slideshows');	
	$output = array("" => "");	
    $count = count($terms);
	    if ( $count > 0 ):
        foreach ( $terms as $term ):
            $output[$term->name] = $term->slug;
        endforeach;
    endif;
	
    return $output;
	}
}
add_action('init', 'getSliderArray',3);

if (!function_exists('getPortfoliosArray')){
	function getPortfoliosArray() {
    $terms = get_terms('portfolios');	
	$output = array("All" => "all");	
    $count = count($terms);
	    if ( $count > 0 ):
        foreach ( $terms as $term ):
            $output[$term->name] = $term->slug;
        endforeach;
    endif;
	
    return $output;
	}
}
add_action('init', 'getPortfoliosArray',3);

if (!function_exists('getCategoriesArray')){
	function getCategoriesArray() {
    $terms = get_terms('category');	
	$output = array("All" => "all");	
    $count = count($terms);
	    if ( $count > 0 ):
        foreach ( $terms as $term ):
            $output[$term->name] = $term->slug;
        endforeach;
    endif;
	
    return $output;
	}
}
add_action('init', 'getCategoriesArray',3);




$slideshows = getSliderArray();
$portfolios = getPortfoliosArray();
$categories = getCategoriesArray();


$add_dp_animation = array(
  "type" => "dropdown",
  "heading" => __("CSS Animation", 'dp-theme'),
  "param_name" => "dp_animation",
  "admin_label" => true,
  'save_always' => true,
  "value" => array ("No" => "",
  		"fadeIn " => "fadeIn",
		"fadeInUp" => "fadeInUp",
		"fadeInDown" => "fadeInDown",
		"fadeInLeft" => "fadeInLeft",
		"fadeInRight" => "fadeInRight",
		"fadeInUpBig" => "fadeInUpBig",
		"fadeInDownBig" => "fadeInDownBig",
		"fadeInLeftBig" => "fadeInLeftBig",
		"fadeInRightBig" => "fadeInRightBig",
		"lightSpeedRight" => "lightSpeedRight",
		"lightSpeedLeft" => "lightSpeedLeft",
		"bounceIn" => "bounceIn",
		"bounceInUp" => "bounceInUp",
		"bounceInDown" => "bounceInDown",
		"bounceInLeft" => "bounceInLeft",
		"bounceInRight" => "bounceInRight",
		"rotateInUpLeft" => "rotateInUpLeft",
		"rotateInDownLeft" => "rotateInDownLeft",
		"rotateInUpRight" => "rotateInUpRight",
		"rotateInDownRight" => "rotateInDownRight",
		"rollIn" => "rollIn",
		"pulse" => "pulse",
		"flipInX" => "flipInX",
)
);

$add_dp_slideshow = array(
  "type" => "dropdown",
  "heading" => __("Slideshow", 'dp-theme'),
  "param_name" => "slideshow",
  "admin_label" => true,
  'save_always' => true,
  "value" => $slideshows,
  "description" => "Select slideshow from available slideshows list"
);

$add_dp_category = array(
  "type" => "dropdown",
  "heading" => __("Category", 'dp-theme'),
  "param_name" => "category",
  "admin_label" => true,
  'save_always' => true,
  "value" => $categories,
  "description" => "Select category from available categories list"

);
$add_dp_portfolios = array(
  "type" => "dropdown",
  "heading" => __("Portfolio category", 'dp-theme'),
  "param_name" => "portfolios",
  "admin_label" => true,
  'save_always' => true,
  "value" => $portfolios,
  "description" => "Select portfolio category from available portfolio categories list"
);



if (!function_exists('getDPAnimation')){
	function getDPAnimation($animation) {
	$output = '';
	if ($animation != '') $output .= 'data-animated ="'.$animation.'"';
    return $output;
	}
}

//Add custom parameters to existing VC elements

//Animations
vc_remove_param("vc_single_image", "css_animation"); 				
vc_add_param("vc_single_image", $add_dp_animation);

vc_remove_param("vc_column_text", "css_animation"); 				
vc_add_param("vc_column_text", $add_dp_animation);

vc_remove_param("vc_cta_button2", "css_animation"); 				
vc_add_param("vc_cta_button2", $add_dp_animation);

vc_add_param("vc_column", $add_dp_animation);

//VC Row
vc_remove_param("vc_row", "el_class"); 				
vc_remove_param("vc_row", "full_width"); 				
vc_remove_param("vc_row", "video_bg"); 				
vc_remove_param("vc_row", "video_bg_url"); 				
vc_remove_param("vc_row", "video_bg_parallax"); 				
vc_remove_param("vc_row", "parallax"); 				
vc_remove_param("vc_row", "parallax_image"); 				
//vc_remove_param("vc_row", "el_id");

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Content Type", 'dp-theme'),
	"param_name" => "type",
	'save_always' => true,
	"value" => array(
		"In Grid" => "grid",	
		"Full Width" => "full_width"
		
	),
    "description" => __('This settings affected only when "Full width" page template is used. Here you can decide if row content should be boxed in page grid or 100% screen width.  ', 'dp-theme')
));

vc_add_param("vc_row", array(
	"type" => "checkbox",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "Parallax Background",
	"value" => array("Enable Parallax Background?" => "true" ),
	"param_name" => "parallax_bg",
    "description" => __("Enable / Disable paralax effect for background image", 'dp-theme')
	));
	
vc_add_param("vc_row",array(
						"type" => "textfield",
						"class" => "",
						"heading" => __( "Parallax Speed", 'dp-theme' ),
						"param_name" => "parallax_speed",
						"value" => "0.3",
						"description" => __( "The movement speed, value should be between 0.1 and 1.0. A lower number means slower scrolling speed. Be mindful of the <strong>background size</strong> and the <strong>dimensions</strong> of your background image when setting this value. Faster scrolling means that the image will move faster, make sure that your background image has enough width or height for the offset.", 'dp-theme' ),
	"group" => 'DP Background Options',
	));
	
vc_add_param("vc_row", array(
    "type" => "dropdown",
	"group" => 'DP Background Options',
    "heading" => __('Video background', 'dp-theme'),
    "param_name" => "video_bg",
	'save_always' => true,
    "value" => array(
                        __("None", 'dp-theme') => '',
                        __("HTML5 video background", 'dp-theme') => 'html5videobg',
                        __('Youtube video background', 'dp-theme') => 'ytvideobg'
                      ),
      "description" => __("Select a background video type for your row", 'dp-theme'),
    ));
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "WebM File URL",
	"value" => "",
	"param_name" => "video_webm",
	"description" => "You must include this format & the mp4 format to render your video with cross browser compatibility. OGV is optional.
Video must be in a 16:9 aspect ratio.",
	"dependency" => array('element' => "video_bg", 'value' =>  array('html5videobg'))
	));
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "MP4 File URL",
	"value" => "",
	"param_name" => "video_mp4",
	"description" => "Enter the URL for your mp4 video file here",
	"dependency" => array('element' => "video_bg", 'value' =>  array('html5videobg'))
	));
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "OGV File URL",
	"value" => "",
	"param_name" => "video_ogv",
	"description" => "Enter the URL for your ogv video file here",
	"dependency" => Array('element' => "video_bg", 'value' =>  array('html5videobg'))
	));
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "YouTube video URL",
	"value" => "",
	"param_name" => "video_yt",
	"description" => "Enter the URL for your YouTube video file here",
	"dependency" => Array('element' => "video_bg", 'value' =>  array('ytvideobg'))
	));
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "Start at",
	"value" => "",
	"param_name" => "start_at",
	"description" => "Enter a Youtube video start time in seconds. If you leave blank video will be start from begining.",
	"dependency" => Array('element' => "video_bg", 'value' =>  array('ytvideobg'))
	));
vc_add_param("vc_row", array(
	"type" => "checkbox",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "Video raster",
	"value" => array("Use raster?" => "use_raster" ),
	"param_name" => "use_raster",
	"description" => "",
	"dependency" => Array('element' => "video_bg", 'value' =>  array('ytvideobg'))
	));
vc_add_param("vc_row", array(
    "type" => "dropdown",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "Audio",
	"param_name" => "mute",
	'save_always' => true,
    "value" => array(
                        __("Muted", 'dp-theme') => 'muted',
                        __("Unmuted", 'dp-theme') => 'unmuted'
                      ),
    "description" => __("Select a video audio default stand", 'dp-theme'),
	"dependency" => Array('element' => "video_bg", 'value' =>  array('ytvideobg','html5videobg'))
	));
vc_add_param("vc_row", array(
	"type" => "checkbox",
	"class" => "",
	"group" => 'DP Background Options',
	"heading" => "Mute Button",
	"value" => array("Enable Mute / Unmute Button?" => "true" ),
	"param_name" => "mute_btn",
	"dependency" => Array('element' => "video_bg", 'value' =>  array('ytvideobg','html5videobg'))
	));
vc_add_param("vc_row", array(
        "type" => "textfield",
        "heading" => __("Extra class name", 'dp-theme'),
        "param_name" => "el_class",
        "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme'),
      ));

//VC Tab

$tab_id_1 = ''; // 'def' . time() . '-1-' . rand( 0, 100 );
$tab_id_2 = ''; // 'def' . time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => __( 'DP Tabs', 'dp-theme' ),
	'base' => 'vc_tabs',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-tab-content',
    'category' =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
	'description' => __( 'Tabbed content', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'dp-theme' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'dp-theme' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate', 'dp-theme' ),
			'param_name' => 'interval',
			'save_always' => true,
			'value' => array( __( 'Disable', 'dp-theme' ) => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => __( 'Auto rotate tabs each X seconds.', 'dp-theme' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'dp-theme' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'dp-theme' )
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[vc_tab title="' . __( 'Tab 1', 'dp-theme' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . __( 'Tab 2', 'dp-theme' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' =>'VcTabsView'
) );


vc_add_param("vc_tab", 
			array(
				"type" => "icon_selector",
				"admin_label" => true,
				"class" => "",
				"heading" => __("Icon", 'dp-theme'),
				"param_name" => "icon"
				));
vc_add_param("vc_tab", array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Subtitle", 'dp-theme'),
				"value" => " ",
				"param_name" => "subtitle",
			    "description" => __("This field will be used only by custom diamond skin", 'dp-theme')
				));
vc_add_param("vc_tabs", array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Fullwidth", 'dp-theme'),
				"param_name" => "fullwidth"
				));
vc_add_param("vc_tabs", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => "Force fullwidth tabs in navigation?",
	"value" => array("Yes, please" => "true" ),
	"param_name" => "fullwidth",
    "description" => __("This setting will work only by tabs count lower then 9 tabs", 'dp-theme')
	));
	  
// Accordion
vc_map( array(
	'name' => __( 'DP Accordion', 'dp-theme' ),
	'base' => 'vc_accordion',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-accordion',
    'category' =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
	'description' => __( 'Collapsible content panels', 'dp-theme' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'dp-theme' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'dp-theme' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Active section', 'dp-theme' ),
			'param_name' => 'active_tab',
			'value' => 1,
			'description' => __( 'Enter section number to be active on load or enter "false" to collapse all sections.', 'dp-theme' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Allow collapse all sections?', 'dp-theme' ),
			'param_name' => 'collapsible',
			'description' => __( 'If checked, it is allowed to collapse all sections.', 'dp-theme' ),
			'value' => array( __( 'Yes', 'dp-theme' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Disable keyboard interactions?', 'dp-theme' ),
			'param_name' => 'disable_keyboard',
			'description' => __( 'If checked, disables keyboard arrow interactions (Keys: Left, Up, Right, Down, Space).', 'dp-theme' ),
			'value' => array( __( 'Yes', 'dp-theme' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'dp-theme' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'dp-theme' )
		)
	),
	'custom_markup' => '
<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
%content%
</div>
<div class="tab_controls">
    <a class="add_tab" title="' . __( 'Add section', 'dp-theme' ) . '"><span class="vc_icon"></span> <span class="tab-label">' . __( 'Add section', 'dp-theme' ) . '</span></a>
</div>
',
	'default_content' => '
    [vc_accordion_tab title="' . __( 'Section 1', 'dp-theme' ) . '"][/vc_accordion_tab]
    [vc_accordion_tab title="' . __( 'Section 2', 'dp-theme' ) . '"][/vc_accordion_tab]
',
	'js_view' => 'VcAccordionView'
) );

vc_add_param("vc_accordion_tab", array(
	"type" => "icon_selector",
	"admin_label" => true,
	"class" => "",
	"heading" => __("Icon", 'dp-theme'),
	"param_name" => "icon",
	"description" => ""
));


// Vertical tabs

$tab_id_1 = time().'-1-'.rand(0, 100);
$tab_id_2 = time().'-2-'.rand(0, 100);
WPBMap::map( 'vc_tour', array(
  "name" => __("DP Vertical Tabs", 'dp-theme'),
  "base" => "vc_tour",
  "show_settings_on_create" => false,
  "is_container" => true,
  "container_not_allowed" => true,
  "icon" => "icon-wpb-ui-tab-content-vertical",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "wrapper_class" => "clearfix",
  "description" => __('Vertical tabbed content', 'dp-theme'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Widget title", 'dp-theme'),
      "param_name" => "title",
      "description" => __("Enter text which will be used as widget title. Leave blank if no title is needed.", 'dp-theme')
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Auto rotate slides", 'dp-theme'),
      "param_name" => "interval",
	  'save_always' => true,
      "value" => array(__("Disable", 'dp-theme') => 0, 3, 5, 10, 15),
      "std" => 0,
      "description" => __("Auto rotate slides each X seconds.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  ),
  "custom_markup" => '  
  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
  <ul class="tabs_controls">
  </ul>
  %content%
  </div>'
  ,
  'default_content' => '
  [vc_tab title="'.__('Slide 1','dp-theme').'" tab_id="'.$tab_id_1.'"][/vc_tab]
  [vc_tab title="'.__('Slide 2','dp-theme').'" tab_id="'.$tab_id_2.'"][/vc_tab]
  ',
  "js_view" => ('VcTabsView' )
) );

/* Dynamicpress elements
---------------------------------------------------------- */

/* Dp Space
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Spacer", 'dp-theme'),
  "base" => "space",
  "icon" => "icon-wpb-spacer",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Blank space at a certain height ', 'dp-theme'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Size in px", 'dp-theme'),
	  "admin_label" => true,
      "param_name" => "size"
    )

  )
) );

// Headline shortcode
vc_map( array(
		"name" => __("DP Headline", 'dp-theme'),
		"base" => "headline",
		"icon" => "icon-wpb-heading",
		"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
		"allowed_container_element" => 'vc_row',
      	"description" => __("Headlin block", 'dp-theme'),
		"params" => array(
			array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __("Style", 'dp-theme'),
				"param_name" => "style",
				'save_always' => true,
				"value" => array(
					"Default" => "",
					"Small underlined" => "heading-line",	
					"Big centered" => "big-centered"
				),
				"description" => "",
			),

			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Headline title", 'dp-theme'),
				"param_name" => "content",
				"value" => "Heading",
				"value" => __("This is header", 'dp-theme')
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Headline subtitle", 'dp-theme'),
				"param_name" => "subtitle",
				"value" => "Subtitle text",
				"dependency" => Array('element' => "style", 'value' => array('big-centered'))
			),
			array(
			  "type" => "textfield",
			  "holder" => "div",
			  "heading" => __("Custom CSS class", 'dp-theme'),
			  "param_name" => "cssclass",
			  "description" => ""
    )
	)
) );


/* DP Button */
vc_map( array(
		"name" => __("DP Button", 'dp-theme'),
		"base" => "button",
		"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
		"icon" => "icon-wpb-dp-button",
		"allowed_container_element" => 'vc_row',
		"params" => array(
			array(
				"type" => "textfield",
				"class" => "",
				"holder" => "div",
				"heading" => __("Button Text", 'dp-theme'),
				"param_name" => "content",
				"description" => "",
				"value" => __("Button text", 'dp-theme')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Size", 'dp-theme'),
				"param_name" => "size",
				'save_always' => true,
				"value" => array(
					"Small" => "small",	
					"Medium" => "medium",	
					"Large" => "large",
				),
				"description" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Style", 'dp-theme'),
				"param_name" => "style",
				'save_always' => true,
				"value" => array(
					"Default" => "",	
					"Dark" => "dark",	
					"Light" => "light",
					"Transparent bordered" => "line",
					"Transparent bordered white" => "line-white",
					"White" => "white",
					"Black" => "black",
					"Gray" => "gray",
					"Limon" => "limon",
					"Pink" => "pink",
					"Burgund" => "burgund",
					"Coffee" => "coffee",
					"Orange" => "orange",
					"Purple" => "purple",
					"Blue" => "blue",
					"Teal" => "teal",
					"-------------------------------------" => "",
					"Custom" => "custom"
				),
				"description" => ""
			),
			 array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => __("Button background color", 'dp-theme'),
				"param_name" => "bgcolor",
				"value" => "",
				"description" => __("Select button background color", 'dp-theme'),
				"dependency" => Array('element' => "style", 'value' => array('custom'))
			),
			 array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => __("Button text color", 'dp-theme'),
				"param_name" => "textcolor",
				"value" => "",
				"description" => __("Select button text color", 'dp-theme'),
				"dependency" => Array('element' => "style", 'value' => array('custom'))
			),			
			 array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => __("Button hover state background color", 'dp-theme'),
				"param_name" => "hbgcolor",
				"value" => "",
				"description" => __("Select hover state background color", 'dp-theme'),
				"dependency" => Array('element' => "style", 'value' => array('custom'))
			),			
			 array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => __("Hover state text color", 'dp-theme'),
				"param_name" => "htextcolor",
				"value" => "",
				"description" => __("Select hover state text color", 'dp-theme'),
				"dependency" => Array('element' => "style", 'value' => array('custom'))
			),			
			array(
				"type" => "icon_selector",
				"class" => "",
				"heading" => __("Icon", 'dp-theme'),
				"param_name" => "icon",
				"description" => "Full list and preview of available icons <a href='http://www.dynamicpress.eu/documentation/icons_evolve/' target='_blank'>here</a>"
				),
			array(
				"type" => "dropdown",
				"heading" => __("Button alignment", 'dp-theme'),
				"param_name" => "align",
				'save_always' => true,
				"value" => array(
					"None" => "",
					"Center" => "center",	
					"Right" => "right"
				),
				"description" => "",
			),
			$add_dp_animation,
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Link", 'dp-theme'),
				"param_name" => "link",
				"description" => ""
			),
			array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __("Open in", 'dp-theme'),
				"param_name" => "linktarget",
				'save_always' => true,
				"value" => array(
					"The same window" => "_self",
					"New window" => "_blank",	
					"Parent" => "_parent"
				),
				"description" => "",
			)
		)
) );
// Progress bar shortcode
vc_map( array(
		"name" => __("DP Progress Bar", 'dp-theme'),
		"base" => "progress_bar",
		"icon" => "icon-wpb-progress_bar",
		"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
		"allowed_container_element" => 'vc_row',
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Title", 'dp-theme'),
				"param_name" => "title",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Title Color", 'dp-theme'),
				"param_name" => "titlecolor",
				"description" => ""
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Percentage", 'dp-theme'),
				"param_name" => "percent",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Bar color", 'dp-theme'),
				"param_name" => "barcolor",
				"description" => ""
			)
		)
) );

// Piechart shortcode
vc_map( array(
		"name" => __("DP Pie Chart", 'dp-theme'),
		"base" => "piechart",
		"icon" => "icon-wpb-pie-chart",
		"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
		"allowed_container_element" => 'vc_row',
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Size", 'dp-theme'),
				"param_name" => "size",
				"description" => "Size of chart in px"
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Bar line width", 'dp-theme'),
				"param_name" => "linewidth"
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Percentage", 'dp-theme'),
				"param_name" => "percent",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Percent color", 'dp-theme'),
				"param_name" => "percentcolor",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Bar color", 'dp-theme'),
				"param_name" => "barcolor",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Track color", 'dp-theme'),
				"param_name" => "trackcolor",
				"description" => ""
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Title", 'dp-theme'),
				"param_name" => "title",
			  	"value" => __("Pie Chart title", 'dp-theme'),
				"description" => ""
			),
			array(
			  "type" => "textarea_html",
			  "holder" => "div",
			  "heading" => __("Description", 'dp-theme'),
			  "param_name" => "content",
			  "value" => __("<p>I am Pie Chart description.</p>", 'dp-theme')
			  
    ),
			$add_dp_animation,
	)
) );

// Piechart 2 shortcode
vc_map( array(
		"name" => __("DP Pie Chart 2", 'dp-theme'),
		"base" => "piechart2",
		"icon" => "icon-wpb-pie-chart",
		"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
		"allowed_container_element" => 'vc_row',
      	"description" => __("Chart with background", 'dp-theme'),
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Size", 'dp-theme'),
				"param_name" => "size",
				"description" => "Size of chart in px"
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Bar line width", 'dp-theme'),
				"param_name" => "linewidth"
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Percentage", 'dp-theme'),
				"param_name" => "percent",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Percent color", 'dp-theme'),
				"param_name" => "percentcolor",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Bar color", 'dp-theme'),
				"param_name" => "barcolor",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Background color", 'dp-theme'),
				"param_name" => "bgcolor",
				"description" => ""
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Title", 'dp-theme'),
				"param_name" => "title",
			  	"value" => __("Pie Chart title", 'dp-theme'),
				"description" => ""
			),
			array(
			  "type" => "textarea_html",
			  "holder" => "div",
			  "heading" => __("Description", 'dp-theme'),
			  "param_name" => "content",
			  "value" => __("<p>I am Pie Chart description.</p>", 'dp-theme'),
    ),
			$add_dp_animation,
	)
) );


/* Dp Pricing column
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Pricing Column", 'dp-theme'),
  "base" => "pricing_column",
  "icon" => "icon-wpb-price-table",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Pricing column for Pricing Table ', 'dp-theme'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Title", 'dp-theme'),
	  "admin_label" => true,
      "param_name" => "title"
    ),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Column style", 'dp-theme'),
				"param_name" => "column_style",
				'save_always' => true,
				"value" => array(
					"Default" => "",	
					"Highlighted" => "premium"
				),
				"description" => ""
			),
    array(
      "type" => "textfield",
      "heading" => __("Price", 'dp-theme'),
      "param_name" => "price"
    ),
    array(
      "type" => "textfield",
      "heading" => __("Currency", 'dp-theme'),
      "param_name" => "currency"
    ),
    array(
      "type" => "textfield",
      "heading" => __("Price subtitle", 'dp-theme'),
      "param_name" => "price_sub"
    ),
	array(
				"type" => "textarea_html",
				"class" => "",
				"holder" => "div",
				"heading" => __("Column contentt", 'dp-theme'),
				"param_name" => "content",
				"description" => "",
				"value" => __("Enter column content here", 'dp-theme')
	),
    array(
      "type" => "textfield",
      "heading" => __("Link", 'dp-theme'),
      "param_name" => "link"
    ),
    array(
      "type" => "textfield",
      "heading" => __("Button text", 'dp-theme'),
      "param_name" => "button_txt",
	  "value" => __("Buy Now", 'dp-theme')
    ),
	
  )
) );


// Counter shortcode
vc_map( array(
		"name" => __("DP Counter", 'dp-theme'),
		"base" => "counter",
		"icon" => "icon-wpb-counter",
		"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
		"allowed_container_element" => 'vc_row',
      	"description" => __("Animated counter", 'dp-theme'),
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Counter title", 'dp-theme'),
				"param_name" => "content",
				"value" => __("Counter title", 'dp-theme')
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Counter number value", 'dp-theme'),
				"param_name" => "number"
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Number value by witch animation will be stopped", 'dp-theme'),
				"param_name" => "animate_stop",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Number color", 'dp-theme'),
				"param_name" => "numbercolor",
				"description" => ""
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Number fontsize", 'dp-theme'),
				"param_name" => "fontsize",
			  	"value" => "",
				"description" => __("Number fontsize in px", 'dp-theme')
			),

			array(
				"type" => "colorpicker",
				"holder" => "div",
				"class" => "",
				"heading" => __("Title color color", 'dp-theme'),
				"param_name" => "titlecolor",
				"description" => ""
			),
			array(
			  "type" => "textfield",
			  "holder" => "div",
			  "heading" => __("Custom CSS class", 'dp-theme'),
			  "param_name" => "cssclass",
			  "description" => ""
    )
	)
) );

/* Toggle (FAQ)
---------------------------------------------------------- */
vc_map( array(
  "name" => __("DP FAQ", 'dp-theme'),
  "base" => "faq",
  "icon" => "icon-wpb-faq",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Toggle element with icon', 'dp-theme'),
  "params" => array(
    array(
      "type" => "textfield",
      "holder" => "h4",
      "class" => "toggle_title",
      "heading" => __("Title", 'dp-theme'),
	  "save_always" => true,
      "param_name" => "title",
      "value" => __("FAQ title", 'dp-theme'),
      "description" => __("FAQ block title.", 'dp-theme')
    ),
	array(
	"type" => "icon_selector",
	"class" => "",
	"heading" => __("Icon", 'dp-theme'),
	"param_name" => "icon",
	"save_always" => true,
	"value" => "icon-help-circled-1",
	"description" => ""
	),
	array(
	"type" => "checkbox",
	"class" => "",
	"heading" => "Default state",
	"value" => array("Open at start?" => "true" ),
	"param_name" => "default_state"
	),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "class" => "toggle_content",
      "heading" => __("FAQ content", 'dp-theme'),
      "param_name" => "content",
      "value" => __("<p>FAQ content goes here, click edit button to change this text.</p>", 'dp-theme'),
      "description" => __("FAQ block content.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  )
) );
/* Team box
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Team box", 'dp-theme'),
  "base" => "teambox",
  "icon" => "icon-wpb-teambox",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Team member presentation box', 'dp-theme'),
  "params" => array(
   array(
	  "type" => "dropdown",
	  "class" => "",
	  "heading" => __("Style", 'dp-theme'),
	  'save_always' => true,
	  "param_name" => "style",
			"value" => array(
				"Default" => "",	
				"Two columns" => "alt"
			),
	  "description" => ""
	),

    array(
      "type" => "textfield",
      "heading" => __("Name", 'dp-theme'),
      "param_name" => "name",
      "value" => __("John Smith", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Position", 'dp-theme'),
      "param_name" => "title",
      "value" => __("Chief Executive Officer / CEO", 'dp-theme')
    ),
	 array(
      "type" => "attach_image",
      "heading" => __("Image", 'dp-theme'),
      "param_name" => "imgurl",
      "value" => "",
      "description" => __("Select image from media library.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Twitter link", 'dp-theme'),
      "param_name" => "twitter",
	  "description" => __("If you leave this field blank link will be not displayed", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Facebook link", 'dp-theme'),
      "param_name" => "facebook",
	  "description" => __("If you leave this field blank link will be not displayed", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Linkedin link", 'dp-theme'),
      "param_name" => "linkedin",
	  "description" => __("If you leave this field blank link will be not displayed", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Skype link", 'dp-theme'),
      "param_name" => "skype",
	  "description" => __("If you leave this field blank link will be not displayed", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("RSS link", 'dp-theme'),
      "param_name" => "rss",
	  "description" => __("If you leave this field blank link will be not displayed", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Google+ link", 'dp-theme'),
      "param_name" => "gplus",
	  "description" => __("If you leave this field blank link will be not displayed", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Email address", 'dp-theme'),
      "param_name" => "email",
	  "description" => __("If you leave this field blank emai link will be not displayed", 'dp-theme')
    ),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "heading" => __("Description", 'dp-theme'),
      "param_name" => "content",
      "value" => __("<p>Team member activity description</p>", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  )
) );

/* Testimonial
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Testimonial", 'dp-theme'),
  "base" => "testimonial",
  "icon" => "icon-wpb-testimonial",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Testimonial bubble', 'dp-theme'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Name", 'dp-theme'),
      "param_name" => "name",
      "value" => __("John Smith", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Position", 'dp-theme'),
      "param_name" => "position",
      "value" => __("CEO", 'dp-theme')
    ),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "heading" => __("Testimonial content", 'dp-theme'),
      "param_name" => "content",
      "value" => __("Client testimonial content.", 'dp-theme')
    ),
	$add_dp_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  )
) );

/* Testimonial2
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Testimonial 2", 'dp-theme'),
  "base" => "testimonial2",
  "icon" => "icon-wpb-testimonial",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Testimonial bubble with client image', 'dp-theme'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Name", 'dp-theme'),
      "param_name" => "name",
      "value" => __("John Smith", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Position", 'dp-theme'),
      "param_name" => "position",
      "value" => __("CEO", 'dp-theme')
    ),
	 array(
      "type" => "attach_image",
      "heading" => __("Image", 'dp-theme'),
      "param_name" => "img",
      "value" => "",
      "description" => __("Select image from media library.", 'dp-theme')
    ),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "heading" => __("Testimonial content", 'dp-theme'),
      "param_name" => "content",
      "value" => __("Client testimonial content.", 'dp-theme')
    ),
	$add_dp_animation,	
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  )
) );
/* Servicebox
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Service Box", 'dp-theme'),
  "base" => "servicebox",
  "icon" => "icon-wpb-servicebox",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Flipping box with icon or image', 'dp-theme'),
  "params" => array(
     array(
	  "type" => "dropdown",
	  "class" => "",
	  "heading" => __("Type", 'dp-theme'),
	  'save_always' => true,
	  "param_name" => "type",
			"value" => array(
				"Icon" => "icon",	
				"Image" => "image"
			),
	  "description" => ""
	),
    array(
      "type" => "textfield",
      "heading" => __("Title", 'dp-theme'),
      "param_name" => "title",
      "value" => __("Service Box", 'dp-theme')
    ),
	array(
	"type" => "icon_selector",
	"class" => "",
	"heading" => __("Icon", 'dp-theme'),
	"param_name" => "icon",
	"description" => "",
	"dependency" => Array('element' => "type", 'value' => array('icon'))
	),	 
	array(
      "type" => "attach_image",
      "heading" => __("Image", 'dp-theme'),
      "param_name" => "img",
      "value" => "",
      "description" => __("Select image from media library.", 'dp-theme'),
	  "dependency" => Array('element' => "type", 'value' => array('image'))
    ),
	array(
	"type" => "colorpicker",
	"heading" => __("Icon color", 'dp-theme'),
	"param_name" => "icon_color",
	"value" => "",
	"description" => __("Select icon custom color", 'dp-theme'),
	"dependency" => Array('element' => "type", 'value' => array('icon'))
	),
	array(
	"type" => "colorpicker",
	"class" => "",
	"heading" => __("Back background color", 'dp-theme'),
	"param_name" => "back_bgcolor",
	"value" => "",
	"description" => __("Select custom background color for back of service box", 'dp-theme')
	),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "heading" => __("Content", 'dp-theme'),
      "param_name" => "content",
      "value" => __("Service box back content.", 'dp-theme')
    ),
	$add_dp_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  )
) );

/* Featured box
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Featured Box", 'dp-theme'),
  "base" => "featuredbox",
  "icon" => "icon-wpb-servicebox",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Box with animated icons', 'dp-theme'),
  "params" => array(
     array(
	  "type" => "dropdown",
	  "class" => "",
	  "heading" => __("Type", 'dp-theme'),
	  'save_always' => true,
	  "param_name" => "type",
			"value" => array(
				"Centerd box" => "centered",	
				"Icon left" => "left"
			),
	  "description" => ""
	),
	array(
	  "type" => "dropdown",
	  "class" => "",
	  "heading" => __("Icon badge", 'dp-theme'),
	  'save_always' => true,
	  "param_name" => "icon_badge",
			"value" => array(
				"No" => "",	
				"Square" => "square",
				"Circle" => "circle",
				"Circle bordered (only for left aligned icons)" => "circle-bordered",
				"Square bordered (only for left aligned icons)" => "square-bordered"
			),
      "description" => __("Select icon badge type.", 'dp-theme'),
	),

    array(
      "type" => "textfield",
      "heading" => __("Title", 'dp-theme'),
      "param_name" => "title",
      "value" => __("Service Box", 'dp-theme')
    ),
	array(
	"type" => "icon_selector",
	"class" => "",
	"heading" => __("Icon", 'dp-theme'),
	"param_name" => "icon",
	"description" => ""
	),	 
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "heading" => __("Content", 'dp-theme'),
      "param_name" => "content",
      "value" => __("Featured box content.", 'dp-theme')
    ),
	array(
      "type" => "textfield",
      "heading" => __("Button link", 'dp-theme'),
      "param_name" => "button_link",
      "description" => __("If you leave this foeld blank 'Read more' button will be not dispaled", 'dp-theme')
    ),
	array(
      "type" => "textfield",
      "heading" => __("Button text", 'dp-theme'),
      "param_name" => "button_text"
    ),
	$add_dp_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  )
) );

/* Alert box
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Notification Box", 'dp-theme'),
  "base" => "box",
  "icon" => "icon-wpb-alertbox",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Box with notifications', 'dp-theme'),
  "params" => array(
     array(
	  "type" => "dropdown",
	  "class" => "",
	  "heading" => __("Type", 'dp-theme'),
	  'save_always' => true,
	  "param_name" => "type",
			"value" => array(
				"Success" => "success",	
				"Warning" => "warning",
				"Error" => "error",
				"Notice" => "notice"
			),
	  "description" =>  __('Select box style', 'dp-theme')
	),

    array(
      "type" => "textfield",
      "heading" => __("Title", 'dp-theme'),
      "param_name" => "title",
      "value" => __("Success!", 'dp-theme')
    ),
	array(
	"type" => "icon_selector",
	"class" => "",
	"heading" => __("Icon", 'dp-theme'),
	"param_name" => "icon",
	"description" =>  __('Select icon to display before message', 'dp-theme')
	),	 
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "heading" => __("Content", 'dp-theme'),
      "param_name" => "content",
      "value" => __("Your message comes here.", 'dp-theme')
    ),
     array(
	  "type" => "dropdown",
	  "class" => "",
	  "heading" => __("Sticky?", 'dp-theme'),
	  'save_always' => true,
	  "param_name" => "sticky",
			"value" => array(
				"No" => "no",	
				"Yes" => "yes"
			),
	  "description" =>  __('If selected yes box will be closeable', 'dp-theme')
	),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", 'dp-theme'),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
    )
  )
) );
/* DP Gallery */
vc_map( array(
    "name" => __("DP Gallery", 'dp-theme'),
    "base" => "photo_gallery",
	"icon" => "icon-wpb-dp-gallery",
    "as_parent" => array('only' => 'image'), 
    "content_element" => true,
    "show_settings_on_create" => false,
	"description" =>  __('Simple image grid gallery with lightbox', 'dp-theme'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'dp-theme'),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
        )
    ),
    "js_view" => 'VcColumnView'
) );
vc_map( array(
    "name" => __("DP Gallery Image", 'dp-theme'),
    "base" => "image",
	"icon" => "icon-wpb-image",
    "content_element" => true,
  	"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
	"description" =>  __('Single image for DP Gallery', 'dp-theme'),
    "as_child" => array('only' => 'photo_gallery'), // Use only|except attributes to limit parent (separate multiple values with comma)
    "params" => array(
		array(
			"type" => "attach_image",
			"class" => "",
			"heading" => __("Image", 'dp-theme'),
			"value" => "",
			"param_name" => "content",
			"description" => ""
		),
        array(
		  "type" => "textfield",
		  "heading" => __("Title", 'dp-theme'),
		  "param_name" => "title",
		),
		array(
		  "type" => "textfield",
		  "heading" => __("Thumbnail width", 'dp-theme'),
		  "param_name" => "twidth",
		  "description" =>  __("If you wish to set custom width for thumbnail enter width in px.", 'dp-theme')
		),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'dp-theme'),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
        )
    )
) );

class WPBakeryShortCode_Photo_Gallery extends WPBakeryShortCodesContainer {
}
class WPBakeryShortCode_Image extends WPBakeryShortCode {
}

/* DP Social Links */
vc_map( array(
    "name" => __("DP Social Links", 'dp-theme'),
    "base" => "social_links",
	"icon" => "icon-wpb-social-icons",
    "as_parent" => array('only' => 'social_link'),
  	"category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
    "content_element" => true,
    "show_settings_on_create" => false,
	"description" =>  __('Social icons block', 'dp-theme'),
    "params" => array(
		array(
			"type" => "dropdown",
			"heading" => __("Badge type", 'dp-theme'),
			"param_name" => "type",
			'save_always' => true,
			"value" => array(
					"Square" => "",
					"Rounded" => "rounded"	
				)
		),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'dp-theme'),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
        )
    ),
    "js_view" => 'VcColumnView'
) );
vc_map( array(
    "name" => __("DP Social Icon", 'dp-theme'),
    "base" => "social_link",
	"icon" => "icon-wpb-icon",
    "content_element" => true,
	"description" =>  __('Single social icon', 'dp-theme'),
    "as_child" => array('only' => 'social_links'), 
    "params" => array(		
		array(
			"type" => "dropdown",
			"heading" => __("Icon type", 'dp-theme'),
			"param_name" => "type",
			'save_always' => true,
			"value" => array(
					"Facebook" => "facebook",
					"Twitter" => "twitter",
					"Linkedin" => "linkedin",		
					"Google Plus" => "gplus",		
					"Spotify" => "spotify",		
					"Yahoo" => "yahoo",		
					"Amazon" => "amazon",		
					"Appstore" => "appstore",		
					"Paypal" => "paypal",		
					"Blogger" => "blogger",		
					"Evernote" => "evernote",		
					"Instagram" => "instagram",		
					"Pinterest" => "pinterest",		
					"Dribbble" => "dribbble",		
					"Flickr" => "flickr",		
					"Youtube" => "youtube",		
					"Vimeo" => "vimeo",		
					"RSS" => "rss",		
					"Steam" => "steam",		
					"Tumblr" => "tumblr",		
					"Github" => "github",		
					"Delicious" => "delicious",		
					"Reddit" => "reddit",		
					"Lastfm" => "lastfm",		
					"Digg" => "digg",		
					"Forrst" => "forrst",		
					"Stumbleupon" => "stumbleupon",		
					"Wordpress" => "wordpress",		
					"Xing" => "xing",		
					"Dropbox" => "dropbox",		
					"Fivehundredpx" => "fivehundredpx",
					"Viadeo" => "viadeo"		
				)
		),
        array(
		  "type" => "textfield",
		  "heading" => __("Title", 'dp-theme'),
		  "param_name" => "title",
		  "description" =>  __("If you wish add title of link displayed in tooltip type it here", 'dp-theme')
		),
        array(
		  "type" => "textfield",
		  "heading" => __("Icon link", 'dp-theme'),
		  "param_name" => "link"
		)
    )
) );

class WPBakeryShortCode_Social_Links extends WPBakeryShortCodesContainer {
}
class WPBakeryShortCode_Social_Link extends WPBakeryShortCode {
}

/* Lightbox image link */
vc_map( array(
  "name" => __("Lightbox thumb", 'dp-theme'),
  "base" => "lightbox",
  "icon" => "icon-wpb-single-image",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Simple image as lightbox link', 'dp-theme'),
  "params" => array(
    array(
      "type" => "attach_image",
      "heading" => __("Thumb image", 'dp-theme'),
      "param_name" => "thumb",
      "value" => "",
      "description" => __("Select image from media library.", 'dp-theme')
    ),
      array(
        "type" => "dropdown",
        "heading" => __("Overlay icon type", 'dp-theme'),
        "param_name" => "hover_icon",
		'save_always' => true,
        "admin_label" => true,
			"value" => array(
					"Zoom" => "zoom",
					"Play" => "play",
					"File" => "file"		
	),
        "description" => __("Select icon to display in thumb fancy overlay.", 'dp-theme')
      ),
    array(
      "type" => "attach_image",
      "heading" => __("Big image", 'dp-theme'),
      "param_name" => "bigimage",
      "value" => "",
      "description" => __("Select image from media library.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("URL (Link to lightbox content)", 'dp-theme'),
      "param_name" => "link",
      "description" => __("Link to content to display in lightbox.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Title", 'dp-theme'),
      "param_name" => "title",
      "description" => __("Title of image to display in lightbox window", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Description", 'dp-theme'),
      "param_name" => "desc",
      "description" => __("Description of image to display in lightbox window", 'dp-theme')
    ),
	$add_dp_animation
  )
));

/* OWL Carousel */
vc_map( array(
  "name" => __("OWL Carousel", 'dp-theme'),
  "base" => "owl_carousel",
  "icon" => "icon-wpb-owl",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('OWL Carousel using slide custom post type', 'dp-theme'),
  "params" => array(
  $add_dp_slideshow,
    array(
      "type" => "textfield",
      "heading" => __("Items", 'dp-theme'),
      "param_name" => "items",
      "description" => __("Items to display on normal screen width > 1200px", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Items on desktop", 'dp-theme'),
      "param_name" => "itemsdesktop",
      "description" => __("Items to display on desktop screen width < 1200px.If you leave this field blank will be used setting from field above.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Items on small desktop", 'dp-theme'),
      "param_name" => "itemsdesktopsmall",
      "description" => __("Items to display on desktop screen width < 980px. If you leave this field blank will be used setting from first not empty field above.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Items on tablet", 'dp-theme'),
      "param_name" => "itemstablet",
      "description" => __("Items to display on tablet screen width < 768px. If you leave this field blank will be used setting from first not empty field above.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Items on mobile devices", 'dp-theme'),
      "param_name" => "itemsmobile",
      "description" => __("Items to display on mobile devices width < 479px. If you leave this field blank will be used setting from first not empty field above.", 'dp-theme')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Autoplay", 'dp-theme'),
      "param_name" => "autoplay",
      "description" => __("Set autoplay speed in ms. If you leave blank autoplay will be disabled.", 'dp-theme')
    ),
      array(
        "type" => "dropdown",
        "heading" => __("Show navigation arrows", 'dp-theme'),
        "param_name" => "navigation",
		'save_always' => true,
			"value" => array(
					"No" => "no",
					"Yes" => "yes"		
	)
      ),
      array(
        "type" => "dropdown",
        "heading" => __("Show pagination bullets", 'dp-theme'),
        "param_name" => "pagination",
		'save_always' => true,
			"value" => array(
					"No" => "no",
					"Yes" => "yes"		
	)
      ),

  )
));



// Showbiz plugin //
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('showbiz/showbiz.php')) {
  global $wpdb;
  $sb = $wpdb->get_results( 
  	"
  	SELECT id, title, alias
  	FROM ".$wpdb->prefix."showbiz_sliders
  	ORDER BY id ASC LIMIT 999
  	"
  );
  $sbsliders = array();
  if ($sb) {
    foreach ( $sb as $slider ) {
      $sbsliders[$slider->title] = $slider->alias;
    }
  } else {
    $sbsliders["No sliders found"] = 0;
  }
  vc_map( array(
    "base" => "sb_slider_vc",
    "name" => __("Showbiz Slider", 'dp-theme'),
    "icon" => "icon-wpb-showbiz",
    "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
    "description" => __('Place Showbiz slider', 'dp-theme'),
    "params"=> array(
      array(
        "type" => "textfield",
        "heading" => __("Widget title", 'dp-theme'),
        "param_name" => "title",
        "description" => __("Enter text which will be used as widget title. Leave blank if no title is needed.", 'dp-theme')
      ),
      array(
        "type" => "dropdown",
        "heading" => __("Showbiz Slider", 'dp-theme'),
        "param_name" => "alias",
        "admin_label" => true,
		'save_always' => true,
        "value" => $sbsliders,
		'save_always' => true,
        "description" => __("Select your Showbiz Slider.", 'dp-theme')
      ),
      array(
        "type" => "textfield",
        "heading" => __("Extra class name", 'dp-theme'),
        "param_name" => "el_class",
        "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'dp-theme')
      )
    )
  ) );
} // if revslider plugin active
class WPBakeryShortCode_Sb_Slider_Vc extends WPBakeryShortCode {
}
/* Portfolio grid */
vc_map( array(
  "name" => __("Portfolio Grid", 'dp-theme'),
  "base" => "portfolio_grid",
  "icon" => "icon-wpb-portfolio-grid",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Portfolio items grid', 'dp-theme'),
  "params" => array(
   array(
      "type" => "textfield",
      "heading" => __("Items", 'dp-theme'),
      "param_name" => "items",
      "description" => __("How many items should be displayed", 'dp-theme')
    ),
   array(
        "type" => "dropdown",
        "heading" => __("Columns count", 'dp-theme'),
        "param_name" => "columns",
		'save_always' => true,
        "admin_label" => true,
			"value" => array(
					"2" => "2",
					"3" => "3",
					"4" => "4",
					"5" => "5",
					"6" => "6",
					"8" => "8"		
			),
      ),
    array(
      "type" => "textfield",
      "heading" => __("Categories", 'dp-theme'),
      "param_name" => "categories",
      "description" => __("Coma separated list of categories to display. If you leave this field blank all items will be displayed.", 'dp-theme')
    ),
   array(
        "type" => "dropdown",
        "heading" => __("Display category filter", 'dp-theme'),
        "param_name" => "filter",
		'save_always' => true,
        "admin_label" => true,
			"value" => array(
					"No" => "no",
					"Yes" => "yes"	
			),
      ),
   array(
        "type" => "dropdown",
        "heading" => __("Links in overlay", 'dp-theme'),
        "param_name" => "links",
		'save_always' => true,
        "admin_label" => true,
			"value" => array(
					"No links" => "no",
					"Text link" => "text",
					"Icon linking to lightbox image" => "zoom",
					"Icon linking to portfolio item view" => "link",
					"Both icons" => "both",	
			),
      ),
  )
));

/* Blog grid */
vc_map( array(
  "name" => __("Blog Grid", 'dp-theme'),
  "base" => "blog_grid",
  "icon" => "icon-wpb-blog-grid",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Blog items grid', 'dp-theme'),
  "params" => array(
   array(
        "type" => "dropdown",
        "heading" => __("Columns count", 'dp-theme'),
        "param_name" => "columns",
		'save_always' => true,
        "admin_label" => true,
			"value" => array(
					"2" => "2",
					"3" => "3",
					"4" => "4",
					"5" => "5",
					"6" => "6",
					"8" => "8"		
			),
      ),
   array(
      "type" => "textfield",
      "heading" => __("Items per page", 'dp-theme'),
      "param_name" => "perpage"
    ),
    array(
      "type" => "textfield",
      "heading" => __("Categories", 'dp-theme'),
      "param_name" => "categories",
      "description" => __("Coma separated list of categories to display. If you leave this field blank all items will be displayed.", 'dp-theme')
    ),
   array(
        "type" => "dropdown",
        "heading" => __("Display category filter", 'dp-theme'),
        "param_name" => "filter",
		'save_always' => true,
        "admin_label" => true,
			"value" => array(
					"No" => "no",
					"Yes" => "yes"	
			),
      )
  )
));

/* Anchor */
vc_map( array(
  "name" => __("Anchor", 'dp-theme'),
  "base" => "anchor",
  "icon" => "icon-wpb-anchor",
  "category" =>array( __('by Dynamicpress', 'dp-theme'),__('Content', 'dp-theme')),
  "description" => __('Anchor in content', 'dp-theme'),
  "params" => array(
   array(
      "type" => "textfield",  
	  "admin_label" => true,
      "heading" => __("Name", 'dp-theme'),
      "param_name" => "name"
    )
  )
));


?>