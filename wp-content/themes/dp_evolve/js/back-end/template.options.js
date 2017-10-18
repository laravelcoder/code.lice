/**
 *
 * -------------------------------------------
 * Script for the template options
 * -------------------------------------------
 *
 **/

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
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

// ID of the upload field 
var uploadID = '';
// common functions and objects
function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
//
dpValidation = [];
dpValidationResults = [];
dpValidationResultsTabs = [];
dpVisibility = {};
dpVisibilityDependicies = {};
//
jQuery(document).ready(function() {
	// tabs
	jQuery('#dpTabs li').each(function(i,el){
		var item = jQuery(el);
		item.click(function() {
			jQuery('#dpTabs li').removeClass('active');
			jQuery('#dpTabsContent > div').removeClass('active');
			
			item.addClass('active');
			jQuery(jQuery('#dpTabsContent > div')[i]).addClass('active');
			// save the cookie with the active tab
			jQuery.cookie(jQuery('#dpMainWrap').attr('data-theme')+'_active_tab', i, { expires: 365, path: '/' });
		});
	});
	// initialize Media uploaders
	dpMediaInit();
	// initialize validation
	dpValidateInit();
	// initialize visualisation
	dpVisibilityInit();
	// initialize switcher
	dpSwitcherInit();
	// initialize slider selector
	dpSliderInit();
	// initialize ColorPicker
	dpPickerInit();
	// initialize Background picker
	dpBackgroundInit();
	// add mini tips
	var fields = jQuery('#dpTabsContent').find('.dpInput');
	
	fields.each(function(i, field) {
		var field = jQuery(field);
		field.prev('label').miniTip();
	});	
	// saving the settings
	jQuery('.dpSave').each(function(i, button) {
		jQuery(button).click(function(event) {
			event.preventDefault();
			
			if(dpValidate()) {
				// save the settings
				var data = {
					action: 'template_save',
					security: $dp_ajax_nonce
				};
				
				var fields = jQuery('#dpTabsContent').find('.dpInput');
				
				fields.each(function(i, field) {
					var field = jQuery(field);
					if(field.hasClass('dpSwitcher') || field.hasClass('dpSelect')) {
						data[field.attr('id')] = field.find('option:selected').val();
					} else {
						data[field.attr('id')] = field.val();
					}
				});			
				// make an effect ;)
				jQuery('#dpTabsContent').find('.active').find('.dpAjaxLoading').css('opacity', 1);
				// make a request
				jQuery.post(ajaxurl, data, function(response) {
					jQuery('#dpTabsContent').find('.active').find('.dpAjaxLoading').css('opacity', 0);
				});
			}
		});
	});
});
// function to init the validation rules
function dpValidateInit() {
	jQuery('#dpTabsContent > div').each(function(i, tab) {
		dpValidation[i] = [];
		dpValidationResults[i] = [];
		dpValidationResultsTabs[i] = true;
		
		var fields = jQuery(tab).find('.dpInput');
		
		fields.each(function(j, field) {
			var data = {
				'type': 'text',
				'format': '',
				'required': ''
			};
			var field = jQuery(field);
			
			if(field.hasClass('dpSwitcher') || field.hasClass('dpSelect')) {
				data.type = 'select';	
				field.blur(function() {
					dpValidateField(field, 'select');
					dpVisibilityField(field, 'select');
				});
				field.change(function() {
					dpValidateField(field, 'select');
					dpVisibilityField(field, 'select');
				});
			} else {
				field.blur(function() {
					dpValidateField(field, 'text');
					dpVisibilityField(field, 'text');
				});
			}
			data.format = (field.attr('data-format') != '') ? new RegExp(field.attr('data-format')) : '';
			data.required = field.attr('data-required');
			dpValidation[i][j] = data;
			dpValidationResults[i][j] = [];
		});	
	});
}
// function to validate
function dpValidate() {
	// validate
	jQuery(dpValidation).each(function(i, fields) {
		var allFields = jQuery(jQuery('#dpTabsContent > div')[i]).find('.dpInput');
		dpValidationResultsTabs[i] = true;
		
		jQuery(fields).each(function(j, field) {
			var value = field.type == 'select' ? jQuery(allFields[j]).find('option:selected').val() : jQuery(allFields[j]).val();
			var data = dpValidation[i][j];
			dpValidationResults[i][j] = [];
			
			if(data.required == 'true' && jQuery(allFields[j]).get('data-visible') == 'true' && !value) {
				dpValidationResults[i][j].push('required');
				dpValidationResultsTabs[i] = false;
			}
			
			if(data.format != '' && jQuery(allFields[j]).attr('data-visible') == 'true' && !value.match(data.format)) {
				dpValidationResults[i][j].push('format');
				dpValidationResultsTabs[i] = false;
			}
		});
	});
	// change elements basic on the results
	var result = true;
	
	jQuery(dpValidationResultsTabs).each(function(i, tabCorrect) {
		if(tabCorrect) {
			jQuery(jQuery('#dpTabs li')[i]).removeClass('wrong');			
		} else {
			jQuery(jQuery('#dpTabs li')[i]).addClass('wrong');
			result = false;
		}
	});
	// validate all fields
	var fields = jQuery('#dpTabsContent').find('.dpInput');
	
	fields.each(function(i, field) {
		var field = jQuery(field);	
		dpValidateField(field, (field.hasClass('dpSwitcher') || field.hasClass('dpSelect')) ? 'select' : 'text');
	});
	
	// return the result
	return result;
}
// function to validate one field
function dpValidateField(field, type) {
	var value = (type == 'select') ? field.find('option:selected').val() : field.val();
	var format = (field.attr('data-format') != '') ? new RegExp(field.attr('data-format')) : '';
	var required = field.attr('data-required');
	var visibility = field.attr('data-visible');
	
	field.removeClass('wrong-format');
	field.removeClass('wrong-required');
	
	if(required == 'true' && visibility == 'true' && !value) {
		field.addClass('wrong-required');
	}
	
	if(format != '' && visibility == 'true' && !value.match(format)) {
		field.addClass('wrong-format');
	}
	// check the tabs
	jQuery('#dpTabsContent > div').each(function(i, tab) {
		var wrongFormat = jQuery(tab).find('.wrong-format');
		var wrongRequired = jQuery(tab).find('.wrong-required');
		
		if(wrongFormat.length == 0 && wrongRequired.length == 0) {
			if(jQuery(jQuery('#dpTabs li')[i]).hasClass('wrong')) {
				jQuery(jQuery('#dpTabs li')[i]).removeClass('wrong');
			}
		} else {
			if(!jQuery(jQuery('#dpTabs li')[i]).hasClass('wrong')) {
				jQuery(jQuery('#dpTabs li')[i]).addClass('wrong');
			}
		}
	});
}
//
function dpVisibilityInit() {
	var allFields = jQuery('#dpTabsContent').find('.dpInput');
	//
	allFields.each(function(i, field) {
		var visibility = jQuery(field).attr('data-visibility');
		
		if(visibility != '') {
			var tempVisibilityRules = visibility.split(',');
			
			for(var j = 0; j < tempVisibilityRules.length; j++) {
				tempVisibilityRules[j] = tempVisibilityRules[j].split('=');
				
				tempVisibilityRules[j] = {
										"field": tempVisibilityRules[j][0],
										"value": tempVisibilityRules[j][1]
									};
									
				var visible = jQuery(field).attr('id');
									
				if(typeof dpVisibilityDependicies[visible] !== "object") {
					dpVisibilityDependicies[visible] = [tempVisibilityRules[j]];
				} else {
					dpVisibilityDependicies[visible].push(tempVisibilityRules[j]);
				}
			}
			
			var visibilityRules = jQuery(field).attr('data-visibility').split(',');
			
			for(var j = 0; j < visibilityRules.length; j++) {
				visibilityRules[j] = visibilityRules[j].split('=');
				var usedField = visibilityRules[j][0];	
				var tempField = jQuery('*[data-name='+usedField+']');
				var type = (tempField.hasClass('dpSwitcher') || tempField.hasClass('dpSelect')) ? 'select' : 'text';
				
				visibilityRules[j] = {
										"type": type,
										"visible": jQuery(field).attr('id')
									};
									
				if(typeof dpVisibility[usedField] !== "object") {
					dpVisibility[usedField] = [visibilityRules[j]];
				} else {
					dpVisibility[usedField].push(visibilityRules[j]);
				}
			}
		}
	});
	//
	allFields.each(function(i, field) {
		dpVisibilityField(jQuery(field));
	});
}
//
function dpVisibilityField(field) {
	//
	if(dpVisibility[field.attr('data-name')]) {
		//
		var dependencies = dpVisibility[field.attr('data-name')];
		//
		for(var i = 0; i < dependencies.length; i++) {
			var dependsFrom = dpVisibilityDependicies[dependencies[i].visible];
			var flag = 'true';
			
			for(var j = 0; j < dependsFrom.length; j++) {
				var type = dpVisibility[dependsFrom[j].field].type;
				var field = jQuery('*[data-name='+dependsFrom[j].field+']');
				var value = (type == 'select') ? field.find('option:selected').val() : field.val();
				
				if(value != dependsFrom[j].value) {
					flag = 'false';
				}
			}
			
			jQuery('#' + dependencies[i].visible).parent('p').attr('data-visible', flag);
		}
	}
}
//
function dpSwitcherInit() {
	// switchers
	
	jQuery('.dpSwitcher').each(
		function(i, el) {
		var switcherid = jQuery(el).attr('id');
		var switchervalue = jQuery('#'+switcherid).val();
		if (switchervalue =='Y') {
			jQuery('#'+switcherid+'_dpswitch').addClass('enabled');
			
			} else {
			jQuery('#'+switcherid+'_dpswitch').addClass('disabled'); 
			}
			
	}
	);
	jQuery('.dpSwitcher1').click(function() {
 	if (jQuery(this).hasClass('enabled')) {jQuery(this).removeClass('enabled');
	jQuery(this).addClass('disabled');
	jQuery(this).prev('.dpSwitcher').val('N');
	dpValidateField(jQuery(this).prev('.dpSwitcher'), 'select');
	dpVisibilityField(jQuery(this).prev('.dpSwitcher'), 'select');
	} else {
	jQuery(this).removeClass('disabled');
	jQuery(this).addClass('enabled');
	jQuery(this).prev('.dpSwitcher').val('Y');
	dpValidateField(jQuery(this).prev('.dpSwitcher'), 'select');
	dpVisibilityField(jQuery(this).prev('.dpSwitcher'), 'select');
	}
});
}

//
function dpSliderInit() {
	// slider selector
	jQuery('.dpSlider').each(
		function(i, el) {
			var sliderid = jQuery(el).attr('id') + '_slider';
			var select = jQuery( this );        
			var slider = jQuery( "<div id='"+sliderid+"' class='option_slider'></div>" ).insertBefore( select ).slider({            
			min: 1,            
			max: 11,            
			range: "min",            
			value: select[ 0 ].selectedIndex + 1,            
			slide: function( event, ui ) {                
			select[ 0 ].selectedIndex = ui.value - 1;            
			}        
			});        
			jQuery( this ).change(function() {            
			slider.slider( "value", this.selectedIndex + 1 );        
			});
	}
	);
}

//
function dpPickerInit() {
	// color pickers
	jQuery('.colorSelector').each(
		function(i, el) {
		var Othis = this; //cache a copy of the this variable for use inside nested function
		var initialColor = jQuery(Othis).prev('input').attr('value');
		jQuery(Othis).children('span').css('backgroundColor', initialColor);
		jQuery(this).ColorPicker({
		color: initialColor,
		onShow: function (colpkr) {
		jQuery(colpkr).fadeIn(500);
		return false;
		},
		onHide: function (colpkr) {
		jQuery(colpkr).fadeOut(500);
		return false;
		},
		onChange: function (hsb, hex, rgb) {
		jQuery(Othis).children('span').css('backgroundColor', '#' + hex);
		jQuery(Othis).prev('input').attr('value','#' + hex);
	}
	});		
		}
	);
	jQuery('.dpColor').change(function() {
		newcolor = jQuery(this).val();
	jQuery(this).next('.colorSelector').children('span').css('backgroundColor', newcolor);
	 });
}
//
function dpBackgroundInit() {
	// color pickers
	jQuery('.dpBackground').each(
		function(i, el) {
		var bgid = jQuery(el).attr('id') + '_bg';
		var pathid = jQuery(el).attr('id') + '_path';
		if (jQuery(el).val() == 'none') jQuery('#'+bgid).hide();
		jQuery(this).change(function() {
		newbg = 'url('+jQuery('#'+pathid).val()+jQuery(this).val()+'.png)';
		jQuery('#'+bgid).css('background-image',newbg);
		if (jQuery(this).val() != "none") {
		jQuery('#'+bgid).show();	
		} else {jQuery('#'+bgid).hide();
		}
	});		
	}
	);
}

//
function dpMediaInit() {
	// image uploaders
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;
	jQuery('.dpMediaInput').each(
		function(i, el) {
			var btnid = jQuery(el).attr('id') + '_button';
			var btn1id = jQuery(el).attr('id') + '_button1'; 
			var imgid = jQuery(el).attr('id') + '_image';
			if (jQuery(el).val().length == 0) jQuery('#'+btn1id).hide(); 
			jQuery('#'+btnid).click(function() {
				uploadID = jQuery(this).prev('input');
				uploadedimg = jQuery('#'+imgid);
				clearbtn = jQuery('#'+btn1id);
				formfield = jQuery(this).prev('input').attr('name');
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button = jQuery(this);
				_custom_media = true;
				wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
				uploadID.val(attachment.url);
				uploadedimg.attr('src',attachment.url);
				uploadedimg.show();
				clearbtn.show();				
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
 
		wp.media.editor.open(button);
				return false;


			});
			jQuery('#'+btn1id).click(function() {
				jQuery(el).val('');
				jQuery('#'+imgid).hide();
				jQuery(this).hide();
				return false;
			});
		}
	);
}
//
