// JavaScript Document

jQuery(document).ready(function(){
		jQuery('.icon_selector_field').each(function(){
			if( jQuery(this).val().length != 0 ) {
			previewid = '#'+jQuery(this).data("preview");
			jQuery(previewid).html('<i class="'+ jQuery(this).val() + '"></i>');
    	}

		});
		jQuery('.icon_selector_field').change(function(){
			previewid = '#'+jQuery(this).data("preview");
			jQuery(previewid).html('<i class="'+ jQuery(this).val() + '"></i>');
		});

});
