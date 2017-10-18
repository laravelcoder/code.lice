<?php

/**
 *
 * The default template for displaying content
 *
 **/

global $dynamo_tpl,$post,$more;

?>	
	<article id="post-<?php the_ID(); ?>" <?php post_class('medium'); ?>>
        <?php if (has_post_thumbnail() || get_post_meta(get_the_ID(), "_dynamo-featured-video", true) != '') { ?>
        <div class="one_half">
        <div class="date-container">
        <dt class="day">
         <?php echo mysql2date('j',get_post()->post_date); ?>
        </dt>
        <dt class="month">
     	<?php echo mysql2date('M',get_post()->post_date); ?>
    	</dt>
        </div>
			<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_dynamo-featured-video", true) != '') : 
	?>
	
	<?php echo wp_oembed_get( get_post_meta(get_the_ID(), "_dynamo-featured-video", true) ); ?>
	
	<?php elseif(has_post_thumbnail()  && get_post_format() != 'gallery') : ?>
	<figure class="featured-image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
	</figure>
	<?php endif; ?>
    <?php if (get_post_format() == 'gallery')  {  
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
			?>
        <?php if($images): 
		dynamo_add_flex();
		?>
        <div class="clearboth"></div>
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
				}
			?>
        </div>
        <div class="one_half_last">
        <header>
        <?php dp_post_meta(); ?>
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
		<section class="summary <?php echo get_post_format(); ?>">
        <?php
		$post_format = get_post_format();
		switch ($post_format) {
		   case 'link':
				$more = 0;
				echo '<i class="icon-link-4"></i>';
				the_content('');
				$more =1;
				 break;
		   case 'audio':
				$more = 0;
				the_content('');
				$more =1;
				 break;
		   case 'status':
				echo '<i class="icon-chat-6"></i>';
				the_content('');
				$more =1;
				 break;
		   case 'video':
				$more = 0;
				the_content('');
				$more =1;
				 break;
		   case 'quote':
				echo '<i class="icon-quote"></i>';
				the_content('');
				$more =1;
				 break;
		  default:		
		  the_excerpt(); 
		  }
		?>
		</section>
        </div>
		<?php } else {?>
        <header>
        <?php dp_post_meta(); ?>
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
		<section class="summary <?php echo get_post_format(); ?>">
        <?php
		$post_format = get_post_format();
		switch ($post_format) {
		   case 'link':
				$more = 0;
				echo '<i class="icon-link-4"></i>';
				the_content('');
				$more =1;
				 break;
		   case 'audio':
				$more = 0;
				the_content('');
				$more =1;
				 break;
		   case 'status':
				echo '<i class="icon-chat-6"></i>';
				the_content('');
				$more =1;
				 break;
		   case 'video':
				$more = 0;
				the_content('');
				$more =1;
				 break;
		   case 'quote':
				echo '<i class="icon-quote"></i>';
				the_content('');
				$more =1;
				 break;
		  default:		
		  the_excerpt(); 
		  }
		?>
		</section>
        
        <?php } ?> 
		<?php get_template_part( 'layouts/content.post.footer' ); ?>
	</article>
