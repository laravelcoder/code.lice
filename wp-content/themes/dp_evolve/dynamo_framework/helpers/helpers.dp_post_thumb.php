<?php
function dpthumb($postid, $zoomlink ,$style,$subtitle) {
	
	$post = get_post($postid);
	if ($post) {
	$title = $post->post_title;
	if ( has_post_thumbnail($postid) ) {
 					$imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
                    } else {
					$imageurl.= get_template_directory_uri().'/images/body/noimage.jpg';}
	$output = '<div class="dp-thumb-container dp-style-'.$style.'"><div class="dp-thumb"><figure>'."\n";
	if ($style =='4') {$output .= '<div>'."\n";}	
	$output .= '<img src="'.$imageurl.'" title="" alt="" />'."\n";
	if ($style =='4') {$output .= '</div>'."\n";}	
	$output .= '<div class="thumb-overlay">'."\n";
	$output .= '<h3>'.$title.'</h3>'."\n";
	$output .= '<p class="subtitle">'.$subtitle.'</p>'."\n";
	$output .= '<div class="buttons">'."\n"; 
	if ($zoomlink !='') {$output .= '<a class="view" rel="dp_lightbox[]"  href="'.$zoomlink.'"  /><span>'.__("View", 'dp-theme').'</span></a>'."\n";}
	$output .= '<a class="go" href="'.get_permalink($postid).'" /><span>'.__("Go", 'dp-theme').'</span></a>'."\n";
	$output .= '</div>'."\n";
	$output .= '</div></figure></div></div>'."\n";
	return $output;
	} else {
	$output='<div class="alert"><div class="typo-icon">';
	$output.=__("Post <b>dont exist</b>. Check if you use right post <b>ID</b> in shortcode.", 'dp-theme');
	$output.='</div></div>';
	return $output;
	}


} 
?>