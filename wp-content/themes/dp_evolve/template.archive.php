<?php
/*
Template Name: Archive Page
*/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody" class="archivepage">
	<?php the_post(); ?>
		<header>
			<?php get_template_part( 'layouts/content.post.header' ); ?>
		</header>
	<article>
		<section class="intro">
			<?php the_content(); ?>
		</section>
		
		<?php
			$posts_to_show = 10; //Max number of articles to display
			$debut = 0; //The first article to be displayed
		?>
		<div class="widget box first">
			<h3><?php __('Latest posts', 'dp-theme'); ?></h3>
			<ul>
				<?php
					$myposts = get_posts('numberposts='.$posts_to_show.'&offset='.$debut);
					foreach($myposts as $post) :
				?>
				<li><small><?php the_time('d/m/y') ?>:</small> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	
		<div class="widget box">
			<h3><?php __('Categories', 'dp-theme'); ?></h3>
			<ul>
				<?php 
					wp_list_categories(array(
						'orderby' => 'name',
						'show_count' => 1,
						'title_li' => ''
					)); 
				?>
			</ul>
		</div>
		
		<div class="widget box last">
			<h3><?php __('Monthly Archives', 'dp-theme'); ?></h3>
			<ul>
				<?php wp_get_archives('type=monthly&show_post_count=1') ?>
			</ul>
		</div>
		
	</article>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF