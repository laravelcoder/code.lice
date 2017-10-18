<?php

function dp_filter_results($content) {
	
	$content = str_replace('src="', 'src=\"', $content);
	$content = str_replace('"</img', '\"</img', $content);
	
	return $content;
	
}

function dp_import_menu($file='') {
	global $wpdb;
	
	if (isset($_POST['import']) && $_POST['import'] == 1 && !isset($file)) {
		if ($_FILES['dp_menu_file']['error'] == UPLOAD_ERR_OK               
		      && is_uploaded_file($_FILES['dp_menu_file']['tmp_name'])) { 

		  ini_set('max_execution_time', '240');
		  set_time_limit(240);
		  
		  eval(dp_filter_results(str_replace("#wpurl#", get_bloginfo("wpurl"), file_get_contents($_FILES['dp_menu_file']['tmp_name'])))); 
		}		
	}
	if(isset($file)) {
		
		ini_set('max_execution_time', '240');
		set_time_limit(240);
		eval(str_replace("wp_redirect", "//wp_redirect", str_replace("#wpurl#", get_bloginfo("wpurl"), file_get_contents($file))));
		
	}
	
}

?>