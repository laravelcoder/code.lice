jQuery(document).ready(function($){

  $('.nav-tabs a').click(function(){
    event.preventDefault();
    var thisID = $(this).attr('aria-controls');
    console.log(thisID);
      $('.tab-content').fadeOut(1);
    $('.tab-pane').each(function(){
        if($(this).attr('id') == thisID ){
          console.log('added')
          $(this).addClass('active');
          $('.tab-content').fadeIn();
        }else{
          $(this).removeClass('active');
        }
    });
  });

});
