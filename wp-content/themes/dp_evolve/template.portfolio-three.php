<?php

/*
Template Name: Portfolio 3 columns
*/
 
global $dynamo_tpl;

$fullwidth = true;

dp_load('header');
dp_load('before', null, array('sidebar' => false));
	$params = get_post_custom();
	$params_category = isset($params['dynamo-post-params-category']) ? esc_attr( $params['dynamo-post-params-category'][0] ) : '';
	$selected_categories = array();
	if ($params_category != '') {$selected_categories = explode(',', $params_category);
		} else {
		$portfolio_category = get_terms('portfolios');
			if($portfolio_category):
			foreach($portfolio_category as $portfolio_cat):
			array_push($selected_categories,$portfolio_cat->slug);
			endforeach;
			endif;
		}
	$item_per_page = get_option($dynamo_tpl->name . "_portfolio_items_per_page");
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args = array(
				'post_type' => 'portfolio',
				'paged' => $paged,
				'posts_per_page' => $item_per_page,
				'orderby' => 'menu_order date',
				'order' => 'ASC',
				'tax_query' => array(
        array(
            'taxonomy' => 'portfolios',
            'field' => 'slug',
            'terms' => $selected_categories
        )
    )
						
				);
			$gallery = new WP_Query($args);

?>

<section id="dp-mainbody" class="portfolio portfolio-three">
<?php the_post(); ?>
<section class="content">
		<?php the_content(); ?>
		
		<?php dp_post_links(); ?>
	</section>
        <div style="clear:both;"></div>
		<?php
		$portfolio_category = get_terms('portfolios');
		if($portfolio_category){
		?>
        <div class="centered-block-outer">
 			<div class="centered-block-middle">
  				<div class="centered-block-inner">
		<ul class="portfolio-tabs">
            <li class="active"><a data-filter="*" href="#"><?php _e('All', 'dp-theme'); ?></a></li>
			<?php foreach($portfolio_category as $portfolio_cat): ?>
            <?php if (in_array($portfolio_cat->slug, $selected_categories)) { ?>
    		<li><a data-filter=".<?php echo $portfolio_cat->slug; ?>" href="#"><?php echo $portfolio_cat->name; ?></a></li>
			<?php } ?>
			<?php endforeach; ?>
		</ul>
        </div>
        </div>
        </div>
		<?php } ?>
                <div style="clear:both;"></div>
<div class="portfolio-wrapper">
			<?php
			while($gallery->have_posts()): $gallery->the_post();
				if(has_post_thumbnail()):
			?>
			<?php
			$item_classes = '';
			$item_desc= get_post_meta($post->ID, 'item_short_desc', true);
			if ($item_desc =='') $item_desc = '&nbsp;';
			$item_cats = get_the_terms($post->ID, 'portfolios');
			if($item_cats):
			foreach($item_cats as $item_cat) {
				$item_classes .= $item_cat->slug . ' ';
				$category = $item_cat->name;
			}
			endif;
			?>
            <div class="portfolio-item-wrapper">
				<div class="portfolio-item <?php echo $item_classes; ?>">
				<?php if(has_post_thumbnail()):
				$title = $post->post_title;
				$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "portfolio-three" ); 
				?>
                <div class="mediaholder"> <a href="<?php echo $image; ?>" rel="dp_lightbox"> 
                         <img alt="" src="<?php echo $thumb[0]; ?>">
                         <div class="hovermask">
                         <div class="hovericon"><i class="ss-search"></i></div>
                         </div>
                          </a> 
                </div>
                <figcaption class="item-description">
                                   <a href="<?php the_permalink(); ?>"><h5><?php echo $title ?></h5></a>
                                   <span><?php echo $item_desc ?></span>
                                   <?php if (get_option($dynamo_tpl->name . '_portfolio_like_state', 'Y') == 'Y') echo getPostLikeLink( $post->ID ) ?>
                </figcaption>              
				<?php endif; ?>
				<?php
				 	
				 ?>
				</div>
    		</div>
    
			<?php endif; endwhile; ?> 
			
		</div>
       <?php dp_content_nav($gallery->max_num_pages, $range = 2); ?>
        <div class="clearboth"></div>
        <?php include('layouts/content.portfolio.footer.php'); ?>
        </section>
        <div class="space40"></div>
<?php

dp_load('after-nosidebar', null, array('sidebar' => false));
dp_load('footer');

// EOF