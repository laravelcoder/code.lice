<?php
/*
Plugin Name: InfoPopup
Plugin URI: http://chromaforge.co.uk/wordpress/plugins
Description: Create multiple popups on any page, which are all individually editable and configurable via their own easy to use admin area. Each popup creates its own easy to use shortcode - just put the relevant shortcode wherever you want to use it.
Version: 1.2
Author: Chris Williams
Author URI: http://imaginativesoftware.co.uk/wordpress
*/

/*****************************************************************************
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *****************************************************************************/

add_action('init', 'infopopup_prepare');
add_action('admin_menu', 'infopopup_prepare_admin_menu');
add_action('wp_footer', 'infopopup_add_rendering_script');

register_activation_hook(__FILE__, 'infopopup_install');
register_uninstall_hook(__FILE__, 'infopopup_uninstall');

/**
 * Make sure JQuery UI script and InfoPopup CSS are included.
 */
function infopopup_prepare(){
    wp_enqueue_script('jquery-ui-position', array('jquery-ui', 'jquery-ui-core'));
	wp_enqueue_style('infopopup-style', '/wp-content/plugins/infopopup/css/infopopup.css');
	add_shortcode('infopopup', 'infopopup_handler');
	
	global $infopopup_tags_used;
	$infopopup_tags_used = array();
}

/**
 * Installation means making sure the single table required by this plugin exists.
 */
function infopopup_install(){
	global $wpdb;
	$infopopup = $wpdb->prefix . 'infopopup';
	if($wpdb->get_var("show tables like '$infopopup'") != $infopopup){
		$sql = 
			"CREATE TABLE `$infopopup` (
				`popup_id` INTEGER NOT NULL AUTO_INCREMENT,
				`tag` VARCHAR(32) NOT NULL,
				`title` VARCHAR(2048) NULL DEFAULT '',
				`markup` TEXT NULL,
				`action` VARCHAR(12) NULL DEFAULT '',
				`css_class` VARCHAR(128) NULL DEFAULT '',
				`my_horiz` VARCHAR(12) NULL DEFAULT '',
				`my_vert` VARCHAR(12) NULL DEFAULT '',
				`at_horiz` VARCHAR(12) NULL DEFAULT '',
				`at_vert` VARCHAR(12) NULL DEFAULT '',
				`offset_horiz` INT NULL DEFAULT NULL,
				`offset_vert` INT NULL DEFAULT NULL,
				`extras` VARCHAR(255) NULL DEFAULT NULL,
				PRIMARY KEY (`popup_id`),
				UNIQUE KEY `unq_tag` (`tag`)
			);";
		
		$wpdb->query($sql);
	}
}

/**
 * Uninstalling removes all traces of infopopup from the database.
 */
function infopopup_uninstall(){
 	global $wpdb;
 	$infopopup = $wpdb->prefix . 'infopopup';
 	if($wpdb->get_var("show tables like '$infopopup'") == $infopopup){
 		$sql = "DROP TABLE IF EXISTS `$infopopup`;";
		
 		$wpdb->query($sql);
 	}
}

/**
 * One single admin page for this plugin, requiring 'edit pages' permission.
 */
function infopopup_prepare_admin_menu(){
	add_options_page('InfoPopup', 'InfoPopup', 'edit_pages', 'infopopup', 'infopopup_show_admin');
}

/**
 * Internal-use helper function to generate options for SELECT elements.
 * @param unknown $options
 * @param unknown $selected_value
 */
function _infopopup_generate_select_options($options, $selected_value){
	foreach($options as $value => $display){
		print "
	<option value='$value'";
		if($value == $selected_value){
			print " selected";
		}
		print ">$display</option>";
	}
}

/**
 * The registered handler.
 * @param unknown $attrs
 * @return string
 */
function infopopup_handler($attrs){
	global $wpdb, $infopopup_tags_used;
	$infopopup = $wpdb->prefix . 'infopopup';
	if(count($attrs)){
		if(isset($attrs['tag'])){
			$tag = $attrs['tag'];
		}else{
			$x = strpos($attrs[0], ':');
			if($x !== false && $x < strlen($attrs[0]) - 1){
				$tag = substr($attrs[0], $x + 1);
			}else{
				$tag = $attrs[0];
			}
		}
		$sql = $wpdb->prepare(
			"SELECT * FROM $infopopup WHERE `tag` = '%s'", $tag
		);
		$result = $wpdb->get_results($sql, ARRAY_A);
		if($result){
			$row = $result[0];
			$infopopup_tags_used[$tag] = $row;
			
			return "<span id='infopopup-link-$tag' class='infopopup-link' infopopup_tag='$tag'>" . stripslashes($row['title']) . "</span>";
		}
	}
	/*
	 * If the tag isn't known, return it surrounded by question-marks.
	 */
	return "?$tag?";
}

/**
 * This function is registered with wp_footer to add the popup content and supporting
 * script at the foot of the page.
 */
function infopopup_add_rendering_script(){
	global $infopopup_tags_used;
	if(!isset($infopopup_tags_used) || !count($infopopup_tags_used)){
		return;
	}
	foreach($infopopup_tags_used as $tag => $row){
		$markup = stripslashes($row['markup']);
		$css_class = $row['css_class'];
		if(!strlen($css_class)){
			$css_class = 'infopopup-popup';
		}
		print "
<!-- InfoPopup: begin displayable item $tag -->
<div style='position:absolute; display:none;' id='infopopup-source-$tag' class='infopopup-popup-base $css_class'>
	$markup
</div>
<!-- InfoPopup: end displayable item $tag -->
";
	}
	
	$my_position = $row['my_horiz'] . ' ' . $row['my_vert'];
	$at_position = $row['at_horiz'] . ' ' . $row['at_vert'];
	$offset = $row['offset_horiz'] . ' ' . $row['offset_vert'];
	print "
<!-- InfoPopup: position displayable items and bind mouse events to link elements -->
<script language='javascript'>
function infoCloseAllPopups(){";
	foreach(array_keys($infopopup_tags_used) as $tag){
		print "
	(function(\$){
		jQuery('#infopopup-source-$tag').hide();
	})(jQuery);
	";
	}
	print "
}
			
function ensureInfoPopupVisible(jqSelector){
	(function($){
		var jqElement = $(jqSelector);
		var position = $(jqElement).position();
		if(position.left < 0){
			$(jqElement).position({
				of: 'body',
				my: 'left',
				at: 'left'
			});
		}
	})(jQuery);
}
	
(function(\$){
	\$(document).ready(function(){";
	foreach($infopopup_tags_used as $tag => $row){
		print "
		if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
			\$('#infopopup-source-$tag').position({
				of: 'body',
				my: 'left center',
				at: 'left center',
				offset: '$offset'
			});
		}else{
			\$('#infopopup-source-$tag').position({
				of: \$('#infopopup-link-$tag'),
				my: '$my_position',
				at: '$at_position',
				offset: '$offset'
			});
		}";
		switch($row['action']){
			case 'mouseenter':
				print "
		\$('#infopopup-link-$tag').bind('mouseenter', function(){
			infoCloseAllPopups();
			\$('#infopopup-source-$tag').show(100);
			ensureInfoPopupVisible('#infopopup-source-$tag');
		});
		\$('#infopopup-link-$tag').bind('mouseleave', function(){
			infoCloseAllPopups();
			\$('#infopopup-source-$tag').hide(100);
		});";
				break;
				
			case 'click':
				print "
		\$('#infopopup-link-$tag').bind('click', function(event){
			event.stopPropagation();
			infoCloseAllPopups();
			\$('#infopopup-source-$tag').toggle(100);
			ensureInfoPopupVisible('#infopopup-source-$tag');
		});
		\$('html').bind('click', function(){
			infoCloseAllPopups();
		});
		\$('#infopopup-source-$tag').bind('click', function(event){
			event.stopPropagation();
		});";
				break;
				
			default:
				/*
				 * Future versions may support other event types.
				 */
				break;
		}
	}
	print "
	});
})(jQuery);
</script>
<!-- End of InfoPopup's interference with this page! -->
";
}

/**
 * Edit handler, using $_POST object, returns an associative array with message for each field
 * with a problem, and overall 'success' if updated.
 */
function infopopup_handle_edit(){
	global $wpdb;
	$infopopup = $wpdb->prefix . 'infopopup';
	
	$edit_result = array();
	$data = array();
	$success = true;
	
	/*
	 * Get values from the form...
	 */
	$field_names = array('tag', 'title', 'action', 'markup', 'css_class', 'my_horiz', 'my_vert', 'at_horiz', 'at_vert', 'offset_horiz', 'offset_vert');
	$field_formats = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d');
	foreach($field_names as $field_name){
		$data[$field_name] = trim($_POST["popup_$field_name"]);
	}
	
	$popup_id = $_POST['popup_id'];
	/*
	 * ... and make sure all are valid, beginning with the tag (required and unique)...
	 */
	if(!$data['tag']){	//	No tag?
		$success = false;
		$edit_result['popup_tag'] = __('Tag is required!', 'infopopup');
		$edit_result['class_popup_tag'] = 'infopopup-admin-error';
	}else if($data['tag'] != preg_replace( '/\s+/', '_', $data['tag'])){	//	Whitespace in tag?
		$success = false;
		$edit_result['popup_tag'] = __('Tag cannot have spaces!', 'infopopup');
		$edit_result['class_popup_tag'] = 'infopopup-admin-error';
	}else{
		$sql = $wpdb->prepare(
				"SELECT * FROM $infopopup WHERE `tag` = '%s' AND `popup_id` <> %d", $data['tag'], $popup_id
		);
		$result = $wpdb->get_results($sql, ARRAY_A);
		if($row = $result[0]){	//	Tag in use elsewhere?
			$success = false;
			$edit_result['popup_tag'] = __('The tag is already in use by another popup and must be unique!', 'infopopup');
			$edit_result['class_popup_tag'] = 'infopopup-admin-error';
		}
	}
	
	/*
	 * ... title (required)...
	 */
	if(!$data['title']){
		$success = false;
		$edit_result['popup_title'] = __('Title is required!', 'infopopup');
		$edit_result['class_popup_title'] = 'infopopup-admin-error';
	}
	
	/*
	 * ... and position offset -- if supplied, must be numeric.
	 */
	if($data['offset_horiz']){
		if(!is_numeric($data['offset_horiz'])){
			$success = false;
			$edit_result['popup_offset_horiz'] = __("Offset 'x' must be numeric! ", 'infopopup');
			$edit_result['class_popup_offset_horiz'] = 'infopopup-admin-error';
		}else{
			$data['offset_horiz'] = (int)$data['offset_horiz'];
		}
	}else{
		$data['offset_horiz'] = 0;
	}
	if($data['offset_vert']){
		if(!is_numeric($data['offset_vert'])){
			$success = false;
			$edit_result['popup_offset_vert'] = __("Offset 'y' must be numeric! ", 'infopopup');
			$edit_result['class_popup_offset_vert'] = 'infopopup-admin-error';
		}else{
			$data['offset_vert'] = (int)$data['offset_vert'];
		}
	}else{
		$data['offset_vert'] = 0;
	}

	/*
	 * So far, so good... now to try actually updating!
	 */
	if($success){
		if($popup_id < 1){
			$wpdb->insert($infopopup, $data);
		}else{
			$wpdb->update(
				$infopopup,
				$data,
				array('popup_id' => $popup_id),
				$field_formats
			);
		}
		if(mysql_error()){
			$success = false;
			$edit_result['mysql_error'] = mysql_error();
			$edit_result['popup_id'] = $popup_id;
			$edit_result['sql'] = $sql;
		}
	}
	
	$edit_result['success'] = $success;
	
	return $edit_result;
}

/**
 * Page to allow administrators to list and access edit/add/delete functions.
 */
function infopopup_show_admin(){
	global $wpdb;
	$infopopup = $wpdb->prefix . 'infopopup';
	
	$popup_id = $_POST['popup_id'];
	$delete_popup_id = 0;
	if($popup_id){
		$edit_result = infopopup_handle_edit();
	}else if($_GET['action'] == 'add'){
		$popup_id = -999;
	}else if($_GET['action'] == 'edit'){
		$popup_id = $_GET['cpid'];
	}else if($_GET['action'] == 'delete'){
		$delete_popup_id = $_GET['cpid'];
	}

	if($popup_id && (!$edit_result || !$edit_result['success'])){
		if( $edit_result['mysql_error']){
			print "<h3 class='infopopup-admin-error'>" . $edit_result['mysql_error'] . "</h3>";
		}
		include_once dirname(__FILE__) . "/includes/infopopup_edit.php";
	}else if($delete_popup_id){
		include_once dirname(__FILE__) . "/includes/infopopup_delete.php";
	}else{
?>
	<h1>InfoPopup</h1>
	<table class="widefat infopopup-admin">
		<thead>
			<tr>
				<td>Shortcode</td>
				<td>Title</td>
				<td>Action</td>
				<td>Content</td>
				<td>Position</td>
				<td>CSS class</td>
				<td>&nbsp;</td>
			</tr>
		</thead>
		<tbody>
<?php
		$sql = "SELECT * FROM $infopopup ORDER BY `popup_id` ASC";
		$result = $wpdb->get_results($sql, ARRAY_A);
		$count = 0;
		foreach($result as $row){
			$markup = htmlspecialchars($row['markup']);
			if(strlen($markup) > 40){
				if($x = strpos($markup, ' ', 40)){
					$markup = substr($markup, 0, $x) . '...';
				}
			}
			$shortcode = "[infopopup tag=" . $row['tag'] ."]";
			$position = array(
				'my_horiz' => 'center',
				'my_vert' => 'center',
				'at_horiz' => 'center',
				'at_vert' => 'center'	
			);
			foreach(array_keys($position) as $field){
				if(strlen($row[$field])){
					$position[$field] = $row[$field];
				}
			}
			$offset = array(
				'offset_horiz' => 0,
				'offset_vert' => 0
			);
			$show_offset = false;
			foreach(array_keys($offset) as $field){
				if($row[$field]){
					$offset[$field] = $row[$field];
					$show_offset = true;
				}
			}
			
			$display_position = $position['my_horiz'] . ',' . $position['my_vert']
				. ' ' . __('at') . ' '
				. $position['at_horiz'] . ',' . $position['at_vert'];
			
			if($show_offset){
				$display_position .= ' (' . $offset['offset_horiz'] . ',' . $offset['offset_vert'] . ')';
			}
?>
			<tr>
				<td><?php print $shortcode; ?></td>
				<td><?php print stripslashes($row['title']); ?></td>
				<td><?php print $row['action']; ?></td>
				<td><?php print stripslashes($markup); ?></td>
				<td><?php print $display_position ?></td>
				<td><?php print $row['css_class']; ?></td>
				<td><a href="options-general.php?page=infopopup&action=edit&cpid=<?php print $row['popup_id'] ?>">edit</a>
					|
					<a href="options-general.php?page=infopopup&action=delete&cpid=<?php print $row['popup_id'] ?>">delete</a>
				</td>
			</tr>
<?php
			$count++;
		}
		if($count == 0){
?>
		<tr>
			<td colspan="5"><?php print __("Please use the 'Add new' button to create a popup.", 'infopopup'); ?></td>
		</tr>
<?php
		}
?>
		</tbody>
	</table>
	<a href="options-general.php?page=infopopup&action=add"><button>Add new</button></a>
	
<?php
	}
}

