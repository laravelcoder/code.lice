/**
 *
 * -------------------------------------------
 * Script for the template.gallery.php page style
 * -------------------------------------------
 *
 **/

var dpGalleryState = 0; // number of the current slide
var dpGalleryAnimationState = 'play'; // play|stop
var dpGalleryTimer = false;

// main onLoad event used to initialize the gallery
jQuery(window).load(function() {
	if(jQuery('#gallery')) {
		dpGalleryTimer = setTimeout(function() {
			dpGalleryAutoanimation('next');
		}, 5000);
		// pagination
		jQuery('#gallery').children('ol').find('li').each(function(i, btn) {
			jQuery(btn).click(function() {
				if(i != dpGalleryState) {
					dpGalleryAnimationState = 'stop'; 
					dpGalleryAutoanimation('next', i);
				}		
			});
		});
	}
});
// gallery animation function
var dpGalleryAnimate = function(imgPrev, imgNext) {
	imgPrev.animate({
		opacity: 0
	}, 500, function() {
		imgPrev.attr('class', ' ');
	});
	
	imgNext.animate({
		opacity: 1
	}, 500, function(){
		imgNext.attr('class', 'active');
		
		dpGalleryTimer = setTimeout(function() {
			dpGalleryAutoanimation('next', null);
		}, 5000);
	});
}; 
// gallery autoanimation function
var dpGalleryAutoanimation = function(dir, nextSlide) {
	var i = dpGalleryState;
	var imgs = jQuery('#gallery figure');
	var next = nextSlide;
	
	if(nextSlide == null) {
		next = (dir == 'next') ? ((i < imgs.length - 1) ? i+1 : 0) : ((i == 0) ? imgs.length - 1 : i - 1); // dir: next|prev
	}
	
	if(dpGalleryAnimationState == 'stop') {
		clearTimeout(dpGalleryTimer);
		dpGalleryAnimationState = 'play';
	}
	
	dpGalleryAnimate(jQuery(imgs[i]), jQuery(imgs[next]));
	dpGalleryState = next;
	jQuery('#gallery').children('ol').find('li').attr('class', '');
	jQuery(jQuery('#gallery').children('ol').find('li')[next]).attr('class', 'active');
};