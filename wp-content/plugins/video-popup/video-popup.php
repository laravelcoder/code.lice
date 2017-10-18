<?php
/*
Plugin Name: Video PopUp
Plugin URI: http://wp-plugins.in/video-popup
Description: Display YouTube or Vimeo video in PopUp, responsive video popup & retina ready, just one shortcode, no options! easy to use.
Version: 1.0.2
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2016 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function alobaidi_video_popup_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'video-popup.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/video-popup" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=24967&url=19683" target="_blank">Elegant Themes</a>',
						'<a href="https://wordpress.org/plugins/extend-link/" target="_blank">Recommended Plugin for Video PopUp</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'alobaidi_video_popup_plugin_row_meta', 10, 2 );


function alobaidi_video_popup_include_css_js() {
	wp_enqueue_style( 'oba_youtubepopup_css', plugins_url( '/css/YouTubePopUp.css', __FILE__ ), array(), null, "all");
	wp_enqueue_script( 'oba_youtubepopup_plugin', plugins_url( '/js/YouTubePopUp.jquery.js', __FILE__ ), array('jquery'), null, false);
	wp_enqueue_script( 'oba_youtubepopup_activate', plugins_url( '/js/YouTubePopUp.js', __FILE__ ), array('jquery'), null, false);
}
add_action( 'wp_enqueue_scripts', 'alobaidi_video_popup_include_css_js' );


function alobaidi_video_popup_shortcode($atts){
	if( !empty($atts["url"]) ){
		$url = $atts["url"];
	}else{
		$url = null;
	}

	if( !empty($atts["text"]) ){
		$text = $atts["text"];
	}else{
		if( !empty($atts["url"]) ){
			$text = $atts["url"];
		}else{
			$text = null;
		}
	}

	if( !empty($atts["auto"]) ){
		if( $atts["auto"] == 'no' ){
			$auto = "vp-s";
		}else{
			$auto = "vp-a";
		}
	}else{
		$auto = "vp-a";
	}

	if( !empty($atts["p"]) ){
		$p_before = '<p>';
		$p_after = '</p>';
	}else{
		$p_before = null;
		$p_after = null;
	}

	if( !empty($atts["n"]) ){
		$nofollow = ' rel="nofollow"';
	}else{
		$nofollow = null;
	}

	$filter1 = null;

	$filter2 = apply_filters('wpt_video_popup_shortcode_filter', 0);

	if($filter2 == 1){
		if( !empty($atts["rel"]) ){
			$rel = 1;
		}else{
			$rel = 0;
		}

		$filter1 = apply_filters('wpt_video_popup_attr_filter', $rel);
	}

	$media = '<a'.$filter1.' class="'.$auto.'" href="'.$url.'"'.$nofollow.'>'.$text.'</a>';

	return $p_before.$media.$p_after;
}
add_shortcode('video_popup', 'alobaidi_video_popup_shortcode');