<?php

/**
 *
 * Archive page
 *
 **/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody">

	<?php if ( have_posts() ) : ?>
		<?php do_action('dynamowp_before_loop'); ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
	
		<?php dp_content_nav(); ?>
	<?php else : ?>
	
		<h1 class="entry-title"><?php _e( 'Nothing Found', 'dp-theme' ); ?></h1>
						
		<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'dp-theme' ); ?></p>
		
		<?php get_search_form(); ?>
	
	<?php endif; ?>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF