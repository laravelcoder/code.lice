<?php
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = $parallax_bg = $video_bg = $video_webm = $video_mp4 = $video_ogv = $video_yt = $video_image = $use_raster = $start_at = $type = $mute = $mute_btn = '';
extract(shortcode_atts(array(
    'el_class'        => '',
	'type'            => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
	'parallax_bg'	  => '',
	'parallax_speed' => '',
	'video_bg'		  => '',
	'video_webm'      => '',
	'video_mp4'       => '',
	'video_ogv'       => '',
	'video_yt'        => '',
	'video_image'     => '',
	'use_raster'      => '',
	'start_at'        => '',
	'mute'			  => 'muted',
	'mute_btn'        => '',
    'css' => ''
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$el_class = $this->getExtraClass($el_class);

$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row vc_row-fluid'.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base'], $atts);
$row_id =uniqid("fws_");
		$has_image = false;
		$style = '';
		if ( (int)$bg_image > 0 && ( $image_url = wp_get_attachment_url( $bg_image, 'large' ) ) !== false ) {
			$has_image = true;
			$style .= "background-image: url(" . $image_url . ");";
			$bg_url = $image_url;
		}
		if ( ! empty( $bg_color ) ) {
			$style .= vc_get_css_color( 'background-color', $bg_color );
		}
		if ( ! empty( $bg_image_repeat ) && $has_image ) {
			if ( $bg_image_repeat === 'cover' ) {
				$style .= "background-repeat:no-repeat;background-size: cover;";
			} elseif ( $bg_image_repeat === 'contain' ) {
				$style .= "background-repeat:no-repeat;background-size: contain;";
			} elseif ( $bg_image_repeat === 'no-repeat' ) {
				$style .= 'background-repeat: no-repeat;';
			}
		}
		if ( ! empty( $font_color ) ) {
			$style .= vc_get_css_color( 'color', $font_color ); // 'color: '.$font_color.';';
		}
		if ( $padding != '' ) {
			$style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
		}
		if ( $margin_bottom != '' ) {
			$style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
		}
		$style .= 'background-position:top center!important;';
		$style = ' style="' . $style . '"';

$output .= '<div id="'.$row_id.'" class="'.$css_class.'"'.$style.'>';
		if(strtolower($parallax_bg) == 'true') {
		$output .= '<div class= "parallax-bg" style="pointer-events: none; background-attachment: fixed; background-color: rgba(0, 0, 0, 0); background-size: cover; background-repeat: no-repeat;" data-speed="'.$parallax_speed.'"></div>';
		}
        if ($video_bg == "html5videobg") { 
                $output .= '<div id="video-container">';
                $output .= '<video autoplay loop ';
				if ($mute == 'muted') $output .= 'muted '; 
				$output .= 'class="fillWidth video-'.$row_id.'">';
                if ($video_mp4 !='') {  
                $output .= '<source src="'.$video_mp4.'" type="video/mp4"/>';
                }
                if ($video_webm !='') {  
                $output .= '<source src="'.$video_webm .'" type="video/webm"/>';
                }
                if ($video_ogv !='') { 
                $output .= '<source src="<?php echo $video_ogv ?>" type="video/ogg"/>'; 
                }
                $output .= 'Your browser does not support the video tag. I suggest you upgrade your browser.'; 
                $output .= '</video>'; 
				if ($mute == 'muted') {
				if(strtolower($mute_btn) == 'true') {$output .= '<a class="dp-video-mute-button mute"></a>';}
				} else {
				if(strtolower($mute_btn) == 'true') {$output .= '<a class="dp-video-mute-button unmute"></a>';}
				}
                $output .= '</div>'; 
            if (strtolower($mute_btn) == 'true') { 
            $output .= '<script type="text/javascript">
            jQuery(document).ready(function(){
            jQuery("#'.$row_id.' .dp-video-mute-button").click(function(event){
                event.preventDefault();
                if( jQuery("#'.$row_id.' .dp-video-mute-button").hasClass("unmute") ) {
                                            jQuery(this).removeClass("unmute").addClass("mute");														
											if( jQuery(".video-'.$row_id.'").prop("muted") ) {
													  jQuery(".video-'.$row_id.'").prop("muted", false);
												} else {
												  jQuery(".video-'.$row_id.'").prop("muted", true);
												}
											  } else {
                                            jQuery(this).removeClass("mute").addClass("unmute");
											if( jQuery(".video-'.$row_id.'").prop("muted") ) {
													  jQuery(".video-'.$row_id.'").prop("muted", false);
												} else {
												  jQuery(".video-'.$row_id.'").prop("muted", true);
												}
                                        }
                });
            
            });
            </script>';		
                     }				
		}
		if ($video_bg == "ytvideobg" && $video_yt !="") {
			$video_options = "showControls:false, autoPlay:true, loop:true, quality:'default',opacity:1";
			if ($start_at !='') $video_options .=", startAt:".$start_at;
			if ($use_raster == 'use_raster') $video_options .= ", addRaster:true";
			if ($mute == 'muted') {
			 $video_options .= ", mute:true";
			 if(strtolower($mute_btn) == 'true') {$output .= '<a class="dp-video-mute-button mute"></a>';}
			 } else {
			 if(strtolower($mute_btn) == 'true') {$output .= '<a class="dp-video-mute-button unmute"></a>';}
			}
			
            if (strtolower($mute_btn) == 'true') { 
            $output .= '<script type="text/javascript">
            jQuery(document).ready(function(){
            jQuery("#'.$row_id.' .dp-video-mute-button").click(function(event){
                event.preventDefault();
                if( jQuery("#'.$row_id.' .dp-video-mute-button").hasClass("unmute") ) {
                                            jQuery(this).removeClass("unmute").addClass("mute");														
                                            jQuery("#video-'.$row_id.'").muteYTPVolume();
                                        } else {
                                            jQuery(this).removeClass("mute").addClass("unmute");
                                            jQuery("#video-'.$row_id.'").unmuteYTPVolume();
                                        }
                });
            
            });
            </script>';		
                     }
           ?>
            <a id="video-<?php echo $row_id ?>" class="mb_ytplayer" data-property="{videoURL:'<?php echo $video_yt;  ?>',containment:'#<?php echo $row_id ?>', <?php echo $video_options ?>}"></a>
		<?php	} 

	if(is_page_template('template.fullwidth.vc.php') &&  $type =='grid') $output .= '<div class="dp-page vc">';
	$output .= wpb_js_remove_wpautop($content);
	if(is_page_template('template.fullwidth.vc.php') &&  $type =='grid') $output .= '</div>';

	$output .= '</div>';


echo $output;