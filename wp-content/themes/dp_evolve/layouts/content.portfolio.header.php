<?php

/**
 *
 * The template fragment to show portfolio header
 *
 **/

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');

global $dynamo_tpl;
$params = get_post_custom();
$params_title = isset($params['dynamo-post-params-title']) ? esc_attr( $params['dynamo-post-params-title'][0] ) : 'Y';
$params_subheader_use =  isset( $params['dynamo-post-params-subheader_use'] ) ? esc_attr( $params['dynamo-post-params-subheader_use'][0] ) : 'Y';
?>

<?php if(((!is_home() and !is_front_page() and !is_page_template('template.frontpage.php')) || (get_option($dynamo_tpl->name . "_template_homepage_title")=='Y')) and $params_subheader_use =='N' ) : ?>
<?php if(get_the_title() != '') : ?>
<hgroup>
	<h3><?php the_title(); ?>
		
		<?php if(is_sticky()) : ?>
		<sup>
			<?php _e( 'Featured', 'dp-theme' ); ?>
		</sup>
		<?php endif; ?>
	</h3>
</hgroup>
<?php endif; ?>
<?php endif; ?>