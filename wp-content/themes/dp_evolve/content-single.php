<?php

/**
 *
 * The template for displaying content in the single.php template
 *
 **/
 
global $dynamo_tpl;
 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(is_page_template('template.fullwidth.php') ? ' page-fullwidth' : null); ?>>
	<?php get_template_part( 'layouts/content.post.fetured' ); ?>
		<?php if((!is_page_template('template.fullwidth.php') && ('post' == get_post_type())) && get_the_title() != '') : ?>
	<header>
		<?php get_template_part( 'layouts/content.post.header' ); ?>
	</header>
		<?php endif; ?>
	<section class="content">
		<?php the_content(); ?>
		
		<?php dp_post_links(); ?>
	</section>

	<?php get_template_part( 'layouts/content.post.footer' ); ?>
</article>
