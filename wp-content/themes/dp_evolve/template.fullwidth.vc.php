<?php

/*
Template Name: Full width page
*/
 
global $dynamo_tpl;

$fullwidth = true;

dp_load('header');
dp_load('before-vc', null, array('sidebar' => false));

?>

<div id="dp-mainbody">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content-vc', 'page' ); ?>
	
		<?php if(get_option($dynamo_tpl->name . '_pages_show_comments_on_pages', 'Y') == 'Y') : ?>
		<?php comments_template( '', true ); ?>
		<?php endif; ?>
		<?php dp_content_nav(); ?>
	<?php endwhile; ?>
</div>

<?php

dp_load('after-nosidebar', null, array('sidebar' => false));
dp_load('footer');

// EOF