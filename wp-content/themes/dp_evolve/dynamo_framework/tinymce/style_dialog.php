<?php

// Bootstrap file for getting the ABSPATH constant to wp-load.php
require_once('config.php');

// check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here", 'dp-theme'));
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo get_option($dynamo_tpl->name . '_branding_admin_page_template_name') .' - Style Shortcodes Panel'; ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" /> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" src="<?php echo home_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/dynamo_framework/tinymce/js/style_dialog.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/back-end/jquery.selectBox.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/back-end/dp_selectBox.js"></script>

    <base target="_self" />
<link href="<?php echo get_template_directory_uri(); ?>/css/back-end/jquery.selectBox.css" rel="stylesheet" type="text/css">
<link href="dialog_style.css" rel="stylesheet" type="text/css">
</head>
<body>

	<form name="<?php echo $dynamo_tpl->name .'_shortcode'; ?>" action="#">
	
    <div class="panel_wrapper height255">
					           
			<h3>Typography Shortcodes</h3><br/>
				<table border="0" cellpadding="4" cellspacing="0">
				 <tr>
					<td nowrap="nowrap"><label for="typography_shortcode" >Select One:</label></td>
					<td> 
                   <select name="dp-typography-shortcode" id="dp-typography-shortcode" class="width220" >
                           <optgroup label="Headers">
                             <option value="h1">Header H1</option>
                            <option value="h2">Header H2</option>
                            <option value="h3">Header H3</option>
                            <option value="h4">Header H4</option>
                            <option value="h5">Header H5</option>
					 </optgroup>
                            <optgroup label="Dividers">
                             <option value="divider">Divider</option>
                            <option value="divider_top">Divider to top</option>
                            <option value="divider_clear">Divider clear</option>
                            </optgroup>
                            <optgroup label="Legends & Insets & Emphasis styles">
                            <option value="dropcaps">Dropcaps</option>
                            <option value="legend1">Legend 1</option>
                            <option value="legend2">Legend 2</option>
                            <option value="legend3">Legend 3 accented</option> 
                            <option value="emphasis1">Emphasis 1</option>
                            <option value="emphasis2">Emphasis 2</option>
                            </optgroup>
                            <optgroup label="Blockquote style">
                            <option value="blockquote">Blockquote</option>
                            </optgroup>
                    </select>
					</td>
				  </tr>
				</table>
				<p class="usage">Select a shortcode and click the "Insert" button to add it to your page.</p>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php echo "Cancel"; ?>" onClick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php echo "Insert"; ?>" onClick="insertShortcode();" />
		</div>
	</div>
</form>
</body>
</html>
