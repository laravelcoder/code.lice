<?php
	
// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

// access to the template object
global $dynamo_tpl;
// load the form parser
include_once(dynamo_file('dynamo_framework/form.parser.php'));
// create a new instance of the form parser
$parser = new DynamoWPFormParser($dynamo_tpl);
// get the tabs list from the JSON file
$tabs = $dynamo_tpl->get_json('options','tabs');
// iterators
$tabsIterator = 0;
$contentIterator = 0;
// active tab
$activeTab = 0;


if(isset($_COOKIE['dp-theme' . '_active_tab']) && is_numeric($_COOKIE['dp-theme' . '_active_tab'])) {
	$activeTab = floor($_COOKIE['dp-theme' . '_active_tab']);
}

?>
<div class="dpWrap" id="dpMainWrap" data-theme="<?php echo 'dp-theme'; ?>">
	<h1>
    <big><?php if (get_option($dynamo_tpl->name . "_branding_admin_page_template_name") !='') echo get_option($dynamo_tpl->name . "_branding_admin_page_template_name"); else echo $dynamo_tpl->full_name; ?></big><small><?php echo get_option($dynamo_tpl->name . "_branding_admin_page_slogan") ?></small>

	
		<a href="customize.php?theme=<?php echo $dynamo_tpl->full_name; ?>" title="<?php __('Customize theme', 'dp-theme'); ?>"><?php __('Customize theme', 'dp-theme'); ?></a>
	</h1>
	<div>
		<ul id="dpTabs">
		<?php foreach($tabs as $tab) : ?>
			<?php if($tab[2] == 'enabled') : ?>
			<li<?php echo ($tabsIterator == $activeTab) ? ' class="'.str_replace(' ', '', strtolower($tab[0])).' active"' : ' class="'.str_replace(' ', '', strtolower($tab[0])).'"'; ?> title="<?php echo $tab[0]; ?>"><?php echo $tab[0]; ?></li>
			<?php 
				$tabsIterator++;
				endif; 
			?>
		<?php endforeach; ?>
		</ul>
		
		<div id="dpTabsContent">
		<?php foreach($tabs as $tab) : ?>
			<?php if($tab[2] == 'enabled') : ?>
			<div<?php if($contentIterator == $activeTab) echo ' class="active"'; ?>>
				<?php echo $parser->generateForm($tab[1]); ?>
				
				<div class="dpSaveSettings">
					<img src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif" class="dpAjaxLoading" alt="Loading">
					<button class="button-primary dpSave" data-loading="<?php _e('Saving&hellip;', 'dp-theme'); ?>" data-loaded="<?php __('Save settings', 'dp-theme'); ?>" data-wrong="<?php __('Please check the form!', 'dp-theme'); ?>"><?php _e('Save settings', 'dp-theme'); ?></button>
				</div>
			</div>
			<?php 
				$contentIterator++;
				endif; 
			?>
		<?php endforeach; ?>
		</div>
	</div>
</div>