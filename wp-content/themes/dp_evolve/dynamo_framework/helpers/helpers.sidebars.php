<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Helper functions
 *
 * Add custom widget areas functionality
 *
 **/
function dp_sidebars_init()
{
	$labels = array(
		'name' => _x('Custom sidebars', 'post type general name', 'dp-theme'),
		'singular_name' => _x('Custom sidebar', 'post type singular name', 'dp-theme'),
		'add_new' => _x('Add New', 'dp_sidebar', 'dp-theme'),
		'add_new_item' => __('Add New Custom Sidebar', 'dp-theme'),
		'edit_item' => __('Edit Custom Sidebar', 'dp-theme'),
		'new_item' => __('New Custom Sidebar', 'dp-theme'),
		'all_items' => __('Custom sidebars', 'dp-theme'),
		'view_item' => __('View Custom Sidebar', 'dp-theme'),
		'search_items' => __('Search Custom Sidebars', 'dp-theme'),
		'not_found' =>  __('No custom sidebars found', 'dp-theme'),
		'not_found_in_trash' => __('No custom sidebars found in Trash', 'dp-theme'), 
		'parent_item_colon' => '',
		'menu_name' => __("Custom sidebars", 'dp-theme')
	);
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",
		"show_in_menu" => "themes.php",
		"hierarchical" => false,  
		"rewrite" => true,  
		"supports" => array("title")
	);
	register_post_type("dp_sidebars", $args);
}  
add_action("init", "dp_sidebars_init"); 

//Adds a box to the main column on the Sidebars edit screens
function dp_add_sidebars_custom_box() 
{
	global $themename;
	add_meta_box( 
        "sidebars_config",
        __("Description", 'dp-theme'),
        "dp_inner_sidebars_custom_box_main",
        "dp_sidebars",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "dp_add_sidebars_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

function dp_inner_sidebars_custom_box_main($post)
{
	
	//Use nonce for verification
	wp_nonce_field("dp_sidebars_noncename");
	
	//The actual fields for data entry
	$widget_description = esc_attr(get_post_meta($post->ID, "widget_description", true));
	
	echo '<p>Add an optional description, to be displayed when adding widgets to this widget area.</p>
	<p>
	<textarea rows="2" cols="40" id="widget_description" name="widget_description" tabindex="6">' . ($widget_description!='' ? ($widget_description!='empty' ? $widget_description : '') : 'Custom widget area') . '</textarea>
	</p>
	';
	
}

//When the post is saved, saves our custom data
function dp_save_sidebars_postdata($post_id) 
{
	//verify if this is an auto save routine. 
	//if it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if (( get_post_type() != 'dp_sidebars' ) || !wp_verify_nonce($_POST['dp_sidebars_noncename'], basename( __FILE__ )))
		return;


	//Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;

	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, "widget_description", ($_POST["widget_description"]=="" ? "empty" : $_POST["widget_description"]));
}
add_action("save_post", "dp_save_sidebars_postdata");

//custom sidebars items list
function dp_sidebars_edit_columns($columns)
{
	$columns = array(  
		"cb" => "<input type=\"checkbox\" />",  
		"title" => _x('Sidebar name', 'post type singular name', 'dp-theme'),
		"order" =>  _x('Order', 'post type singular name', 'dp-theme'),
		"sidebars_hidden" => __('Hidden', 'dp-theme'),
		"date" => __('Date', 'dp-theme')
	);    

	return $columns;  
}  
add_filter("manage_edit-dp_sidebars_columns", "dp_sidebars_edit_columns");


//register sidebars
function dp_register_custom_sidebars() {
$sidebars = get_posts( array( 'post_type' => 'dp_sidebars', 'posts_per_page' => '-1', 'suppress_filters' => 'false' ) );

		if ( count( $sidebars ) > 0 ) {
			foreach ( $sidebars as $k => $v ) {
				$sidebar_id = $v->post_name;
				// $sidebar_id = $this->prefix . $v->ID;
				register_sidebar( array( 'name' => $v->post_title, 'id' => $sidebar_id, 'description' => $v->post_excerpt ) );
			}
		}
}
add_action( 'widgets_init', 'dp_register_custom_sidebars',11 );
?>