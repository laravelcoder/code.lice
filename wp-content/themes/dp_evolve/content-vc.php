<?php

/**
 *
 * The default template for displaying content
 *
 **/

global $dynamo_tpl; 

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header>
			<?php get_template_part( 'layouts/content.post.header' ); ?>
		</header>
		<?php if (is_single() || is_home()) {
		include(dynamo_file('layouts/content.post.featured.php')); 
		}?>
		<?php if ( is_search() || is_archive() || is_tag() ) : ?>
		<?php if (get_option($dynamo_tpl->name . '_archive_style','big')=='big') {
			include(dynamo_file('layouts/content.post.featured.php'));
			?>
        <section class="summary">
			<?php the_excerpt(); ?>
		</section>
        <?php } ?> 
        <?php if (get_option($dynamo_tpl->name . '_archive_style','big')=='small') {?>
        <?php if (has_post_thumbnail()) { ?>
        <div class="one_half">
		<?php get_template_part( 'layouts/content.post.fetured' ); ?>
        </div>
        <div class="two_half_last">
        <section class="summary">
			<?php the_excerpt(); ?>
		</section>
        </div>
		<?php } else {?>
        <section class="summary">
			<?php the_excerpt(); ?>
		</section>
        <?php } ?> 
        <?php } ?> 

		<?php else : ?>
		<section class="content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'dp-theme' ) ); ?>
			
			<?php dp_post_links(); ?>
		</section>
		<?php endif; ?>
		
	</article>