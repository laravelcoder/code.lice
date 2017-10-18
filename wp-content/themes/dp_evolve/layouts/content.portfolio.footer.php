<?php

/**
 *
 * The template fragment to show post footer
 *
 **/

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');

global $dynamo_tpl; 

?>

<?php do_action('dynamowp_after_post_content'); ?>

<?php if(is_singular()) : ?>
	<?php 
		// variable for the social API HTML output
		$social_api_output = dp_social_api(get_the_title(), get_the_ID()); 
	?>
		
	<?php if($social_api_output != '' || dp_author(false, true)): ?>
	<footer>
		<?php echo $social_api_output; ?>
	</footer>
	<?php endif; ?>
<?php endif; ?>