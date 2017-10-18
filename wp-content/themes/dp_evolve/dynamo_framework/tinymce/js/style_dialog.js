function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function insertShortcode() {
	
	var shortcodeText;
	
	
	
	
	
	
	
	var shortcodeId = document.getElementById('dp-typography-shortcode').value;
	
	
	    if (shortcodeId != 0 && shortcodeId == 'h1' ){
		shortcodeText = "[h1]This is a H1 Header[/h1]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'h2' ){
		shortcodeText = " [h2]This is a H2 Header[/h2]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'h3' ){
		shortcodeText = " [h3]This is a H3 Header[/h3]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'h4' ){
		shortcodeText = "[h4]This is a H4 Header[/h4]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'h5' ){
		shortcodeText = "[h5]This is a H5 Header[/h5]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'divider_clear' ){
		shortcodeText = "[clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'divider' ){
		shortcodeText = "[divider]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'divider_top' ){
		shortcodeText = "[divider_top]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'dropcaps' ){
		shortcodeText = "[dropcap cap='P']Your content here...[/dropcap]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'legend1' ){
		shortcodeText = "[legend1 title='Sample title']Your content here...[/legend1]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'legend2' ){
		shortcodeText = "[legend2 title='Sample title']Your content here...[/legend2]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'legend3' ){
		shortcodeText = "[legend3 title='Sample title']Your content here...[/legend3]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'emphasis1' ){
		shortcodeText = "[emphasisbold]Your content here...[/emphasisbold]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'emphasis2' ){
		shortcodeText = "[emphasisbold2]Your content here...[/emphasisbold2]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'inset_left' ){
		shortcodeText = "[inset side='left' title='Inset Left Title']Your content here...[/inset]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'inset_right' ){
		shortcodeText = "[inset side='right' title='Inset Right Title']Your content here...[/inset]"+" ";
		}
		if (shortcodeId != 0 && shortcodeId == 'blockquote' ){
		shortcodeText = "[blockquote]Your content here...[/blockquote]"+" ";
		}
		if (shortcodeId != 0 && shortcodeId == 'blockquote1' ){
		shortcodeText = "[blockquote1]Your content here...[/blockquote1]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'blockquote2' ){
		shortcodeText = "[blockquote2]Your content here...[/blockquote2]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'blockquote3' ){
		shortcodeText = "[blockquote3]Your content here...[/blockquote3]"+" ";
		}
		if (shortcodeId != 0 && shortcodeId == 'blockquote4' ){
		shortcodeText = "[blockquote4]Your content here...[/blockquote4]"+" ";	
		}	
		if ( shortcodeId == 0 ){
			tinyMCEPopup.close();
		}	
		tinyMCEPopup.editor.insertContent(shortcodeText);		
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();

	return;
}
