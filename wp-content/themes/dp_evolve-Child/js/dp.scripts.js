/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie = function (key, value, options) {

	// key and at least value given, set cookie...
	if (arguments.length > 1 && String(value) !== "[object Object]") {
		options = jQuery.extend({}, options);

		if (value === null || value === undefined) {
			options.expires = -1;
		}

		if (typeof options.expires === 'number') {
			var days = options.expires, t = options.expires = new Date();
			t.setDate(t.getDate() + days);
		}

		value = String(value);

		return (document.cookie = [
			encodeURIComponent(key), '=',
			options.raw ? value : encodeURIComponent(value),
			options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
			options.path ? '; path=' + options.path : '',
			options.domain ? '; domain=' + options.domain : '',
			options.secure ? '; secure' : ''
		].join(''));
	}

	// key and possibly options given, get cookie...
	options = value || {};
	var result, decode = options.raw ? function (s) {
		return s;
	} : decodeURIComponent;
	return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

/**
 *
 * Template scripts
 *
 **/

jQuery(window).load(function () {
	jQuery('#dp_status').fadeOut(); // will first fade out the loading animation
	jQuery('#dp_preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
});

/** Shifted images engine **/


function shiftImages() {
	if (typeof $DP_TABLET_WIDTH != 'undefined') {
		if (jQuery(window).width() < $DP_TABLET_WIDTH) {
			jQuery(".shifted").removeClass("moved");
			jQuery('.shifted').each(function () {
				jQuery(this).css('margin-top', '0');
				jQuery(this).css('margin-left', '0');
				jQuery(this).css('margin-right', '0');
				jQuery(this).css('margin-bottom', '0');
			});
		} else {
			jQuery('.shifted').each(function () {
				jQuery(this).css('margin-top', jQuery(this).attr('data-margintop'));
				jQuery(this).css('margin-left', jQuery(this).attr('data-marginleft'));
				jQuery(this).css('margin-right', jQuery(this).attr('data-marginright'));
				jQuery(this).css('margin-bottom', jQuery(this).attr('data-marginbottom'));

			});
			jQuery('.shifted').addClass("moved");
		}
	}
}
jQuery(document).ready(function () {
	shiftImages();//run when page first loads
});

jQuery(window).resize(function () {
	shiftImages();//run on every window resize
});

jQuery(function () {
	jQuery("input:radio[name=iconview]").change(function () {
		chosen = jQuery('input:radio[name=iconview]:checked').val();
		if (chosen == 'code') {
			jQuery('.i-name').hide();
			jQuery('.i-code').show();
		} else {
			jQuery('.i-code').hide();
			jQuery('.i-name').show();
		}
	});
});

//Fitvids//
jQuery(document).ready(function () {
	jQuery(".post,.page,.single-portfolio,#main-menu").fitVids();
});
//Widget titles indicator
jQuery(document).ready(function () {
	elements = jQuery('.arrowed .indicator');
	elements.each(function () {
		var color = jQuery(this).parent().css('backgroundColor');
		var width = parseInt(jQuery(this).parent().width()) + parseInt(jQuery(this).parent().css("padding-left")) + parseInt(jQuery(this).parent().css("padding-right"));
		width = parseInt(width / 2);
		width = width.toString() + "px";
		jQuery(this).css({'border-top-color': color, 'border-left-width': width, 'border-right-width': width});
	});


});


// onDOMLoadedContent event
jQuery(document).ready(function () {
	// Back to Top Scroll scroll
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() != 0) {
			jQuery('#back-to-top').fadeIn();
		} else {
			jQuery('#back-to-top').fadeOut();
		}

	});
	// Sticky header scroll
	jQuery(window).scroll(function () {
		if (typeof $DP_STICKY_HEADER != 'undefined') {
			if ($DP_STICKY_HEADER == 'Y') {
				var switchvalue = jQuery('#dp-head-wrap').height();
				if (jQuery(this).scrollTop() > switchvalue) {

					jQuery('#dp-navigation-wrapper').hide();
					jQuery('#dp-sticky-navigation-wrapper').show();
					jQuery(".clinic-container").addClass("sticky");
				} else {
					jQuery('#dp-navigation-wrapper').show();
					jQuery('#dp-sticky-navigation-wrapper').hide();
					jQuery(".clinic-container").removeClass("sticky");
				}
			}
		}
	});

	jQuery('#back-to-top').click(function () {
		jQuery('body,html').animate({scrollTop: 0}, 800);
	});
	// Thickbox use
	jQuery(document).ready(function () {
		if (typeof tb_init != "undefined") {
			tb_init('div.wp-caption a');//pass where to apply thickbox
		}
	});
	// style area
	if (jQuery('#dp-style-area')) {
		jQuery('#dp-style-area div').each(function (i) {
			jQuery(this).find('a').each(function (index) {
				jQuery(this).click(function (e) {
					e.stopPropagation();
					e.preventDefault();
					changeStyle(jQuery(this).attr('href').replace('#', ''));
					window.location.reload();
				});
			});
		});
	}
	// demo color variations
	if (jQuery('#dp-demo-area')) {
		jQuery('#dp-demo-area div').each(function (i) {
			jQuery(this).find('a').each(function (index) {
				jQuery(this).click(function (e) {
					e.stopPropagation();
					e.preventDefault();
					changeStyle(jQuery(this).attr('href').replace('#', ''));
					window.location.reload();
				});
			});
		});
	}
	// font-size switcher
	if (jQuery('#dp-font-size') && jQuery('#dp-mainbody')) {
		var current_fs = 100;
		jQuery('#dp-mainbody').css('font-size', current_fs + "%");

		jQuery('#dp-increment').click(function (e) {
			e.stopPropagation();
			e.preventDefault();

			if (current_fs < 150) {
				jQuery('#dp-mainbody').animate({'font-size': (current_fs + 10) + "%"}, 200);
				current_fs += 10;
			}
		});

		jQuery('#dp-reset').click(function (e) {
			e.stopPropagation();
			e.preventDefault();

			jQuery('#dp-mainbody').animate({'font-size': "100%"}, 200);
			current_fs = 100;
		});

		jQuery('#dp-decrement').click(function (e) {
			e.stopPropagation();
			e.preventDefault();

			if (current_fs > 70) {
				jQuery('#dp-mainbody').animate({'font-size': (current_fs - 10) + "%"}, 200);
				current_fs -= 10;
			}
		});
	}

	// Function to change styles
	function changeStyle(style) {
		var file = $DP_TMPL_URL + '/css/' + style;
		jQuery('head').append('<link rel="stylesheet" href="' + file + '" type="text/css" />');
		jQuery.cookie($DP_TMPL_NAME + '_style', style, {expires: 365, path: '/'});
	}


	// login popup
	if (jQuery('#dp-popup-login')) {
		var popup_overlay = jQuery('#dp-popup-overlay');
		popup_overlay.css({'opacity': '0', 'display': 'block'});
		popup_overlay.fadeOut();

		var opened_popup = null;
		var popup_login = null;
		var popup_login_h = null;
		var popup_login_fx = null;

		popup_login = jQuery('#dp-popup-login');
		popup_login.css({'opacity': 0, 'display': 'block'});
		popup_login_h = popup_login.find('.dp-popup-wrap').outerHeight();

		popup_login.animate({'opacity': 0, 'height': 0}, 200);

		jQuery('#dp-login, #dp-logout').click(function (e) {
			e.preventDefault();
			e.stopPropagation();

			popup_overlay.fadeTo(200, 0.55);
			popup_login.css("z-index", "1000002");
			popup_login.animate({'opacity': 1, 'height': popup_login_h}, 200);
			opened_popup = 'login';
		});

		popup_overlay.click(function () {
			if (opened_popup == 'login') {
				popup_overlay.fadeOut();
				popup_login.animate({
					'opacity': 0,
					'height': 0
				}, 200, function () {
					popup_login.css("z-index", "0");
				});

			}
		});
	}
});
//Woocommerce scripts //
jQuery(document).ready(function () {
//product zoom
	jQuery(document).find('.woocommerce-main-image.zoom').each(function (i, el) {
		el = jQuery(el);
		el.find('img').each(function (i, img) {
			img = jQuery(img);

			if (img.width() > 80) {
				var zoomicon = jQuery('<div class="dp-wc-zoom"><span></span></div>');
				var parent = img.parent();
				var text = jQuery('.images.wc-image-zoom').attr('data-zoomtext');
				var span = jQuery('<span class="zoom-text"></span>');
				span.text(text);

				parent.append(zoomicon);
				zoomicon.find('span').append(span);

				parent.mouseenter(function () {
					if (img.height() > 80) {
						zoomicon.attr('class', 'dp-wc-zoom active');
					}
				});
				parent.mouseleave(function () {
					zoomicon.attr('class', 'dp-wc-zoom');
				});
			}
		});
	});

	jQuery(document).find('.post-type-archive-product .wc-product-overlay, .tax-product_cat .wc-product-overlay, .tax-product_tag .wc-product-overlay').each(function (i, el) {
		el = jQuery(el);
		el.find('img').each(function (i, img) {
			img = jQuery(img);

			if (img.width() > 80) {
				var zoomicon = jQuery('<div class="dp-wc-view"><span></span></div>');
				var parent = img.parent();
				var text = jQuery('.wc-product-overlay').attr('data-viewtext');
				var span = jQuery('<span></span>').text(text);

				parent.append(zoomicon);
				zoomicon.find('span').append(span);

				parent.mouseenter(function () {
					if (img.height() > 80) {
						zoomicon.attr('class', 'dp-wc-view active');
						el.addClass('active');
					}
				});
				parent.mouseleave(function () {
					zoomicon.attr('class', 'dp-wc-view');
					el.removeClass('active');
				});
			}
		});
	});
});

//Serch popup //
jQuery(document).ready(function () {

	jQuery('#dp-header-search').click(function () {
		jQuery('#dp-header-search-form').fadeIn(500);
	});
	jQuery('#cancel-search').click(function () {
		jQuery('#dp-header-search-form').fadeOut(200);
	});
});

//Tipsy and pulsating point
jQuery(window).load(function () {
	jQuery(".dp-tipsy").tipsy({gravity: 'ne', fade: true, html: true, title: "data-tipcontent", opacity: 1});
});
jQuery(window).load(function () {
	jQuery(".dp-tipsy-t").tipsy({gravity: 's', fade: true, html: true, title: "data-tipcontent", opacity: 1});
	jQuery(".dp-tipsy-r").tipsy({gravity: 'w', fade: true, html: true, title: "data-tipcontent", opacity: 1});
});
jQuery(window).load(function () {
	jQuery(".dp-tipsy-l").tipsy({gravity: 'e', fade: true, html: true, title: "data-tipcontent", opacity: 1});
	jQuery(".dp-tipsy-b").tipsy({gravity: 'n', fade: true, html: true, title: "data-tipcontent", opacity: 1});
});
jQuery(window).load(function () {
	jQuery(".dp-tipsy1").tipsy({gravity: 'se', fade: true, html: true, title: "data-tipcontent", opacity: 1});
});

jQuery(window).load(function () {
	jQuery("#dp-social-icons.left a").tipsy({
		gravity: 'w',
		fade: true,
		html: true,
		title: "data-tipcontent",
		opacity: 1
	});
});
jQuery(window).load(function () {
	jQuery("#dp-social-icons.right a").tipsy({
		gravity: 'e',
		fade: true,
		html: true,
		title: "data-tipcontent",
		opacity: 1
	});
});

//Waypoints animation engine

jQuery(document).ready(function () {

	jQuery('.fade-in').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).animate({opacity: 1}, 800);
	}, {
		triggerOnce: true,
		offset: '100%'
	});
	jQuery('.from-left').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).animate({opacity: 1, left: 0}, {duration: 1100, easing: "easeInOutExpo"});
	}, {
		triggerOnce: true,
		offset: '80%'
	});
	jQuery('.from-right').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).animate({opacity: 1, right: 0}, {duration: 1100, easing: "easeInOutExpo"});
	}, {
		triggerOnce: true,
		offset: '90%'
	});
	jQuery('.from-top').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).addClass('isloaded');
	}, {
		triggerOnce: true,
		offset: '65%'
	});
	jQuery('.from-bottom').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).addClass('isloaded');
	}, {
		triggerOnce: true,
		offset: '80%'
	});
	jQuery('.explode').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).addClass('isloaded');
	}, {
		triggerOnce: true,
		offset: '85%'
	});
	jQuery('.colapse').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).addClass('isloaded');
	}, {
		triggerOnce: true,
		offset: '85%'
	});


})
/* Animation engine
 ================================================== */


jQuery(document).ready(function () {

	var isMobile = false;

	/* Mobile Detect
	 ================================================== */

	if (navigator.userAgent.match(/Android/i) ||
		navigator.userAgent.match(/webOS/i) ||
		navigator.userAgent.match(/iPhone/i) ||
		navigator.userAgent.match(/iPad/i) ||
		navigator.userAgent.match(/iPod/i) ||
		navigator.userAgent.match(/BlackBerry/i)) {
		isMobile = true;
	}


	/* Content Animation
	 ================================================== */


	if (isMobile == false) {
		jQuery('*[data-animated]').addClass('dpanimated');
	}


	function animated_contents() {
		jQuery(".dpanimated:appeared").each(function (i) {
			var $this = jQuery(this),
				animated = jQuery(this).data('animated');

			setTimeout(function () {
				$this.addClass(animated);
			}, 100 * i);

		});
	}

	animated_contents();
	jQuery(window).scroll(function () {
		animated_contents();
	});
})

//Isotope
jQuery(window).load(function () {

	jQuery('.portfolio-wrapper ').isotope({
		itemSelector: '.portfolio-item',
		layoutMode: 'fitRows'
	});
	jQuery('.masonry ').isotope({
		itemSelector: '.portfolio-item-wrapper',
		layoutMode: 'masonry'
	});
	jQuery(window).resize(function () {
		jQuery('.masonry ').isotope('Layout');

	});

	jQuery('.portfolio-tabs a').click(function (e) {
		e.preventDefault();

		var selector = jQuery(this).attr('data-filter');
		jQuery('.portfolio-wrapper').isotope({filter: selector});
		jQuery(this).parents('ul').find('li').removeClass('active');
		jQuery(this).parent().addClass('active');
	});
	jQuery('.blog-tabs a').click(function (e) {
		e.preventDefault();
		var selector = jQuery(this).attr('data-filter');
		jQuery('.masonry').isotope({filter: selector});
		jQuery(this).parents('ul').find('li').removeClass('active');
		jQuery(this).parent().addClass('active');
	});
});
//Scroll down button //
jQuery(document).ready(function () {

	jQuery(".rev_slider_wrapper.fullscreen-container").append("<div class='scroll-down-wrapper'><a class='scroll-down-button' ></a></div>");
	jQuery(".scroll-down-button").click(function () {
		jQuery("html, body").animate({scrollTop: jQuery(window).height()}, 1000);
	});
});
// Toolbar //
jQuery(document).ready(function () {
	var handler = jQuery('#dpToolbarButton');
	jQuery(handler).click(function () {
		jQuery('#dpToolbar').toggleClass('active');

		handler.find('i').addClass('icon-spin');

		setTimeout(function () {
			handler.find('i').removeClass('icon-spin');
		}, 800);
	});
	//Layout Switcher
	jQuery("#layout-style").change(function (e) {
		if (jQuery(this).val() == 1) {
			jQuery("body").removeClass("boxed"),
				jQuery("body").css("backgroundImage", "none");
			jQuery(window).resize();
		} else {
			jQuery("body").addClass("boxed"),
				bg = $DP_TMPL_URL + '/images/patterns/noise_pattern_with_crosslines.png';
			jQuery("body").css("backgroundImage", "url(" + bg + ")");
			jQuery(window).resize();
		}
	});
	//Background switcher
	jQuery('.dp-style-switcher-bg a').click(function () {
		var current = jQuery('.layout-style select[id=layout-style]').find('option:selected').val();
		if (current == '2') {
			var bg = jQuery(this).css("backgroundImage");
			jQuery("body").css("backgroundImage", bg);

		} else {
			alert('Please select boxed layout');
		}
	});
});


//Menu scripts

jQuery(document).ready(function () {

	function initSF() {

		jQuery(".sf-menu").superfish({
			delay: 900,
			speed: 200,
			speedOut: 'fast',
			autoArrows: false,
			animation: {opacity: 'show', height: 'show'}
		});

	}

	initSF();

});


(function ($) {
	$.fn.hoverIntent = function (handlerIn, handlerOut, selector) {

		// default configuration values
		var cfg = {
			interval: 100,
			sensitivity: 7,
			timeout: 0
		};

		if (typeof handlerIn === "object") {
			cfg = $.extend(cfg, handlerIn);
		} else if ($.isFunction(handlerOut)) {
			cfg = $.extend(cfg, {over: handlerIn, out: handlerOut, selector: selector});
		} else {
			cfg = $.extend(cfg, {over: handlerIn, out: handlerIn, selector: handlerOut});
		}

		// instantiate variables
		// cX, cY = current X and Y position of mouse, updated by mousemove event
		// pX, pY = previous X and Y position of mouse, set by mouseover and polling interval
		var cX, cY, pX, pY;

		// A private function for getting mouse position
		var track = function (ev) {
			cX = ev.pageX;
			cY = ev.pageY;
		};

		// A private function for comparing current and previous mouse position
		var compare = function (ev, ob) {
			ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
			// compare mouse positions to see if they've crossed the threshold
			if (( Math.abs(pX - cX) + Math.abs(pY - cY) ) < cfg.sensitivity) {
				$(ob).off("mousemove.hoverIntent", track);
				// set hoverIntent state to true (so mouseOut can be called)
				ob.hoverIntent_s = 1;
				return cfg.over.apply(ob, [ev]);
			} else {
				// set previous coordinates for next time
				pX = cX;
				pY = cY;
				// use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
				ob.hoverIntent_t = setTimeout(function () {
					compare(ev, ob);
				}, cfg.interval);
			}
		};

		// A private function for delaying the mouseOut function
		var delay = function (ev, ob) {
			ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
			ob.hoverIntent_s = 0;
			return cfg.out.apply(ob, [ev]);
		};

		// A private function for handling mouse 'hovering'
		var handleHover = function (e) {
			// copy objects to be passed into t (required for event object to be passed in IE)
			var ev = jQuery.extend({}, e);
			var ob = this;

			// cancel hoverIntent timer if it exists
			if (ob.hoverIntent_t) {
				ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
			}

			// if e.type == "mouseenter"
			if (e.type == "mouseenter") {
				// set "previous" X and Y position based on initial entry point
				pX = ev.pageX;
				pY = ev.pageY;
				// update "current" X and Y position based on mousemove
				$(ob).on("mousemove.hoverIntent", track);
				// start polling interval (self-calling timeout) to compare mouse coordinates over time
				if (ob.hoverIntent_s != 1) {
					ob.hoverIntent_t = setTimeout(function () {
						compare(ev, ob);
					}, cfg.interval);
				}

				// else e.type == "mouseleave"
			} else {
				// unbind expensive mousemove event
				$(ob).off("mousemove.hoverIntent", track);
				// if hoverIntent state is true, then call the mouseOut function after the specified delay
				if (ob.hoverIntent_s == 1) {
					ob.hoverIntent_t = setTimeout(function () {
						delay(ev, ob);
					}, cfg.timeout);
				}
			}
		};

		// listen for mouseenter and mouseleave
		return this.on({'mouseenter.hoverIntent': handleHover, 'mouseleave.hoverIntent': handleHover}, cfg.selector);
	};
})(jQuery);

/* Nice Scroll
 ================================================== */
jQuery(window).load(function () {
	jQuery("#dp-mobile-menu").mCustomScrollbar({
		autoHideScrollbar: true,
		theme: "light-thin"
	});
});

//Video background stuff //
jQuery(document).ready(function () {
	jQuery(".mb_ytplayer").mb_YTPlayer();
	var viewportheight = jQuery(window).height() - jQuery("#dp-head").height();
	if (jQuery("body").hasClass("menu-position-2")) viewportheight = jQuery(window).height();
	if (jQuery("body").hasClass("menu-position-3")) viewportheight = jQuery(window).height();
	if (jQuery("body").hasClass("admin-bar")) viewportheight = viewportheight - jQuery("#wpadminbar").height();
	jQuery(".fullscreen").css("height", viewportheight)
	jQuery(".dp-video-mute-button").click(function (event) {
		event.preventDefault();
		if (jQuery(".dp-video-mute-button").hasClass("unmute")) {
			jQuery(this).removeClass("unmute").addClass("mute");
			jQuery(".mb_YTVPlayer").unmuteYTPVolume();
		} else {
			jQuery(this).removeClass("mute").addClass("unmute");
			jQuery(".mb_YTVPlayer").muteYTPVolume();
		}
	});

});

//piechart settings//
jQuery(document).ready(function () {
	jQuery('.easyPieChart').easyPieChart;
	jQuery('.easyPieChart2').easyPieChart;
});
jQuery(document).ready(function () {

	jQuery('.easyPieChart').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		size = jQuery(this).data("size");
		barcolor = jQuery(this).data("barcolor");
		line = jQuery(this).data("line");
		trackcolor = jQuery(this).data("trackcolor");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).easyPieChart({
			scaleColor: 'transparent',
			size: size,
			animate: 3000,
			lineWidth: line,
			lineCap: 'butt',
			barColor: barcolor,
			trackColor: trackcolor,
			onStep: function (from, to, percent) {
				jQuery(this.el).find('.percent').text(Math.round(percent));
			}
		});
	}, {
		triggerOnce: true,
		offset: '100%'
	});
});

jQuery(document).ready(function () {

	jQuery('.easyPieChart2').waypoint(function () {
		delayvalue = jQuery(this).data("delay");
		size = jQuery(this).data("size");
		barcolor = jQuery(this).data("barcolor");
		line = jQuery(this).data("line");
		if (typeof delayvalue === "undefined") {
			delayvalue = 0
		}
		;
		jQuery(this).delay(delayvalue).easyPieChart({
			scaleColor: 'transparent',
			size: size,
			animate: 5000,
			lineWidth: line,
			lineCap: 'butt',
			barColor: barcolor,
			scaleLength: 10,
			trackColor: 'rgba(255,255,255,0.2)',
			onStep: function (from, to, percent) {
				jQuery(this.el).find('.percent').text(Math.round(percent));
			}
		});
	}, {
		triggerOnce: true,
		offset: '100%'
	});
});

/*
 **	Counter shortcode
 */
function number(num, content, target, duration) {
	if (duration) {
		var count = 0;
		var speed = parseInt(duration / num);
		var interval = setInterval(function () {
			if (count - 1 < num) {
				target.html(count);
			}
			else {
				target.html(content);
				clearInterval(interval);
			}
			count++;
		}, speed);
	} else {
		target.html(content);


	}
}

function stats(duration) {
	jQuery('.stats .num, .stats-alt .num').each(function () {
		var container = jQuery(this);
		var num = container.attr('data-num');
		var content = container.attr('data-content');
		number(num, content, container, duration);
	});
}


var $i = 1;
jQuery('.stats, .stats-alt').appear().on('appear', function () {
	if ($i === 1) {
		stats(300);
	}
	$i++;
});
/* Skills Bar Animation
 ================================================== */

jQuery('.skill-bar').each(function (i) {

	jQuery(this).appear1(function () {

		var percent = jQuery(this).find('span').attr('data-width');

		jQuery(this).find('span').animate({
			'width': percent + '%'
		}, 2700, 'easeOutCirc', function () {
		});

		jQuery(this).find('span strong').animate({
			'opacity': 1
		}, 2400);

		////100% progress bar
		if (percent == '100') {
			jQuery(this).find('span strong').addClass('full');
		}

	});

});
/* Team Image Overlay
 ================================================== */
jQuery('.team, .team-alt').on('mouseover', function () {
	var overlay = jQuery(this).find('.overlay-wrp');
	var content = jQuery(this).find('.overlay-content');
	var top = parseInt(overlay.height() * 0.5 - 4);

	overlay.stop(true, true).fadeIn(300);
	content.stop().animate({'top': top}, 400);

}).on('mouseleave', function () {
	var overlay = jQuery(this).find('.overlay-wrp');
	var content = jQuery(this).find('.overlay-content');
	var top = parseInt(overlay.height() * 0.2);

	content.stop().animate({'top': top}, 100);
	overlay.fadeOut(200);
});
/*  Alert Boxes
 ================================================== */

jQuery(document).ready(function () {
	jQuery("a.close").removeAttr("href").click(function () {
		jQuery(this).parent().fadeOut(200);
	});
});
/*  Woocommerce stuff
 ================================================== */
jQuery(document).ready(function () {
	jQuery(".orderby").selectFix({arrowWidth: '30px', arrowContent: '\ue9fd', responsive: true});
});

/*  Fix Rev Sleider and Fitvids */


jQuery(document).ready(function () {

	// see the following link for getting your slider's specific "API variable
	// http://www.themepunch.com/home/plugins/wordpress-plugins/revolution-slider-wordpress/api-tutorial/


	jQuery('body').find('.tp-caption iframe').each(function () {

		var $this = jQuery(this);

		// if "FitVids" has wrapped the video in a special div, "unwrap" it
		if ($this.parent().hasClass('fluid-width-video-wrapper')) {

			$this.unwrap();

		}

	});


});

/*  Scroll to anchor JS
 ================================================== */

jQuery(document).ready(function () {
	jQuery('.smooth-scroll a').click(function () {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = jQuery(this.hash);
			var offset = 0;
			if (jQuery('#wpadminbar').length) offset += 32;
			if (jQuery('#dp-head-wrap').length) offset += jQuery('#dp-head-wrap').height();

			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				jQuery('html,body').animate({
					scrollTop: target.offset().top - offset
				}, 900, 'easeInQuint');
				return false;
			}
		}
	});

});

