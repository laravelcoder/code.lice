<?php

/**
 *
 * Single page
 *
 **/

global $dynamo_tpl;
$fullwidth = true;
dp_load('header');
dp_load('before', null, array('sidebar' => false));

?>

<div id="dp-mainbody">
	<?php while ( have_posts() ) : the_post(); ?>
	
		<article id="post-<?php the_ID(); ?>" <?php post_class(is_page_template('template.fullwidth.php') ? ' page-fullwidth' : null); ?>>
        <ul class="item-nav">
            <?php previous_post_link("<li class='prev'> %link </li>", "<i class='icon-left-open-1'></i>"); ?>
            <?php if (get_option($dynamo_tpl->name . '_portfolio_default_page') != '') : ?>
            <li class="all"><a href="<?php echo get_option($dynamo_tpl->name . '_portfolio_default_page'); ?>" title="All items"><i class='icon-th-3'></i></a></li>
            <?php endif ?>
            <?php next_post_link("<li class='next'> %link </li>", "<i class='icon-right-open-1'></i>"); ?>
        </ul>
        <div class="clearboth"></div>
        <?php if (get_post_meta($post->ID, 'item_type', true)== 'c') { 
		$content = get_the_content();
		$content = preg_replace('/\[gallery ids=[^\]]+\]/', '',  $content );
		$content = apply_filters('the_content', $content );
		echo $content;
		} else {?>
        
        <div class="two_third">
        <header>
		<?php include('layouts/content.portfolio.header.php'); ?>
		</header>
       	<?php if (get_post_meta($post->ID, 'item_type', true)== 'v') : 	?>
        <?php if (get_post_meta($post->ID, 'item_video', true) != '') : ?>
        <?php echo wp_oembed_get( get_post_meta($post->ID, "item_video", true) ); ?>
        <?php else :?>
		<div class="help">
        <div class="typo-icon">
        This is a portfolio item <b>video</b> type. But you have not given video URL. Please fill in this field. 
        </div>
        </div>        
		<?php endif ?>
        <?php elseif (get_post_meta($post->ID, 'item_type', true)== 'g'):?>
        <?php dynamo_add_flex();?>
		<div class="content">
			<?php
				$gallery = get_post_gallery( $post->ID, false );
				$images = explode(",", $gallery['ids']);
			?>
			
			<?php if($gallery) { ?>
			<div id="gallery" class="flexgallery">
            
				<?php 
					$id = "flexslider_".mt_rand();
					$output = '<script type="text/javascript">'."\n";
					$output .= "   jQuery(window).load(function() {"."\n"; 
					$output .=  "jQuery('#".$id."').flexslider({"."\n";
					$output .=  '    animation: "slide",'."\n";
					$output .=  '    slideshowSpeed:"5000",'."\n";
					$output .=  '    controlNav: false,'."\n";
					$output .=  '    pauseOnHover: true,'."\n";
					$output .=  '    smoothHeight: true'."\n";
					$output .=  "  });"."\n";      
					$output .= "   });"."\n";
					$output .= "</script>"."\n";
					echo $output; 

				?>
                <div class="flexslider" id="<?php echo $id; ?>"><ul class="slides">
				<?php 
					foreach($images as $image) : 
					$src = wp_get_attachment_image_src( $image, 'full' );
				?>
				<li><div>
					<img src="<?php echo $src[0]; ?>" />
					
				</div></li>
				<?php 
					endforeach;
				?>
			</ul></div>	
			</div>
		  	<?php } else { ?>
        <div class="notification warning"><p><span>Warning! </span> This is a portfolio item <b>gallery</b> type. But you have not create gallery in post content. </p></div>
            <?php }?>
		</div>
        
        <?php else : ?>
<?php        
$params = get_post_custom();
$params_image = isset($params['dynamo-post-params-featuredimg']) ? esc_attr( $params['dynamo-post-params-featuredimg'][0] ) : 'Y';
?>

<?php if(is_single() && $params_image == 'Y') : ?>
	<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_dynamo-featured-video", true) != '') : 
	?>
	
	<?php echo wp_oembed_get( get_post_meta(get_the_ID(), "_dynamo-featured-video", true) ); ?>
	
	<?php elseif(has_post_thumbnail()) : ?>
	<figure class="featured-image">
		<?php the_post_thumbnail(); ?>
		
		<?php if(is_single()) : ?>
			<?php echo dp_post_thumbnail_caption(); ?>
		<?php endif; ?>
	</figure>
	<?php endif; ?>
<?php elseif(!is_single()) : ?>
	<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_dynamo-featured-video", true) != '') : 
	?>
	
	<?php echo wp_oembed_get( get_post_meta(get_the_ID(), "_dynamo-featured-video", true) ); ?>
	
	<?php elseif(has_post_thumbnail()) : ?>
	<figure class="featured-image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
		
		<?php if(is_single()) : ?>
			<?php echo dp_post_thumbnail_caption(); ?>
		<?php endif; ?>
	</figure>
	<?php endif; ?>
<?php endif; ?>
        <?php endif ?>
        </div>
        <div class="one_third_last">
        <div class="headline heading-line "><h3><?php echo __("Project details", 'dp-theme'); ?><?php if (get_option($dynamo_tpl->name . '_portfolio_like_state', 'Y') == 'Y') echo getPostLikeLink( $post->ID ) ?></h3></div>
        
        <div class="content">
		<?php $content = get_the_content();
$content = preg_replace('/\[gallery ids=[^\]]+\]/', '',  $content );
$content = apply_filters('the_content', $content );
echo $content;
	    ?>
        <?php if( get_post_meta($post->ID, 'item_desc', true) ): ?>
        <div class="headline heading-line "><h3><?php echo __("Job description", 'dp-theme'); ?></h3></div>
        <p><?php echo get_post_meta($post->ID, 'item_desc', true); ?></p>
        <?php endif ?>
        <div class="space10"></div>
        <?php if( get_post_meta($post->ID, 'item_date', true) || get_post_meta($post->ID, 'item_client', true) ) :
			 ?>
		<?php if( get_post_meta($post->ID, 'item_date', true) ): ?>
        <div class="date"><?php echo get_post_meta($post->ID, 'item_date', true); ?></div>
        <?php endif ?>
        <?php if( get_post_meta($post->ID, 'item_client', true) ): ?>
        <div class="client"><?php echo get_post_meta($post->ID, 'item_client', true); ?></div>
        <?php endif ?>
        <div class="space15"></div>
        <?php endif ?>
        <div class="clearboth"></div>
        <?php if( get_post_meta($post->ID, 'item_link', true) ): ?>
        <div class="space10"></div>
        <p><a class="readon" href="<?php echo get_post_meta($post->ID, 'item_link', true); ?>" target="_blank"><span><?php echo  __("Launch Project", 'dp-theme') ?></span></a></p>       
        <?php endif ?>
		</div>
        </div>
        <?php } ?>
        <div class="clearboth"></div>
	<?php include('layouts/content.portfolio.footer.php'); ?>
	<?php if(get_option($dynamo_tpl->name . '_portfolio_show_comments_on_portfolio', 'Y') == 'Y') : ?>
    <?php comments_template( '', true ); ?>
	<?php endif; ?>
   

<?php endwhile; // end of the loop. ?>
</article>
<?php if (have_related_projects($post->ID)) { ?>
 		<div class="headline heading-line "><h3><?php echo __("Related Projects", 'dp-theme'); ?></h3></div>

 <div class="related-projects portfolio-four">
 	<div class="related-projects-inner">
        <?php dp_print_related_projects_grid($post->ID,3); ?>
	</div>
   </div>
  <?php } ?>
        <div class="clearboth"></div>
        <div class="space30"></div>
   </div>


<?php

dp_load('after-nosidebar', null, array('sidebar' => false)); 
dp_load('footer');

// EOF