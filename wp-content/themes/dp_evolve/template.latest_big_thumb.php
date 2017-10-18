<?php
/*
Template Name: Latest Posts (Big Thumbs)
*/

global $dynamo_tpl,$post,$more;
dp_load('header');
dp_load('before');

$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
$params = get_post_custom();
$params_category = isset($params['dynamo-post-params-category']) ? esc_attr( $params['dynamo-post-params-category'][0] ) : '';
$args = array(
				'paged' => $paged,
				'posts_per_page' =>  get_option('posts_per_page'),
				'orderby' => 'date&order=ASC',
				'category_name' => $params_category
			);
$newsquery = new WP_Query($args);
?>
<?php if ( have_posts() ) : ?>
	<section id="dp-mainbody">
    <?php while($newsquery->have_posts()): $newsquery->the_post(); ?>
	<?php get_template_part( 'article-blog-large'); ?>
    <?php endwhile; ?>
    <?php dp_content_nav($newsquery->max_num_pages, $range = 2); ?>
	<?php wp_reset_query(); ?>
    </section>
      <?php if(get_option($dynamo_tpl->name . '_postmeta_date_state') == 'Y' || get_option($dynamo_tpl->name . '_post_like_state') == 'Y') { ?>
		</div>
      <?php } ?>
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

<?php

dp_load('after');
dp_load('footer');

// EOF