<?php
/*
Plugin Name: Make images relative to site
Plugin URI: http://www.affordableprogrammer.com
Description: Replaces absolute URLs with Relative URLs for image paths in posts.
Author: Phillip Madsen
Author URI: http://www.affordableprogrammer.com
Version: 0.1
License: GPLv3
*/
add_filter('image_send_to_editor','image_to_relative',5,8);

function image_to_relative($html, $id, $caption, $title, $align, $url, $size, $alt)
{
	$sp = strpos($html,"src=") + 5;
	$ep = strpos($html,"\"",$sp);
	
	$imageurl = substr($html,$sp,$ep-$sp);
	
	$relativeurl = str_replace("http://","",$imageurl);
	$sp = strpos($relativeurl,"/");
	$relativeurl = substr($relativeurl,$sp);
	
	$html = str_replace($imageurl,$relativeurl,$html);
	
	return $html;
}

?>