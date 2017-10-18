<?php
function contentslideshow($slideshow, $speed, $type) {
			
	$query_string = 'post_type=slide&order=ASC&orderby=menu_order&nopaging=true';
	
	if($slideshow != 'All'){
		
		$query_string .= "&slideshows=$slideshow";
		
	}
	
	$slideshow_query = new WP_Query($query_string);
	
	
	$id = "flexslider_".mt_rand();
	
	
	if($speed != '0'){
		$speed .= '000';
	}
	
		$output = '<script type="text/javascript">'."\n";
		$output .= "   jQuery(window).load(function() {"."\n"; 
		$output .=  "jQuery('#".$id."').flexslider({"."\n";
		$output .=  '    animation: "slide",'."\n";
		$output .=  '    slideshowSpeed:"'.$speed.'",'."\n";
		$output .=  '    smoothHeight: true'."\n";
		$output .=  "  });"."\n";      
		$output .= "   });"."\n";
		$output .= "</script>"."\n";
		$output .= '<div class="incontent_'.$type.'"><div class="flexslider" id="'.$id.'"><ul class="slides">'."\n";
			
		if($slideshow_query->have_posts()) {
		
			while ($slideshow_query->have_posts()) {

            	$slideshow_query->the_post();
        		global $post;
        		$slide_type = get_post_meta($post->ID, 'slide_type', true);
        		$output .= '<li>'."\n";
        			
        		

                if($slide_type == 'i') {

                    if ( has_post_thumbnail() ) {
 					$imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );				
					$slide_desc = get_post_meta($post->ID, 'slide_description', true);
                    $output .='<img src="'.$imageurl.'" title="" alt="" />'."\n";
					$output .='<div class=slide_desc>'.$slide_desc.'</div>';
                    }
                } else {
                    $output .= '<div class="flex_content">';
                    $output .= apply_filters( 'the_content', get_the_content() );
                    $output .= '</div>';
                } // end slide_type
        			
        		$output .= '</li>'."\n";    	
		
			} //End while
		
		} else {
		
			$output .= '<p class="warning">'."\n";
			$output .= __("You don't have any Slides to display.", 'dp-theme');
			$output .= '</p>'."\n";
			
		}
	
		$output .= '	</ul></div></div>'."\n";
	

	return $output;

} 
?>