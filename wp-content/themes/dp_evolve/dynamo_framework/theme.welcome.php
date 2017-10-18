<?php
/**
 * Adds a simple WordPress pointer to Settings menu
 */
 
function thsp_enqueue_pointer_script_style( $hook_suffix ) {
	
	// Assume pointer shouldn't be shown
	$enqueue_pointer_script_style = false;

	// Get array list of dismissed pointers for current user and convert it to array
	$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	// Check if our pointer is not among dismissed ones
	if( !in_array( 'thsp_settings_pointer', $dismissed_pointers ) ) {
		$enqueue_pointer_script_style = true;
		
		// Add footer scripts using callback function
		add_action( 'admin_print_footer_scripts', 'thsp_pointer_print_scripts' );
	}

	// Enqueue pointer CSS and JS files, if needed
	if( $enqueue_pointer_script_style ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
	
}
add_action( 'admin_enqueue_scripts', 'thsp_enqueue_pointer_script_style' );

function thsp_pointer_print_scripts() {

	$pointer_content  = "<h3>Welcome to Evolve Multipurpose Wordpress Theme</h3>";
	$pointer_content .= addslashes("<p>Here is where you can customize the theme.</p><p>Online documentation is available <a href='http://www.dynamicpress.eu/documentation/evolve/doc/' target='_blank'>here</a></p><p>Please visit our <a href='http://www.support.dynamicpress.eu/discussions' target='_blank'>support forum</a> for further information</p>");
	
	?>
	
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		$('#toplevel_page_dynamo-menu').pointer({
			content:		'<?php echo $pointer_content; ?>',
			position:		{
								edge:	'left', // arrow direction
								align:	'center' // vertical alignment
							},
			pointerWidth:	350,
			close:			function() {
								$.post( ajaxurl, {
										pointer: 'thsp_settings_pointer', // pointer ID
										action: 'dismiss-wp-pointer'
								});
							}
		}).pointer('open');
	});
	//]]>
	</script>

<?php
}
