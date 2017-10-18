<?php

/*
Template Name: Page with sidebar right
*/

global $dynamo_tpl;

dp_load('header');
dp_load('before-sr');

?>

<section id="dp-mainbody">
	<?php the_post(); ?>
	
	<?php get_template_part( 'content', 'page' ); ?>
	<?php if(get_option($dynamo_tpl->name . '_pages_show_comments_on_pages', 'Y') == 'Y') : ?>
	<?php comments_template( '', true ); ?>
	<?php endif; ?>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF