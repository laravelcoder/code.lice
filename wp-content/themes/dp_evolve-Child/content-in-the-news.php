<?php

/**
 *
 * The template used for displaying page content for in the news page template
 *
 **/
 
global $dynamo_tpl;

$show_title = true;

if ((is_page_template('template.fullwidth.php') && ('post' == get_post_type() || 'page' == get_post_type())) || get_the_title() == '') {
	$show_title = false;
}

$classname = '';

if(!$show_title) {
	$classname = 'no-title';
}

if(is_page() && get_option($dynamo_tpl->name . '_template_show_details_on_pages', 'Y') == 'N') {
	$classname .= ' page-fullwidth';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classname); ?>>
	<header>
		<?php get_template_part( 'layouts/content.post.header' ); ?>
	</header>

	<?php get_template_part( 'layouts/content.post.fetured' ); ?>

	<section class="content">
		<?php get_template_part( 'layouts/aw-custom' ); ?>

		<?php get_template_part( 'layouts/aw-custom-news' ); ?>

		<?php the_content(); ?>
		
		<?php dp_post_links(); ?>
	</section>
	
	<?php get_template_part( 'layouts/content.post.footer' ); ?>
</article>