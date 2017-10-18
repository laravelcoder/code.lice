<?php 
$values = get_post_custom( $post->ID ); 
$sidebar_description = isset( $values['sidebar_description'] ) ? esc_attr( $values['sidebar_description'][0] ) : '';
 ?>
<div class="my_meta_control"> 
	<p>
		<textarea name="sidebar_description" id="sidebar_description" rows="2"><?php echo $sidebar_description ?></textarea>
		<span><?php __("Add an optional description. Will be displayed when adding widgets to this widget area.", 'dp-theme'); ?></span>
	</p>
 
</div>	