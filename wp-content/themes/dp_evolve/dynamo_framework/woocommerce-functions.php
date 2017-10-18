<?php

/**
 *
 * Woocommerce functions:
 *
 **/
 
 global $dynamo_tpl;
 
// Display 8 products per page.
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 8;' ), 20 );

// Change number or products per row to 4
add_filter('loop_shop_columns', 'loop_columns');
	if (!function_exists('loop_columns')) {
		function loop_columns() {
		return 4; // 4 products per row
		}
	}

// Redefine woocommerce_output_related_products()
function woo_related_products_limit() {
  global $product;
	
	$args = array(
		'post_type'        		=> 'product',
		'no_found_rows'    		=> 1,
		'posts_per_page'   		=> 4,
		'ignore_sticky_posts' 	=> 1,
	);
	return $args;
}
add_filter( 'woocommerce_related_products_args', 'woo_related_products_limit' );

// Redefine the breadcrumb
function dynamo_woocommerce_breadcrumb() {
	woocommerce_breadcrumb(array(
		'delimiter'   => '',
		'wrap_before' => '<div class="dp-woocommerce-breadcrumbs">',
		'wrap_after'  => '</div>',
		'before' => '<span>',
		'after' => '</span>'
	));
}

// remove old breadcrumb callback
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
// add our own breadcrumb callback
add_action( 'woocommerce_before_main_content', 'dynamo_woocommerce_breadcrumb', 20, 0);


add_action( 'woocommerce_after_shop_loop_item_price', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_rating', 'woocommerce_template_loop_rating', 5 );

// Display short description on catalog pages.
function wc_short_description($count) {
	global $product;
	global $woocommerce;
	
	$input = $product->get_post_data()->post_excerpt;
	$output = '';
	$input = strip_tags($input);
	
	if (function_exists('mb_substr')) {
		$output = mb_substr($input, 0, $count);
		if (mb_strlen($input) > $count){
			$output .= '&hellip;';
		}
	}
	else {
		$output = substr($input, 0, $count);
		if (strlen($input) > $count){
			$output .= '&hellip;';
		}
	}	
	
	return '<p class="short-desc">'.$output.'</p>';
}


//remove add to cart, select options buttons on catalog pages
if(!(get_option($dynamo_tpl->name . '_woocommerce_show_cart_button', 'Y') == 'Y')) : 
function remove_loop_button(){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}

add_action('init','remove_loop_button');
endif;
