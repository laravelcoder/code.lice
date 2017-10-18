 <script type="text/javascript">
	jQuery(window).load( function () {
		jQuery("#video_slide_panel").hide();

		if ( jQuery('#item_type').val() == 'v' ) {
				  jQuery("#video_slide_panel").slideDown("slow");
			}
		if ( jQuery('#item_type').val() == 'c' ) {
				  jQuery("#protfolio-details-panel").slideUp("slow");
			}
		jQuery('#item_type').change(function(){
			if ( jQuery(this).val() == 'i' ) {
				  jQuery("#video_slide_panel").slideUp("slow");
				  jQuery("#protfolio-details-panel").slideDown("slow");			
			}
			if ( jQuery(this).val() == 'g' ) {
				  jQuery("#video_slide_panel").slideUp("slow");
				  jQuery("#protfolio-details-panel").slideDown("slow");	
			}
			if ( jQuery(this).val() == 'v' ) {
				  jQuery("#video_slide_panel").slideDown("slow");
				  jQuery("#protfolio-details-panel").slideDown("slow");	
			}
			if ( jQuery(this).val() == 'c' ) {
				  jQuery("#protfolio-details-panel").slideUp("slow");
			}
		});
		
	});
	</script>
<?php 
$values = get_post_custom( $post->ID );
$item_short_desc = isset( $values['item_short_desc'] ) ? esc_attr( $values['item_short_desc'][0] ) : '';
$item_type = isset( $values['item_type'] ) ? esc_attr( $values['item_type'][0] ) : '';
$item_desc = isset( $values['item_desc'] ) ? esc_attr( $values['item_desc'][0] ) : '';
$item_video = isset( $values['item_video'] ) ? esc_attr( $values['item_video'][0] ) : '';    
$item_link = isset( $values['item_link'] ) ? esc_attr( $values['item_link'][0] ) : ''; 
$item_client = isset( $values['item_client'] ) ? esc_attr( $values['item_client'][0] ) : '';
$item_date = isset( $values['item_date'] ) ? esc_attr( $values['item_date'][0] ) : ''; 
 ?>
<div class="my_meta_control">
	<label><?php _e("Define item short description <span>Will be used as subtitle in portfolio grid view</span>", 'dp-theme'); ?></label>
	<p>
		<input type="text" name="item_short_desc" id="item_short_desc" value="<?php echo $item_short_desc ?>"/>
	</p>	
	<label><?php _e("Define the type of portfolio item <span>(This choice determines the type of displayable content)</span>", 'dp-theme'); ?></label>
	
	<p class="note"><?php _e("<i><b>Note!</b><br/>An ''<b>Single image portfolio item</b>'' will be use fetured image as thumb and main image in single portfolio view.<br/>An ''<b>Gallery portfolio item</b>'' vill be use featured image of post as thumb and  post gallery as source for slideshow in single portfolio view.<br/>An ''<b>Video portfolio item</b>'' will be use featured image as thubnail and video (entered bellow) in single portfolio view. <br/>An ''<b>Custom portfolio item</b>'' will be use featured image as thubnail and content (entered in WYSIWYG entered above) in single portfolio view. </i> ", 'dp-theme'); ?></p>
       <p>
        <select name="item_type" id="item_type" class="width180">
                            <option value="i" <?php if ($item_type == "i") echo 'selected'; ?> ><?php _e("Single Image", 'dp-theme'); ?></option>
                            <option value="g" <?php if ($item_type == "g") echo 'selected'; ?> ><?php _e("Gallery", 'dp-theme'); ?></option>
                            <option value="v" <?php if ($item_type == "v") echo 'selected'; ?>><?php _e("Video", 'dp-theme'); ?></option>
                            <option value="c" <?php if ($item_type == "c") echo 'selected'; ?>><?php _e("Custom", 'dp-theme'); ?></option>
        </select>
        </p>
 <div id="protfolio-details-panel">	
 <div id="video_slide_panel" class="panel">
 <label><?php _e("Link to video", 'dp-theme'); ?></label>
	<p>
		<input type="text" name="item_video" id="item_video" value="<?php echo $item_video ?>"/>
		<?php _e("<span>Just link, not embed code, this field is used for oEmbed.</span>", 'dp-theme'); ?>
	</p>
 </div>
 
 <label><?php _e("Project details <span>(optional)</span>", 'dp-theme'); ?></label>
 <?php _e("<span>Enter project details description you want to show in the single portfolio item view. Leave blank if you do not want a description to show.</span>", 'dp-theme'); ?>
	<p>
    <?php 
	wp_editor( $item_desc, 'item_desc',$settings = array( 'media_buttons' => false,'textarea_rows' => 6)); ?>
		
	</p>

<label><?php _e("Date of project <span>(optional)</span>", 'dp-theme'); ?></label>
	<p>
		<input type="text" name="item_date" id="item_date" value="<?php echo $item_date ?>"/>
	</p>	
<label><?php _e("Client name <span>(optional)</span>", 'dp-theme'); ?></label>
	<p>
		<input type="text" name="item_client" id="item_client" value="<?php echo $item_client ?>"/>
	</p>
<label><?php _e("Launch project URL <span>(optional)</span>", 'dp-theme'); ?></label>
	<p>
		<input type="text" name="item_link" id="item_link" value="<?php echo $item_link ?>"/>
	</p>
</div>
    </div>