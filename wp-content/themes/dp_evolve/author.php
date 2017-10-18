<?php

/**
 *
 * Author page
 *
 **/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody">
	<?php if ( have_posts() ) : ?>
	
		<?php the_post(); ?>
	
		<h1 class="page-title author">
			<?php printf( __( 'Author archives: %s %s', 'dp-theme' ), get_the_author_meta('first_name', get_the_author_meta( 'ID' )), get_the_author_meta('last_name', get_the_author_meta( 'ID' )) ); ?>
		</h1>
	
		<?php rewind_posts(); ?>
	
		<?php dp_author(true); ?>
	
	
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
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