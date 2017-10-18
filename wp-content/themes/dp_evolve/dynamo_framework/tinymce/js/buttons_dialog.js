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
	var buttonText = document.getElementById('dp_button_text').value;
	var buttonIcon = document.getElementById('dp_button_icon').value;
	var buttonUrl = document.getElementById('dp_button_url').value;
	var buttonTarget = document.getElementById('dp_button_target').value;
	var buttonStyle = document.getElementById('dp_button_style').value;
	var buttonSize = document.getElementById('dp_button_size').value;
	var buttonColor = document.getElementById('button_bg').value;
	var buttonTColor = document.getElementById('button_text').value;
	
		if ( buttonText == 0 ){
			buttonText ='Button Text';
		}
		if ( buttonUrl == 0 ){
			buttonUrl ='#';
		}
	    
		if (buttonStyle != 'custom')
		{ if (buttonStyle == 'readon') {
		shortcodeText = "["+buttonStyle+" url='"+buttonUrl+"']"+buttonText+"[/"+buttonStyle+"]"+" ";	
			} else
		{
		shortcodeText = "[button size='"+buttonSize+"' style='"+buttonStyle+"' link='"+buttonUrl+"' linktarget='"+buttonTarget+"' icon='"+buttonIcon+"']"+buttonText+"[/button]"+" ";
		}} else { 
		shortcodeText = "[button  size='"+buttonSize+"' bgColor='"+buttonColor+"' textColor ='"+buttonTColor+"' link='"+buttonUrl+"' linktarget='"+buttonTarget+"' icon='"+buttonIcon+"']"+buttonText+"[/button]"+" ";}
		
		if ( buttonStyle == 0 ){
			tinyMCEPopup.close();
		}	
		tinyMCEPopup.editor.insertContent(shortcodeText);		
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	return;
}
