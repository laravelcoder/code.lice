<?php



global $shortname;


# Register custom post type


$labels = array(
	'name' => _x('Custom Sidebars', 'post type general name', 'dp-theme'),
    'singular_name' => _x('Sidebar', 'post type singular name', 'dp-theme'),
    'add_new' => _x('Add New', 'sidebar', 'dp-theme'),
    'add_new_item' => __('Add New Sidebar', 'dp-theme'),
    'edit_item' => __('Edit Sidebar', 'dp-theme'),
    'new_item' => __('New Sidebar', 'dp-theme'),
    'view_item' => __('Preview Sidebar', 'dp-theme'),
    'search_items' => __('Search Sidebars', 'dp-theme'),
    'not_found' => __('No sidebars found.', 'dp-theme'),
    'not_found_in_trash' => __('No sidebars found in Trash.', 'dp-theme'),
	'parent_item_colon' => '',
    'menu_name' => 'Custom Sidebars'
);

register_post_type('sidebar', array(
    'label' => __('Custom Sidebars', 'dp-theme'),
    'labels' => $labels,
    'singular_label' => __('Sidebar', 'dp-theme'),
    'public' => true,
    'show_ui' => true, 
	'show_in_menu' => 'themes.php',
	'menu_position' => null, 
    '_builtin' => false, 
    'exclude_from_search' => true, 
    'capability_type' => 'page',
	'rewrite' => array("slug" => ""), 
    'query_var' => "sidebar", 
     'supports' => array('title'),
    'menu_icon' => ''
));




##############################################################
# Customize Manage Posts interface
##############################################################

function edit_columns_sidebar($columns) {
    
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __("Sidebar name", 'dp-theme'),
    );

    return $columns;
}


add_filter("manage_edit-sidebar_columns", "edit_columns_sidebar");

//register sidebars
function dp_register_custom_sidebars() {
$sidebars = get_posts( array( 'post_type' => 'sidebar', 'posts_per_page' => '-1', 'suppress_filters' => 'false' ) );

		if ( count( $sidebars ) > 0 ) {
			foreach ( $sidebars as $k => $v ) {
				$sidebar_id = $v->post_name;
				$sidebar_description = get_post_meta($v->ID,'sidebar_description',TRUE);
				register_sidebar( array( 'name' => $v->post_title, 'id' => $sidebar_id, 'description' => $sidebar_description ) );
			}
		}
}
add_action( 'widgets_init', 'dp_register_custom_sidebars',11 );



?>