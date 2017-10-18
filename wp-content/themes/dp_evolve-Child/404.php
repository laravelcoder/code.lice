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
    <h3>Oops! Page Not Found</h3>
    <p>
		<?php _e( 'Sorry about that, but we couldn\'t find the page you were looking for. The good news is, after a trip to our clinic we\'ll have just as much trouble finding lice on your head! Try searching for the page you were looking for.', 'dp-theme'); ?>
	</p>
	
	<p><?php get_search_form(); ?></p>
	
    <p><a href="<?php echo home_url(); ?>" target="_self" class="button_sc line medium"><span><i class="ss-right"></i> Back to Home Page</span></a></p>
    <div class="space50"></div>
    
	
</section>

<?php

dp_load('after-nosidebar', null, array('sidebar' => false));
dp_load('footer');

// EOF