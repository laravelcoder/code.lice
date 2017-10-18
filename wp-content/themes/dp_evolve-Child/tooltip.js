jQuery(document).ready(function(){
  jQuery('.mcTooltipWrapper').each(function(){

    var position = jQuery(this).parent().position();
    var width = jQuery(this).parent().innerWidth();
    var height = jQuery(this).parent().innerHeight();
    var horizontal = (width/2)-175 ;
    var docWidth = jQuery(document).width();

    jQuery(this).offset( { top:0, left:0} );
    jQuery(this).offset( { top:height, left:horizontal} );
    console.log(jQuery(this).offset().left, jQuery(this).outerWidth(), jQuery(this).attr('class'));


  //  console.log(jQuery(this).offset().left, jQuery(this).attr('class'));

    jQuery(this).parent().click(function(){
      event.preventDefault();
      //jQuery(this).show().fadeIn();
//      console.log(jQuery(this).offset(), jQuery(this).parent().offset());
      if(jQuery(this).find('.mcTooltipWrapper').hasClass('hidden') ){

        jQuery('.mcTooltipWrapper').each(function(){ jQuery(this).hide().addClass('hidden'); });
        jQuery(this).find('div').each(function(){
          jQuery(this).fadeIn().removeClass('hidden');
        });
        if (jQuery(this).offset().left + jQuery(this).outerWidth() > docWidth){
          var leftOff = docwidth - ( jQuery(this).offset().left + jQuery(this).outerWidth() );
          jQuery(this).css( 'left','-='+leftOff );
        }
      } else {
        //jQuery(this).fadeOut().hide();
        jQuery(this).find('div').each(function(){
          jQuery(this).fadeOut().addClass('hidden');
        });
      }
    });
  });
/*
if(jQuery('.post-box')){
  jQuery('.post-box').matchHeight({
    byRow: true
  });
}
jQuery('.vc_col-sm-4').matchHeight({
  byRow: true
});
*/
/*
jQuery('.locator-wrapper').mousedown(function(){

  if ( jQuery('.locator-wrapper').hasClass('locator-hidden') ){
    jQuery('.locator-wrapper');.fadeIn();
  }

});
*/
});
