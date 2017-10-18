<?php
	
// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	
/**
 *
 * Shortcodes
 *
 * CSS loaded from shortcodes.css
 * JS loaded from shortcodes.js
 *
 * Groups of shortcodes
 *
 * - typography
 * - layout shortcodes
 * - page interactive elements
 * - template specific shortcodes
 *
 **/


		
	function typo_h1($atts, $content = null) {
		return '<h1>'.$content.'</h1>';
	}
	
	function typo_h2($atts, $content = null) {
		return '<h2>'.$content.'</h2>';
	}
	
	function typo_h3($atts, $content = null) {
		return '<h3>'.$content.'</h3>';
	}
	
	function typo_h4($atts, $content = null) {
		return '<h4>'.$content.'</h4>';
	}
	
	function typo_h5($atts, $content = null) {
		return '<h5>'.$content.'</h5>';
	}
	
	function typo_h6($atts, $content = null) {
		return '<h6>'.$content.'</h6>';
	}
	
	function typo_contentheading($atts, $content = null) {
		return '<div class="contentheading">'.$content.'</div>';
	}
	
	function typo_componentheading($atts, $content = null) {	
		return '<div class="component-header"><h2 class="componentheading">'.$content.'</h2></div>';
	}
	
	function typo_div($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => '',
			'class2' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		if($class2 != '') $class2 = ' class="'.$class2.'"';
		return '<div'.$class.'><div'.$class2.'>'.do_shortcode($content).'</div></div>';
	}
	
	function typo_div2($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		return '<div'.$class.'>'.do_shortcode($content).'</div>';
	}

	function typo_div3($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => '',
			'class2' => '',
			'class3' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		if($class2 != '') $class2 = ' class="'.$class2.'"';
		if($class3 != '') $class3 = ' class="'.$class3.'"';
		return '<div'.$class.'><div'.$class2.'><div'.$class3.'>'.do_shortcode($content).'</div></div></div>';
	}
	
	function typo_alert_box($atts, $content = null) {
		extract(shortcode_atts(array(
			'type' => 'error',
			'sticky' => 'no',
			'title' => '',
			'icon' => ''
			), $atts));
		$class = ' class="notification '.$type.'"';
		$html = '<div '.$class.'>';
        $html .='<p>';
		if ($icon != '') {
		$html .= '<i class="'.$icon.'"></i>';
		}
		if ($title != '') {
		$html .= '<span>'.$title.'</span>';
		}
		$html .= do_shortcode($content);
		$html .= '</p>';
		if ($sticky != 'no') {
		$html .= '<a class="close"></a>';
		}
		$html .= '</div>';
		
		return $html;
	}
	
	function typo_icon($atts, $content = null) {
		global $dynamo_tpl;
		extract(shortcode_atts(array(
			'icon' => 'icon-wordpress',
			'color' => '#555555',
			'badge' => '',
			'size' => 'small'
			
			), $atts));
		$icon = preg_replace('/\s/', '', $icon);
		if ($badge == "accented") {	$badge = get_option($dynamo_tpl->name . '_maincontent_accent_color');}
		if ($color == "accented") {	$color = get_option($dynamo_tpl->name . '_maincontent_accent_color');}
		if ($badge != '' || $color != ''){
		$style = 'style="';
		if ($color != '') {$style .= 'color:'.$color.';';}  
		if ($badge != '') {$style .= 'background-color:'.$badge.';';}
		$style .= '"';
		}
		$class = 'class="dp_icon '.$size.'';
		if ($badge!='') $class .=' badge';
		$class .= '"';
		
		$outputcontent = '<div '.$class.' >';
		$outputcontent.='<i class="'.$icon.'" '.$style.'></i></div>';
		return $outputcontent;
	}
	
	
	function typo_pre($atts, $content = null) {	
		return '<pre>'.$content.'</pre>';
	}
	
	function typo_blockquote($atts, $content = null) {	
		return '<blockquote>'.$content.'</blockquote>';
	}
	
	
	function typo_legend1($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => ''
		), $atts));
		return '<div class="dp_legend1"> <h4>'.$title.'</h4><p>'.do_shortcode($content).'</p></div>';
	}

	function typo_legend2($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => ''
		), $atts));
		return '<div class="dp_legend2"> <h4>'.$title.'</h4><p>'.do_shortcode($content).'</p></div>';
	}
	function typo_legend3($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => ''
		), $atts));
		return '<div class="dp_legend3"> <h4>'.$title.'</h4><p>'.do_shortcode($content).'</p></div>';
	}
	function typo_list($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		return '<ul'.$class.'>'.do_shortcode($content).'</ul>';
	}
	
	function typo_li($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => '',
			'size' => '',
			'color' => ''
		), $atts));
        global $dynamo_tpl;
		if ($color == "accented") {	$color = get_option($dynamo_tpl->name . '_maincontent_accent_color');}
		$class = preg_replace('/\s/', '', $class);
		if($class != '') :
			$class= 'icon-'.$class;
			if ($size !='') $class = $class = $class.' icon-large';
			if ($color !='') $color = ' style="color:'.$color.'"';
			return '<li style="list-style:none;line-height:2em"><i class="'.$class.'" '.$color.'></i>'.do_shortcode($content).'</li>';
		else :
			return '<li>'.do_shortcode($content).'</li>';
		endif;
	}
	
	function typo_ord_list($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		return '<ol'.$class.'>'.do_shortcode($content).'</ol>';
	}
	
	function typo_discnumber($atts, $content = null) {
		extract(shortcode_atts(array(
			'number' => '',
			'color1' => '#555',
			'color2' => '#fff'
		), $atts));
		return '<div class="number"><p><span style="color:'.$color2.'; background-color:'.$color1.'">'.$number.'</span>'.do_shortcode($content).'</p></div>';
	}
	
	function typo_bignumber($atts, $content = null) {
		extract(shortcode_atts(array(
			'number' => '',
			'color1' => '#555',
			'color2' => '#fff'
		), $atts));
		$style = 'style = "color:'.$color2.'; background-color:'.$color1.';"';
		return '<p  class="bignumber"><span class="bnumber" '.$style.'>'.$number.'</span>'.do_shortcode($content).'</p>';
	}
	
	function typo_emphasis($atts, $content = null) {	
		return '<em class="color">'.$content.'</em>';
	}
	
	function typo_emphasisbold($atts, $content = null) {	
		return '<em class="bold">'.$content.'</em>';
	}
	
	function typo_emphasisbold2($atts, $content = null) {	
		return '<em class="bold2">'.$content.'</em>';
	}
	
	
	function typo_dropcap($atts, $content = null) {
		extract(shortcode_atts(array(
			'cap' => ''
		), $atts));

		return '<p class="dropcap"><span class="dropcap">'.$cap.'</span>'.do_shortcode($content).'</p>';
	}
	
	function typo_important($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => ''
		), $atts));

		return '<div class="important"><span class="important-title">'.$title.'</span>'.do_shortcode($content).'</div>';
	}
	
	function typo_underline($atts, $content = null) {	
		return '<span style="text-decoration:underline;">'.$content.'</span>';
	}
	
	function typo_bold($atts, $content = null) {	
		return '<span style="font-weight:bold;">'.$content.'</span>';
	}
	
	function typo_italic($atts, $content = null) {	
		return '<span style="font-style:italic;">'.$content.'</span>';
	}
	
	function typo_clear($atts, $content = null) {	
		return '<div class="clear"></div>';
	}
	
	function typo_readon($atts, $content = null) {
		extract(shortcode_atts(array(
			'url' => ''
		), $atts));

		return '<p><a class="button" style="margin-top:0!important;" href="'.$url.'">'.$content.'</a></p>';
	}
	
	function typo_readon2($atts, $content = null) {
		extract(shortcode_atts(array(
			'url' => ''
		), $atts));

		return '<a href="'.$url.'">&nbsp;&nbsp;'.$content.' &rarr;</a>';
	}
	
	function typo_clearboth() {
   return '<div class="clearboth"></div>';
	}
	
	function typo_divider() {
		return '<div class="divider"></div>';
	}
	
	function typo_divider_top() {
		return '<div class="divider top"><a href="#">'.__('Top','dp_theme').'</a></div>';
	}
	
	function typo_space($atts, $content = null) {
		extract(shortcode_atts(array(
			'size' => '5'
			), $atts));
		return '<div style="height:'.$size.'px; width:100%;clear:both"></div>';
	}
	
	function typo_divider_padding() {
		return '<div class="divider_padding"></div>';
	}
	
	function typo_divider_line() {
		return '<div class="divider_line"></div>';
	}
		
	function typo_button($atts, $content = null, $code) {
		extract(shortcode_atts(array(
			'id' => 'button-'.mt_rand(),
			'class' => false,
			'size' => 'small',
			'link' => '',
			'linktarget' => '',
			'style' => '',
			'icon' => '',
			'bgcolor' => '',
			'hbgcolor' =>'',
			'width' => false,
			'textcolor' => '',
			'htextcolor' => '',
			'full' => "false",
			'align' => '',
			'button' => "false",
			'dp_animation' => ""
		), $atts));
		$icon = preg_replace('/\s/', '', $icon);
		$cssid = $id;
		$id = $id?' id="'.$id.'"':'';
		$full = ($full==="false")?'':' full';
		$style = $style?' '.$style:'';
		$class = $class?' '.$class:'';
		$link = $link?' href="'.$link.'"':'';
		$linktarget = $linktarget?' target="'.$linktarget.'"':'';
		if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
		if ($icon !='') $icon = '<i class="'.$icon.'"></i> ';
		
		$width = $width?'width:'.$width.'px;':'';
		if($align == 'right'){
			$aligncss = ' style="float:right"';
		}else{
			$aligncss = '';
		}
		if($button == 'true'){
			$tag = 'button_sc';
		}else{
			$tag = 'a';
		}
		
		$customstyle = "";
		$customstyle ='<style type="text/css">';
		if ($bgcolor !="") $customstyle .= '#'.$cssid.' {background-color: '.$bgcolor.'!important;}';
		if ($textcolor !="") $customstyle .= '#'.$cssid.' span {color: '.$textcolor.'!important;}';
		if ($hbgcolor !="") $customstyle .= '#'.$cssid.':hover {background-color: '.$hbgcolor.'!important;}';
		if ($htextcolor !="") $customstyle .= '#'.$cssid.' span:hover {color: '.$htextcolor.'!important;}';
		$customstyle .= '</style>';
		$content = '<'.$tag.$id.$link.$linktarget.$dp_animation.' class="button_sc '.$size.$style.$full.$class.'" '.$aligncss.'><span'.(($width!='')?' style="'.$width.'"':'').'>'.$icon.' ' . trim($content) . '</span></'.$tag.'>';
		$content .= $customstyle;
		if($align === 'center'){
			return '<p class="center">'.$content.'</p>';
		}else{
			return $content;
		}
	}
	function typo_button_standart($atts, $content = null, $code) {
		extract(shortcode_atts(array(
			'link' => '',
			'linktarget' => '',
		), $atts));
		$link = $link?' href="'.$link.'"':'';
		$linktarget = $linktarget?' target="'.$linktarget.'"':'';
		
		
		$content = '<a class="btn" '.$link.' '.$linktarget. '>' . trim($content) . '</a>';
			return $content;
	}
/**
 *
 * Layout shortcodes
 *
 **/
function dp_column($atts, $content = null, $code) {
		
		return '<div class="'.$code.'">' . do_shortcode(trim($content)) . '</div>';
	}

 function dp_tabs($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'history' => false
	), $atts));
	
	if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
		}
		
		$output = '<ul class="'.$code.'">';
		
		for($i = 0; $i < count($matches[0]); $i++) {
			if($history=='true'){
				$href= '#'.str_replace(" ", "_", trim($matches[3][$i]['title']));
			}else{
				$href = '#';
			}
			$output .= '<li><a href="'.$href.'"><div class="tab_title"><i class="'.$matches[3][$i]['icon'].'"></i>' . $matches[3][$i]['title'] . '</div></a></li>';
		}
		$output .= '</ul>';
		$output .= '<div class="panes">';
		for($i = 0; $i < count($matches[0]); $i++) {
			$output .= '<div class="pane">' . do_shortcode(trim($matches[5][$i])) . '</div>';
		}
		$output .= '</div>';
		
		if($history=='true'){
			$data_history = ' data-history="true"';
		}else{
			$data_history = '';
		}
		
		return '<div class="'.$code.'_container"'.$data_history.'>' . $output . '</div>';
	}
}





function dp_faq($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'title' => false,
		'icon' => '',
		'default_state' => '',
		'el_class' => ''
	), $atts));
	if(strtolower($default_state) == 'true') $el_class .= ' current';
	return '<div class="toggle faq"><h4 class="toggle_title '.$el_class.'"><span class="icon-holder"><i class="'.$icon.'"></i></span>'. $title . '</h4><div class="toggle_content">' . do_shortcode(trim($content)) . '</div></div>';
}

function dp_frame_left( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'link' => '',
		'icon' => '',
		'lightbox' => '',
		'title' => '',
		'desc' => '',
		'popupw' => '',
		'popuph' => ''
		), $atts));
	if($link !='') { if($lightbox =='true') {$output= '<a class="imgeffect '.$icon.'" href="'.$link.'?width='.$popupw.'&height='.$popuph.'" rel="dp_lightbox" title="'.$title.' :: '.$desc.'"><img src="'.do_shortcode($content).'" /></a>';} 
	else {$output= '<a class="imgeffect '.$icon.'" href="'.$link.'"><img src="'.do_shortcode($content).'" /></a>';}} else {$output= '<img src="' .do_shortcode($content) . '" />';}; 
   return '<span class="frame alignleft">' .$output . '</span>';
}

function dp_frame_right( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'link' => '',
		'icon' => '',
		'lightbox' => '',
		'title' => '',
		'desc' => '',
		'popupw' => '',
		'popuph' => ''
		), $atts));
	if($link !='') { if($lightbox =='true') {$output= '<a class="imgeffect '.$icon.'" href="'.$link.'?width='.$popupw.'&height='.$popuph.'" rel="dp_lightbox" title="'.$title.' :: '.$desc.'"><img src="'.do_shortcode($content).'" /></a>';} 
	else {$output= '<a class="imgeffect '.$icon.'" href="'.$link.'"><img src="'.do_shortcode($content).'" /></a>';}} else {$output= '<img src="' .do_shortcode($content) . '" />';}; 
   return '<span class="frame alignright">' .$output . '</span>';
}

function dp_frame_center( $atts, $content = null ) {
extract(shortcode_atts(array(
		'link' => '',
		'icon' => '',
		'lightbox' => '',
		'title' => '',
		'desc' => '',
		'popupw' => '',
		'popuph' => ''
		), $atts));
	if($link !='') { if($lightbox =='true') {$output= '<a class="imgeffect '.$icon.'" href="'.$link.'?width='.$popupw.'&height='.$popuph.'" rel="dp_lightbox" title="'.$title.' :: '.$desc.'"><img src="'.do_shortcode($content).'" /></a>';} 
	else {$output= '<a class="imgeffect '.$icon.'" href="'.$link.'"><img src="'.do_shortcode($content).'" /></a>';}} else {$output= '<img src="' .do_shortcode($content) . '" />';}; 
    return '<div class="textaligncenter"><span class="frame aligncenter">' .$output . '</span></div>';
}

function dp_frame_caption( $atts, $content = null ) {
extract(shortcode_atts(array(
		'title' => '',
		'caption' => ''
		), $atts));
	if($title !='') {$title = '<h5 class="cap1">'.$title.'</h5>';};
	if($caption !='') {$caption = '<h5 class="cap2">'.$caption.'</h5>';}; 
	$output= '<img src="' .do_shortcode($content) . '" width="100%" />'; 
    return '<div class="frame_caption">' .$output . '<div class="captions">'.$title.$caption.'</div></div>';
}

function dp_table($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'id' => false,
		'width' => false,
	), $atts));
	
	
	if($width){
		$width = ' style="width:'.$width.'"';
	}else{
		$width = '';
	}
	
	$id = $id?' id="'.$id.'"':'';
	
	return '<div'.$id.$width.' class="table_style">' . do_shortcode(trim($content)) . '</div>';
}

function dp_photo_gallery( $atts, $content = null ) {
	//[photo_gallery]
	$dp_photo_gallery='<ul class="photo_gallery">';
	$dp_photo_gallery .= do_shortcode(strip_tags($content));
	$dp_photo_gallery.='</ul><div class="clearboth"></div>';
	return $dp_photo_gallery;
}

function dp_photo_gallery_lines( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
		'twidth' => '',
		'theight' => ''
		), $atts));
		if (is_numeric($content)) {
            $photo_link = wp_get_attachment_url($content);
        } else {
		$photo_link=trim($content);        
		}

	
	//dimension defaults
	if(!$twidth && !$theight):
		$twidth="290";
		$theight="200";
	endif;
 
 	
	$lightbox='rel="dp_lightbox[gallery]"';

	//title
	$alt ='alt="'.$title.'"';
	$title ='title="'.$title.'"';
	

 
	$dp_photo_gallery_lines='<li><div class="mediaholder"><a class="" href="'.$photo_link.' " '.$title.'  '.$alt.'  '.$lightbox.'><img style="width:'.$twidth.'px; height: '.$theight.'px " src="'.$photo_link.'"  />';
	$dp_photo_gallery_lines .= '<div class="hovermask">
                                        <div class="hovericon"><i class="ss-search"></i></div>
                                   </div>';
	$dp_photo_gallery_lines .= '</a></div></li>';
	
	return $dp_photo_gallery_lines;
}	


function lightbox_shortcode($atts, $content = null) {
   
	extract(shortcode_atts(array(
		'link' => '',
		'bigimage' => '',
		'title' => '',
		'album' => '',
		'desc' => '',
		'thumb' => '',
		'hover_icon' => 'zoom',
		'dp_animation' => ''
	), $atts));
	if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
	if ($hover_icon == 'zoom') $hover_icon = 'ss-search';
	if ($hover_icon == 'play') $hover_icon = 'ss-play';
	if ($hover_icon == 'file') $hover_icon = 'ss-file';
	if (is_numeric($thumb)) {
            $thumb_src = wp_get_attachment_url($thumb);
        } else {
            $thumb_src = $thumb;
        }
	if (is_numeric($bigimage)) {
            $bigimage_src = wp_get_attachment_url($bigimage);
        } else {
            $bigimage_src = $bigimage;
        }
	if ($link == '') {if ($bigimage != '') {$link = $bigimage_src;} else {$link = $thumb_src;}}
	if ($desc !='') $title= $title.' :: '.$desc;
	if ($album !='') $album = '['.$album.']';
	$generate_lightbox = '';
	
	$generate_lightbox = '<div class="mediaholder" '.$dp_animation.'><a " href="'.$link.'" title="'.$title.'" alt="" rel="dp_lightbox'.$album.'"><img src="'.$thumb_src.'"><div class="hovermask">
                                        <div class="hovericon"><i class="'.$hover_icon.'"></i></div>
                                   </div></a></div>';
	return $generate_lightbox;
}

function dp_slideshow($atts) {

	//extract short code attr
	extract(shortcode_atts(array(
		'id' => '',
		'speed' =>'5',
		'type' =>'image',
	), $atts));
	
	dynamo_add_flex();
	include_once (get_template_directory() . '/dynamo_framework/helpers/helpers.contentslideshow.php');
		
		 
	$return_html = contentslideshow($id, $speed, $type);
	
	return $return_html;
}

function dp_carousel($atts) {

	//extract short code attr
	extract(shortcode_atts(array(
		'id' => '',
		'itemwidth' =>'190',
		'itemmargin' =>'5',
		'minitem' =>'3',
		'maxitem' =>'5',

	), $atts));
	
	dynamo_add_flex();
	include_once (get_template_directory() . '/dynamo_framework/helpers/helpers.contentcarousel.php');
		
		 
	$return_html = contentcarousel($id, $itemwidth ,$itemmargin ,$minitem , $maxitem);
	
	return $return_html;
}

function dp_pricing_column( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'column_style' => '',
		'title' => '',
		'price' => '',
		'currency' => '',
		'price_sub' => '',
		'link' => '',
		'button_txt' => 'Buy Now'
    ), $atts));
	
	$html = '<div class="plan '.$column_style.'">
               <h3>'.$title.'</h3>
               <div class=" plan-price"> <span class="plan-currency">'.$currency.'</span> <span class="value">'.$price.'</span> <span class="period">'.$price_sub.'</span> </div>
               <div class="plan-features">'.$content.'
                    <div class="button-area"><a href="'.$link.'">'.$button_txt.'</a></div> 
					</div>
          </div>';
	
	return $html;
	
}

function dp_googlemap( $atts ){

    global $attributes;
    global $js;
    wp_register_script('google-map-api-js', 'http://maps.google.com/maps/api/js?sensor=false', array(), false, true);
	wp_enqueue_script('google-map-api-js');
	wp_register_script('gmap3-js', get_template_directory_uri().'/js/gmap3.min.js', array('jquery'),false);
	wp_enqueue_script('gmap3-js');

    extract(shortcode_atts(Array(
            'id'     => 'map1',
            'width'  => '600',
            'height' => '350',
            'margin' => '0',
            'text'  => '',
            'long'      => '',
            'lat'      => '',
            'zoom'   => 12 ,
			'mapcontrol' => 'false',
			'streetview' => 'false',
			'zoomcontrol'=> 'true',
			'pancontrol'=> 'true',
			'address' => ''
        ), $atts ));
    

	$id = 'map-'.mt_rand();
	$js = '<script type="text/javascript">';
	$js .= 'jQuery(document).ready(function(){'.'';
    $js .= 'jQuery("#'.$id.'").gmap3(
	 { map:{';
	if ($address != "") {
	$js .= 'address:"'.$address.'",';
	}
	$js .= 'options:{';
	if ($lat != "" && $long != "") {
	$js .= 'center:['.$lat.', '.$long.'],';
	}
	if ($text != '') {
	$js .= 'content:"'.$text.'",';
	}
	$js .= '
     zoom:'.$zoom.',';
	$js .= 'disableDefaultUI: '.$mapcontrol.',';
	$js .= 'panControl: '.$pancontrol.',';
	$js .= 'zoomControl: '.$zoomcontrol.',';
	$js .= 'streetViewControl: '.$streetview.',';
	$js .='}
 },
	marker:{';
	if ($lat != "" && $long != "") {
    $js .= 'latLng:['.$lat.', '.$long.']';
	}
	if ($address != "") {
	$js .= 'address:"'.$address.'",';
	}
	$js .= '		},';
	if ($text != '') {
	$js .= 'infowindow:{';
     $js .= 'options:{
       content: "'.$text.'"
     }
	}';
	}
	 $js .='}
	)';
    $js .= '});';
    $js .= '</script>';
    
    $attributes = 'id="'. $id .'" class="gmap" style="width:'. $width .'px; height:'. $height .'px; margin:'. $margin .'px; overflow:hidden;"';
    return $js . '<div '. $attributes .'></div>';}
	
function dp_chart( $atts ) {
	extract(shortcode_atts(array(
	    'data' => '',
	    'colors' => '',
		'size' => '400x200',
	    'bg' => 'bg,s,ffffff',
	    'title' => '',
	    'labels' => '',
	    'advanced' => '',
	    'type' => 'pie'
	), $atts));
 
	switch ($type) {
		case 'line' :
			$charttype = 'lc'; break;
		case 'xyline' :
			$charttype = 'lxy'; break;
		case 'sparkline' :
			$charttype = 'ls'; break;
		case 'meter' :
			$charttype = 'gom'; break;
		case 'scatter' :
			$charttype = 's'; break;
		case 'venn' :
			$charttype = 'v'; break;
		case 'pie' :
			$charttype = 'p3'; break;
		case 'pie2d' :
			$charttype = 'p'; break;
		case 'pie2d' :
			$charttype = 'p'; break;
		default :
			$charttype = $type;
		break;
	}
 
	
	$string = '&chs='.$size.'';
	$string .= '&chd=t:'.$data.'';
	$string .= '&chf='.$bg.'';
	if ($charttype=='bhg') $string .= '&chxt=x,y';
	if ($title) $string .= '&chtt='.$title.'';
	if ($labels) $string .= '&chxl='.$labels.'';
	if ($colors) $string .= '&chco='.$colors.'';
	
	return '<img title="'.$title.'" src="http://chart.apis.google.com/chart?cht='.$charttype.''.$string.$advanced.'" alt="'.$title.'" />';
}
function dp_youtube($atts) {

	//extract short code attr
	extract(shortcode_atts(array(
		'width' => 640,
		'height' => 385,
		'video_id' => '',
		'autoplay' => ''
	), $atts));
	$params = '';
	if ($autoplay == 'yes') $params ='&autoplay=1'; 
	$custom_id = time().rand();
	$return_html = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_id.'?feature=player_detailpage'.$params.'" frameborder="0" allowfullscreen></iframe>';
	return $return_html;
}


function dp_vimeo($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'width' => 640,
		'height' => 385,
		'video_id' => '',
		'autoplay' => '',
		'loop' => ''
	), $atts));
	$params = '';
	if ($autoplay == 'yes') $params ='&autoplay=1'; 
	if ($loop == 'yes') $params .='&loop=1';
	$custom_id = time().rand();
	
	$return_html = '<iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=777d80'.$params.'&amp;api=1&amp;player_id=vim_id0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>';
	return $return_html;
}

function dp_html5video($atts) {

	//extract short code attr
	extract(shortcode_atts(array(
		'width' => 640,
		'height' => 385,
		'poster' => '',
		'mp4' => '',
		'webm' => '',
		'ogg' => '',
	), $atts));
	
	$custom_id = time().rand();
	
	$return_html = '<div class="video-js-box vim-css"> 
    <video id="example_video_1" class="video-js" width="'.$width.'" height="'.$height.'" controls="controls" preload="auto" poster="'.$poster.'"> 
      <source src="'.$mp4.'" type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\' /> 
      <source src="'.$webm.'" type=\'video/webm; codecs="vp8, vorbis"\' /> 
      <source src="'.$ogg.'" type=\'video/ogg; codecs="theora, vorbis"\' /> 
      <object id="flash_fallback_1" class="vjs-flash-fallback" width="640" height="264" type="application/x-shockwave-flash"
        data="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf"> 
        <param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" /> 
        <param name="allowfullscreen" value="true" /> 
        <param name="flashvars" value=\'config={"playlist":["'.$poster.'", {"url": "'.$mp4.'","autoPlay":false,"autoBuffering":true}]}\' /> 
        <img src="'.$poster.'" width="640" height="264" alt="Poster Image"
          title="No video playback capabilities." /> 
      </object> 
    </video> 
  </div> ';
	
	return $return_html;
}

function dp_mp3($atts) {

	//extract short code attr
	extract(shortcode_atts(array(
		'url' => '',
		'autoplay' => '',
		'title' => '',
		'author' => ''
	), $atts));
	if ($author != '') $author = $author.': ';
	$return_html = '';
	$id = 'mp3-'.mt_rand();
  $return_html = '<script type="text/javascript">';
  $return_html .= 'jQuery(document).ready(function(){';
  $return_html .= 'jQuery("#jquery_jplayer_'.$id.'").jPlayer({';
  $return_html .= 'ready: function () {';
  $return_html .= 'jQuery(this).jPlayer("setMedia", {';
  $return_html .= 'mp3: "'.$url.'",';
  $return_html .= '});';
  $return_html .= '},';
  $return_html .= 'cssSelectorAncestor: "#jp_container_'.$id.'",';
  $return_html .= 'supplied: "mp3"';
  $return_html .= '});';
  $return_html .= '});';
  $return_html .= '</script>';
 
  		$return_html .= '<div class="clearboth"></div><div id="jquery_jplayer_'.$id.'" class="jp-jplayer"></div>';
		$return_html .= '<div id="jp_container_'.$id.'" class="jp-audio">';
		$return_html .= '<div class="jp-type-single">';
		$return_html .= '	<div class="jp-gui jp-interface">';
	  	$return_html .= '		<div class="jp-progress">';
		$return_html .= '		  <div class="jp-seek-bar">';
		$return_html .= '			<div class="jp-play-bar"><span></span></div>';
		$return_html .= '		  </div>';
		$return_html .= '		</div>';
		$return_html .= '		<a href="javascript:;" class="jp-play" tabindex="1">play</a>';
		$return_html .= '		<a href="javascript:;" class="jp-pause" tabindex="1">pause</a>';
		$return_html .= '		<div class="jp-volume-bar">';
		$return_html .= '		  <div class="jp-volume-bar-value"><span class="handle"></span></div>';
		$return_html .= '		</div>';
		$return_html .= '		<a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a>';
		$return_html .= '		<a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a>';
		$return_html .= '		<div class="mp3-title"><span>'.$author.'</span>'.$title.'</div>';
		$return_html .= '	</div><div class="clearboth"></div>';

		$return_html .= '	<div class="jp-no-solution">';
		$return_html .= '		<span>Update Required</span>';
		$return_html .= '		To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.';
		$return_html .= '	</div>';
		$return_html .= '</div>';
		$return_html .= '</div>';
	
	return $return_html;
}

function dp_soundcloud($atts) {
global $dynamo_tpl;
	//extract short code attr
	extract(shortcode_atts(array(
		'url' => '',
		'autoplay' => 'false',
		'artwork' => 'true',
		'playlist' => 'no',
		'color' =>''
	), $atts));
	$height = '166';
	if ($color =='') $color = get_option($dynamo_tpl->name . '_maincontent_accent_color');
	$color = str_replace( '#', '', $color);
	if ($playlist == 'yes') $height ='450';
	$return_html = '<iframe width="100%" height="'.$height.'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.$url.'&amp;color='.$color.'&amp;auto_play='.$autoplay.'&amp;show_artwork='.$artwork.'"></iframe>';
	return $return_html;
}

function dp_popular_posts($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'number' => '3',
		'thumb_width' => '70',
		'words' => '15'
	), $atts));
	
	
	$return_html = dp_print_popular_posts($number,$thumb_width, $words );
	
	return $return_html;
}

function dp_recent_posts($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'cat' => '',
		'number' => '3',
		'thumb_width' => '70',
		'words' => '15'
	), $atts));
	
	$return_html = dp_print_recent_post($cat,$number,$thumb_width, $words );
	
	return $return_html;
}
function dp_social_links( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'type' => ''
		), $atts));
	//[social_links_container]
	$dp_social_links='<ul class="social-icons '.$type.'">';
	$dp_social_links .= do_shortcode(strip_tags($content));
	$dp_social_links.='</ul><div class="clearboth"></div>';
	return $dp_social_links;
}

function dp_social_links_item( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'link' => '#',
		'title' =>'',
		'type' => ''
		), $atts));
		$atributes = 'class="'.$type;
		if ($title != '') $atributes .= ' dp-tipsy';
		$atributes .= '" href="'.$link.'" '; 
		if ($title != '') $atributes .= ' data-tipcontent="'.$title.'"';
	$dp_social_links_item='<li><a '.$atributes.' target="_blank"></a></li>';
	
	return $dp_social_links_item;
	
}	

function dp_team_box($atts, $content) {
	//extract short code attr
	extract(shortcode_atts(array(
		'style' => '',
		'name' => '',
		'title' => '',
		'imgurl' => '',
		'twitter' => '',
		'facebook' => '',
		'linkedin' => '',
		'skype' => '',
		'rss' => '',
		'gplus' => '',
		'email' => ''

	), $atts));
	if (is_numeric($imgurl)) {
            $image_src = wp_get_attachment_url($imgurl);
        } else {
            $image_src = $imgurl;
        }
	$html = '<div class="team '.$style.'">';
    $html .= '<div class="img-wrp"> <img alt="" src="'.$image_src.'">';
    $html .= '<div class="overlay-wrp" style="display: none;">';
    $html .= '<div class="overlay"></div>';
    $html .= '<div class="social-icon-wrap overlay-content"><div class="social-icon-wrap-inner"><ul class="social-icons ">';
	if ($twitter != '') {
		$html .='<li><a class="twitter" title="Twitter" href="'.$twitter.'"></a></li>';
	}
	if ($facebook != '') {
		$html .='<li><a class="facebook" title="Facebook" href="'.$facebook.'"></a></li>';
	}
	if ($linkedin != '') {
		$html .='<li><a class="linkedin" title="Linkedin" href="'.$linkedin.'"></a></li>';
	}
	if ($skype != '') {
		$html .='<li><a class="skype" title="Skype" href="'.$skype.'"></a></li>';
	}
	if ($rss != '') {
		$html .='<li><a class="gplus" title="Google+" href="'.$gplus.'"></a></li>';
	}
	if ($rss != '') {
		$html .='<li><a class="rss" title="RSS" href="'.$rss.'"></a></li>';
	}
	$html .= '</ul></div></div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<div class="content">';
    $html .= '<div class="team-name">';
    $html .= '<h5>'.$name.'</h5>';
    $html .= '<span>'.$title.'</span></div>';
    $html .= '<div class="team-about">';
    $html .= '<p>'.$content.'</p>';
    $html .= '</div>';
	if ($email != '') {
    $html .= '<div class="team-email"><a href="mailto:'.$email.'"><i class="icon-mail"></i> '.$email.' </a></div>';
	}
	$html .= '</div>';
    $html .= '<div class="clearboth"></div>';
    $html .= '</div>';
	return $html;
}

/**
 *
 * Template specific shortcodes
 *
**/ 
function dp_thumb($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'postid' => '',
		'zoomlink' => '',
		'style' => '1',
		'subtitle' => ''
		), $atts));
	include_once (get_template_directory() . '/dynamo_framework/helpers/helpers.dp_post_thumb.php');
		
		 
	$return_html = dpthumb($postid, $zoomlink, $style, $subtitle);
	
	return $return_html;	}
	
function piechart($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'percent' => '80',
		'size' => '180',
		'title' => '',
		'linewidth' => '5',
		'barcolor' => '#5F8CB4',
		'trackcolor' => '#DFE0E0',
		'percentcolor' => '',
		'dp_animation' => ''
		), $atts));
	if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
	$style = 'style ="width:'.$size.'px; height:'.$size.'px;"';
	$style1 = 'style ="line-height:'.$size.'px;';
	if ($percentcolor !='') $style1 .= ' color:'.$percentcolor;
	$style1 .= '"';
	$return_html = '<div class="chartBox" '.$dp_animation.'>
					<span '.$style.' class="easyPieChart" data-percent="'.$percent.'" data-size="'.$size.'" data-line="'.$linewidth.'" data-barcolor="'.$barcolor.'" data-trackcolor="'.$trackcolor.'">
					<span class="percent" '.$style1.'>86</span>
					</span>
					<h3>'.$title.'</h3>'.do_shortcode($content).'                   
      				</div>';	
	return $return_html;	}

function piechart2($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'percent' => '80',
		'size' => '180',
		'title' => '',
		'linewidth' => '5',
		'barcolor' => '#FFFFFF',
		'percentcolor' => '#ffffff',
		'bgcolor' => '#5F8CB4',
		'dp_animation' => ''
		), $atts));
	if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
	$style = 'style ="width:'.$size.'px; height:'.$size.'px;"';
	$style1 = 'style ="line-height:'.$size.'px;';
	if ($percentcolor !='') $style1 .= ' color:'.$percentcolor;
	$style1 .= '"';
	$return_html = '<div class="chartBox" '.$dp_animation.'>
					<span '.$style.' class="easyPieChart2" data-percent="'.$percent.'" data-size="'.$size.'" data-line="'.$linewidth.'" data-barcolor="'.$barcolor.'">
					<span class="percent" '.$style1.'>86</span>
					</span>
					<h3>'.$title.'</h3>'.do_shortcode($content).'                   
      				</div>';	
	return $return_html;	}


function counter($atts, $content = null) {
	extract(shortcode_atts(array(
	"numbercolor"=>"",
	"fontsize" => "",
	"titlecolor"=>"",
	"number"=>"1000",
	"animate_stop"=>"800",
	"cssclass" => ""), $atts));
	$titlestyle = "";
	if ($titlecolor != "") $titlestyle = ' style ="color:'.$titlecolor.';"';
    $html = "";
	$html .=  '<div class="stats '.$cssclass.'"><div class="num" data-content="'.$number.'" data-num="'.$animate_stop.'"';
	if ( ($numbercolor !='') || ($fontsize != '') ) {  
	$html .= ' style="';
	if ($numbercolor != '') $html .= 'color:'.$numbercolor.';'; 
	if ($fontsize !='') $html .= 'font-size:'.$fontsize.'px; height:'.$fontsize.'px; line-height:'.$fontsize.'px;';
	$html .= '"';
	}
	$html .= '>0</div><div class="type"'.$titlestyle.'>'.do_shortcode($content).'</div></div>';
    return $html;
	
}

function progressbar($atts, $content = null) {
	extract(shortcode_atts(array(
	"title"=>"Title",
	"titlecolor"=>"",
	"percent"=>"50",
	"barcolor"=>""
	), $atts));
	$titlestyle = "";
	$barstyle = "";
	if ($titlecolor != "") $titlestyle = ' style ="color:'.$titlecolor.';"';
	if ($barcolor != "") $barstyle = ' style ="background-color:'.$barcolor.';"';
    $html = "";
	$html .=  '<div class="skill-bar"><p'.$titlestyle.'>'.$title.'</p><div class="bar-wrap"><span data-width="'.$percent.'"'.$barstyle.'>';
	$html .= '<strong>'.$percent.'%</strong> </span> </span></div></div>';
    return $html;
	
}

function headline($atts, $content = null) {
	extract(shortcode_atts(array(
	"subtitle"=>"Headline",
	"style"=>"",
	"cssclass"=>""
	), $atts));
	$addclass = ' '.$style.' '.$cssclass;
	
    $html = "";
	$html .=  '<div class="headline'.$addclass.'"><h3>'.$content.'</h3>';
	if ($subtitle != "" && ($style == "big-centered")) {
	$html .= '<p class="subtitle">'.$subtitle.'</p>';
	}
	$html .= '</div>';
    return $html;
	
}
function testimonial($atts, $content = null) {
	extract(shortcode_atts(array(
	"name"=>"",
	"position"=>"",
	"dp_animation" => "",
	"cssclass"=>""
	), $atts));
	if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
    $html = "";
	$html .=  '<div class="testimonial '.$cssclass.'" '.$dp_animation.'><div class="testimonials"><p>'.do_shortcode($content).'</p></div>';
	$html .= '<div class="testimonials-bg"></div>';	
	$html .= '<div class="testimonials-author">'.$name;
	if ($position != '') {
	$html .= ', <span>'.$position.'</span>';
	}
	$html .= '</div></div>';
    return $html;

}

function testimonial2($atts, $content = null) {
	extract(shortcode_atts(array(
	"img" =>"",
	"name"=>"",
	"position"=>"",
	"dp_animation" => "",
	"cssclass"=>""
	), $atts));
	if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
	if (is_numeric($img)) {
            $image_src = wp_get_attachment_url($img);
        } else {
            $image_src = $img;
        }
    $html = "";   
	$html .= '<div class="'.$cssclass.'" '.$dp_animation.'>';
	$html .= '<div class="happy-clients-photo"><img alt="" src="'.$image_src.'"></div>';
	$html .= '<div class="happy-clients-content">';
	$html .= '<div class="happy-clients-cite">'.do_shortcode($content).'</div>';
	$html .= '<div class="happy-clients-author">'.$name;
		if ($position != '') {
		$html .= ', <strong>'.$position.'</strong>';
		}
	$html .= '</div></div></div>';
	return $html;
}
function servicebox($atts, $content = null) {
	extract(shortcode_atts(array(
	"type" => "icon",
	"title"=>"",
	"img" =>"",
	"icon"=>"",
	"icon_color" => "",
	"back_bgcolor" => "",
	"dp_animation" => "",
	"el_class"=>""
	), $atts));
	if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
	if (is_numeric($img)) {
            $image_src = wp_get_attachment_url($img);
        } else {
            $image_src = $img;
        }
	$iconstyle = "";
	if ($icon_color != "")  $iconstyle = 'style= "color:'.$icon_color.'"';
	$backstyle = "";
	if ($back_bgcolor != "")  $backstyle = 'style= "background-color:'.$back_bgcolor.'; border-color:'.$back_bgcolor.'; "';
    $html = "";   
	$html .= '<div class="services-box services-box-animated '.$el_class.'" '.$dp_animation.'>';
	$html .= '<div class="inner">';
	$html .= '<div class="front">';
	if ($type == "icon") {
	$html .= '<div class="icon-container"><i class="'.$icon.'" '.$iconstyle.'></i></div>';
	} else {
	$html .= '<div class="image-container"><img src="'.$image_src.'" alt=""></div>';
	}
	$html .= '<h3>'.$title.'</h3>';
	$html .= '</div>';
	$html .= '<div class="back"  '.$backstyle.'>';
	$html .= '<h3>'.$title.'</h3>';
	$html .= '<p>'.do_shortcode($content).'</p>';
	$html .= '</div></div></div>';
	return $html;
}
function featuredbox($atts, $content = null) {
	extract(shortcode_atts(array(
	"type" => "centered",
	"icon"=>"",
	"icon_badge" => "",
	"title" => "Title",
	"icon_color" => "",
	"button_text" => "Read more",
	"button_link" => "",
	"dp_animation" => "",
	"cssclass"=>""
	), $atts));
	$css1 = "";
	$css2 = "";
	$css3 = "";
	$css4 = "";
	if ($dp_animation != "") $dp_animation = ' data-animated ="'.$dp_animation.'"';
	if ($icon_badge =="") $css4 ="nobadge";
	if ($type == "centered") {
		$css1= "icons-center";
		$css2 = "featured-desc-center";
		$css3 = "medium";
		if ($icon_badge == "circle")  $css1= "circle-1";
		if ($icon_badge == "square")  $css1= "square-1";
	}
	if ($type == "left") {
		$css1 = "icon-lefted";
		$css2 = "featured-desc-2";
		$css3 = "small";
		if ($icon_badge == "circle")  $css1= "circle-2";
		if ($icon_badge == "square")  $css1= "square-2";
		if ($icon_badge == "circle-bordered")  $css1= "circle-2-line";
		if ($icon_badge == "square-bordered")  $css1= "square-2-line";
	}
    $html = "";   
	$html .= '<div class="featured-box '.$css4.'" '.$dp_animation.'>';
    $html .= '<div class="'.$css1.'"><i class="'.$icon.'"></i></div>';
    $html .= '<div class="'.$css2.'">';
    $html .= '<h3>'.$title.'</h3>';
    $html .= '<p>'.do_shortcode($content).'</p>';
	if ($button_link != "") {
    $html .= '<a href="'.$button_link.'" class="button_sc line '.$css3.'"><span>'.$button_text.'</span></a> ';
	}
	$html .= '</div></div>';
	return $html;
}

function dp_portfolio_grid($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'items' => '4',
		'columns' => '4',
		'categories' => '',
		'filter' => '',
		'links' => 'zoom'
		), $atts));
		
		 
	$return_html = dp_print_recent_projects_grid($items, $columns,$categories,$filter,$links);
	
	return $return_html;	}
	
function dp_posts_grid($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'perpage' => '4',
		'columns' => '4',
		'categories' => '',
		'filter' => ''
		), $atts));
		
		 
	$return_html = dp_print_recent_post_grid($perpage, $columns,$categories,$filter);
	
	return $return_html;	}

function dp_owl_carousel($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'slideshow' => '',
		'items' => '4',
		'itemsdesktop' => '',
		'itemsdesktopsmall' => '',
		'itemstablet' => '',
		'itemsmobile' => '',
		'autoplay' => '',
		'pagination' => '',
		'navigation' => ''
		), $atts));
		$query_string = 'post_type=slide&order=ASC&orderby=menu_order&nopaging=true';
	
	if($slideshow != 'All'){
		
		$query_string .= "&slideshows=$slideshow";
		
	}
		$slideshow_query = new WP_Query($query_string);
		$items = 'items : '.$items.',';
		if ($itemsdesktop !='')  $itemsdesktop = 'itemsDesktop : [1199,'.$itemsdesktop.'],';
		 else $itemsdesktop = 'itemsDesktop : false,';
		if ($itemsdesktopsmall !='')  $itemsdesktopsmall = 'itemsDesktopSmall : [980,'.$itemsdesktopsmall.'],';
		 else $itemsdesktopsmall = 'itemsDesktopSmall : false,';
		if ($itemstablet !='')  $itemstablet = 'itemsTablet : [768,'.$itemstablet.'],';
		 else $itemstablet = 'itemsTablet : false,';
		if ($itemsmobile !='')  $itemsmobile = 'itemsMobile : [479,'.$itemsmobile.'],';
		 else $itemsmobile = 'itemsMobile : false,';
		if ($autoplay !='') $autoplay = 'autoPlay: '.$autoplay.',';
		 else $autoplay = 'autoplay : false,';
		if ($navigation =='yes') $navigation = 'navigation : true,';
		 else $navigation = 'navigation : false,';
		if ($pagination =='yes') $pagination = 'pagiantion: true,';
		 else $pagination = 'pagination : false,';
		
		$id = "carousel".mt_rand();
		$carouselout = '<script type="text/javascript">
						jQuery(document).ready(function() {
 						jQuery("#'.$id.'").owlCarousel({'
        				.$autoplay.$items.$itemsdesktop.$itemsdesktopsmall.$itemstablet.$itemsmobile.$navigation.$pagination.
        				'navigationText : ["",""] });});
						</script>';
		$carouselout .= '<div id="'.$id.'" class="owl-carousel">';
		
if($slideshow_query->have_posts()) {
		
			while ($slideshow_query->have_posts()) {
				$carouselout .= '<div class="item">';
            	$slideshow_query->the_post();
        		
        		global $post;
        		$slide_type = get_post_meta($post->ID, 'slide_type', true);       			
        		

                if($slide_type == 'i') {

                    if ( has_post_thumbnail() ) {
 					$imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					if( get_post_meta($post->ID, 'slide_link', true) ){$carouselout .= '<a href="'.get_post_meta($post->ID, 'slide_link', true).'" title="">'."\n";} 
                    $carouselout .='<img src="'.$imageurl.'" title="" alt="" />'."\n";
					if( get_post_meta($post->ID, 'slide_link', true) ){$carouselout .= '</a>'."\n";}
                    }
                } // end image slide_type
        		else  {
				$carouselout .=	 apply_filters( 'the_content', get_the_content() );
				}
		$carouselout .= '</div>'."\n";    
		
			} //End while
		$carouselout .= '</div>'."\n";    
		} else {
		
			$carouselout .= '<p class="warning">'."\n";
			$carouselout .= __("You don't have any Slides to display.", 'dp-theme');
			$carouselout .= '</p>'."\n";
			
		}


	 return $carouselout;
	}		
function dp_anchor( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'name' => ''
		), $atts));
	$output='<div class="dp-anchor" id="'.$name.'"></div>';
	return $output;
}

 		add_shortcode('one_half', 'dp_column');
		add_shortcode('one_third', 'dp_column');
		add_shortcode('one_fourth','dp_column');
		add_shortcode('one_fifth', 'dp_column');
		add_shortcode('one_sixth', 'dp_column');
		add_shortcode('two_third', 'dp_column');
		add_shortcode('three_fourth', 'dp_column');
		add_shortcode('two_fifth', 'dp_column');
		add_shortcode('three_fifth', 'dp_column');
		add_shortcode('four_fifth', 'dp_column');
		add_shortcode('five_sixth', 'dp_column');
		add_shortcode('one_half_last', 'dp_column');
		add_shortcode('one_third_last', 'dp_column');
		add_shortcode('one_fourth_last','dp_column');
		add_shortcode('one_fifth_last', 'dp_column');
		add_shortcode('one_sixth_last', 'dp_column');
		add_shortcode('two_third_last' ,'dp_column');
		add_shortcode('three_fourth_last', 'dp_column');
		add_shortcode('two_fifth_last', 'dp_column');
		add_shortcode('three_fifth_last','dp_column');
		add_shortcode('four_fifth_last','dp_column');
		add_shortcode('five_sixth_last','dp_column');
		add_shortcode('h1', 'typo_h1');
		add_shortcode('h2', 'typo_h2');
		add_shortcode('h3', 'typo_h3');
		add_shortcode('h4', 'typo_h4');
		add_shortcode('h5', 'typo_h5');
		add_shortcode('h6', 'typo_h6');
		add_shortcode('contentheading', 'typo_contentheading');
		add_shortcode('componentheading', 'typo_componentheading');
    	add_shortcode('div', 'typo_div');
		add_shortcode('div2', 'typo_div2');
    	add_shortcode('div3', 'typo_div3');
		add_shortcode('box', 'typo_alert_box');
		add_shortcode('icon', 'typo_icon');
		add_shortcode('pre', 'typo_pre');
    	add_shortcode('blockquote', 'typo_blockquote');
		add_shortcode('legend1', 'typo_legend1');
		add_shortcode('legend2', 'typo_legend2');
		add_shortcode('legend3', 'typo_legend3');
		add_shortcode('list', 'typo_list');
		add_shortcode('li', 'typo_li');
		add_shortcode('ord_list', 'typo_ord_list');
		add_shortcode('discnumber', 'typo_discnumber');
		add_shortcode('bignumber', 'typo_bignumber');
		add_shortcode('emphasis', 'typo_emphasis');
		add_shortcode('emphasis', 'typo_emphasis');
		add_shortcode('emphasisbold', 'typo_emphasisbold');
		add_shortcode('emphasisbold2', 'typo_emphasisbold2');
		add_shortcode('dropcap', 'typo_dropcap');
		add_shortcode('important', 'typo_important');
		add_shortcode('underline', 'typo_underline');
		add_shortcode('bold', 'typo_bold');
		add_shortcode('italic', 'typo_italic');
		add_shortcode('clear', 'typo_clear');
		add_shortcode('readon', 'typo_readon');
		add_shortcode('readon2', 'typo_readon2');
		add_shortcode('clearboth', 'typo_clearboth');
		add_shortcode('divider', 'typo_divider');
		add_shortcode('divider_top', 'typo_divider_top');
		add_shortcode('space', 'typo_space');
		add_shortcode('divider_padding', 'typo_divider_padding');
		add_shortcode('divider_line', 'typo_divider_line');
		add_shortcode('button', 'typo_button');
		add_shortcode('btn', 'typo_button_standart');
		add_shortcode('tabs', 'dp_tabs');
		add_shortcode('frame_left', 'dp_frame_left');
		add_shortcode('frame_right', 'dp_frame_right');
		add_shortcode('frame_caption', 'dp_frame_caption');
		add_shortcode('table_style', 'dp_table');
		add_shortcode('photo_gallery', 'dp_photo_gallery');
        add_shortcode('image', 'dp_photo_gallery_lines');
		add_shortcode('lightbox', 'lightbox_shortcode');
		add_shortcode('slideshow', 'dp_slideshow');
		add_shortcode('carousel', 'dp_carousel');	
		add_shortcode('pricing_column', 'dp_pricing_column');
		add_shortcode('gmap', 'dp_googlemap');
		add_shortcode('chart', 'dp_chart');
		add_shortcode('vimeo', 'dp_vimeo');
		add_shortcode('youtube', 'dp_youtube');
		add_shortcode('html5video', 'dp_html5video');
		add_shortcode('mp3', 'dp_mp3');
		add_shortcode('soundcloud', 'dp_soundcloud');
		add_shortcode('recent_posts', 'dp_recent_posts');
		add_shortcode('popular_posts', 'dp_popular_posts');
		add_shortcode('social_links', 'dp_social_links');
        add_shortcode('social_link', 'dp_social_links_item');
		add_shortcode('teambox', 'dp_team_box');
		add_shortcode('dp_thumb', 'dp_thumb');
		add_shortcode('piechart', 'piechart');
		add_shortcode('piechart2', 'piechart2');
		add_shortcode('counter', 'counter');
		add_shortcode('progress_bar', 'progressbar');
		add_shortcode('headline', 'headline');
		add_shortcode('faq', 'dp_faq');
		add_shortcode('testimonial', 'testimonial');
		add_shortcode('testimonial2', 'testimonial2');
		add_shortcode('servicebox', 'servicebox');
		add_shortcode('featuredbox', 'featuredbox');
		add_shortcode('portfolio_grid', 'dp_portfolio_grid');
		add_shortcode('blog_grid', 'dp_posts_grid');
		add_shortcode('owl_carousel', 'dp_owl_carousel');
		add_shortcode('anchor', 'dp_anchor');

/*EOF*/