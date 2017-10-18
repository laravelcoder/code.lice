<?php

/*
Template Name: Tag cloud
*/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody" class="tagcloud">
	<?php while ( have_posts() ) : the_post(); ?>
		<header>
			<?php get_template_part( 'layouts/content.post.header' ); ?>
		</header>
		
		<section class="content">
			<?php the_content(); ?>
			
			<div class="tag-cloud">
				<?php wp_tag_cloud('number=0'); ?>
			</div>
		</section>
		
	<?php endwhile; ?>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF