<?php

/**
 *
 * The template fragment to show post header
 *
 **/

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');

global $dynamo_tpl; 

$params = get_post_custom();
$params_title = isset($params['dynamo-post-params-title']) ? esc_attr( $params['dynamo-post-params-title'][0] ) : 'Y';
$params_subheader_use =  isset( $params['dynamo-post-params-subheader_use'] ) ? esc_attr( $params['dynamo-post-params-subheader_use'][0] ) : 'Y';
$params_custom_title =  isset( $params['dynamo-post-params-custom_title'] ) ? esc_attr( $params['dynamo-post-params-custom_title'][0] ) : '';
$params_custom_subtitle =  isset( $params['dynamo-post-params-custom_subtitle'] ) ? esc_attr( $params['dynamo-post-params-custom_subtitle'][0] ) : '';
$params_custom_headerbg =  isset( $params['dynamo-post-params-header_img'] ) ? esc_attr( $params['dynamo-post-params-header_img'][0] ) : '';
?>
<?php if (is_single() || is_page()) : ?>
<?php if ($params_subheader_use == 'N') :?> 
<?php if(get_the_title() != '' && $params_title == 'Y') : ?>
<h1>
	<?php if(!is_singular()) : ?>
	<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'dp-theme' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
	<?php endif; ?>
		<?php the_title(); ?>
	<?php if(!is_singular()) : ?>
	</a>
	<?php endif; ?>
	<?php if(is_sticky()) : ?>
	<sup>
		<?php _e( 'Featured', 'dp-theme' ); ?>
	</sup>
	<?php endif; ?>
</h1>
<?php endif; ?>
<?php endif; ?>
<?php else : ?>
<?php if(get_the_title() != '' && $params_title == 'Y')  : ?>
<h2>
	<?php if(!is_singular()) : ?>
	<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'dp-theme' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
	<?php endif; ?>
		<?php the_title(); ?>
	<?php if(!is_singular()) : ?>
	</a>
	<?php endif; ?>
	
	<?php if(is_sticky()) : ?>
	<sup>
		<?php _e( 'Featured', 'dp-theme' ); ?>
	</sup>
	<?php endif; ?>
</h2>
<?php endif; ?>
<?php endif; ?>
<?php if((!is_page_template('template.fullwidth.php') && ('post' == get_post_type() || 'page' == get_post_type())) && get_the_title() != '') : ?>
	<?php if(!is_page()&& !is_search()) : ?>
	<?php dp_post_meta(); ?>
	<?php endif; ?>
<?php endif; ?>


<?php do_action('dynamowp__before_post_content'); ?>