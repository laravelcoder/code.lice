<?php



global $shortname;


# Register custom post type


$labels = array(
	'name' => _x('Portfolio', 'post type general name', 'dp-theme'),
    'singular_name' => _x('Portfolio', 'post type singular name', 'dp-theme'),
    'add_new' => _x('Add New', 'portfolio', 'dp-theme'),
    'add_new_item' => __('Add New Portfolio Item', 'dp-theme'),
    'edit_item' => __('Edit Portfolio Item', 'dp-theme'),
    'new_item' => __('New Portfolio Item', 'dp-theme'),
    'view_item' => __('Preview Portfolio Item', 'dp-theme'),
    'search_items' => __('Search Portfolio', 'dp-theme'),
    'not_found' => __('No portfolio found', 'dp-theme'),
    'not_found_in_trash' => __('No portfolio items found in Trash.', 'dp-theme'),
	'parent_item_colon' => '',
    'menu_name' => 'Portfolio'
);

register_post_type('portfolio', array(
    'label' => __('Portfolio', 'dp-theme'),
    'labels' => $labels,
    'singular_label' => __('Portfolio', 'dp-theme'),
    'public' => true,
    'show_ui' => true, 
	'show_in_menu' => true,
	'menu_position' => null, 
    '_builtin' => false, 
    'exclude_from_search' => false, 
    'capability_type' => 'page',
    'hierarchical' => false,
	'rewrite' => array("slug" => "portfolio"), 
    'query_var' => "portfolio", 
    'supports' => array('title', 'thumbnail', 'page-attributes', 'editor', 'comments'),
    'menu_icon' => ''
));




##############################################################
# Register associated taxonomy
##############################################################


function register_portfolio_taxonomy($args_portfolio) {
$labels_portfolio = array(
    'name' => __('Portfolio Categories', 'post type general name'),
    'all_items' => __('All Categories', 'all items'),
    'add_new_item' => __('Add New Category', 'adding a new item'),
    'new_item_name' => __('New Category Name', 'adding a new item'),
);

$args_portfolio = array(
    'labels' => $labels_portfolio,
    'hierarchical' => true
);
	
register_taxonomy( 'portfolios', 'portfolio', $args_portfolio );
}
add_action("init", "register_portfolio_taxonomy",1);

##############################################################
# Customize Manage Posts interface
##############################################################

function edit_columns_portfolio($columns) {
    
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __("Portfolio Item Title", 'dp-theme'),
		"date" => __("Date", 'dp-theme'),
        "pcategories" =>__("Categories", 'dp-theme'),
        "portfolio_image" => __("Image", 'dp-theme')
    );

    return $columns;
}

function custom_columns_portfolio($column) {
    global $post;
   switch ($column) {

        case "portfolio_image":
		if (has_post_thumbnail()) { $imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
            <img src="<?php echo $imageurl ?>" height="120"  />
			<?php }
			break;
        case "pcategories":

            $pcategories = get_the_terms(0, "portfolios");
            $pcategories_html = array();
            if($pcategories) {
                foreach ($pcategories as $pcategory)
                    array_push($pcategories_html, $pcategory->name);

                echo implode($pcategories_html, ", ");
            }
            break;
    }
}

add_filter("manage_edit-portfolio_columns", "edit_columns_portfolio");
add_action("manage_posts_custom_column", "custom_columns_portfolio");



?>