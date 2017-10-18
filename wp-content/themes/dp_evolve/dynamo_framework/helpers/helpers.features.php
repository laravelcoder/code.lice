<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * DynamoWP admin panel & page features
 *
 * Functions used to create DynamoWP-specific functions 
 *
 **/



/**
 *
 * Code to create custom metaboxes with post additional features (description, keywords, title params)
 *
 **/
global $post;
function add_dynamo_metaboxes() {
	global $dynamo_tpl;	
	add_meta_box( 'dynamo-slide-setting', __('Slide Setting', 'dp-theme'), 'dynamo_slide_setting_callback', 'slide', 'normal', 'high' );
	add_meta_box( 'dynamo-portfolio-setting', __('Portfolio Item Setting', 'dp-theme'), 'dynamo_portfolio_setting_callback', 'portfolio', 'normal', 'high' );
	add_meta_box( 'dynamo-post-params', __('Post additional params', 'dp-theme'), 'dynamo_post_params_callback', 'post', 'normal', 'high' );
	add_meta_box( 'dynamo-post-params', __('Page additional params', 'dp-theme'), 'dynamo_post_params_callback', 'page', 'normal', 'high' );
	add_meta_box( 'dynamo-post-params', __('Page additional params', 'dp-theme'), 'dynamo_post_params_callback', 'portfolio', 'normal', 'high' );
	add_meta_box( 'dynamo-sidebar-setting', __('Sidebar Description', 'dp-theme'), 'dynamo_sidebar_setting_callback', 'sidebar', 'normal', 'high' );

	// post description custom meta box
	if(get_option($dynamo_tpl->name . '_seo_use_dp_seo_settings') == 'Y' && get_option($dynamo_tpl->name . '_seo_post_desc') == 'custom') {
		add_meta_box( 'dynamo-post-desc', __('Post keywords and description', 'dp-theme'), 'dynamo_post_seo_callback', 'post', 'normal', 'high' );
		add_meta_box( 'dynamo-post-desc', __('Page keywords and description', 'dp-theme'), 'dynamo_post_seo_callback', 'page', 'normal', 'high' );
	}
	
}

function dynamo_post_seo_callback($post) { 
	$values = get_post_custom( $post->ID );  
	$value_desc = isset( $values['dynamo-post-desc'] ) ? esc_attr( $values['dynamo-post-desc'][0] ) : '';    
	$value_keywords = isset( $values['dynamo-post-keywords'] ) ? esc_attr( $values['dynamo-post-keywords'][0] ) : ''; 
	// nonce 
	wp_nonce_field( 'dynamo-post-seo-nonce', 'dynamo_meta_box_seo_nonce' ); 
    // output
    echo '<label for="dynamo-post-desc-value">'.__('Description:', 'dp-theme').'</label>';
    echo '<textarea name="dynamo-post-desc-value" id="dynamo-post-desc-value" rows="5" class="width100percent">'.$value_desc.'</textarea>'; 
    // output
    echo '<label for="dynamo-post-desc-value">'.__('Keywords:', 'dp-theme').'</label>';
    echo '<textarea name="dynamo-post-keywords-value" id="dynamo-post-keywords-value" rows="5" class="width100percent">'.$value_keywords.'</textarea>';    
} 

function dynamo_post_params_callback($post) {
	global $post; 
	$values = get_post_custom( $post->ID );  
	$value_title = isset( $values['dynamo-post-params-title'] ) ? esc_attr( $values['dynamo-post-params-title'][0] ) : 'Y';
	$value_featured = isset( $values['dynamo-post-params-featuredimg'] ) ? esc_attr( $values['dynamo-post-params-featuredimg'][0] ) : 'Y';
	$value_templates = isset( $values['dynamo-post-params-templates'] ) ? $values['dynamo-post-params-templates'][0] : false;   
    // if the data are JSON  
    if($value_templates) {  
      $value_templates = unserialize(unserialize($value_templates));  
      $value_contact = $value_templates['contact'];  
      if($value_contact != '' && count($value_contact) > 0) {  
         $value_contact = explode(',', $value_contact); // [0] - name, [1] - e-mail, [2] - send copy     
        }  
     }  
	$value_category = isset( $values['dynamo-post-params-category'] ) ? esc_attr( $values['dynamo-post-params-category'][0] ) : ''; 
	$page_template = get_post_meta( $post->ID, '_wp_page_template', true );	
	$custom_headerbg_color =  isset( $values['dynamo-post-params-header_bgcolor'] ) ? esc_attr( $values['dynamo-post-params-header_bgcolor'][0] ) : '';
	$custom_headerbg =  isset( $values['dynamo-post-params-header_img'] ) ? esc_attr( $values['dynamo-post-params-header_img'][0] ) : '';
	$subheader_use =  isset( $values['dynamo-post-params-subheader_use'] ) ? esc_attr( $values['dynamo-post-params-subheader_use'][0] ) : 'Y';
	$custom_title =  isset( $values['dynamo-post-params-custom_title'] ) ? esc_attr( $values['dynamo-post-params-custom_title'][0] ) : '';
	$custom_subtitle =  isset( $values['dynamo-post-params-custom_subtitle'] ) ? esc_attr( $values['dynamo-post-params-custom_subtitle'][0] ) : '';
	$custom_subheaderbg_color =  isset( $values['dynamo-post-params-subheader_bgcolor'] ) ? esc_attr( $values['dynamo-post-params-subheader_bgcolor'][0] ) : '';
	$custom_subheaderdbg =  isset( $values['dynamo-post-params-subheader_img'] ) ? esc_attr( $values['dynamo-post-params-subheader_img'][0] ) : '';

	// nonce 
	wp_nonce_field( 'dynamo-post-params-nonce', 'dynamo_meta_box_params_nonce' ); 
    // output
	echo '<p class="col_onefourth"><label for="dynamo-post-params-title-value">'.__('Show title:', 'dp-theme').'</label>';
    echo '<select name="dynamo-post-params-title-value" id="dynamo-post-params-title-value">';
    echo '<option value="Y"'.selected($value_title, 'Y', false).'>'.__('Enabled', 'dp-theme').'</option>';
    echo '<option value="N"'.selected($value_title, 'N', false).'>'.__('Disabled', 'dp-theme').'</option>';
    echo '</select></p>';
    echo '<p class="col_onefourth last">';
    echo '<label for="dynamo-post-params-featuredimg-value">'.__('Show featured image:', 'dp-theme').'</label>';
    echo '<select name="dynamo-post-params-featuredimg-value" id="dynamo-post-params-featuredimg-value">';
    echo '<option value="Y"'.selected($value_title, 'Y', false).'>'.__('Enabled', 'dp-theme').'</option>';
    echo '<option value="N"'.selected($value_title, 'N', false).'>'.__('Disabled', 'dp-theme').'</option>';
    echo '</select>';
    echo '</p>';
	echo '<div class="clearboth"></div>'; 
	if ($page_template == 'template.latest_small_thumb.php' || $page_template == 'template.latest_big_thumb.php' || $page_template == 'template.portfolio-four.php' || $page_template == 'template.portfolio-three.php' || $page_template == 'template.portfolio-two.php' || $page_template == 'template.portfolio-five.php') {
	echo '<p class="subsection-title">'.__('Category settings <small><i>(only for blog and portfolio page templates)</i></small>', 'dp-theme').'</p>';
	echo '<p class="dp-indent">';
	echo '<label for="dynamo-post-params-category-value">'.__('Category: &nbsp;', 'dp-theme').'</label>';
	echo '<input name="dynamo-post-params-category-value" type="text" id="dynamo-post-params-category-value" value="'.$value_category.'">';
	echo '&nbsp;<small>'.__('You can specify category or coma separated list of categories witch will be used on this page. If you leave this field empty will be displayed posts from all categories.', 'dp-theme').'</small></p>';
	}

	// output for the contact page options
	echo '<p data-template="template.contact.php" class="subsection-title">'.__('Contact page settings', 'dp-theme').'</p>';
    echo '<p data-template="template.contact.php" class="col_onefourth">';
    echo '<label for="dynamo-post-params-contact-name">'.__('Show name field:', 'dp-theme').'</label>';
    echo '<select name="dynamo-post-params-contact-name" id="dynamo-post-params-contact-name">';
    echo '<option value="Y"'.((!$value_contact || $value_contact[0] == 'Y') ? ' selected="selected"' : '').'>'.__('Enabled', 'dp-theme').'</option>';
    echo '<option value="N"'.(($value_contact !== FALSE && $value_contact[0] == 'N') ? ' selected="selected"' : '').'>'.__('Disabled', 'dp-theme').'</option>';
    echo '</select>';
    echo '</p>';
    echo '<p data-template="template.contact.php" class="col_onefourth">';
    echo '<label for="dynamo-post-params-contact-email">'.__('Show e-mail field:', 'dp-theme').'</label>';
    echo '<select name="dynamo-post-params-contact-email" id="dynamo-post-params-contact-email">';
    echo '<option value="Y"'.((!$value_contact || $value_contact[1] == 'Y') ? ' selected="selected"' : '').'>'.__('Enabled', 'dp-theme').'</option>';
    echo '<option value="N"'.(($value_contact !== FALSE && $value_contact[1] == 'N') ? ' selected="selected"' : '').'>'.__('Disabled', 'dp-theme').'</option>';
    echo '</select>';
    echo '</p>';  
    echo '<p data-template="template.contact.php" class="col_onefourth">';
    echo '<label for="dynamo-post-params-contact-copy">'.__('Show "send copy":', 'dp-theme').'</label>';
    echo '<select name="dynamo-post-params-contact-copy" id="dynamo-post-params-contact-copy">';
    echo '<option value="Y"'.((!$value_contact || $value_contact[2] == 'Y') ? ' selected="selected"' : '').'>'.__('Enabled', 'dp-theme').'</option>';
    echo '<option value="N"'.(($value_contact !== FALSE && $value_contact[2] == 'N') ? ' selected="selected"' : '').'>'.__('Disabled', 'dp-theme').'</option>';
    echo '</select>';
    echo '</p>';
	echo '<div class="clearboth"></div>';
	// output for the header area style
	echo '<p class="subsection-title">'.__('Header area custom style', 'dp-theme').'</p>';
	echo '<p class="description">'.__('Here can you set custom layout of header widget area for this post/page', 'dp-theme').'</p>';
	echo '<p><label for="dynamo-post-params-header_bgcolor">'.__('Custom background color for header:', 'dp-theme').'</label>';
	echo '<input type="text" value="'.$custom_headerbg_color.'" class=" dpColor" name="dynamo-post-params-header_bgcolor-value" id="dynamo-post-params-header_bgcolor-value"><span class="colorSelector"><span></span></span></p>';
	
	echo '<p class="col_twothird">';
	echo '<label for="dynamo-post-params-header_img-value">'.__('Custom background image for header:', 'dp-theme').'</label>';
	echo '<input class="widefat" name="dynamo-post-params-header_img-value" type="text" id="dynamo-post-params-header_img-value" value="'.$custom_headerbg.'">';
	//echo '<span class="img-holder"><img id="dynamo-post-params-header_img-thumb" alt="" src="'.$custom_headerbg.'"></span>';
	echo '<input  class="button uploadbtn" name="dynamo-post-params-header_img-button" id="dynamo-post-params-header_img-button" value="'.__('Upload image', 'dp-theme').'" />';
	echo '<small><a  href="#" id="dynamo-post-params-header_img-clear" />'.__('Remove Image', 'dp-theme').'</a></small>';
	echo '<br clear="all"><span class="description">Recomended dimension 1680x860 px.</span>';
	echo '</p>';
	echo '<p class="col_onefourth">';
	echo '<span class="img-holder"><img id="dynamo-post-params-header_img-thumb" alt="" src="'.$custom_headerbg.'"></span>';
	echo '</p>';
	echo '<div class="clearboth"></div>';
	// output for the subheader area
	echo '<p class="subsection-title">'.__('Subheader area custom style', 'dp-theme').'</p>';
	echo '<p class="description">'.__('Here can you set custom layout of subheader area for this post/page', 'dp-theme').'</p>';
	echo '<p class="dp-indent"><label for="dynamo-post-params-subheader_use-value">'.__('Use subheader:', 'dp-theme').'</label>';
    echo '<select name="dynamo-post-params-subheader_use-value" id="dynamo-post-params-subheader_use-value">';
    echo '<option value="Y"'.(($subheader_use == 'Y') ? ' selected="selected"' : '').'>'.__('Enabled', 'dp-theme').'</option>';
    echo '<option value="N"'.(($subheader_use == 'N') ? ' selected="selected"' : '').'>'.__('Disabled', 'dp-theme').'</option>';
    echo '</select><br clear="all"><span class="description">If you enable this you can also use custom title and subtitle.</span></p>';
	echo '<p class="dp-indent">';
	echo '<label for="dynamo-post-params-custom_title">'.__('Custom title in header:', 'dp-theme').'</label>';
	echo '<input class="widefat" name="dynamo-post-params-custom_title-value" type="text" id="dynamo-post-params-custom_title-value" value="'.$custom_title.'">';
	echo '</p>';
	echo '<p class="dp-indent">';
	echo '<label for="dynamo-post-params-custom_subtitle">'.__('Custom subtitle in header:', 'dp-theme').'</label>';
	echo '<input class="widefat" name="dynamo-post-params-custom_subtitle-value" type="text" id="dynamo-post-params-custom_subtitle-value" value="'.$custom_subtitle.'">';
	echo '</p>';
	echo '<p><label for="dynamo-post-params-subheader_bgcolor">'.__('Custom background color for subheader:', 'dp-theme').'</label>';
	echo '<input type="text" value="'.$custom_subheaderbg_color.'" class=" dpColor" name="dynamo-post-params-subheader_bgcolor-value" id="dynamo-post-params-subheader_bgcolor-value"><span class="colorSelector"><span></span></span></p>';
	echo '<p class="col_twothird">';
	echo '<label for="dynamo-post-params-subheader_img-value">'.__('Custom background image for subheader area:', 'dp-theme').'</label>';
	echo '<input class="widefat" name="dynamo-post-params-subheader_img-value" type="text" id="dynamo-post-params-subheader_img-value" value="'.$custom_subheaderdbg.'">';
	echo '<input  class="button uploadbtn" name="dynamo-post-params-subheader_img-button" id="dynamo-post-params-subheader_img-button" value="'.__('Upload image', 'dp-theme').'" />';
	echo '<small><a  href="#" id="dynamo-post-params-subheader_img-clear" />'.__('Remove Image', 'dp-theme').'</a></small>';
	echo '<br clear="all"><span class="description">Recomended width 1680 px.</span>';
	echo '</p>';
	echo '<p class="col_onefourth">';	
	echo '<span class="img-holder"><img id="dynamo-post-params-subheader_img-thumb" alt="" src="'.$custom_subheaderdbg.'"></span>';
	echo '</p>';
	echo '<div class="clearboth"></div>';
	?>
    <script type="text/javascript">
	jQuery(document).ready(function($) {
	dpPickerInit();
		//
		function dpPickerInit() {
			// color pickers
			jQuery('.colorSelector').each(
				function(i, el) {
				var Othis = this; //cache a copy of the this variable for use inside nested function
				var initialColor = jQuery(Othis).prev('input').attr('value');
				jQuery(Othis).children('span').css('backgroundColor', initialColor);
				jQuery(this).ColorPicker({
				color: initialColor,
				onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
				},
				onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
				},
				onChange: function (hsb, hex, rgb) {
				jQuery(Othis).children('span').css('backgroundColor', '#' + hex);
				jQuery(Othis).prev('input').attr('value','#' + hex);
			}
			});		
				}
			);
			jQuery('.dpColor').change(function() {
				newcolor = jQuery(this).val();
			jQuery(this).next('.colorSelector').children('span').css('backgroundColor', newcolor);
			 });
		}
if($('#dynamo-post-params-header_img-value').val().length>0) {
	$('#dynamo-post-params-header_img-thumb').show("slow");
	$('#dynamo-post-params-header_img-clear').show();
	} 
else {
	$('#dynamo-post-params-header_img-thumb').hide();
	$('#dynamo-post-params-header_img-clear').hide();
	}
$('#dynamo-post-params-header_img-clear').click(function() {
	$('#dynamo-post-params-header_img-value').val('');
	$('#dynamo-post-params-header_img-clear').hide();
	$('#dynamo-post-params-header_img-thumb').hide("slow");
});
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;
 
	$('#dynamo-post-params-header_img-button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$('#dynamo-post-params-header_img-value').val(attachment.url);
				$('#dynamo-post-params-header_img-thumb').attr("src",attachment.url);
	$('#dynamo-post-params-header_img-thumb').show("slow");
	$('#dynamo-post-params-header_img-clear').show();

			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
};
		}
 
		wp.media.editor.open(button);
		return false;
});
 
	$('.add_media').on('click', function(){
		_custom_media = false;
	});



if($('#dynamo-post-params-subheader_img-value').val().length>0) {
	$('#dynamo-post-params-subheader_img-thumb').show("slow");
	$('#dynamo-post-params-subheader_img-clear').show();
	} 
else {
	$('#dynamo-post-params-subheader_img-thumb').hide();
	$('#dynamo-post-params-subheader_img-clear').hide();
	}
$('#dynamo-post-params-subheader_img-clear').click(function() {
	$('#dynamo-post-params-subheader_img-value').val('');
	$('#dynamo-post-params-subheader_img-clear').hide();
	$('#dynamo-post-params-subheader_img-thumb').hide("slow");
});
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;
 
	$('#dynamo-post-params-subheader_img-button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$('#dynamo-post-params-subheader_img-value').val(attachment.url);
				$('#dynamo-post-params-subheader_img-thumb').attr("src",attachment.url);
	$('#dynamo-post-params-subheader_img-thumb').show("slow");
	$('#dynamo-post-params-subheader_img-clear').show();

			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
};
		}
 
		wp.media.editor.open(button);
		return false;
});
 
	$('.add_media').on('click', function(){
		_custom_media = false;
	});

});
	</script>
    <?php
     
} 

function dynamo_slide_setting_callback($post) { 
include (get_template_directory() . '/dynamo_framework/metaboxes/slide_meta_box.php'); 
} 

function dynamo_portfolio_setting_callback($post) { 
include (get_template_directory() . '/dynamo_framework/metaboxes/portfolio_meta_box.php'); 
}
 
function dynamo_sidebar_setting_callback($post) { 
include (get_template_directory() . '/dynamo_framework/metaboxes/sidebar_meta_box.php'); 
} 
function dynamo_metaboxes_save( $post_id ) {  
    // check the user permissions  
    if( !current_user_can( 'edit_post', $post_id ) ) {
    	return;
    }
    // avoid requests on the autosave 
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    	return; 
    }  
    // check the existing of the fields and save it
    if( isset( $_POST['dynamo-post-desc-value'] ) ) {
        // check the nonce
        if( !isset( $_POST['dynamo_meta_box_seo_nonce'] ) || !wp_verify_nonce( $_POST['dynamo_meta_box_seo_nonce'], 'dynamo-post-seo-nonce' ) ) {
        	return;
        }
        // update post meta
        update_post_meta( $post_id, 'dynamo-post-desc', esc_attr( $_POST['dynamo-post-desc-value'] ) );  
    }
  	//
    if( isset( $_POST['dynamo-post-keywords-value'] ) ) {
    	// check the nonce
    	if( !isset( $_POST['dynamo_meta_box_seo_nonce'] ) || !wp_verify_nonce( $_POST['dynamo_meta_box_seo_nonce'], 'dynamo-post-seo-nonce' ) ) {
    		return;
    	}
    	// update post meta
        update_post_meta( $post_id, 'dynamo-post-keywords', esc_attr( $_POST['dynamo-post-keywords-value'] ) ); 
    }

    //
    if( isset( $_POST['dynamo-post-params-title-value'] ) ) {
    	// check the nonce
    	if( !isset( $_POST['dynamo_meta_box_params_nonce'] ) || !wp_verify_nonce( $_POST['dynamo_meta_box_params_nonce'], 'dynamo-post-params-nonce' ) ) {
    		return;
    	}
    	// update post meta
        update_post_meta( $post_id, 'dynamo-post-params-title', esc_attr( $_POST['dynamo-post-params-title-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-featuredimg', esc_attr( $_POST['dynamo-post-params-featuredimg-value'] ) );
		//
    if( isset( $_POST['dynamo-post-params-contact-name'] ) ) {
    	// check the nonce
    	if( !isset( $_POST['dynamo_meta_box_params_nonce'] ) || !wp_verify_nonce( $_POST['dynamo_meta_box_params_nonce'], 'dynamo-post-params-nonce' ) ) {
    		return;
    	}
    	// update post meta
    	$contact_value = esc_attr( $_POST['dynamo-post-params-contact-name'] ) . ',' . esc_attr( $_POST['dynamo-post-params-contact-email'] ) . ',' . esc_attr( $_POST['dynamo-post-params-contact-copy'] );
    	$templates_value = array('contact' => $contact_value);
        update_post_meta( $post_id, 'dynamo-post-params-templates', serialize($templates_value) ); 
    }
		update_post_meta( $post_id, 'dynamo-post-params-category', esc_attr( $_POST['dynamo-post-params-category-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-header_bgcolor', esc_attr( $_POST['dynamo-post-params-header_bgcolor-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-header_img', esc_attr( $_POST['dynamo-post-params-header_img-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-subheader_use', esc_attr( $_POST['dynamo-post-params-subheader_use-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-custom_title', esc_attr( $_POST['dynamo-post-params-custom_title-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-custom_subtitle', esc_attr( $_POST['dynamo-post-params-custom_subtitle-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-subheader_img', esc_attr( $_POST['dynamo-post-params-subheader_img-value'] ) );
		update_post_meta( $post_id, 'dynamo-post-params-subheader_bgcolor', esc_attr( $_POST['dynamo-post-params-subheader_bgcolor-value'] ) );
		
 
 
    }
}  
add_action( 'save_post', 'dynamo_portfolio_setting_save' );   
function dynamo_portfolio_setting_save( $post_id ) {  
    // check the user permissions  
    if( !current_user_can( 'edit_post', $post_id ) ) {
    	return;
    }
    // avoid requests on the autosave 
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    	return; 
    }
    // check the existing of the fields and save it
	if( isset( $_POST['item_short_desc'] ) ) {
        update_post_meta( $post_id, 'item_short_desc', esc_attr( $_POST['item_short_desc'] ) );  
    }
	if( isset( $_POST['item_type'] ) ) {
        update_post_meta( $post_id, 'item_type', esc_attr( $_POST['item_type'] ) );  
    }
	if( isset( $_POST['item_desc'] ) ) {
        update_post_meta( $post_id, 'item_desc', esc_attr( $_POST['item_desc'] ) );  
    }
	if( isset( $_POST['item_video'] ) ) {
        update_post_meta( $post_id, 'item_video', esc_attr( $_POST['item_video'] ) );  
    }
	if( isset( $_POST['item_link'] ) ) {
        update_post_meta( $post_id, 'item_link', esc_attr( $_POST['item_link'] ) );  
    }
	if( isset( $_POST['item_date'] ) ) {
        update_post_meta( $post_id, 'item_date', esc_attr( $_POST['item_date'] ) );  
    }
	if( isset( $_POST['item_client'] ) ) {
        update_post_meta( $post_id, 'item_client', esc_attr( $_POST['item_client'] ) );  
    }
}   
add_action( 'save_post', 'dynamo_slide_setting_save' );   
function dynamo_slide_setting_save( $post_id ) {  
    // check the user permissions  
    if( !current_user_can( 'edit_post', $post_id ) ) {
    	return;
    }
    // avoid requests on the autosave 
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    	return; 
    }
    // check the existing of the fields and save it
	if( isset( $_POST['slide_type'] ) ) {
    update_post_meta( $post_id, 'slide_type', esc_attr( $_POST['slide_type'] ) );  
    }
	if( isset( $_POST['slide_description'] ) ) {
        update_post_meta( $post_id, 'slide_description', esc_attr( $_POST['slide_description'] ) );  
    }
	if( isset( $_POST['slide_link'] ) ) {
        update_post_meta( $post_id, 'slide_link', esc_attr( $_POST['slide_link'] ) );  
    }
}
add_action( 'save_post', 'dynamo_sidebar_setting_save' );   
function dynamo_sidebar_setting_save( $post_id ) {  
    // check the user permissions  
    if( !current_user_can( 'edit_post', $post_id ) ) {
    	return;
    }
    // avoid requests on the autosave 
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    	return; 
    }
    // check the existing of the fields and save it
	if( isset( $_POST['sidebar_description'] ) ) {
        update_post_meta( $post_id, 'sidebar_description', esc_attr( $_POST['sidebar_description'] ) );  
    }
}   
   
/**
 *
 * Code to create Featured Video metabox
 *
 **/


function dynamo_add_featured_video() {
    add_meta_box( 'dynamo_featured_video', __( 'Featured Video', 'dp-theme' ), 'dynamo_add_featured_video_metabox', 'post', 'side', 'low' );
    add_meta_box( 'dynamo_featured_video', __( 'Featured Video', 'dp-theme' ), 'dynamo_add_featured_video_metabox', 'page', 'side', 'low' );
}


function dynamo_add_featured_video_metabox() {
    global $post;


    $featured_video = get_post_meta($post->ID, '_dynamo-featured-video', true);
    
    echo '<p>';
    echo '<label>'.__('Featured video link', 'dp-theme').'</label>';
    echo '<input class=" widefat" name="dynamo_featured_video" type="text" value="'.$featured_video.'">'; ?>
	<span class="description"><?php _e("Just link, not embed code, this field is used for oEmbed.", 'dp-theme'); ?></span>
    <?php echo '<input type="hidden" name="dynamo_featured_video_nonce" id="dynamo_featured_video_nonce" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" />';
    echo '</p>';
}


function dynamo_save_featured_video(){
    global $post;
	// check nonce
    if(!isset($_POST['dynamo_featured_video_nonce']) || !wp_verify_nonce($_POST['dynamo_featured_video_nonce'], plugin_basename(__FILE__))) {
    	return is_object($post) ? $post->ID : $post;
	}
	// autosave
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return is_object($post) ? $post->ID : $post;
	}
	// user permissions
    if( !current_user_can( 'edit_post', $post_id ) ) {
    	return;
    }
	// if the value exists
    if(isset($_POST['dynamo_featured_video'])) {
	    $featured_video = $_POST['dynamo_featured_video'];


	    if($featured_video != '') {
	    	delete_post_meta($post->ID, '_dynamo-featured-video');
	    	add_post_meta($post->ID, '_dynamo-featured-video', $featured_video);
	    } else {
	    	delete_post_meta($post->ID, '_dynamo-featured-video');
	    }
    }
    
	return true;
}




add_action( 'save_post',  'dynamo_save_featured_video' );
add_action( 'admin_menu', 'dynamo_add_featured_video' );

/**
 *
 * Code to create widget showing manager
 *
 **/

// define an additional operation when save the widget
add_filter( 'widget_update_callback', 'dynamo_widget_update', 10, 4);

// definition of the additional operation
function dynamo_widget_update($instance, $new_instance, $old_instance, $widget) {	
	global $dynamo_tpl;
	// check if param was set
	if ( isset( $_POST[$dynamo_tpl->name . '_widget_rules_' . $widget->id] ) ) {	
		// get option and style value
		$options_type = get_option($dynamo_tpl->name . '_widget_rules_type');
		$options = get_option($dynamo_tpl->name . '_widget_rules');
		$styles = get_option($dynamo_tpl->name . '_widget_style');
		$styles_css = get_option($dynamo_tpl->name . '_widget_style_css');
		$responsive = get_option($dynamo_tpl->name . '_widget_responsive');
		$users = get_option($dynamo_tpl->name . '_widget_users');
		// if this option is set at first time
		if(!is_array($options_type) ) {
			$options_type = array();
		}
		// if this option is set at first time
		if(!is_array($options) ) {
			$options = array();
		}
		// if this styles is set at first time
		if( !is_array($styles) ) {
			$styles = array();
		}
		// if this styles_css is set at first time
		if( !is_array($styles_css) ) {
			$styles_css = array();
		}
		
		// if this responsive is set at first time
		if( !is_array($responsive) ) {
			$responsive = array();
		}
		// if this users is set at first time
		if( !is_array($users) ) {
			$users = array();
		}
		// set the new key in the array
		$options_type[$widget->id] = $_POST[$dynamo_tpl->name . '_widget_rules_type_' . $widget->id];
		$options[$widget->id] = $_POST[$dynamo_tpl->name . '_widget_rules_' . $widget->id];
		$styles[$widget->id] = $_POST[$dynamo_tpl->name . '_widget_style_' . $widget->id];
		$styles_css[$widget->id] = $_POST[$dynamo_tpl->name . '_widget_style_css_' . $widget->id];
		$responsive[$widget->id] = $_POST[$dynamo_tpl->name . '_widget_responsive_' . $widget->id];
		$users[$widget->id] = $_POST[$dynamo_tpl->name . '_widget_users_' . $widget->id];


		//
		// Clean up the variables
		//


		// get all widgets names
		$all_widgets = array();
		$all_widgets_assoc = get_option('sidebars_widgets'); 
		// iterate throug the sidebar widgets settings to get all active widgets names
		foreach($all_widgets_assoc as $sidebar_name => $sidebar) {
			// remember about wp_inactive_widgets and array_version fields!
			if($sidebar_name != 'wp_inactive_widgets' && is_array($sidebar) && count($sidebar) > 0) {
				foreach($sidebar as $widget_name) {
					array_push($all_widgets, $widget_name);
				}
			}
		}
		// get the widget names from the exisitng settings
		$widget_names = array_keys($options_type);
		// check for the unexisting widgets
        foreach($widget_names as $widget_name) {
            // if widget doesn't exist - remove it from the options
            if(in_array($widget_name, $all_widgets) !== TRUE) {
                if(isset($options_type) && is_array($options_type) && isset($options_type[$widget_name])) {
                    unset($options_type[$widget_name]);
                }


                if(isset($options) && is_array($options) && isset($options[$widget_name])) {
                    unset($options[$widget_name]);
                }


                if(isset($styles) && is_array($styles) && isset($styles[$widget_name])) {
                    unset($styles[$widget_name]);
                }


                if(isset($styles_css) && is_array($styles_css) && isset($styles_css[$widget_name])) {
                    unset($styles_css[$widget_name]);
                }


                if(isset($responsive) && is_array($responsive) && isset($responsive[$widget_name])) {
                    unset($responsive[$widget_name]);
                }


                if(isset($users) && is_array($users) && isset($users[$widget_name])) {
                    unset($users[$widget_name]);
                }
            }
        }

		
		// update the settings
		update_option($dynamo_tpl->name . '_widget_rules_type', $options_type);
		update_option($dynamo_tpl->name . '_widget_rules', $options);
		update_option($dynamo_tpl->name . '_widget_style', $styles);
		update_option($dynamo_tpl->name . '_widget_style_css', $styles_css);
		update_option($dynamo_tpl->name . '_widget_responsive', $responsive);
		update_option($dynamo_tpl->name . '_widget_users', $users);
	}	
	// return the widget instance
	return $instance;
}

// function to add the widget control 
function dynamo_widget_control() {	
	// get the access to the registered widget controls
	global $wp_registered_widget_controls;
	global $dynamo_tpl;
	
	// check if the widget rules are enabled
	if(get_option($dynamo_tpl->name . '_widget_rules_state') == 'Y') {
		// get the widget parameters
		$params = func_get_args();
		// find the widget ID
		$id = $params[0]['widget_id'];
		$unique_id = $id . '-' . rand(10000000, 99999999);
		// get option value
		$options_type = get_option($dynamo_tpl->name . '_widget_rules_type');
		$options = get_option($dynamo_tpl->name . '_widget_rules');
		$styles = get_option($dynamo_tpl->name . '_widget_style');
		$styles_css = get_option($dynamo_tpl->name . '_widget_style_css');
		$responsive = get_option($dynamo_tpl->name . '_widget_responsive');
		$users = get_option($dynamo_tpl->name . '_widget_users');
		// if this option is set at first time
		if( !is_array($options_type) ) {
			$options_type = array();
		}
		// if this option is set at first time
		if( !is_array($options) ) {
			$options = array();
		}
		// if this styles is set at first time
		if( !is_array($styles) ) {
			$styles = array();
		}
		
		// if this responsive is set at first time
		if( !is_array($responsive) ) {
			$responsive = array();
		}
		// if this users is set at first time
		if( !is_array($users) ) {
			$users = array();
		}
		// get the widget form callback
		$callback = $wp_registered_widget_controls[$id]['callback_redir'];
		// if the callbac exist - run it with the widget parameters
		if (is_callable($callback)) {
			call_user_func_array($callback, $params);
		}
		// value of the option
		$value_type = !empty($options_type[$id]) ? htmlspecialchars(stripslashes($options_type[$id]),ENT_QUOTES) : '';
		$value = !empty($options[$id]) ? htmlspecialchars(stripslashes($options[$id]),ENT_QUOTES) : '';	
		$style = !empty($styles[$id]) ? htmlspecialchars(stripslashes($styles[$id]),ENT_QUOTES) : '';
		$style_css = !empty($styles_css[$id]) ? htmlspecialchars(stripslashes($styles_css[$id]),ENT_QUOTES) : '';	
		$responsiveMode = !empty($responsive[$id]) ? htmlspecialchars(stripslashes($responsive[$id]),ENT_QUOTES) : '';
		$usersMode = !empty($users[$id]) ? htmlspecialchars(stripslashes($users[$id]),ENT_QUOTES) : '';	
		// 
		echo '
		<a class="dp_widget_rules_btn button">Widget rules</a>
		<div class="dp_widget_rules_wrapper'.((isset($_COOKIE['dp_last_opened_widget_rules_wrap']) && $_COOKIE['dp_last_opened_widget_rules_wrap'] == 'dp_widget_rules_form_'.$id) ? ' active' : '').'" data-id="dp_widget_rules_form_'.$id.'">
			<p>
				<label for="' . $dynamo_tpl->name . '_widget_rules_'.$id.'">'.__('Visible at: ', 'dp-theme').'</label>
				<select name="' . $dynamo_tpl->name . '_widget_rules_type_'.$id.'" id="' . $dynamo_tpl->name . '_widget_rules_type_'.$id.'" class="dp_widget_rules_select">
					<option value="all"'.(($value_type != "include" && $value_type != 'exclude') ? " selected=\"selected\"":"").'>'.__('All pages', 'dp-theme').'</option>
					<option value="exclude"'.(($value_type == "exclude") ? " selected=\"selected\"":"").'>'.__('All pages except:', 'dp-theme').'</option>
					<option value="include"'.(($value_type == "include") ? " selected=\"selected\"":"").'>'.__('No pages except:', 'dp-theme').'</option>
				</select>
			</p>
			<fieldset class="dp_widget_rules_form" id="dp_widget_rules_form_'.$unique_id.'" data-id="dp_widget_rules_form_'.$id.'">
				<legend>'.__('Select page to add', 'dp-theme').'</legend>
				 <select class="dp_widget_rules_form_select">
				 	<option value="homepage">'.__('Homepage', 'dp-theme').'</option>
				 	<option value="page:">'.__('Page', 'dp-theme').'</option>
				 	<option value="post:">'.__('Post', 'dp-theme').'</option>
				 	<option value="category:">'.__('Category', 'dp-theme').'</option>
					<option value="category_descendant:">'.__('Category with descendants', 'dp-theme').'</option>
				 	<option value="tag:">'.__('Tag', 'dp-theme').'</option>
				 	<option value="archive">'.__('Archive', 'dp-theme').'</option>
				 	<option value="author:">'.__('Author', 'dp-theme').'</option>
				 	<option value="template:">'.__('Page Template', 'dp-theme').'</option>
					<option value="taxonomy:">'.__('Taxonomy', 'dp-theme').'</option>
                    <option value="posttype:">'.__('Post type', 'dp-theme').'</option>
				 	<option value="search">'.__('Search page', 'dp-theme').'</option>
				 	<option value="page404">'.__('404 page', 'dp-theme').'</option>
				 </select>
				 <p><label>'.__('Page ID/Title/slug:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_page" /></label></p>
				 <p><label>'.__('Post ID/Title/slug:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_post" /></label></p>
				 <p><label>'.__('Category ID/Name/slug:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_category" /></label></p>
				 <p><label>'.__('Category ID:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_category_descendant" /></label></p>
				 <p><label>'.__('Tag ID/Name:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_tag" /></label></p>
				 <p><label>'.__('Author:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_author" /></label></p>
				 <p><label>'.__('Template:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_template" /></label></p>
                 <p><label>'.__('Taxonomy:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_taxonomy" /></label></p>
                 <p><label>'.__('Taxonomy term:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_taxonomy_term" /></label></p>
                 <p><label>'.__('Post type:', 'dp-theme').'<input type="text" class="dp_widget_rules_form_input_posttype" /></label></p>
                 <p><button class="dp_widget_rules_btn button-secondary">'.__('Add page', 'dp-theme').'</button></p>
				 <input type="text" name="' . $dynamo_tpl->name . '_widget_rules_'.$id.'"  id="' . $dynamo_tpl->name . '_widget_rules_'.$id.'" value="'.$value.'" class="dp_widget_rules_output" />
				 <fieldset class="dp_widget_rules_pages">
				 	<legend>'.__('Selected pages', 'dp-theme').'</legend>
				 	<span class="dp_widget_rules_nopages">'.__('No pages', 'dp-theme').'</span>
				 	<div></div>
				 </fieldset>
			</fieldset>
			<script type="text/javascript">dp_widget_control_init(\'#dp_widget_rules_form_'.$unique_id.'\');</script>';
		// create the list of suffixes
		dynamo_widget_control_styles_list($params[0]['widget_id'], $id, $style, $responsiveMode, $usersMode, $style_css);
	} else {
		// get the widget parameters
		$params = func_get_args();
		// find the widget ID
		$id = $params[0]['widget_id'];

		// get the widget form callback
		$callback = $wp_registered_widget_controls[$id]['callback_redir'];
		// if the callbac exist - run it with the widget parameters
		if (is_callable($callback)) {
			call_user_func_array($callback, $params);
		}
	}
}

add_action( 'sidebar_admin_setup', 'dynamo_add_widget_control'); 

function dynamo_add_widget_control() {	
	global $dynamo_tpl;
	global $wp_registered_widgets; 
	global $wp_registered_widget_controls;
	// get option value
	$options_type = get_option($dynamo_tpl->name . '_widget_rules_type');
	$options = get_option($dynamo_tpl->name . '_widget_rules');
	$styles = get_option($dynamo_tpl->name . '_widget_style');
	$styles_css = get_option($dynamo_tpl->name . '_widget_style_css');
	$responsive = get_option($dynamo_tpl->name . '_widget_responsive');
	$users = get_option($dynamo_tpl->name . '_widget_users');
	// if this option is set at first time
	if( !is_array($options) ) {
		$options = array();
	}
	// if this styles is set at first time
	if( !is_array($styles) ) {
		$styles = array();
	}
	// if this style CSS is set at first time
	if( !is_array($styles_css) ) {
		$styles_css = array();
	}
	// if this responsive is set at first time
	if( !is_array($responsive) ) {
		$responsive = array();
	}
	// if this users is set at first time
	if( !is_array($users) ) {
		$users = array();
	}
	// AJAX updates
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {	
		foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id ) {
			// save widget rules type
			if (isset($_POST[$dynamo_tpl->name . '_widget_rules_type_' . $widget_id])) {
				$options_type[$widget_id] = $_POST[$dynamo_tpl->name . '_widget_rules_type_' . $widget_id];
			}
			// save widget rules
			if (isset($_POST[$dynamo_tpl->name . '_widget_rules_' . $widget_id])) {
				$options[$widget_id] = $_POST[$dynamo_tpl->name . '_widget_rules_' . $widget_id];
			}
			// save widget style
			if (isset($_POST[$dynamo_tpl->name . '_widget_style_' . $widget_id])) {
				$styles[$widget_id] = $_POST[$dynamo_tpl->name . '_widget_style_' . $widget_id];
			}
			// save widget style CSS
			if (isset($_POST[$dynamo_tpl->name . '_widget_style_css_' . $widget_id])) {
				$styles_css[$widget_id] = $_POST[$dynamo_tpl->name . '_widget_style_css_' . $widget_id];
			}
			// save widget responsive
			if (isset($_POST[$dynamo_tpl->name . '_widget_responsive_' . $widget_id])) {
				$responsive[$widget_id] = $_POST[$dynamo_tpl->name . '_widget_responsive_' . $widget_id];
			}
			// save widget users
			if (isset($_POST[$dynamo_tpl->name . '_widget_users_' . $widget_id])) {
				$users[$widget_id] = $_POST[$dynamo_tpl->name . '_widget_users_' . $widget_id];
			}
		}
	}
	// save the widget id
	foreach ( $wp_registered_widgets as $id => $widget ) {	
		// save the widget id		
		$wp_registered_widget_controls[$id]['params'][0]['widget_id'] = $id;
		// do the redirection
		$wp_registered_widget_controls[$id]['callback_redir'] = $wp_registered_widget_controls[$id]['callback'];
		$wp_registered_widget_controls[$id]['callback'] = 'dynamo_widget_control';		
	}
}

function dynamo_widget_control_styles_list($widget_name, $id, $value1, $value2, $value3, $value4 = '') {
	// getting access to the template global object. 
	global $dynamo_tpl;
	// clear the widget name - get the name without number at end
	$widget_name = preg_replace('/\-[0-9]+$/mi', '', $widget_name);
	// load and parse widgets JSON file.
	$json_data = $dynamo_tpl->get_json('config','widgets.styles');
	// prepare an array of options
	$items = array('<option value="" selected="selected">'.__('None', 'dp-theme').'</option>');
	$for_only_array = array();
	$exclude_array = array();
	// iterate through all styles in the file
	foreach ($json_data as $style) {
		// flag
		$add_the_item = true;
		// check the for_only tag
		if(isset($style->for_only)) {
			$for_only_array = explode(',', $style->for_only);
			if(array_search($widget_name, $for_only_array) === FALSE) {
				$add_the_item = false;
			}
		// check the exclude tag
		} else if(isset($style->exclude)) {
			$exclude_array = explode(',', $style->exclude);
			
			if(array_search($widget_name, $exclude_array) !== FALSE) {
				$add_the_item = false;
			}
		} 
		// check the flag state
		if($add_the_item) {
			// add the item if the module isn't excluded
			array_push($items, '<option value="'.$style->css_class.'"'.(($style->css_class == $value1) ? ' selected="selected"' : '').'>'.$style->name.'</option>');
		}
	}
	// check if the items array is blank - the prepare a basic field
	if(count($items) == 1) {
		$items = array('<option value="" selected="selected">'.__('No styles available', 'dp-theme').'</option>');
	}
	// add the last option
	array_push($items, '<option value="dpcustom"'.(($value1 == 'dpcustom') ? ' selected="selected"' : '').'>'.__('Custom CSS class', 'dp-theme').'</option>');
	// output the control
	echo '<div>';
	echo '<p><label for="' . $dynamo_tpl->name . '_widget_style_'.$id.'">'.__('Widget style: ', 'dp-theme').'<select name="' . $dynamo_tpl->name . '_widget_style_'.$id.'"  id="' . $dynamo_tpl->name . '_widget_style_'.$id.'" class="dp_widget_rules_select_styles">';
	foreach($items as $item) echo $item;
	echo '</select></label></p>';
	//
	echo '<p'.(($value1 != 'dpcustom') ? ' class="dp-unvisible"' : '').'><label for="' . $dynamo_tpl->name . '_widget_style_css_'.$id.'">'.__('Custom CSS class: ', 'dp-theme').'<input type="text" name="'. $dynamo_tpl->name . '_widget_style_css_'.$id.'"  id="'. $dynamo_tpl->name . '_widget_style_class_'.$id.'" value="'.$value4.'" /></label></p>';
	// output the responsive select
	$items = array(
		'<option value="all"'.((!$value2 || $value2 == 'all') ? ' selected="selected"' : '').'>'.__('All devices', 'dp-theme').'</option>',
		'<option value="onlyDesktop"'.(($value2 == 'onlyDesktop') ? ' selected="selected"' : '').'>'.__('Desktop', 'dp-theme').'</option>',
		'<option value="onlyTablets"'.(($value2 == 'onlyTablets') ? ' selected="selected"' : '').'>'.__('Tablets', 'dp-theme').'</option>',
		'<option value="onlySmartphones"'.(($value2 == 'onlySmartphones') ? ' selected="selected"' : '').'>'.__('Smartphones', 'dp-theme').'</option>',
		'<option value="onlyTabltetsAndSmartphones"'.(($value2 == 'onlyTabltetsAndSmartphones') ? ' selected="selected"' : '').'>'.__('Tablet/Smartphones', 'dp-theme').'</option>'
	);
	//
	echo '<p><label for="' . $dynamo_tpl->name . '_widget_responsive_'.$id.'">'.__('Visible on: ', 'dp-theme').'<select name="' . $dynamo_tpl->name . '_widget_responsive_'.$id.'"  id="' . $dynamo_tpl->name . '_widget_responsive_'.$id.'">';
	//
	foreach($items as $item) {
		echo $item;
	}
	//
	echo '</select></label></p>';
	// output the user groups select
	$items = array(
		'<option value="all"'.(($value3 == null || !$value3 || $value3 == 'all') ? ' selected="selected"' : '').'>'.__('All users', 'dp-theme').'</option>',
		'<option value="guests"'.(($value3 == 'guests') ? ' selected="selected"' : '').'>'.__('Only guests', 'dp-theme').'</option>',
		'<option value="registered"'.(($value3 == 'registered') ? ' selected="selected"' : '').'>'.__('Only registered users', 'dp-theme').'</option>',
		'<option value="administrator"'.(($value3 == 'administrator') ? ' selected="selected"' : '').'>'.__('Only administrator', 'dp-theme').'</option>'
	);
	//
	echo '<p><label for="' . $dynamo_tpl->name . '_widget_users_'.$id.'">'.__('Visible for: ', 'dp-theme').'<select name="' . $dynamo_tpl->name . '_widget_users_'.$id.'"  id="' . $dynamo_tpl->name . '_widget_users_'.$id.'">';
	//
	foreach($items as $item) {
		echo $item;
	}
	//
	echo '</select></label></p></div>';
	//
	if(get_option($dynamo_tpl->name . '_widget_rules_state') == 'Y') {
		echo '</div>';
	}
	//
	echo '<hr />';
}
 
// Add the Meta Box
function dynamo_add_og_meta_box() {
    add_meta_box(
		'dynamo_og_meta_box',
		'Open Graph metatags',
		'dynamo_show_og_meta_box',
		'post',
		'normal',
		'high'
	);
	
	add_meta_box(
		'dynamo_og_meta_box',
		'Open Graph metatags',
		'dynamo_show_og_meta_box',
		'page',
		'normal',
		'high'
	);
}
// check if the Open Graph is enabled
if(get_option($dynamo_tpl->name . '_opengraph_use_opengraph') == 'Y') {
    add_action('add_meta_boxes', 'dynamo_add_og_meta_box');
}


// The Callback
function dynamo_show_og_meta_box() {
	global $dynamo_tpl, $post;
	// load custom meta fields
	$custom_meta_fields = $dynamo_tpl->get_json('config', 'opengraph');
	// Use nonce for verification
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($custom_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field->id, true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field->id.'">'.$field->label.'</label></th>
				<td>';
				switch($field->type) {
					// case items will go here
					// text
					case 'text':
						echo '<input type="text" name="'.$field->id.'" id="'.$field->id.'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field->desc.'</span>';
					break;
					
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field->id.'" id="'.$field->id.'" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field->desc.'</span>';
					break;
					
					// image
					case 'image':
						$image = 'none';   
    			            if (get_option($dynamo_tpl->name . '_og_default_image', '') != '')  {  
    			              $image = get_option($dynamo_tpl->name . '_og_default_image');   
    			            }  
						echo '<span class="dynamo_opengraph_default_image" style="display:none">'.$image.'</span>';
						if ($meta) { 
							$image = wp_get_attachment_image_src($meta, 'medium');	
							$image = $image[0];
						}
						echo	'<input name="'.$field->id.'" type="hidden" class="dynamo_opengraph_upload_image" value="'.$meta.'" />
									<img src="'.$image.'" class="dynamo_opengraph_preview_image" alt="" /><br />
										<input class="dynamo_opengraph_upload_image_button button" type="button" value="Choose Image" />
										<small><a href="#" class="dynamo_opengraph_clear_image">Remove Image</a></small>
										<br clear="all" /><span class="description">'.$field->desc.'';
					break;
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}
 
// Save the Data
function dynamo_save_custom_meta($post_id) {
    global $dynamo_tpl;
    
    if(isset($post_id)) {
		// load custom meta fields
		$custom_meta_fields = $dynamo_tpl->get_json('config', 'opengraph');
		// verify nonce
		if (isset($_POST['custom_meta_box_nonce']) && !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
			return $post_id;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		// check permissions
		if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
			} elseif (!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}
	
		// loop through fields and save the data
		foreach ($custom_meta_fields as $field) {
			$old = get_post_meta($post_id, $field->id, true);
			
			if(isset($_POST[$field->id])) {
				$new = $_POST[$field->id];
				if ($new && $new != $old) {
					update_post_meta($post_id, $field->id, $new);
				} elseif ('' == $new && $old) {
					delete_post_meta($post_id, $field->id, $old);
				}
			}
		} // end foreach
	}
}

add_action('save_post', 'dynamo_save_custom_meta');  

// Add the Meta Box for Twitter cards
function dynamo_add_twitter_meta_box() {
    add_meta_box(
                'dynamo_twitter_meta_box',
                'Twitter Cards metatags',
                'dynamo_show_twitter_meta_box',
                'post',
                'normal',
                'high'
        );
        
        add_meta_box(
                'dynamo_twitter_meta_box',
                'Twitter Cards metatags',
                'dynamo_show_twitter_meta_box',
                'page',
                'normal',
                'high'
        );
}


if(get_option($dynamo_tpl->name . '_twitter_cards') == 'Y') {
        add_action('add_meta_boxes', 'dynamo_add_twitter_meta_box');
}


// The Callback for Twiter metabox
function dynamo_show_twitter_meta_box() {
        global $dynamo_tpl, $post;
        // load custom meta fields
        $custom_meta_fields = $dynamo_tpl->get_json('config', 'twitter');
        // Use nonce for verification
        echo '<input type="hidden" name="custom_meta_box_nonce2" value="'.wp_create_nonce(basename(__FILE__)).'" />';
        // Begin the field table and loop
        echo '<table class="form-table">';
        foreach ($custom_meta_fields as $field) {
                // get value of this field if it exists for this post
                $meta = get_post_meta($post->ID, $field->id, true);
                
                // begin a table row with
                echo '<tr>
                                <th><label for="'.$field->id.'">'.$field->label.'</label></th>
                                <td>';
                                switch($field->type) {
                                        // case items will go here
                                        // text
                                        case 'text':
                                                echo '<input type="text" name="'.$field->id.'" id="'.$field->id.'" value="'.$meta.'" size="30" />
                                                        <br /><span class="description">'.$field->desc.'</span>';
                                        break;
                                        
                                        // textarea
                                        case 'textarea':
                                                echo '<textarea name="'.$field->id.'" id="'.$field->id.'" cols="60" rows="4">'.$meta.'</textarea>
                                                        <br /><span class="description">'.$field->desc.'</span>';
                                        break;
                                        
                                        // image
                                        case 'image':
                                                $image = 'none';
                                                if (get_option($dynamo_tpl->name . '_og_default_image', '') != '')  {
                                                        $image = get_option($dynamo_tpl->name . '_og_default_image'); 
                                                }
                                                echo '<span class="dynamo_opengraph_default_image" style="display:none">'.$image.'</span>';
                                                if ($meta) { 
                                                        $image = wp_get_attachment_image_src($meta, 'medium');        
                                                        $image = $image[0];
                                                }
                                                echo        '<input name="'.$field->id.'" type="hidden" class="dynamo_opengraph_upload_image" value="'.$meta.'" />
                                                                        <img src="'.$image.'" class="dynamo_opengraph_preview_image" alt="" /><br />
                                                                                <input class="dynamo_opengraph_upload_image_button button" type="button" value="Choose Image" />
                                                                                <small><a href="#" class="dynamo_opengraph_clear_image">Remove Image</a></small>
                                                                                <br clear="all" /><span class="description">'.$field->desc.'';
                                        break;
                                } //end switch
                echo '</td></tr>';
        } // end foreach
        echo '</table>'; // end table
}


function dynamo_save_custom__twitter_meta($post_id) {
    global $dynamo_tpl;
    
    if(isset($post_id)) {
                // load custom meta fields
                $custom_meta_fields = $dynamo_tpl->get_json('config', 'twitter');
                // verify nonce
                if (isset($_POST['custom_meta_box_nonce']) && !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
                        return $post_id;
                // check autosave
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                        return $post_id;
                // check permissions
                if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
                        if (!current_user_can('edit_page', $post_id))
                                return $post_id;
                        } elseif (!current_user_can('edit_post', $post_id)) {
                                return $post_id;
                }
        
                // loop through fields and save the data
                foreach ($custom_meta_fields as $field) {
                        $old = get_post_meta($post_id, $field->id, true);
                        
                        if(isset($_POST[$field->id])) {
                                $new = $_POST[$field->id];
                                if ($new && $new != $old) {
                                        update_post_meta($post_id, $field->id, $new);
                                } elseif ('' == $new && $old) {
                                        delete_post_meta($post_id, $field->id, $old);
                                }
                        }
                } // end foreach
        }
}


add_action('save_post', 'dynamo_save_custom__twitter_meta');


/**
 *
 * Code used to implement the OpenSearch
 *
 **/

// function used to put in the page header the link to the opensearch XML description file
function dynamo_opensearch_head() {
	echo '<link href="'.get_bloginfo('url').'/?opensearch_description=1" title="'.get_bloginfo('name').'" rel="search" type="application/opensearchdescription+xml" />';
}

// function used to add the opensearch_description variable
function dynamo_opensearch_query_vars($vars) {
	$vars[] = 'opensearch_description';
	return $vars;
}

// function used to generate the openserch XML description output 
function dynamo_opensearch() {
	// access to the wp_query variable
	global $wp_query;
	// check if there was an variable opensearch_description in the query vars
	if (!empty($wp_query->query_vars['opensearch_description']) ) {
		// if yes - return the XML with OpenSearch description
		header('Content-Type: text/xml');
		// the XML content
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		echo "<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">\n";
		echo "\t<ShortName>".get_bloginfo('name')."</ShortName>\n";
		echo "\t<LongName>".get_bloginfo('name')."</LongName>\n";
		echo "\t<Description>Search &quot;".get_bloginfo('name')."&quot;</Description>\n";
		echo "\t<Image width=\"16\" height=\"16\" type=\"image/x-icon\">".dynamo_file_uri('favicon.ico')."</Image>\n";
		echo "\t<Contact>".get_bloginfo('admin_email')."</Contact>\n";
		echo "\t<Url type=\"text/html\" template=\"".get_bloginfo('url')."/?s={searchTerms}\"/>\n";
		echo "\t<Url type=\"application/atom+xml\" template=\"".get_bloginfo('url')."/?feed=atom&amp;s={searchTerms}\"/>\n";
		echo "\t<Url type=\"application/rss+xml\" template=\"".get_bloginfo('url')."/?feed=rss2&amp;s={searchTerms}\"/>\n";
		echo "\t<Language>".get_bloginfo('language')."</Language>\n";
		echo "\t<OutputEncoding>".get_bloginfo('charset')."</OutputEncoding>\n";
		echo "\t<InputEncoding>".get_bloginfo('charset')."</InputEncoding>\n";
		echo "</OpenSearchDescription>";
		exit;
	}
	// if not just end the function
	return;
}

// add necessary actions and filters if OpenSearch is enabled
if(get_option($dynamo_tpl->name . "_opensearch_use_opensearch", "Y") == "Y") {
	add_action('wp_head', 'dynamo_opensearch_head');
	add_action('template_redirect', 'dynamo_opensearch');
	add_filter('query_vars', 'dynamo_opensearch_query_vars');
}

/**
 * Tests if any of a post's assigned categories are descendants of target categories
 *
 * @param int|array $cats The target categories. Integer ID or array of integer IDs
 * @param int|object $_post The post. Omit to test the current post in the Loop or main query
 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
 * @see get_term_by() You can get a category by name or slug, then pass ID to this function
 * @uses get_term_children() Passes $cats
 * @uses in_category() Passes $_post (can be empty)
 * @version 2.7
 * @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
 */
if ( ! function_exists( 'post_is_in_descendant_category' ) ) {
        function post_is_in_descendant_category( $cats, $_post = null ) {
                foreach ( (array) $cats as $cat ) {
                        // get_term_children() accepts integer ID only
                        $descendants = get_term_children( (int) $cat, 'category' );
                        if ( $descendants && in_category( $descendants, $_post ) )
                                return true;
                }
                return false;
        }
}


/**
 *
 * Code used to implement parsing shortcodes and emoticons in the text widgets
 *
 **/

if(get_option($dynamo_tpl->name . "_shortcodes_widget_state", "Y") == "Y") {
	add_filter('widget_text', 'do_shortcode');
}
	
if(get_option($dynamo_tpl->name . "_emoticons_widget_state", "Y") == "Y") {
	add_filter('widget_text', 'convert_smilies');
}



/**
 *
 * Code used to shortcode buttons in TinyMCE editor
 *
 **/
function add_dp_shortcode_buttons() {
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
    // verify the post type
	// check if WYSIWYG is enabled
		add_filter("mce_external_plugins", "add_dp_tinymce_plugin");
		add_filter('mce_buttons', 'register_dp_shortcode_buttons');
}
function add_dp_tinymce_plugin($plugin_array) {
   	$plugin_array['DPWPShortcodes'] = get_template_directory_uri() . '/dynamo_framework/tinymce/editor_plugin.js';
   	return $plugin_array;
}

function register_dp_shortcode_buttons($buttons) {
   array_push($buttons, "dp_style","dp_columns","dp_button","dp_icon","dp_map","dp_vimeo","dp_youtube");
   return $buttons;
}
// 
function dp_previous_next_post() {
    // retrieve the value for next post link
    $next_string = "Next post &rarr;";
    ob_start();
    next_post_link("%link", $next_string);
    $next_link = ob_get_clean();
    
    // retrieve the value for previous post link
    $previous_string = "&larr; Previous post";
    ob_start();
    previous_post_link("%link", $previous_string);
    $previous_link = ob_get_clean();
    
    // build output
    $return = PHP_EOL . '<div id="next-previous" class="navigation clearfix">' . PHP_EOL;
 
    // display previous link if any
    if ($previous_link) {
        $return .= '<div class="nav-previous alignleft">'. PHP_EOL;
        $return .= $previous_link. PHP_EOL;
        $return .= '</div>'. PHP_EOL;
    }
 
    // display next link if any
    if ($next_link) {
        $return .= '<div class="nav-next alignright">'. PHP_EOL;
        $return .=  $next_link . PHP_EOL;
        $return .= '</div>'. PHP_EOL;
    }
 
    $return .= '</div>';
 
    return $return;
}
//add_shortcode('previous-next-post-links', 'dp_previous_next_post');	
	// EOF