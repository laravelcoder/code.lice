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
	
	
	
	
	
	
	
	var shortcodeId = document.getElementById('dp-columns-shortcode').value;
	
	
	    if (shortcodeId != 0 && shortcodeId == 'one_half' ){
		shortcodeText = "[one_half]Your content here...[/one_half]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_half_last' ){
		shortcodeText = "[one_half_last]Your content here...[/one_half_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_third' ){
		shortcodeText = "[one_third]Your content here...[/one_third]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_third_last' ){
		shortcodeText = "[one_third_last]Your content here...[/one_third_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'two_third' ){
		shortcodeText = "[two_third]Your content here...[/two_third]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'two_third_last' ){
		shortcodeText = "[two_third_last]Your content here...[/two_third_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_fourth' ){
		shortcodeText = "[one_fourth]Your content here...[/one_fourth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_fourth_last' ){
		shortcodeText = "[one_fourth_last]Your content here...[/one_fourth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'three_fourth' ){
		shortcodeText = "[three_fourth]Your content here...[/three_fourth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'three_fourth_last' ){
		shortcodeText = "[three_fourth_last]Your content here...[/three_fourth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_fifth' ){
		shortcodeText = "[one_fifth]Your content here...[/one_fifth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_fifth_last' ){
		shortcodeText = "[one_fifth_last]Your content here...[/one_fifth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'two_fifth' ){
		shortcodeText = "[two_fifth]Your content here...[/two_fifth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'two_fifth_last' ){
		shortcodeText = "[two_fifth_last]Your content here...[/two_fifth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'three_fifth' ){
		shortcodeText = "[three_fifth]Your content here...[/three_fifth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'three_fifth_last' ){
		shortcodeText = "[three_fifth_last]Your content here...[/three_fifth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'four_fifth' ){
		shortcodeText = "[four_fifth]Your content here...[/four_fifth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'four_fifth_last' ){
		shortcodeText = "[four_fifth_last]Your content here...[/four_fifth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_sixth' ){
		shortcodeText = "[one_sixth]Your content here...[/one_sixth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'one_sixth_last' ){
		shortcodeText = "[one_sixth_last]Your content here...[/one_sixth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'five_sixth' ){
		shortcodeText = "[five_sixth]Your content here...[/five_sixth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == 'five_sixth_last' ){
		shortcodeText = "[five_sixth_last]Your content here...[/five_sixth_last]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_2|1_2_last' ){
		shortcodeText = "[one_half]Your content here...[/one_half] [one_half_last]Your content here...[/one_half_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_3|1_3|1_3_last' ){
		shortcodeText = "[one_third]Your content here...[/one_third] [one_third]Your content here...[/one_third] [one_third_last]Your content here...[/one_third_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_3|2_3_last' ){
		shortcodeText = "[one_third]Your content here...[/one_third] [two_third_last]Your content here...[/two_third_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '2_3|1_3_last' ){
		shortcodeText = "[two_third]Your content here...[/two_third] [one_third_last]Your content here...[/one_third_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_4|1_4|1_4|1_4_last' ){
		shortcodeText = "[one_fourth]Your content here...[/one_fourth] [one_fourth]Your content here...[/one_fourth] [one_fourth]Your content here...[/one_fourth] [one_fourth_last]Your content here...[/one_fourth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_4|3_4_last' ){
		shortcodeText = "[one_fourth]Your content here...[/one_fourth] [three_fourth_last]Your content here...[/three_fourth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '3_4|1_4_last' ){
		shortcodeText = "[three_fourth]Your content here...[/three_fourth] [one_fourth_last]Your content here...[/one_fourth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_4|1_4|1_2_last' ){
		shortcodeText = "[one_fourth]Your content here...[/one_fourth] [one_fourth]Your content here...[/one_fourth] [one_half_last]Your content here...[/one_half_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_2|1_4|1_4_last' ){
		shortcodeText = "[one_half]Your content here...[/one_half] [one_fourth]Your content here...[/one_fourth] [one_fourth_last]Your content here...[/one_fourth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_4|1_2|1_4_last' ){
		shortcodeText = "[one_fourth]Your content here...[/one_fourth] [one_half]Your content here...[/one_half] [one_fourth_last]Your content here...[/one_fourth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_5|1_5|1_5|1_5|1_5_last' ){
		shortcodeText = "[one_fifth]Your content here...[/one_fifth] [one_fifth]Your content here...[/one_fifth] [one_fifth]Your content here...[/one_fifth] [one_fifth]Your content here...[/one_fifth] [one_fifth_last]Your content here...[/one_fifth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '2_5|3_5_last' ){
		shortcodeText = "[two_fifth]Your content here...[/two_fifth] [three_fifth_last]Your content here...[/three_fifth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '3_5|2_5_last' ){
		shortcodeText = "[three_fifth]Your content here...[/three_fifth] [two_fifth_last]Your content here...[/two_fifth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_6|1_6|1_6|1_6|1_6|1_6_last' ){
		shortcodeText = "[one_sixth]Your content here...[/one_sixth][one_sixth]Your content here...[/one_sixth] [one_sixth]Your content here...[/one_sixth] [one_sixth]Your content here...[/one_sixth] [one_sixth]Your content here...[/one_sixth] [one_sixth_last]Your content here...[/one_sixth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '1_6|5_6_last' ){
		shortcodeText = "[one_sixth]Your content here...[/one_sixth] [five_sixth_last]Your content here...[/five_sixth_last] [clearboth]"+" ";	
		}
		if (shortcodeId != 0 && shortcodeId == '5_6|1_6_last' ){
		shortcodeText = "[five_sixth]Your content here...[/five_sixth] [one_sixth_last]Your content here...[/one_sixth_last] [clearboth]"+" ";	
		}
		if ( shortcodeId == 0 ){
			tinyMCEPopup.close();
		}	
		tinyMCEPopup.editor.insertContent(shortcodeText);		
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	return;
}
