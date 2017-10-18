<?php

/**
 *
 * Single page
 *
 **/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody">
	<?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'content', get_post_format() ); ?>			
		<?php comments_template( '', true ); ?>
		<?php dp_content_nav(); ?>
	<?php endwhile; // end of the loop. ?>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF