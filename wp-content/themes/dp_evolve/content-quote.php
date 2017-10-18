<?php

/**
 *
 * The default template for displaying content
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
		include(dynamo_file('layouts/content.post.featured.php')); 
     	if(get_option($dynamo_tpl->name . '_postmeta_date_state','Y') == 'Y' || get_option($dynamo_tpl->name . '_post_like_state','Y') == 'Y') { ?>
		<div class="date-container">
        <div class = "inner">
        <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state','Y') == 'Y') { ?>
        <dt class="month">
     	<h4><?php echo mysql2date('M',get_post()->post_date); ?></h4>
    	</dt>
        <dt class="day">        
         <h2><?php echo mysql2date('j',get_post()->post_date); ?></h2>
        </dt>
        <?php } ?>
   		<?php if(get_option($dynamo_tpl->name . '_post_like_state','Y') == 'Y') { ?>
        <dt class="like"><?php echo getPostLikeLink( $post->ID ); ?></dt>
        <?php } ?>
        </div>
        </div>
     <?php } ?>
      <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state','Y') == 'Y' || get_option($dynamo_tpl->name . '_post_like_state','Y') == 'Y') { ?>
		<div class="shifted-content">
      <?php } ?>
		<?php dp_post_meta(); ?>
        <?php if (get_post_format() != 'link' && get_post_format() != 'quote' && get_post_format() != 'status' ){?>
		<header>
        <h2>
        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr_e( 'Permalink to %s', 'dp-theme' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
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
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'dp-theme' ) ); ?>
			
			<?php dp_post_links(); ?>
		</section>
	
      <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state','Y') == 'Y' || get_option($dynamo_tpl->name . '_post_like_state','Y') == 'Y') { ?>
		</div>
      <?php }  ?>
		<?php get_template_part( 'layouts/content.post.footer' ); ?>
	</article>
      <?php } ?>
