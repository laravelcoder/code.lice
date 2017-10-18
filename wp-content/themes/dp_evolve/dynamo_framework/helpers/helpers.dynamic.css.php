<?php
function CompileOptionsLess($inputFile) {
	global $dynamo_tpl;
    require_once ( get_template_directory() . '/dynamo_framework/lib/lessc.inc.php' );
    $less = new lessc;
    $less->setPreserveComments(true);
	$url = "'".get_template_directory_uri()."'";
	$body_bg_image = "'".get_option($dynamo_tpl->name . '_body_bg_image')."'";
	$header_area_bgimage = "'".get_option($dynamo_tpl->name . '_header_area_bgimage')."'";
	$subheader_area_bgimage = "'".get_option($dynamo_tpl->name . '_subheader_area_bgimage')."'";
	$branding_logo_image = "'".get_option($dynamo_tpl->name . '_branding_logo_image')."'";
	$sticky_header_bgcolor = hexToRGBA(get_option($dynamo_tpl->name . '_sticky_header_bgcolor'),get_option($dynamo_tpl->name . '_sticky_header_opacity'));
    $footer_bg_image =  "'".get_option($dynamo_tpl->name . '_footer_bg_image')."'";
	$footerbgtype = 'n';
	if (get_option($dynamo_tpl->name . '_footer_pattern','none') != 'none') $footerbgtype = 'p';
	if (get_option($dynamo_tpl->name . '_footer_bg_image') != '') $footerbgtype = 'i';
	$less->setVariables(array(
		"url" => $url,
		"maincontent_accent_color" => get_option($dynamo_tpl->name . '_maincontent_accent_color','#5F8CB4'),
		"maincontent_secondary_accent_color" => get_option($dynamo_tpl->name . '_maincontent_secondary_accent_color','#213344'),
		"maincontent_third_accent_color" => get_option($dynamo_tpl->name . '_maincontent_third_accent_color','#232D37'),
		"page_wrap_state" => get_option($dynamo_tpl->name . '_page_wrap_state','streched'),
		"page_bgcolor" => get_option($dynamo_tpl->name . '_page_bgcolor','#ffffff'),
		"body_bg_image_state" => get_option($dynamo_tpl->name . '_body_bg_image_state','N'),
		"body_bg_image" => $body_bg_image,
		"body_bgcolor" => get_option($dynamo_tpl->name . '_body_bgcolor','#ffffff'),
		"body_pattern" => get_option($dynamo_tpl->name . '_body_pattern','none'),
		"page_pattern" => get_option($dynamo_tpl->name . '_page_pattern','none'),
		"fontsize_body" => get_option($dynamo_tpl->name . '_fontsize_body','14px'),
		"fontsize_h1" => get_option($dynamo_tpl->name . '_fontsize_h1','32px'),
		"fontsize_h2" => get_option($dynamo_tpl->name . '_fontsize_h2','24px'),
		"fontsize_h3" => get_option($dynamo_tpl->name . '_fontsize_h3','20px'),
		"fontsize_h4" => get_option($dynamo_tpl->name . '_fontsize_h4','18px'),
		"fontsize_h5" => get_option($dynamo_tpl->name . '_fontsize_h5','16px'),
		"fontsize_h6" => get_option($dynamo_tpl->name . '_fontsize_h6','14px'),
        "header_area_bg_color" => get_option($dynamo_tpl->name . '_header_area_bg_color','#232D37'),
		"header_area_pattern" => get_option($dynamo_tpl->name . '_header_area_pattern','none'),
		"header_area_text_color" => get_option($dynamo_tpl->name . '_header_area_text_color','#f5f5f5'),
		"header_area_link_color" => get_option($dynamo_tpl->name . '_header_area_link_color','#5F8CB4'),
		"header_area_hlink_color" =>get_option($dynamo_tpl->name . '_header_area_hlink_color','#f2f2f2'),
		"header_area_bgimage" => $header_area_bgimage,
		"subheader_area_bg_color" => get_option($dynamo_tpl->name . '_subheader_area_bg_color','#232D37'),
		"subheader_area_pattern" => get_option($dynamo_tpl->name . '_subheader_area_pattern','none'),
		"subheader_area_text_color" => get_option($dynamo_tpl->name . '_subheader_area_text_color', '#ffffff'),
		"subheader_area_bgimage" => $subheader_area_bgimage,
		"branding_logo_type" => get_option($dynamo_tpl->name . '_branding_logo_type'),
		"branding_logo_image" => $branding_logo_image,
		"branding_logo_image_width" => get_option($dynamo_tpl->name . '_branding_logo_image_width','160').'px',
		"branding_logo_image_height" => get_option($dynamo_tpl->name . '_branding_logo_image_height','50').'px',
		"branding_logo_top_margin" => get_option($dynamo_tpl->name . '_branding_logo_top_margin','30').'px',
		"header_bg_color" => get_option($dynamo_tpl->name . '_header_bg_color','transparent'),
		"sticky_header_bgcolor" => $sticky_header_bgcolor,
		"mainmenu_hlink_color" => get_option($dynamo_tpl->name . '_mainmenu_hlink_color','#5F8CB4'),
		"mainmenu_link_color" => get_option($dynamo_tpl->name . '_mainmenu_link_color','#2d3e52'),
		"subheader_bgcolor" => get_option($dynamo_tpl->name . '_subheader_bgcolor','#000'),
		"subheader_pattern" => get_option($dynamo_tpl->name . '_subheader_pattern','none'),
		"subheader_area_bgimage" => $subheader_area_bgimage,
		"subheader_text_color" => get_option($dynamo_tpl->name . '_subheader_text_color', '#ffffff'),
		"maincontent_text_color" => get_option($dynamo_tpl->name . '_maincontent_text_color','#7A7A7A'),
		"maincontent_link_color" => get_option($dynamo_tpl->name . '_maincontent_link_color', '#5F8CB4'),
		"maincontent_hlink_color" => get_option($dynamo_tpl->name . '_maincontent_hlink_color','#76797C'),
		"footer_bg_color" => get_option($dynamo_tpl->name . '_footer_bg_color','#232D37'),
		"footer_bg_image" => $footer_bg_image,
		"footer_pattern" => get_option($dynamo_tpl->name . '_footer_pattern','none'),
		"footer_text_color" => get_option($dynamo_tpl->name . '_footer_text_color','#BCC1C5'),
		"footer_header_color" => get_option($dynamo_tpl->name . '_footer_header_color','#ffffff'),
		"footer_link_color" => get_option($dynamo_tpl->name . '_footer_link_color','#ffffff'),
		"footer_hlink_color" => get_option($dynamo_tpl->name . '_footer_hlink_color','#5F8CB4'),
		"footerbgtype" => $footerbgtype
    ));
	$upload_dir = wp_upload_dir();
	$dynamic_css_path		= $upload_dir['basedir'];
    $less->compileFile(get_template_directory() . '/css/less/' . $inputFile, $dynamic_css_path . '/dp-evolve-dynamic.css');
}
function addCustomCSS() {
	global $dynamo_tpl;
	$customcss = get_option($dynamo_tpl->name . '_custom_css_code');
	$output = '';
	if ($customcss != '') {
	$output="<style type='text/css'>".$customcss."</style>";
	}
	echo $output;
}
add_action('wp_head','addCustomCSS');
?>