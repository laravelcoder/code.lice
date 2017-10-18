<?php
/*
 * Should only ever be included in another page as no reference is made to global $wpdb, etc.
 * 
 * Will use $handle_result if available from the including page.
 */
if(!(function_exists('current_user_can') && current_user_can('manage_options'))){
	header("HTTP/1.0 403 Forbidden");
?>
	<h1>Forbidden</h1>
	<p>This page should be accessed via the administration menu and only by an
	authorised logged-in user.</p>
<?php
	exit();
}
$popup_id = $_POST['popup_id'];
$redisplay = $popup_id;
if(!$popup_id){
	$popup_id = $_GET['cpid'] ? $_GET['cpid'] : 0;
}
if($popup_id && is_numeric($popup_id)){
	if($redisplay){
		$row = array(
			'tag' => $_POST['popup_tag'],
			'title' => $_POST['popup_title'],
			'action' => $_POST['popup_action'],
			'markup' => $_POST['popup_markup'],
			'css_class' => $_POST['popup_css_class'],
			'my_horiz' => $_POST['popup_my_horiz'],
			'my_vert' => $_POST['popup_my_vert'],
			'at_horiz' => $_POST['popup_at_horiz'],
			'at_vert' => $_POST['popup_at_vert'],
			'offset_horiz' => $_POST['popup_offset_horiz'],
			'offset_vert' => $_POST['popup_offset_vert'],
		);
	}else{
		$sql = $wpdb->prepare(
			"SELECT * FROM $infopopup WHERE `popup_id` = %d", $popup_id
		);
		$result = $wpdb->get_results($sql, ARRAY_A);
		$row = $result[0];
	}
}else if(!($popup_id > 0)){
	$row = array(
		'my_horiz' => 'center',
		'my_vert' => 'center',
		'at_horiz' => 'center',
		'at_vert' => 'center',
		'offset_horiz' => '',
		'offset_vert' => '',
	);
}
if($popup_id && !$row){
?>
<h3>Hmmm, <?php print $popup_id ?> isn't a proper id is it?</h3>
<?php
}else{
	$row['markup'] = stripslashes($row['markup']);
	$row['title'] = stripslashes($row['title']);
	if(!$edit_result){
		$edit_result = array();
	}
	if($popup_id > 0){
		print "
 	<h1>Edit InfoPopup '" . $row['tag'] . "'</h1>";
	}else{
		print "
	<h1>Create new InfoPopup</h1>";
	}
?>
	<form method="post" name="infopopup-form" id="infopopup-form">
		<input type="hidden" name="popup_id" id="popup_id" value="<?php print $popup_id ? $popup_id : -999; ?>" />
		<p>
			<label for="popup_tag" class="<?php print $edit_result['class_popup_tag']; ?>">Shortcode tag*:</label><br />
			<label>[infopopup tag=</label><input type="text" id="popup_tag" name="popup_tag" value="<?php print $row ? $row['tag'] : ''; ?>" /><label>]</label>
			<span class="infopopup-admin-error"><?php print $edit_result['popup_tag']; ?></span><br />
			<span class="infopopup-admin-tip">This is the unique identifier for each popup, which you will use
				in the shortcode to create the popup link, and it mustn't have any spaces in it!.</span>
		</p>
		<p>
			<label for="popup_title" class="<?php print $edit_result['class_popup_title']; ?>">Title*:</label><br />
			<textarea id="popup_title" name="popup_title"
				rows="1" cols="120"><?php print $row ? $row['title'] : ''; ?></textarea><br />
			<span class="infopopup-admin-error"><?php print $edit_result['popup_title']; ?></span><br />
			<span class="infopopup-admin-tip">This is the link title that appears in content in place of the
				shortcode.</span>
		</p>
		<p>
			<label for="popup_action">Action*:</label><br />
			<select id="popup_action" name="popup_action">
<?php _infopopup_generate_select_options(array('mouseenter' => 'mouse-over', 'click' => 'click'), $row['action']); ?>
			</select><br />
			<span class="infopopup-admin-tip">How do you want this popup activated?</span>
		</p>
		<p>
			<label for="popup_markup">Content:</label><br />
			<textarea id="popup_markup" name="popup_markup"
				rows="15" cols="120"><?php if($row) print $row['markup']; ?></textarea><br />
			<span class="infopopup-admin-tip">This is the content of your popup, which may contain valid HTML markup.<br />
			If you include HTML, do <em>not</em> include the &lt;html&gt; or &lt;body&gt; tags, and be sure to close any
			&lt;div&gt;s, etc.</span>
		</p>
		<p>
			<label>Position*:</label><br />
			<label>Popup</label>
			<label for="popup_my_horiz">x:</label>
			<select id="popup_my_horiz" name="popup_my_horiz">
<?php _infopopup_generate_select_options(
		array(
			'left' => __('left'),
			'center' => __('centre'),
			'right' => __('right')
 		), $row['my_horiz']
	); ?>
			</select>
			<label for="popup_my_vert">y:</label>
			<select id="popup_my_vert" name="popup_my_vert">
<?php _infopopup_generate_select_options(
		array(
			'top' => __('top'),
			'center' => __('centre'),
			'bottom' => __('bottom')
 		), $row['my_vert']
	); ?>
			</select>
			<label>at</label>
			<label for="popup_at_horiz">x:</label>
			<select id="popup_at_horiz" name="popup_at_horiz">
<?php _infopopup_generate_select_options(
		array(
			'left' => __('left'),
			'center' => __('centre'),
			'right' => __('right')
 		), $row['at_horiz']
	); ?>
			</select>
			<label for="popup_at_vert">y:</label>
			<select id="popup_at_vert" name="popup_at_vert">
<?php _infopopup_generate_select_options(
		array(
			'top' => __('top'),
			'center' => __('centre'),
			'bottom' => __('bottom')
 		), $row['at_vert']
	); ?>
			</select>
			<label>of target (offset by</label>
			<label class="<?php print $edit_result['class_popup_offset_horiz']; ?>" for="popup_offset_horiz">x:</label>
			<input type="text" maxLength="4" size="5" id="popup_offset_horiz" name="popup_offset_horiz" value="<?php print $row ? $row['offset_horiz'] : ''; ?>" />
			<label class="<?php print $edit_result['class_popup_offset_vert']; ?>" for="popup_offset_vert">, y:</label>
			<input type="text" maxLength="4" size="5" id="popup_offset_vert" name="popup_offset_vert" value="<?php print $row ? $row['offset_vert'] : ''; ?>" />
			<label>)</label><br />
			<span class="infopopup-admin-tip">The popup will appear in a position relative to the link ('target') and
			can be offset by any number of pixels. Leave the 'offset' boxes blank for zero offset.</span>
			<span class="infopopup-admin-error"><?php print $edit_result['popup_offset_horiz']; ?></span>
			<span class="infopopup-admin-error"><?php print $edit_result['popup_offset_vert']; ?></span>
		</p>
		<p>
			<label for="popup_css_class">CSS class:</label><br />
			<input type="text" id="popup_css_class" name="popup_css_class" value="<?php print $row ? $row['css_class'] : ''; ?>" /><br />
			<span class="infopopup-admin-tip">You may enter any number of CSS class names, separated by spaces,
				or you may leave this blank to use the default 'infopopup-popup' class.</span>
		</p>
		<p>
			<input type="submit" value="Submit" />
		</p>
	</form>
<?php
}