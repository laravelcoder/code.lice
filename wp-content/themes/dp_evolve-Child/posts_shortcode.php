<?php



function make_post_boxes( $atts , $content = '' ){


    $atts = shortcode_atts(
		array(
			'category' => null,
      'text' => false,
      'anchor' => '',
		),
    $atts, 'posts_boxes' );

    $args = array(
      'numberposts' => 3,
    );
    $args['cat'] = (isset($atts['category'])) ? $atts['category'] : null;



//  $content = 'COMPLETE SERVICE';
  $html = '<div class="wpb_row vc_row-fluid" style="background-position:top center!important;margin-bottom:50px;"><div class="block greyjoy"><h3>'.$content.'<a name="'.$atts["anchor"].'">&nbsp;</a></h3> </div>';

  $posts = wp_get_recent_posts( $args);
//var_dump($posts);
  foreach ( $posts as $curr_post ){

//    $excerpt = get_the_excerpt($curr_post['ID']);

    $thumb_id = get_post_thumbnail_id($curr_post['ID']);
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id(  $curr_post['ID'] ), 'large' );
    $post_img = ( has_post_thumbnail($curr_post['ID']) ) ? $thumb : array('http://lca2016.staging.ribbitt.com/wp-content/uploads/2016/08/download.png') ;
    $title = $curr_post["post_title"];
    $post_href = get_permalink($curr_post["ID"]);
    if ($atts['text']){
    $excerpt = wp_trim_words(strip_shortcodes($curr_post["post_content"]), 13, '...');
  }else{
    $excerpt = '';
  }


$yeah_boi = "post-". $curr_post["ID"];

$box = <<<BOX
<div class="wpb_column vc_column_container vc_col-sm-4 post-box $yeah_boi"><div class="vc_column-inner" style="height:100%;"><div class="wpb_wrapper" style="height:100%;">
	<div class="wpb_text_column wpb_content_element " style="height:100%;">
		<div class="wpb_wrapper" style="position:relative;padding-bottom: 20px;height:100%;">
<p><div class="img-sizer" style="background: url($post_img[0]); background-size: cover; min-height:200px; width:100%;">&nbsp;</div></p>
<h3 class="green-title" style="margin-top: 15px;"><a href="$post_href" target="_blank">$title</a></h3>
<p class="post-excerpt">$excerpt</p>
<p class="post-btn-container"><a class="btn dark-grey" href="$post_href" target="_blank"><i class="fa fa-plus-circle"></i> Read More</a></p>

		</div>
	</div> </div></div></div>
BOX;

$html .= $box;
  }

  $html .= '</div>';


  return $html;
}


add_shortcode('circle','make_circles');
function make_circles( $atts , $content = '' ){
  $html = '';

  $atts = shortcode_atts(
	array(
    'color' => '#FFFFFF',
		'background-color' => '#5BC500',
    'size' => '20',
    'label' => '1'
 	),
  $atts, 'circles' );
  $color = $atts['color'];
  $size = $atts['size'];
  $gpsize = $size*2;
  $bgcolor = $atts['background-color'];
//  $padd_diff = $size/2

  $size .= 'px';
  $gpsize .= 'px';
  $label = $atts['label'];


$box = <<<BOX
<div class="circle-wrapper">
  <div class="circle-grandparent" style="width: $gpsize; height: $gpsize;">
  <div class="circle-parent" style=" background-color: $bgcolor;">
  <div class="circle" style="color: $color; width: $size; height: $size; font-size: $size; padding-bottom: 20%; padding-top: 30%; ">
  $label
  </div></div></div>
  <div class='circle-text'>
    <p style="line-height: $gpsize;">
      $content
    </p>
  </div>
</div>
<div class="clearfix"></div>
BOX;

  $html .= $box;

  return $html;
}
