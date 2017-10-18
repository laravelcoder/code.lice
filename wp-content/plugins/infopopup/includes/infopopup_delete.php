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
$admin_url_params = array(
	'page' => 'infopopup'
);
$admin_url = add_query_arg($admin_url_params, admin_url('options-general.php'));

$popup_id = $_POST['delete_popup_id'];
if(!$popup_id){
	$popup_id = $_GET['cpid'] ? $_GET['cpid'] : 0;
}
if($popup_id && is_numeric($popup_id)){
	$sql = $wpdb->prepare(
		"SELECT * FROM $infopopup WHERE `popup_id` = %d", $popup_id
	);
	$result = $wpdb->get_results($sql, ARRAY_A);
	$row = $result[0];
	if($_POST['delete_confirm']){
		$sql = $wpdb->prepare(
			"DELETE from $infopopup WHERE `popup_id` = %d", $popup_id
		);
		if($wpdb->query($sql)){
			$row['_DELETED'] = true;
		}
	}
}
if($popup_id && !$row){
?>
<h3>Hmmm, <?php print $popup_id ?> isn't a proper id is it?</h3>
<?php
}else if($row && isset($row['_DELETED'])){
?>
	<form method="get" name="infopopup-form" id="infopopup-form">
		<input type="hidden" name="page" value="infopopup" />
		<p>Popup <span style="font-weight: bold"><?php print stripslashes($row['title']) ?></span> has been deleted.</p>
		<p>
			<input name="admin_return" type="submit" value="OK" />
		</p>
	</form>
<?php
}else{
?>
	<form method="post" name="infopopup-form" id="infopopup-form">
		<input type="hidden" name="delete_popup_id" id="delete_popup_id" value="<?php print $popup_id; ?>" />
		<p>Please confirm that you want to delete this popup: <span style="font-weight: bold"><?php print stripslashes($row['title']) ?></span></p>
		<p>
			<input name="delete_confirm" type="submit" value="Confirm" />
			<a href="<?php print $admin_url; ?>">Cancel</a>
		</p>
	</form>
<?php
}