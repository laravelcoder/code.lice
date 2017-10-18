<?php



global $shortname;


# Register custom post type


$labels = array(
	'name' => _x('Slides', 'post type general name', 'dp-theme'),
    'singular_name' => _x('Slide', 'post type singular name', 'dp-theme'),
    'add_new' => _x('Add New', 'slide', 'dp-theme'),
    'add_new_item' => __('Add New Slide', 'dp-theme'),
    'edit_item' => __('Edit Slide', 'dp-theme'),
    'new_item' => __('New Slide', 'dp-theme'),
    'view_item' => __('Preview Slide', 'dp-theme'),
    'search_items' => __('Search Slides', 'dp-theme'),
    'not_found' => __('No slides found.', 'dp-theme'),
    'not_found_in_trash' => __('No slides found in Trash.', 'dp-theme'),
	'parent_item_colon' => '',
    'menu_name' => 'Slides'
);

register_post_type('slide', array(
    'label' => __('Slides', 'dp-theme'),
    'labels' => $labels,
    'singular_label' => __('Slide', 'dp-theme'),
    'public' => true,
    'show_ui' => true, 
	'show_in_menu' => true,
	'menu_position' => null, 
    '_builtin' => false, 
    'exclude_from_search' => true, 
    'capability_type' => 'page',
    'hierarchical' => true,
	'rewrite' => array("slug" => "slide"), 
    'query_var' => "slide", 
     'supports' => array('title', 'thumbnail', 'page-attributes', 'editor'),
    'menu_icon' => ''
));




##############################################################
# Register associated taxonomy
##############################################################




function register_slide_taxonomy() {
	$labels_slideshow = array(
    'name' => __('Slideshows', 'post type general name'),
    'all_items' => __('All Slideshows', 'all items'),
    'add_new_item' => __('Add New Slideshow', 'adding a new item'),
    'new_item_name' => __('New Slideshow Name', 'adding a new item'),
);
$args_slideshow = array(
    'labels' => $labels_slideshow,
    'hierarchical' => true
);
register_taxonomy( 'slideshows', 'slide', $args_slideshow );
}
add_action("init", "register_slide_taxonomy",1);

##############################################################
# Customize Manage Posts interface
##############################################################

function edit_columns_slide($columns) {
    
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __("Slide Title", 'dp-theme'),
        "slideshows" =>__("Slideshow", 'dp-theme'),
        "slide_type" => __("Slide Type", 'dp-theme'),
        "slide_image" => __("Image", 'dp-theme')
    );

    return $columns;
}

function custom_columns_slide($column) {
    global $post;
   $type = get_post_meta(get_the_ID(),'slide_type',TRUE);
   switch ($column) {

        case "slide_image":
            if ( $type == "i") {if (has_post_thumbnail()) { $imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
            <img src="<?php echo $imageurl ?>" height="120"  />
			<?php } else  __("Oops! This is an image slide, but you forgot a featured image.", 'dp-theme');}
			if ( $type == "c") {echo '<img src="'. get_template_directory_uri().'/images/admin/html.png" width="48" height="48" />';} 
            break;

        case "slideshows":

            $slideshows = get_the_terms(0, "slideshows");
            $slideshows_html = array();
            if($slideshows) {
                foreach ($slideshows as $slideshow)
                    array_push($slideshows_html, $slideshow->name);

                echo implode($slideshows_html, ", ");
            }
            break;

        case "slide_type":
		if ($type == "i") { __("Image Slide", 'dp-theme');} 
		if ($type == "c") { __("Content Slide", 'dp-theme');}
            break;

    }
}

add_filter("manage_edit-slide_columns", "edit_columns_slide");
add_action("manage_pages_custom_column", "custom_columns_slide");




?>