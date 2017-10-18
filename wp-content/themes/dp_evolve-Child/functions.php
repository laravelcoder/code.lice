<?php
function dp_child_theme_stylesheet()
{
    wp_enqueue_style('dp_child_css', get_stylesheet_uri(), false, null);
}

add_action('wp_enqueue_scripts', 'dp_child_theme_stylesheet', 110);



function font_styles()
{
    ?>
    <style type="text/css">
        body, html {
            font-family: "Global-light", Arial, sans-serif
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: "Global-light", Arial, sans-serif
        }

    </style>
    <?php
}

add_action('wp_head', 'font_styles');


function clinic_near_you_func($atts, $content = null)

{
    $a = shortcode_atts(array('url' => 'clinics-near-you'), $atts);
    $url = $a['url'];
    $value = '';
    if (isset($_GET['docname'])) {
        $value = $_GET['docname'];
    }
    $select = '';
    $s1 = '';
    $s2 = '';
    $s3 = '';
    $s4 = '';
    $s5 = '';
    $s6 = 'selected="selected"';
    $s7 = '';
    $s8 = '';
    if (isset($_GET['cws-stafftreatments'])) {

        $select = $_GET['cws-stafftreatments'];

        if ($select == 1) {
            $s1 = 'selected="selected"';
            $s6 = '';
        }
        if ($select == 5) {
            $s2 = 'selected="selected"';
            $s6 = '';
        }
        if ($select == 10) {
            $s3 = 'selected="selected"';
            $s6 = '';
        }
        if ($select == 25) {
            $s4 = 'selected="selected"';
            $s6 = '';
        }
        if ($select == 50) {
            $s5 = 'selected="selected"';
            $s6 = '';
        }
        if ($select == 100) {
            $s6 = 'selected="selected"';
        }
        if ($select == 200) {
            $s7 = 'selected="selected"';
            $s6 = '';
        }
        if ($select == 500) {
            $s8 = 'selected="selected"';
            $s6 = '';
        }
    }

    $clinic_near_you = '<div id="locator-finder" class="the-container">
            <div class="vc_col-sm-5">
            <h2>Enter Zip Code or City:</h2>
        </div>
        <div class="cws-widget-content doc_search vc_col-sm-7">
            <section id="search-shortcode" class="find_a_doctor">
                <form id="quick-search" class="doctors-search-form" action="/' . $url . '/" method="get">
                    <div class="search_field by_name">
                        <input class="addressSearch" id="addressSearch" name="docname" type="text" placeholder="" value="' . $value . '" />
                    </div>
                    <div class="submit_field">
                        <button id="fcbutton" type="submit" class="show-locator">Search</button>
                    </div>
                </form>
            </section>
            <div class="doc_searchxx"></div>
        </div>
    </div>
    <div class="clearfix"></div>';

    $search = '<div class="search_field by_treatment">
        <select name="cws-stafftreatments">
            <option disabled="disabled" value="">search radius</option>
            <option value="1" ' . $s1 . '>1 mi</option>
	        <option value="5" ' . $s2 . '>5 mi</option>
	        <option value="10" ' . $s3 . '>10 mi</option>
	        <option value="25" ' . $s4 . '>25 mi</option>
	        <option value="50" ' . $s5 . '>50 mi</option>
	        <option value="100" ' . $s6 . '>100 mi</option>
	        <option value="200" ' . $s7 . '>200 mi</option>
	        <option value="500" ' . $s8 . '>500 mi</option>
        </select>
    </div>';


    return $clinic_near_you;
}

add_shortcode('clinic-near-you', 'clinic_near_you_func');


function products_near_you_func($atts, $content = null)

{
    $a = shortcode_atts(array('url' => 'products-near-you', 'placeholder' => 'enter your zip code or city', 'search-text' => 'Search'), $atts);
    $url = $a['url'];
    $placeholder = $a['placeholder'];
    $search_text = $a['search-text'];
    if ($content == null) {
        $content = "Where To Buy:";
    }
    $value = '';

    if (isset($_GET['docname'])) {
        $value = $_GET['docname'];
    }


    $products_near_you = '<div class="products-near-you">' . '<label for="productSearch">' . $content . '</label>' . '<input id="productSearch" onkeypress="handleKeyPress(event)" name="docname" type="text" placeholder="' . $placeholder . '" value="' . $value . '" />' . '<a id="searchForProduct" onclick="checkInput()">' . $search_text . '</a>' . '</div>' . '<script>' . 'function handleKeyPress(e){' . 'var key=e.keyCode || e.which;' . 'if (key==13){' . 'checkInput();' . '}' . '};' . 'function checkInput() {' . 'var input = document.getElementById("productSearch");' . 'if(input.value === ""){' . 'alert("Enter a zip code or city to search");' . '}else{' . 'var url = "/' . $url . '?docname=" + input.value;' . 'window.location = url;' . '}' . '};' . '</script>';


    return $products_near_you;
}

add_shortcode('products-near-you', 'products_near_you_func');

function get_sl_location($locationId)
{
    //$wpdb used in the get_sl_location() function
    global $wpdb;
    $results = $wpdb->get_results("SELECT sl_store as store, sl_address as address, sl_email as email, sl_youtube_phone_number as phone, sl_description as description FROM wp_store_locator WHERE sl_id =  " . urlencode($locationId), OBJECT);

    return $results[0];
}

function get_booking_image()
{
    $defaultImages = array(get_stylesheet_directory_uri() . '/img/booking-imgs/Image1.jpg', get_stylesheet_directory_uri() . '/img/booking-imgs/Image2.jpg', get_stylesheet_directory_uri() . '/img/booking-imgs/Image3.jpg', get_stylesheet_directory_uri() . '/img/booking-imgs/Image4.jpg', get_stylesheet_directory_uri() . '/img/booking-imgs/Image5.jpg',);
    shuffle($defaultImages);

    return $defaultImages[0];
}

//clinic pages custom code

// Register Custom Post Type
function custom_post_type()
{

    $labels = array('name' => _x('Clinic Pages', 'Post Type General Name', 'text_domain'), 'singular_name' => _x('Landing Page', 'Post Type Singular Name', 'text_domain'), 'menu_name' => __('Clinic Pages', 'text_domain'), 'name_admin_bar' => __('Clinic Page', 'text_domain'), 'archives' => __('Item Archives', 'text_domain'), 'parent_item_colon' => __('Parent Item:', 'text_domain'), 'all_items' => __('All Items', 'text_domain'), 'add_new_item' => __('Add New Item', 'text_domain'), 'add_new' => __('Add New', 'text_domain'), 'new_item' => __('New Item', 'text_domain'), 'edit_item' => __('Edit Item', 'text_domain'), 'update_item' => __('Update Item', 'text_domain'), 'view_item' => __('View Item', 'text_domain'), 'search_items' => __('Search Item', 'text_domain'), 'not_found' => __('Not found', 'text_domain'), 'not_found_in_trash' => __('Not found in Trash', 'text_domain'), 'featured_image' => __('Featured Image', 'text_domain'), 'set_featured_image' => __('Set featured image', 'text_domain'), 'remove_featured_image' => __('Remove featured image', 'text_domain'), 'use_featured_image' => __('Use as featured image', 'text_domain'), 'insert_into_item' => __('Insert into item', 'text_domain'), 'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'), 'items_list' => __('Items list', 'text_domain'), 'items_list_navigation' => __('Items list navigation', 'text_domain'), 'filter_items_list' => __('Filter items list', 'text_domain'),);
    $args = array('label' => __('Landing Page', 'text_domain'), 'description' => __('displays the clinic landing pages', 'text_domain'), 'menu_icon' => 'dashicons-layout', 'labels' => $labels, 'supports' => array(), 'taxonomies' => array('category', 'post_tag', ' services'), 'hierarchical' => false, 'public' => true, 'show_ui' => true, 'show_in_menu' => true, 'menu_position' => 5, 'show_in_admin_bar' => true, 'show_in_nav_menus' => true, 'can_export' => true, 'has_archive' => true, 'exclude_from_search' => false, 'publicly_queryable' => true, 'capability_type' => 'page', 'rewrite' => array('slug' => 'landing'),);
    register_post_type('landing_page', $args);
}

add_action('init', 'custom_post_type', 0);

// Register Custom Taxonomy
function custom_taxonomy()
{

    $labels = array('name' => _x('services', 'taxonomy general name', 'text_domain'), 'singular_name' => _x('service', 'taxonomy singular name', 'text_domain'), 'menu_name' => __('Services', 'text_domain'), 'all_items' => __('All Items', 'text_domain'), 'parent_item' => __('Parent Item', 'text_domain'), 'parent_item_colon' => __('Parent Item:', 'text_domain'), 'new_item_name' => __('New Item Name', 'text_domain'), 'add_new_item' => __('Add New Service', 'text_domain'), 'edit_item' => __('Edit Item', 'text_domain'), 'update_item' => __('Update Item', 'text_domain'), 'view_item' => __('View Item', 'text_domain'), 'separate_items_with_commas' => __('Separate items with commas', 'text_domain'), 'add_or_remove_items' => __('Add or remove items', 'text_domain'), 'choose_from_most_used' => __('Choose from the most used', 'text_domain'), 'popular_items' => __('Popular Items', 'text_domain'), 'search_items' => __('Search Items', 'text_domain'), 'not_found' => __('Not Found', 'text_domain'), 'no_terms' => __('No items', 'text_domain'), 'items_list' => __('Items list', 'text_domain'), 'items_list_navigation' => __('Items list navigation', 'text_domain'),);
    $args = array('labels' => $labels, 'hierarchical' => true, 'public' => true, 'show_ui' => true, 'show_admin_column' => true, 'show_in_nav_menus' => true, 'show_tagcloud' => true,);
    register_taxonomy('services', array('landing_page'), $args);
}

add_action('init', 'custom_taxonomy', 0);

function add_custom_rewrite_rule()
{

    // First, try to load up the rewrite rules. We do this just in case
    // the default permalink structure is being used.
    if (($current_rules = get_option('rewrite_rules'))) {

        // Next, iterate through each custom rule adding a new rule
        // that replaces 'movies' with 'films' and give it a higher
        // priority than the existing rule.
        foreach ($current_rules as $key => $val) {
            if (strpos($key, 'landing_page') !== false) {
                add_rewrite_rule(str_ireplace('landing_page', 'landing', $key), $val, 'top');
            } // end if
        } // end foreach
    } // end if/else
    // ...and we flush the rules
    flush_rewrite_rules();
}

// end add_custom_rewrite_rule
add_action('init', 'add_custom_rewrite_rule');

// acf options page

if (function_exists('acf_add_options_page')) {

    acf_add_options_page('Default Landing Page Content');
}

// shortcode for display name for landing pages
// [display-name]
function dname_func($atts)
{

    return get_field('display_name', false, false);
}

add_shortcode('display-name', 'dname_func');

// [guarantee]
function guarantee_func($atts)
{

    return get_field('guarantee_time', false, false);
}

add_shortcode('guarantee', 'guarantee_func');

// [social]
function social_func($atts)
{

    echo '<div class="social-links">
    <h3 class="box-title">Connect With Us</h3>
<a class="fb" href="http://facebook.com/lcacorp" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
<a class="pin" href="https://www.pinterest.com/liceclinics/" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
<div class="clearfix"></div>
<a class="you" href="https://www.youtube.com/user/liceclinicsofamerica" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a>
<a class="vim" href="https://vimeo.com/liceclinics" target="_blank"><i class="fa fa-vimeo" aria-hidden="true"></i></a>
<div class="clearfix"></div>
<a class="tw" href="https://twitter.com/licenomore" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
<a class="li" href="https://www.linkedin.com/company/larada-sciences-inc" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
<div class="clearfix"></div>
<a class="gp" href="https://plus.google.com/+Liceclinicsofamerica/" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
<a class="rss" href="http://liceclinicsofamerica.com/feed" target="_blank"><i class="fa fa-rss" aria-hidden="true"></i></a>
</div>';
}

add_shortcode('social', 'social_func');

// [get-the-facts]
function get_the($atts)
{

    echo '<div class="greyjoy block" style="height: 23px;"><h3>GET THE FACTS!</h3></div>
<img class="size-full wp-image-6429" align="left" src="/wp-content/uploads/2016/06/graph.png" alt="graph" width="176" height="177" />

<p>See a comparison of lice treatment solutions that have been tested.</p>
<div class="clearfix"></div>
<p style="margin-top: 35px;"><a href="/wp-content/uploads/2016/07/Treatment-Chart.pdf" class="btn dark-grey" target="_blank">VIEW THE CHART</a></p>';
}

add_shortcode('get-the-facts', 'get_the');

include_once('posts_shortcode.php');
add_shortcode('posts_boxes', 'make_post_boxes');
wp_enqueue_script('match-height', get_stylesheet_directory_uri() . '/js/jquery.matchHeight-min.js', array('jquery'), null, true);

function register_special_stylesheet()
{
    wp_deregister_script('dp-js');
    wp_enqueue_script('dp-js-child-appear', get_stylesheet_directory_uri() . '/js/appear.js');
    wp_enqueue_script('dp-js-child', get_stylesheet_directory_uri() . '/js/dp.scripts.js');
    wp_enqueue_style('custom-changes', get_stylesheet_directory_uri() . '/css/ust-changes.css');

    wp_enqueue_style('david-changes', get_stylesheet_directory_uri() . '/css/david.css');

    if (is_page('book-appointment')) {
        wp_enqueue_style('booking-changes', get_stylesheet_directory_uri() . '/css/booking-page.css');
        wp_enqueue_style('weather-icons', get_stylesheet_directory_uri() . '/css/weather-icons.css');
        wp_enqueue_script('booking-script', get_stylesheet_directory_uri() . '/js/booking-script.js', array('jquery'), '1.0.0', true);
    }
}

add_action('wp_enqueue_scripts', 'register_special_stylesheet', 111);


function set_the_terms_in_order($terms, $id, $taxonomy)
{

    $terms = wp_cache_get($id, "{$taxonomy}_relationships_sorted");

    if (false === $terms) {

        $terms = wp_get_object_terms($id, $taxonomy, array('orderby' => 'term_order'));

        wp_cache_add($id, $terms, $taxonomy . '_relationships_sorted');
    }
    return $terms;
}

add_filter('get_the_terms', 'set_the_terms_in_order', 10, 4);


function do_the_terms_in_order()
{

    global $wp_taxonomies;  //fixed missing semicolon
    // the following relates to tags, but you can add more lines like this for any taxonomy
    $wp_taxonomies['post_tag']->sort = true;
    $wp_taxonomies['post_tag']->args = array('orderby' => 'term_order');
}

add_action('init', 'do_the_terms_in_order');

add_action('wpcf7_before_send_mail', 'sendMyTxt');
function sendMyTxt($cf7)
{
    $submission = WPCF7_Submission::get_instance();
    $data = $submission->get_posted_data();


    $id = $data['clinic_id'];
    $location = get_sl_location($id);
    $clinic_location = get_sl_location_for_text($id);
    //get phone numbers
    //$location->description;

    if (!empty($location->description)) {

        $date = date("m-d-Y", strtotime($data['requested_date']));

        $msg = $data['customername'] . " has requested an appt on " . $date . " at " . $data['requested_time'] . " for " . $clinic_location . " " . $data['phone'];

        if (stripos($location->description, ";") !== false) {
            $nums = explode(";", $location->description);
            foreach ($nums as $num) {
                wp_mail($num, "Booking Request", $msg, "From: Lice Clinics of America <support@laradasciences.com>\r\n");
            }
        } else {
            wp_mail($location->description, "Booking Request", $msg);
        }

    } else {

        return;
    }
}

function lca_from_email($email)
{
    return 'noreply@liceclinicsofamerica.com';
} // end example_from_email
add_filter('wp_mail_from', 'lca_from_email');

function lca_from_name($name)
{
    return 'No Reply';
} // end example_from_name
add_filter('wp_mail_from_name', 'lca_from_name');


function get_sl_location_for_text($locationId)
{
    //$wpdb used in the get_sl_location() function
    global $wpdb;
    $results = $wpdb->get_row("SELECT sl_store as store FROM wp_store_locator WHERE sl_id =  " . urlencode($locationId), OBJECT);
    $return = end(explode(' - ', $results->store));
    return $return;
}

	
add_action( 'after_setup_theme', 'lca_theme_setup' );
 
function lca_theme_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
