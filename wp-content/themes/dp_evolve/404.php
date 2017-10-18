<?php

/**
 *
 * 404 Page
 *
 **/
 
global $dynamo_tpl; 
$fullwidth = true;
dp_load('header');
dp_load('before', null, array('sidebar' => false));

?>
<section id="dp-mainbody" class="page404">
	<h1><?php _e( '404', 'dp-theme'); ?></h1>
    <h3>Opps! Page Not Found</h3>
    <p>
		<?php _e( 'We couldn\'t able to find the page you were looking for, so cool down, please go back or search again thanks.', 'dp-theme'); ?>
	</p>
	
	<p><?php get_search_form(); ?></p>
    <p><a href="<?php echo home_url(); ?>" target="_self" class="button_sc line medium"><span><i class="ss-right"></i> Back to Home Page</span></a></p>
    <div class="space50"></div>
    
	
</section>

<?php

dp_load('after-nosidebar', null, array('sidebar' => false));
dp_load('footer');

// EOF