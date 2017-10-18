<?php 
	
	/**
	 *
	 * Template header
	 *
	 **/
	
	// create an access to the template main object
	global $dynamo_tpl;
?>
<?php do_action('dynamowp_doctype'); ?>
<html <?php do_action('dynamowp_html_attributes'); ?>>
<head>
	<title><?php do_action('dynamowp_title'); ?></title>
	<?php do_action('dynamowp_metatags'); ?>
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="shortcut icon" href="<?php get_stylesheet_directory_uri(); ?>/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php do_action('dynamowp_fonts'); ?>
	<?php dp_head_config(); ?>
	
	<?php if(is_singular() && get_option('thread_comments' )) wp_enqueue_script( 'comment-reply' ); ?>
	
	<?php do_action('dynamowp_ie_scripts'); ?>
	<?php dp_head_style_pages(); ?>	
	
	<?php echo $assets_output; ?>
	
	<?php dp_thickbox_load(); ?>	
	<?php 
		echo stripslashes(
			htmlspecialchars_decode(
				str_replace( '&#039;', "'", get_option($dynamo_tpl->name . '_head_code', ''))
			)
		); 
	?>    
	<?php dp_load('responsive_css'); ?> 
    <?php wp_head(); ?>
</head>