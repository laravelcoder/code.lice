<?php
	
// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

// access to the template object
global $dynamo_tpl;

?>

<script type="text/javascript">
	$DP_TEMPLATE_UPDATE_NAME = '<?php echo $dynamo_tpl->update_name; ?>';
	$DP_TEMPLATE_UPDATE_VERSION = '<?php echo $dynamo_tpl->version; ?>';
</script>

<div class="dpWrap wrap">
	<h2><?php __('Updates', 'dp-theme'); ?></h2>
	
	<dl>
		<dt>
			<h3><?php __('Template version: ', 'dp-theme'); echo $dynamo_tpl->version; ?></h3>
		</dt>
		<dd>
			<div id="dpTemplateUpdates"></div>
		</dd>
	</dl>
</div>