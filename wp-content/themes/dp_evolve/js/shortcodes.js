/**
 *
 * -------------------------------------------
 * Script for the interactive elements shortcodes
 * -------------------------------------------
 *
 **/
 jQuery.noConflict(); 
 // ---------------------------------------------------------
// Slideshow Navigation
// ---------------------------------------------------------

function paginate(idx, slide){
    return '<li><a href="#" title=""></a></li>';
}

	/* Tabs */
	jQuery(window).load(function() {
		jQuery('.tabs_container ul.tabs li:first-child a').addClass('current');
		jQuery('.tabs_container .panes div.pane:first-child').show();
		
		jQuery('.tabs_container ul.tabs li a').click(function (a) { 
			var tab = jQuery(this).parent().parent().parent(), 
				index =jQuery(this).parent().index();
			
			tab.find('ul.tabs').find('a').removeClass('current');
			jQuery(this).addClass('current');
			
			tab.find('.panes').find('div.pane').not('div.pane:eq(' + index + ')').slideUp(100);
			tab.find('.panes').find('div.pane:eq(' + index + ')').slideDown(100);
			
			a.preventDefault();
		} );
 });
	/* Toggle */
	jQuery(document).ready(function() {
		jQuery('.toggle .toggle_title').click(function (b) { 
			var toggled = jQuery(this).parent().find('.toggle_content');
			
			jQuery(this).parent().find('.toggle_content').not(toggled).slideUp();
			
			if (jQuery(this).hasClass('current')) {
				jQuery(this).removeClass('current');
			} else {
				jQuery(this).addClass('current');
			}
			
			toggled.stop(false, true).slideToggle().css( { 
				display : 'block' 
			} );
			
			b.preventDefault();
		} );
		jQuery('.toggle .toggle_title').each(function (b) { 
			var toggled = jQuery(this).parent().find('.toggle_content');
			if (jQuery(this).hasClass('current')) {
			jQuery(this).parent().find('.toggle_content').not(toggled).slideDown();			
			toggled.stop(false, true).slideToggle().css( { 
				display : 'block' 
			} );
			}
		} );
	});
	/* Accordion */
	jQuery(document).ready(function() {
		//jQuery(".accordions .acc_title").first().addClass('current');
		//jQuery(".accordions .tab_content").first().slideToggle().css( { 
		//		display : 'block' 
		//	} );
		
		jQuery('.accordions .acc_title').click(function (c) { 
			var toggled = jQuery(this).parent().find('.tab_content');
			
			jQuery(this).parent().parent().find('.tab_content').not(toggled).slideUp();
			
			if (jQuery(this).hasClass('current')) {
				jQuery(this).removeClass('current');
			} else {
				jQuery(this).parent().parent().find('.acc_title').removeClass('current');
				jQuery(this).addClass('current');
			}
			
			toggled.stop(false, true).slideToggle().css( { 
				display : 'block' 
			} );
			
			c.preventDefault();
		} );
	});
//Vertical tabs
	jQuery(document).ready( function () {
	var navh = jQuery('.vtabs').css('height');
		jQuery('.vtab_pane_inner').css({"min-height":navh});
		jQuery('.vertical_tabs ul.vtabs li:first-child').addClass('current');
		jQuery('.vertical_tabs div.vtab_pane:first').show();
		
		jQuery('.vtab_title').click(function (d) { 
			var tour = jQuery(this).parent().parent().parent().parent(), 
				index = jQuery('ul.vtabs li').index(jQuery(this).parent());
			
			tour.find('ul.vtabs').find('li').removeClass('current');
			jQuery(this).parent().addClass('current');
			
			tour.find('div.vtab_pane').not('div.vtab_pane:eq(' + index + ')').slideUp();
			tour.find('div.vtab_pane:eq(' + index + ')').slideDown();
			
			d.preventDefault();
		} );	
	});
	/* Lightbox */
	jQuery(document).ready(function() {
	jQuery('a[rel^="dp_lightbox"]').prettyPhoto({
		deeplinking: true,
		overlay_gallery: false,
		social_tools: false,
		opacity: 0.45,
		show_title: false
	});
	});
	
	


/*==================================================
	     SLIDING GRAPH
==================================================*/
jQuery(window).load(function(){
								
	function isScrolledIntoView(id)
	{
		var elem = "#" + id;
		var docViewTop = jQuery(window).scrollTop();
		var docViewBottom = docViewTop + jQuery(window).height();
	
		if (jQuery(elem).length > 0){
			var elemTop = jQuery(elem).offset().top;
			var elemBottom = elemTop + jQuery(elem).height();
		}

		return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
		  && (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
	}

	function sliding_horizontal_graph(id, speed){
		//alert(id);
		jQuery("#" + id + " li span").each(function(i){
			var j = i + 1; 										  
			var cur_li = jQuery("#" + id + " li:nth-child(" + j + ") span");
			var w = cur_li.attr("class");
			cur_li.animate({width: w + "%"}, speed);
		})
	}
	
	function graph_init(id, speed){
		jQuery(window).scroll(function(){
			if (isScrolledIntoView(id)){
				sliding_horizontal_graph(id, speed);
			}
			else{
				//jQuery("#" + id + " li span").css("width", "0");
			}
		})
		
		if (isScrolledIntoView(id)){
			sliding_horizontal_graph(id, speed);
		}
	}
	
	graph_init("graph-1", 1000);
	

});
