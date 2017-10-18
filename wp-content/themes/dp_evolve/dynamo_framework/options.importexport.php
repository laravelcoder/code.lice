<?php
	
// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Function to create import/export options page
 *
 * @return null
 *
 **/
	
ob_start();	
function dynamo_importexport_options() {
	// getting access to the template and database global object. 
	global $dynamo_tpl;
	global $wpdb;
	wp_register_style('dp-import-export-css', get_template_directory_uri().'/css/back-end/importexport.css');
	wp_enqueue_style('dp-import-export-css');

	// check permissions
	if (!current_user_can('manage_options')) {  
	    wp_die(__('You don\'t have sufficient permissions to access this page!', 'dp-theme'));  
	}
	// Import Starts Here
?>
	<div class="dpWrap wrap">
		<h1><big><?php echo $dynamo_tpl->full_name; ?></big><small><?php __('Based on the Dynamo WP framework', 'dp-theme'); ?></small></h1>
		<div class="dpImport">
			<h2>Import Template Settings</h2>
			<?php
				if (isset($_FILES['import']) && check_admin_referer('dynamo_importexport')) {
					if ($_FILES['import']['error'] > 0) 
						echo "<div class='error'><p>No file selected, please make sure to select a file.</p></div>";	
					else {
						$file_name = $_FILES['import']['name'];
						$file_ext = explode(".", $file_name);
						$file_ext = strtolower(end($file_ext));
						$file_size = $_FILES['import']['size'];
						if (($file_ext == "json") && ($file_size < 100000)) {
							$encode_options = file_get_contents($_FILES['import']['tmp_name']);
							$options = json_decode($encode_options, true);
							if(is_array($options) && count($options) > 0) {
								foreach($options as $key => $value) {
									update_option($key, esc_attr($value));
								}
							}
							CompileOptionsLess('options.less'); 
							echo "<div class='updated'><p>All template options are restored successfully.</p></div>";
						}	
						else 
							echo "<div class='error'><p>Invalid file or file size too big.</p></div>";
					}
				}
		?>
			<p>1. Click "Browse" button and choose a backup file that you backup before.</p>
			<p>2. Click "Restore Template Settings" button to restore your template settings.</p>
			<form method='post' enctype='multipart/form-data'>
				<p class="submit">
					<?php wp_nonce_field('dynamo_importexport'); ?>
					<input type='file' name='import' />
					<input type='submit' name='submit' class="dpMedia" value='Restore Template Settings'/>
				</p>
			</form>
		</div>
<?php
	// Export Starts Here
	if (!isset($_POST['export'])) { 
?>
		<div class="dpExport">
	        <h2>Export Template Settings</h2>
	        <p>When you click "Backup Template Settings" button, system will generate a template backup file for you to save on your computer.</p>
	        <p>This backup file contains your template configuration and setting options.</p>
	        <p>After exporting, you can either use the backup file to restore your template settings on this site again or another Wordpress site when using same template.</p>
            <form method='post'>
	        <p class="submit">
            	<?php wp_nonce_field('dynamo_importexport'); ?>
	        	<input type='submit' name='export' class="dpMedia" value='Backup Template Settings'/>
	        </p>
            </form>
	    </div>
<?php 
  	} elseif (check_admin_referer('dynamo_importexport')) {
		$option_prefix = $dynamo_tpl->name;
		$blogname = str_replace(" ", "", get_option('blogname'));
		$date = date("d_m_Y_H_i_s");
		$json_name = $blogname."_".$dynamo_tpl->name."_".$date; // Generating filename.
		
		// get all rows with options containing specific prefix
		$options = $wpdb->get_results(  
				'SELECT
					option_value,
					option_name
				FROM 
				'.$wpdb->options.'
				WHERE 
					option_name LIKE \''.$option_prefix.'%\';' 
		);
		$value = array();
		if ($options) {
			foreach ($options as $key) {
				if(
					$key->option_name != $dynamo_tpl->name . '_widget_responsive' &&
					$key->option_name != $dynamo_tpl->name . '_widget_rules' &&
					$key->option_name != $dynamo_tpl->name . '_widget_rules_type' &&
					$key->option_name != $dynamo_tpl->name . '_widget_style' &&
					$key->option_name != $dynamo_tpl->name . '_widget_style_css' && 
					$key->option_name != $dynamo_tpl->name . '_widget_users'
				) {
					$value[$key->option_name] = $key->option_value;
				}
			}
		}
		$json_file = json_encode($value); // Encode data into json data
		
		ob_clean();
		echo $json_file;
		header("Content-Type: text/json; charset=" . get_option( 'blog_charset'));
		header("Content-Disposition: attachment; filename=$json_name.json");
		exit();
	}
	?>
	</div>
<?php 
	// Export Ends Here
}

   /**
   	*
   	* Function to load widget settings from the *.data file
   	*
   	* @return boolean - result
   	* 
   	**/ 
   	
   	function loadWidgetSettings() {
   		global $wpdb;
   		global $dynamo_tpl;
		
   		$data_file = get_template_directory() . '/dynamo_framework/demo/widgets.data';
   		$lines = file($data_file);
   		
   		$option_name = '';
   		$option_value = '';
   		
   		foreach($lines as $line_num => $line) {
   			if($line_num % 3 == 0) {
   				$option_name = $line;
   			}
   			
   			if($line_num % 3 == 1) {
   				$option_value = $line;
   			}
   			
   			if($line_num % 3 == 2) {
   				if($option_name != '' && $option_value != '') {
   					$res = $wpdb->query("UPDATE " . $wpdb->prefix . "options SET option_value='" . trim($option_value) . "' WHERE option_name= '" . trim($option_name) . "' LIMIT 1; ");
   					
   					if($res === 0) {
   						$wpdb->query("INSERT INTO " . $wpdb->prefix . "options VALUES (NULL, '".trim($option_name)."', '" . trim($option_value) . "', 'yes'); ");
   					}
   				}
   				
   				$option_name = '';
   				$option_value = '';
   			}
   		}
   		
   		update_option($dynamo_tpl->name . '_widget_settings_loaded', 'Y');
   		
   		return 'loaded';
   	}

// EOF