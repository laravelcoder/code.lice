<?php 
/*
Plugin Name: Phillips Additional Functions
Plugin URI: http://www.affordableprogrammer.com
Description: Adds development functions needed by phillip
Author: Phillip Madsen
Author URI: http://www.affordableprogrammer.com
Version: 0.1
License: GPLv3
*/


function scripts_styles_print_frontend() 
{
    global $loader;
    if (!is_user_logged_in()) :
    wp_enqueue_style('playto', get_stylesheet_directory_uri() . '/custom.css?');    
	else:
	wp_enqueue_style('playto', get_stylesheet_directory_uri() . '/custom.css?'. time());   
    endif;
}
add_action( 'wp_enqueue_scripts','scripts_styles_print_frontend', 120 );  

 

// add_action( 'wp_print_scripts', 'deregister_cf7_javascript', 100 );
// function deregister_cf7_javascript() {
//     if ( !is_page(array(8,10)) ) {
//         wp_deregister_script( 'contact-form-7' );
//     }
// }
// add_action( 'wp_print_styles', 'deregister_cf7_styles', 100 );
// function deregister_cf7_styles() {
//     if ( !is_page(array(8,10)) ) {
//         wp_deregister_style( 'contact-form-7' );
//     }
// }

 


function example_function()
{
    if ( is_user_logged_in() ) 
    {
        // code
    }
}
add_action('init', 'example_function');