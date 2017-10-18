jQuery(window).load(function(){	
    jQuery('.dp-news-slideshow').each(function(i, carousel){
    	carousel = jQuery(carousel)
		var current_position = 0;
		var items = carousel.find('.dp-ns-art');
		var animate = false;
		var wrapper = carousel.find('.dp-ns-arts-scroll');
		carousel.find('.dp-ns-arts-scroll').first().css('width', (jQuery(items[0]).outerWidth() * items.length) + 2);
		
		var margin = parseInt(carousel.find('.dp-ns-art').first().css('margin-right'));
		var offset = carousel.find('.dp-ns-art').first().outerWidth() + margin;
		var size = carousel.find('.dp-ns-arts').first().outerWidth();
		var carouselSize = (jQuery(items[0]).outerWidth() + margin) * items.length;
		var visibleItemCount = Math.floor(size / offset);
		var itemcount = carousel.find('.dp-ns-art').length;
	
		wrapper.css('margin-left', 0);
		current_art = visibleItemCount;
		
		if(itemcount > visibleItemCount) {
			if(carousel.find('.dp-ns-prev')) {
				carousel.find('.dp-ns-prev').click(function() {
					animate = true;
					
					if(current_position <= 0) {
						current_position = carouselSize - size - (2 * itemcount);
					} else {
						current_position -= offset;
					}	
	
					wrapper.animate({'margin-left': -1 * current_position},{duration: 'slow',easing: 'easeInOutQuart'});
				});
			}
			
			if(carousel.find('.dp-ns-next')) {
				carousel.find('.dp-ns-next').click(function() {
					animate = true;
					
					if(current_position <= carouselSize - size - (2 * itemcount)) {
						current_position += offset;
					} else {
						current_position = 0;
					}
		
					wrapper.animate({'margin-left': -1 * current_position},{duration: 'slow',easing: 'easeInOutQuart'});

				});
			}
		}
	});
});