<?php

/**
 *
 * Tag page
 *
 **/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<div id="dp-mainbody" class="tag-page">
	<?php if ( have_posts() ) : ?>
		<?php
			$tag_description = tag_description();
			if ( ! empty( $tag_description ) )
				echo apply_filters( 'tag_archive_meta', '<div class="intro">' . $tag_description . '</div>' );
		?>
		
		<?php do_action('dynamowp_before_loop'); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
		
		<?php dp_content_nav(); ?>
		
		<?php do_action('dynamowp_after_loop'); ?>
		
	<?php else : ?>
		<h1 class="page-title">
			<?php _e( 'Nothing Found', 'dp-theme' ); ?>
		</h1>
	
		<div class="intro">
			<?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'dp-theme' ); ?>
		</div>
		
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>

<?php

dp_load('after');
dp_load('footer');

// EOF