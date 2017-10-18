/**
 *
 * -------------------------------------------
 * Script for the Dynamo metaboxes
 * -------------------------------------------
 *
 **/

(function () {
    "use strict";
    // Open Graph metatags
    jQuery(function (jQuery) {
        jQuery('.dynamo_opengraph_upload_image_button').click(function () {
            var formfield = jQuery(this).siblings('.dynamo_opengraph_upload_image');
            var preview = jQuery(this).siblings('.dynamo_opengraph_preview_image');
            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
            window.send_to_editor = function (html) {
                var imgurl = jQuery('img', html).attr('src');
                var classes = jQuery('img', html).attr('class');
                var id = classes.replace(/(.*?)wp-image-/, '');
                formfield.val(id);
                preview.attr('src', imgurl);
                tb_remove();
            };
            return false;
        });


        jQuery('.dynamo_opengraph_clear_image').click(function () {
            var defaultImage = jQuery(this).parent().siblings('.dynamo_opengraph_default_image').text();
            jQuery(this).parent().siblings('.dynamo_opengraph_upload_image').val('');
            jQuery(this).parent().siblings('.dynamo_opengraph_preview_image').attr('src', defaultImage);
            return false;
        });
    });
	// Menu additional fields
    jQuery(document).ready(function ($) {
	jQuery(".megamenu-options-togle").click(function(){
		var id = jQuery(this).attr("data-id");
		var panelid = "#mega-menu-options-panel-" + id;
		jQuery(this).parent().siblings('.mega-menu-options-panel').toggle("slow");
		if (jQuery(this).hasClass('active')) {jQuery(this).removeClass('active'); jQuery(this).find('span:first').text('Show mega menu options')} else {jQuery(this).addClass('active'); jQuery(this).find('span:first').text('Hide mega menu options')}; 

  	});			 
	
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;
			 
	jQuery('.menu-upload-image').each(
		function() {
			var btnid = 'menu-item-image-button-' + jQuery(this).attr('data-parentid');
			var clearid = 'menu-item-image-clear-' + jQuery(this).attr('data-parentid');
			var imgid = 'edit-menu-item-image-' + jQuery(this).attr('data-parentid');
			var imgurl = '';
			if (jQuery('#'+imgid).val().length == 0) jQuery('#'+clearid).hide(); 
			jQuery('#'+btnid).click(function() {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = $(this);
			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				jQuery('#'+imgid).val(attachment.url);
				jQuery('#'+clearid).show();
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
			  }
 
		wp.media.editor.open(button);
			  return false;
			});

	jQuery('.add_media').on('click', function(){
		_custom_media = false;
			});

			jQuery('#'+clearid).click(function() {
				jQuery('#'+imgid).val('');
				jQuery(this).hide();
				return false;
			});
		}
	);	});

    // Page additional params
    jQuery(document).ready(function () {
        var templateSelect = jQuery('#page_template');
        var template = templateSelect.find('option:selected').val();


        jQuery(document).find('p[data-template]').removeClass('active');
        jQuery(document).find('p[data-template="' + template + '"]').addClass('active');


        templateSelect.change(function () {
            var template = templateSelect.find('option:selected').val();
            jQuery(document).find('p[data-template]').removeClass('active');
            jQuery(document).find('p[data-template="' + template + '"]').addClass('active');
        });


        templateSelect.blur(function () {
            var template = templateSelect.find('option:selected').val();
            jQuery(document).find('p[data-template]').removeClass('active');
            jQuery(document).find('p[data-template="' + template + '"]').addClass('active');
        });
    });
}());
