<?php

/**
 *
 * The template for displaying posts in the Gallery Post Format on index and archive pages
 *
 **/

global $dynamo_tpl,$post,$more;

?>
		<?php if ( is_search() || is_archive() || is_tag() || is_home() ) { 
				 if (get_option($dynamo_tpl->name . '_archive_style','big')=='big') {get_template_part( 'article-blog-large');}
				 if (get_option($dynamo_tpl->name . '_archive_style','big')=='small') {get_template_part( 'article-blog-medium'); }
		} else { 
		?>       
        <!--If is single --> 
        <article id="post-<?php the_ID(); ?>" <?php post_class('large'); ?>>
		<?php
				// Load images
				$images = get_children(
					array(
						'numberposts' => -1, // Load all posts
						'orderby' => 'menu_order', // Images will be loaded in the order set in the page media manager
						'order'=> 'ASC', // Use ascending order
						'post_mime_type' => 'image', // Loads only images
						'post_parent' => $post->ID, // Loads only images associated with the specific page
						'post_status' => null, // No status
						'post_type' => 'attachment' // Type of the posts to load - attachments
					)
				);
		         if($images): 
		dynamo_add_flex();
		?>
			<div class="flexgallery">
                    			<?php 
					$gallery_id = "flexslider_".mt_rand();
					$output = '<script type="text/javascript">'."\n";
					$output .= "   jQuery(window).load(function() {"."\n"; 
					$output .=  "jQuery('#".$gallery_id."').flexslider({"."\n";
					$output .=  '    animation: "slide",'."\n";
					$output .=  '    slideshowSpeed:"5000",'."\n";
					$output .=  '    pauseOnHover: true,'."\n";
					$output .=  '    smoothHeight: true'."\n";
					$output .=  "  });"."\n";      
					$output .= "   });"."\n";
					$output .= "</script>"."\n";
					echo $output; 
				?>

            <div class="flexslider" id="<?php echo $gallery_id; ?>"><ul class="slides">
				<?php 
					foreach($images as $image) : 
				?>
				<li><figure>
					<img src="<?php echo $image->guid; ?>" alt="<?php echo $image->post_title; ?>" title="<?php echo $image->post_title; ?>" />
					<?php if($image->post_title != '' || $image->post_content != '' || $image->post_excerpt != '') : ?>
					<figcaption>
						<h3><?php echo $image->post_title; // get the attachment title ?></h3>
						<p><?php echo $image->post_content; // get the attachment description ?></p>
						<small><?php echo $image->post_excerpt; // get the attachment caption ?></small>
					</figcaption>
					<?php endif; ?>
				</figure></li>
				<?php 
					endforeach;
				?>
			</ul></div>
			</div>
		  	<?php endif; 

		if(get_option($dynamo_tpl->name . '_postmeta_date_state') == 'Y' || get_option($dynamo_tpl->name . '_post_like_state') == 'Y') { ?>
		<div class="date-container">
        <div class = "inner">
        <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state') == 'Y') { ?>
        <dt class="month">
     	<h4><?php echo mysql2date('M',get_post()->post_date); ?></h4>
    	</dt>
        <dt class="day">        
         <h2><?php echo mysql2date('j',get_post()->post_date); ?></h2>
        </dt>
        <?php } ?>
   		<?php if(get_option($dynamo_tpl->name . '_post_like_state') == 'Y') { ?>
        <dt class="like"><?php echo getPostLikeLink( $post->ID ); ?></dt>
        <?php } ?>
        </div>
        </div>
     <?php } ?>
      <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state') == 'Y' || get_option($dynamo_tpl->name . '_post_like_state') == 'Y') { ?>
		<div class="shifted-content">
      <?php } ?>
		<?php dp_post_meta(); ?>
        <?php if (get_post_format() != 'link' && get_post_format() != 'quote' && get_post_format() != 'status' ){?>
		<header>
        <h2>
        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'dp-theme' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
        <?php the_title(); ?>
        </a>
			<?php if(is_sticky()) : ?>
            <sup>
                <?php _e( 'Featured', 'dp-theme' ); ?>
            </sup>
            <?php endif; ?>
        </h2>
	
		</header>
     <?php } ?>
        
		<section class="content">

			<?php $content = get_the_content();
			$content = preg_replace('/\[gallery ids=[^\]]+\]/', '',  $content );
			$content = apply_filters('the_content', $content );
			echo $content;
			?>			
			<?php dp_post_links(); ?>
		</section>
	
      <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state') == 'Y' || get_option($dynamo_tpl->name . '_post_like_state') == 'Y') { ?>
		</div>
      <?php }  ?>
		<?php get_template_part( 'layouts/content.post.footer' ); ?>
	</article>
      <?php } ?>