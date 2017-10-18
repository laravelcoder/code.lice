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
	var geocoding = document.getElementById('dp-map-geocoding').value;	
	var lat = document.getElementById('dp_map_lat').value;
	var long = document.getElementById('dp_map_long').value;
	var address = document.getElementById('dp_map_address').value;
	var width = document.getElementById('dp_map_width').value;
	var height = document.getElementById('dp_map_height').value;
	var zoom = document.getElementById('dp_map_zoom').value;
	var info = document.getElementById('dp-map-info').value;
	var infotext = document.getElementById('dp_map_infotext').value;
	var id = document.getElementById('dp_map_id').value;
	var mapcontrol = document.getElementById('dp-map-control').value;
	var streetview = document.getElementById('dp-map-street').value;
	var zoomcontrol = document.getElementById('dp-map-zoomcontrol').value;
	if (width == 0 ) width = 910;
	if (height == 0 ) height = 400;
	if (zoom == 0) zoom =12;
	if (id == 0) id='map-canvas' ;
	 if (geocoding == 'coor') {
		 shortcodeText = '[gmap id="'+id+'" width="'+width+'" height="'+height+'" zoom="'+zoom+'" long="'+long+'" lat="'+lat+'" mapcontrol="'+mapcontrol+'" zoomcontrol="'+zoomcontrol+'" streetview="'+streetview+'"';
		 if (info == '1') {shortcodeText = shortcodeText+' text="'+infotext+'"';};
		 shortcodeText = shortcodeText+'] ';
	 }
		
	 if (geocoding == 'address') {
		 shortcodeText = '[gmap id="'+id+'" width="'+width+'" height="'+height+'" zoom="'+zoom+'" address="'+address+'" mapcontrol="'+mapcontrol+'" zoomcontrol="'+zoomcontrol+'" streetview="'+streetview+'"';
		 if (info == '1') {shortcodeText = shortcodeText+' text="'+infotext+'"';};
		 shortcodeText = shortcodeText+'] ';
	 }
	 
		if ( lat == 0 && long == 0 && address ==0 ){
			tinyMCEPopup.close();
		}	
		tinyMCEPopup.editor.insertContent(shortcodeText);		
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	return;
}
