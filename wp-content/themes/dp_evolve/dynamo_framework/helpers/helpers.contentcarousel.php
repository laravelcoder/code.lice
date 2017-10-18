<?php
function contentcarousel($slideshow, $itemWidth, $itemMargin, $minitem, $maxitem) {
		$query_string = 'post_type=slide&order=ASC&orderby=menu_order&nopaging=true';
	
	if($slideshow != 'All'){
		
		$query_string .= "&slideshows=$slideshow";
		
	}
	
	$slideshow_query = new WP_Query($query_string);

	$id = "carousel".mt_rand();
		$carouselout = '<script type="text/javascript">'."\n";
		$carouselout .= "   jQuery(window).load(function() {"."\n"; 
		$carouselout .=  "jQuery('#".$id."').flexslider({"."\n";
		$carouselout .=  '    animation: "slide",'."\n";
		$carouselout .=  '    animationLoop: true,'."\n";
		$carouselout .=  '    itemWidth: '.$itemWidth.','."\n";
		$carouselout .=  '    itemMargin: '.$itemMargin.','."\n";
		$carouselout .=  '    controlNav: false,'."\n";
		$carouselout .=  '    minItems: '.$minitem.','."\n";
		$carouselout .=  '    maxItems: '.$maxitem.','."\n";
		$carouselout .=  "  });"."\n";      
		$carouselout .= "   });"."\n";
		$carouselout .= "</script>"."\n";
    	$carouselout .= '<div class="incontent_carousel"><div class="flexslider carousel1" id="'.$id.'"><ul class="slides">'."\n";
	
		if($slideshow_query->have_posts()) {
		
			while ($slideshow_query->have_posts()) {

            	$slideshow_query->the_post();
        		
        		global $post;
        		$slide_type = get_post_meta($post->ID, 'slide_type', true);
        		$carouselout .= '<li>'."\n";
        			
        		

                if($slide_type == 'i') {

                    if ( has_post_thumbnail() ) {
 					$imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					if( get_post_meta($post->ID, 'slide_link', true) ){$carouselout .= '<a href="'.get_post_meta($post->ID, 'slide_link', true).'" title="">'."\n";} 
                    $carouselout .='<img src="'.$imageurl.'" title="" alt="" />'."\n";
					if( get_post_meta($post->ID, 'slide_link', true) ){$carouselout .= '</a>'."\n";}
                    }
                } // end slide_type
        			
        		$carouselout .= '</li>'."\n";    	
		
			} //End while
		
		} else {
		
			$carouselout .= '<p class="warning">'."\n";
			$carouselout .= __("You don't have any Slides to display.", 'dp-theme');
			$carouselout .= '</p>'."\n";
			
		}
	
	 $carouselout .= '</ul>'."\n";
	
	 $carouselout .= '</div></div><div class="clearfix"></div>';
return  $carouselout;

} 
?>