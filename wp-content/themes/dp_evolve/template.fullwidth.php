<?php

/*
Template Name: Page without sidebar
*/
 
global $dynamo_tpl;

$fullwidth = true;

dp_load('header');
dp_load('before', null, array('sidebar' => false));

?>

<div id="dp-mainbody">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'page' ); ?>
	
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