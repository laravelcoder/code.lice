<?php

// Bootstrap file for getting the ABSPATH constant to wp-load.php
require_once('config.php');

// check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here", 'dp-theme'));
global $dynamo_tpl; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo get_option($dynamo_tpl->name . '_branding_admin_page_template_name') .' - Columns Shortcodes Panel'; ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" /> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" src="<?php echo home_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/dynamo_framework/tinymce/js/columns_dialog.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/back-end/jquery.selectBox.min.js"></script>

    <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/back-end/dp_selectBox.js"></script>

    <base target="_self" />
    <link href="<?php echo get_template_directory_uri(); ?>/css/back-end/jquery.selectBox.css" rel="stylesheet" type="text/css">    <link href="dialog_style.css" rel="stylesheet" type="text/css">
</head>
<body>

	<form name="<?php echo $dynamo_tpl->name .'_shortcode'; ?>" action="#">
	
    <div class="panel_wrapper height280">
			
				<h3>Columns Shortcodes</h3><br/>
				<table border="0" cellpadding="4" cellspacing="0">
				 <tr>
					<td nowrap="nowrap"><label for="columns_shortcode" >Select One:</label></td>
					<td> 
                    
                    <select name="dp-columns-shortcode" id="dp-columns-shortcode" class="width420">
                    		<optgroup label="Single Columns Usages">
                       		<option value="one_half">One Half</option>
                            <option value="one_half_last">One Half Last</option>
                            <option value="one_third">One Third</option>
							<option value="one_third_last">One Third Last</option>
                            <option value="two_third">Two Third</option>
							<option value="two_third_last">Two Third Last</option>
                            <option value="one_fourth">One Fourth</option>
                            <option value="one_fourth_last">One Fourth Last</option>
                            <option value="three_fourth">Three Fourth</option>
                            <option value="three_fourth_last">Three Fourth Last</option>
                            <option value="one_fifth">One Fifth</option>
                            <option value="one_fifth_last">One Fifth Last</option>
                            <option value="two_fifth">Two Fifth</option>
                            <option value="two_fifth_last">Two Fifth Last</option>
                            <option value="three_fifth">Three Fifth</option>
                            <option value="three_fifth_last">Three Fifth Last</option>
                            <option value="four_fifth">Four Fifth</option>
                            <option value="four_fifth_last">Four Fifth Last</option>
                            <option value="one_sixth">One Sixth</option>
                            <option value="one_sixth_last">One Sixth Last</option>
                            <option value="five_sixth">Five Sixth</option>
                            <option value="five_sixth">Five Sixth Last</option>
                            </optgroup>
                            <optgroup label="Grouped Columns Usages">
                            <option value="1_2|1_2_last">One Half | One Half Last</option>
                            <option value="1_3|1_3|1_3_last">One Third | One Third | One Third Last</option>
                            <option value="1_3|2_3_last">One Third | Two Third Last</option>
                            <option value="2_3|1_3_last">Two Third | One Third Last</option>
                            <option value="1_4|1_4|1_4|1_4_last">One Fourth | One Fourth | One Fourth | One Fourth Last</option>
                            <option value="1_4|3_4_last">One Fourth | Three Fourth Last</option>
                            <option value="3_4|1_4_last">Three Fourth | One Fourth Last</option>
                            <option value="1_4|1_4|1_2_last">One Fourth | One Fourth | One Half Last</option>
                            <option value="1_2|1_4|1_4_last">One Half | One Fourth | One Fourth Last</option>
                            <option value="1_4|1_2|1_4_last">One Fourth | One Half | One Fourth Last</option>
                            <option value="1_5|1_5|1_5|1_5|1_5_last">One Fifth | One Fifth | One Fifth | One Fifth | One Fifth Last</option>
                            <option value="2_5|3_5_last">Two Fifth | Three Fifth Last</option>
                            <option value="3_5|2_5_last">Three Fifth | Two Fifth Last</option>
                            <option value="1_6|1_6|1_6|1_6|1_6|1_6_last">One Sixth | One Sixth | One Sixth | One Sixth | One Sixth | One Sixth Last</option>
                            <option value="1_6|5_6_last">One Sixth | Five Sixth Last</option>
                            <option value="5_6|1_6_last">Five Sixth | One Sixth Last</option>
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