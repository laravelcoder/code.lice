jQuery(document).on('DOMNodeInserted', function(e) {
    if(jQuery(e.target).hasClass('wpcf7-mail-sent-ok')) {
        reveal_booking_submit_success();
    }
});

function reveal_booking_submit_success() {
    jQuery("#wpcf7-f4915-o1").hide();
    jQuery(".book-title").hide();
    jQuery(".book-subtitle").hide();
    jQuery(".book-confirmation").show();
}