<?php

/*
Template Name: In The News
*/
 
global $dynamo_tpl;

$fullwidth = true;

dp_load('header');
dp_load('before', null, array('sidebar' => false));

?>

<div id="dp-mainbody" class="interior">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'in-the-news' ); ?>

	
		<?php if(get_option($dynamo_tpl->name . '_pages_show_comments_on_pages', 'Y') == 'Y') : ?>
		<?php comments_template( '', true ); ?>
		<?php endif; ?>
		<?php dp_content_nav(); ?>
	<?php endwhile; ?>
    <div id='lca_map' style="display: none; visibility: hidden;"></div>
</div>

<?php
dp_load('after-nosidebar', null, array('sidebar' => false));
dp_load('footer');

// EOF
