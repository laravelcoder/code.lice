<?php

/**
 *
 * The template fragment to show post featured image
 *
 **/

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');

global $dynamo_tpl; 
$params = get_post_custom();
$params_image = isset($params['dynamo-post-params-featuredimg']) ? esc_attr( $params['dynamo-post-params-featuredimg'][0] ) : 'Y';
?>

<?php if((is_single() || is_page()) && $params_image == 'Y') : ?>
	<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_dynamo-featured-video", true) != '') : 
	?>
	<figure class="featured-video">
	<?php echo wp_oembed_get( get_post_meta(get_the_ID(), "_dynamo-featured-video", true) ); ?>
	</figure>
	<?php elseif(has_post_thumbnail()) : ?>
	<figure class="featured-image">
		<?php the_post_thumbnail(); ?>
		
		<?php if(is_single()) : ?>
        			<?php echo dp_post_thumbnail_caption(); ?>
		<?php endif; ?>
	</figure>
	<?php endif; ?>
<?php elseif(!is_single()) : ?>
	<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_dynamo-featured-video", true) != '') : 
	?>
	
	<?php echo wp_oembed_get( get_post_meta(get_the_ID(), "_dynamo-featured-video", true) ); ?>
	
	<?php elseif(has_post_thumbnail()) : ?>
	<figure class="featured-image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
		
		<?php if(is_single()) : ?>
			<?php echo dp_post_thumbnail_caption(); ?>
		<?php endif; ?>
	</figure>
	<?php endif; ?>
<?php endif; ?>