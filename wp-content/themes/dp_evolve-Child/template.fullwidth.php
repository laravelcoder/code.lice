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
<div id='lca_map' style="display: none; visibility: hidden;"></div>

<?php

dp_load('after-nosidebar', null, array('sidebar' => false));
 
if ( is_page (['13829','13847','3295','7131']) ):  

	get_template_part( 'layouts/bare-footer' );  

else:

	dp_load('footer');

endif;  