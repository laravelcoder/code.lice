//Paralax setting //	
jQuery(window).load(function(){
		jQuery('.parallax-bg').each(function(){
			jQuery(this).css( {
			'backgroundImage': jQuery(this).parent().css( 'backgroundImage' )
		} );
		var otherStyles = '';
            if ( typeof jQuery(this).parent().attr('style') !== 'undefined' ) {
                otherStyles = jQuery(this).parent().attr('style') + ';';
            }
            jQuery(this).parent().attr('style', otherStyles + 'background-image: none !important; background-color: transparent !important;');
			jQuery(this).parallax('50%', jQuery(this).data('speed'));
		});
	});