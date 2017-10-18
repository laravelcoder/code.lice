<?php
global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>		
		
	<?php if(get_option($dynamo_tpl->name . '_template_homepage_mainbody', 'N') == 'N') : ?>
		<?php do_action('dynamowp_before_mainbody'); ?>
		<?php if ( have_posts() ) : ?>		
			<section id="dp-mainbody">
				<?php do_action('dynamowp_before_loop'); ?>
			
				
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', get_post_format() ); ?>
				<?php endwhile; ?>
				<?php dp_content_nav(); ?>
				
				<?php do_action('dynamowp_after_loop'); ?>
			</section>
		<?php else : ?>
			<section id="dp-mainbody">
				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php __( 'Nothing Found', 'dp-theme' ); ?></h1>
					</header>
		
					<div class="entry-content">
						<p><?php __( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'dp-theme' ); ?></p>
						<?php get_search_form(); ?>
					</div>
				</article>
			</section>
		<?php endif; ?>
		
		<?php do_action('dynamowp_after_mainbody'); ?>
	<?php else: ?>
		<?php if(dp_is_active_sidebar('mainbody')) : ?>
		<section id="dp-mainbody">
			<?php dp_dynamic_sidebar('mainbody'); ?>
		</section>
		<?php endif; ?>
	<?php endif; ?>
<?php

dp_load('after');
dp_load('footer');

/* EOF */