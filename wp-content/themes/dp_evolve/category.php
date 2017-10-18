<?php

/**
 *
 * Category page
 *
 **/

global $dynamo_tpl,$post,$more;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody" class="category-page">
	<?php if ( have_posts() ) : ?>
    <?php if(get_option($dynamo_tpl->name . '_archive_slideshow','N') == 'Y') { 
	$cat_id = get_query_var('cat');
	$cat_meta = get_option("category_$cat_id");
	echo '<section>';
	if ($cat_meta['slideshow']!='No') dp_category_slideshow($cat);
	echo '</section>';
		 }?>
		<?php
			$category_description = category_description();
			if ( ! empty( $category_description ) )
				echo apply_filters( 'category_archive_meta', '<section class="intro">' . $category_description . '</section>' );
		?>
	
		<?php while ( have_posts() ) : the_post(); ?>
        <?php if (get_option($dynamo_tpl->name . '_archive_style','big') == 'big') { 
		get_template_part( 'article-blog-large');
		} else { 
		get_template_part( 'article-blog-medium');
		} ?>
    <?php endwhile; ?>
	
		<?php dp_content_nav(); ?>
	
	<?php else : ?>
	
		<h1 class="page-title">
			<?php __( 'Nothing Found', 'dp-theme' ); ?>
		</h1>
	
		<section class="intro">
			<?php __( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'dp-theme' ); ?>
		</section>
		
		<?php get_search_form(); ?>
		
	<?php endif; ?>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF