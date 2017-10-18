<?php
if (!function_exists ('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

include_once(get_template_directory() . '/dynamo_framework/import/menu_importer.php'); 
class Dynamo_Import {

    public $message = "";
    public $attachments = false;			
	           

    function Dynamo_Import() {
        add_action('admin_menu', array(&$this, 'sample_data_import_menu'));

    }


    public function import_content($file){
        if (!class_exists('WP_Importer')) {
            ob_start();
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            require_once($class_wp_importer);
            require_once(get_template_directory() . '/dynamo_framework/import/class.wordpress-importer.php');
			$dynamo_import = new WP_Import();
            set_time_limit(0);
            $path = get_template_directory() . '/dynamo_framework/import/files/' . $file;

            $dynamo_import->fetch_attachments = $this->attachments;
            $returned_value = $dynamo_import->import($path);
            if(is_wp_error($returned_value)){
                $this->message = __("An Error Occurred During Import", 'dp-theme');
            }
            else {
                $this->message = __("Content imported successfully", 'dp-theme');
            }
            ob_get_clean();
        } else {
            $this->message = __("Error loading files", 'dp-theme');
        }
    }



    public function import_widgets($file){
		global $wp_registered_sidebars, $wp_registered_widget_controls;
		$widget_controls = $wp_registered_widget_controls;
	
		$available_widgets = array();
	
		foreach ( $widget_controls as $widget ) {
	
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { 
	
				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];
	
			}
	
		}
		$file = get_template_directory() . '/dynamo_framework/import/files/' . $file ;
		$data = file_get_contents( $file );
		$data = json_decode( $data );
		// Get all existing widget instances
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
		}
	// Begin results
	$results = array();

	// Loop import data's sidebars
	foreach ( $data as $sidebar_id => $widgets ) {

		// Skip inactive widgets
		// (should not be in export file)
		if ( 'wp_inactive_widgets' == $sidebar_id ) {
			continue;
		}

		// Check if sidebar is available on this site
		// Otherwise add widgets to inactive, and say so
		if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
			$sidebar_available = true;
			$use_sidebar_id = $sidebar_id;
			$sidebar_message_type = 'success';
			$sidebar_message = '';
		} else {
			$sidebar_available = false;
			$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
			$sidebar_message_type = 'error';
			$sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'widget-importer-exporter' );
		}

		// Result for sidebar
		$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
		$results[$sidebar_id]['message_type'] = $sidebar_message_type;
		$results[$sidebar_id]['message'] = $sidebar_message;
		$results[$sidebar_id]['widgets'] = array();

		// Loop widgets
		foreach ( $widgets as $widget_instance_id => $widget ) {

			$fail = false;

			// Get id_base (remove -# from end) and instance ID number
			$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
			$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

			// Does site support this widget?
			if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
				$fail = true;
				$widget_message_type = 'error';
				$widget_message = __( 'Site does not support widget', 'widget-importer-exporter' ); // explain why widget not imported
			}

			// Filter to modify settings before import
			// Do before identical check because changes may make it identical to end result (such as URL replacements)
			$widget = apply_filters( 'wie_widget_settings', $widget );

			// Does widget with identical settings already exist in same sidebar?
			if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

				// Get existing widgets in this sidebar
				$sidebars_widgets = get_option( 'sidebars_widgets' );
				$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

				// Loop widgets with ID base
				$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
				foreach ( $single_widget_instances as $check_id => $check_widget ) {

					// Is widget in same sidebar and has identical settings?
					if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

						$fail = true;
						$widget_message_type = 'warning';
						$widget_message = __( 'Widget already exists', 'widget-importer-exporter' ); // explain why widget not imported

						break;

					}

				}

			}

			// No failure
			if ( ! $fail ) {

				// Add widget instance
				$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
				$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
				$single_widget_instances[] = (array) $widget; // add it

					// Get the key it was given
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					// If key is 0, make it 1
					// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;
						$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					// Move _multiwidget to end of array for uniformity
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					// Update option with new widget
					update_option( 'widget_' . $id_base, $single_widget_instances );

				// Assign widget instance to sidebar
				$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
				$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
				$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
				update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

				// Success message
				if ( $sidebar_available ) {
					$widget_message_type = 'success';
					$widget_message = __( 'Imported', 'widget-importer-exporter' );
				} else {
					$widget_message_type = 'warning';
					$widget_message = __( 'Imported to Inactive', 'widget-importer-exporter' );
				}

			}

			// Result for widget instance
			$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
			$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = $widget->title ? $widget->title : __( 'No Title', 'widget-importer-exporter' ); // show "No Title" if widget instance is untitled
			$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
			$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

		}

	}

		}



    public function import_options($file,$setings_file){
						global $wpdb;
						$file = get_template_directory() . '/dynamo_framework/import/files/' . $file ;
							$encode_options = file_get_contents($file);
							$options = json_decode($encode_options, true);
							if(is_array($options) && count($options) > 0) {
								foreach($options as $key => $value) {
									update_option($key, esc_attr($value));
								}
							}
							CompileOptionsLess('options.less'); 
							$setings_file = get_template_directory() . '/dynamo_framework/import/files/' . $setings_file ;
						    require_once($setings_file);
							$homepage = get_page_by_title( $hompage_title );
							if ( $homepage )
							{
								update_option( 'page_on_front', $homepage->ID );
								update_option( 'show_on_front', 'page' );
							}
							
							$locations = get_theme_mod( 'nav_menu_locations' ); 
							foreach($theme_menus as $menu){
								$term = get_term_by('name', $menu['name'], 'nav_menu');
    							$locations[$menu['location']] = $term->term_id;
								} 
							set_theme_mod('nav_menu_locations', $locations);

    }

    public function import_revsliders($folder){
	if(file_exists(ABSPATH .'wp-content/plugins/revslider/revslider.php')){
		require_once(ABSPATH .'wp-content/plugins/revslider/revslider.php');
			if ($handle = opendir(get_template_directory().'/dynamo_framework/import/files/'.$folder.'/revsliders')) {
			    while (false !== ($entry = readdir($handle))) {
			        if ($entry != "." && $entry != "..") {
			            $_FILES['import_file']['tmp_name']=get_template_directory().'/dynamo_framework/import/files/'.$folder.'/revsliders/'.$entry;
			            $slider = new RevSlider();
						$response = $slider->importSliderFromPost(true, true);
			        }
			    }
			    closedir($handle);
			}
		}
	
	}
	public function import_menus($folder,$setings_file){
			if ($handle = opendir(get_template_directory().'/dynamo_framework/import/files/'.$folder.'/menus')) {
			    while (false !== ($entry = readdir($handle))) {
			        if ($entry != "." && $entry != "..") {
			            $menu_file = get_template_directory().'/dynamo_framework/import/files/'.$folder.'/menus/'.$entry;
						dp_import_menu($menu_file);
			        }
			    }
			    closedir($handle);
			}
	
	}

    public function import_showbiz(){
	if(file_exists(ABSPATH .'wp-content/plugins/showbiz/showbiz.php')){
		global $wpdb;
		$table = $wpdb->prefix . 'showbiz_sliders';
		$wpdb->insert( $table, array(
		'id' => '1',
		'title' => 'Latest Projects',
		'alias' => 'latest-projects',
		'params' => '{"title":"Latest Projects","alias":"latest-projects","shortcode":"[showbiz latest-projects]","source_type":"posts","post_types":"portfolio","post_category":"portfolios_16,portfolios_18,portfolios_19","post_sortby":"ID","posts_sort_direction":"DESC","max_slider_posts":"30","img_random_order":"off","posts_list":"","title_limit":"99","excerpt_limit":"33","limit_by_type":"words","img_source_type":"medium","img_source_type_width":"","img_source_type_height":"","img_ratio":"none","wc_post_types":"","wc_post_category":"","wc_post_sortby":"ID","wc_posts_sort_direction":"DESC","wc_max_slider_posts":"30","wc_regular_price_from":"","wc_regular_price_to":"","wc_sale_price_from":"","wc_sale_price_to":"","wc_instock_only":"false","wc_featured_only":"false","template_id":"20","hide_if_no_image":"off","force_full_width":"off","enable_wc":"on","navigation_mode":"default","closeOtherOverlays":"off","rewindFromEnd":"off","autoPlay":"on","scrollOrientation":"left","delay":"3000","speed":"300","easing":"Power1.easeOut","navigation_type":"template","nav_template_id":"26","nav_position":"top","left_buttonid":"","right_buttonid":"","visible_items_1":"4","visible_items_2":"3","visible_items_3":"2","visible_items_4":"1","custom_offset_control":"false","custom_offset":"0","heightOffsetBottom":"0","entrySizeOffset":"0","conteainerOffsetRight":"0","position":"center","margin_top":"0","margin_bottom":"0","margin_left":"0","margin_right":"0","clear_both":"false","enable_lazy_load":"off","lazy_load_height":"100","lazy_load_image":"","jquery_noconflict":"off","js_to_body":"false","output_type":"none","debug_mode":"false","refresh_images":"false"}'
	));
		$wpdb->insert( $table, array(
  		'id' => '2',
		'title' => 'Latest Posts',
		'alias' => 'latest-posts',
		'params' => '{"title":"Latest Posts","alias":"latest-posts","shortcode":"[showbiz latest-posts]","source_type":"posts","post_types":"post","post_category":"category_5","post_sortby":"ID","posts_sort_direction":"DESC","max_slider_posts":"30","img_random_order":"off","posts_list":"","title_limit":"99","excerpt_limit":"24","limit_by_type":"words","img_source_type":"medium","img_source_type_width":"","img_source_type_height":"","img_ratio":"none","wc_post_types":"","wc_post_category":"","wc_post_sortby":"ID","wc_posts_sort_direction":"DESC","wc_max_slider_posts":"30","wc_regular_price_from":"","wc_regular_price_to":"","wc_sale_price_from":"","wc_sale_price_to":"","wc_instock_only":"false","wc_featured_only":"false","template_id":"19","hide_if_no_image":"off","force_full_width":"off","enable_wc":"on","navigation_mode":"default","closeOtherOverlays":"off","rewindFromEnd":"off","autoPlay":"on","scrollOrientation":"left","delay":"3000","speed":"300","easing":"Power1.easeOut","navigation_type":"template","nav_template_id":"27","nav_position":"top","left_buttonid":"","right_buttonid":"","visible_items_1":"4","visible_items_2":"3","visible_items_3":"2","visible_items_4":"1","custom_offset_control":"false","custom_offset":"0","heightOffsetBottom":"0","entrySizeOffset":"0","conteainerOffsetRight":"0","position":"center","margin_top":"0","margin_bottom":"0","margin_left":"0","margin_right":"0","clear_both":"false","enable_lazy_load":"off","lazy_load_height":"100","lazy_load_image":"","jquery_noconflict":"off","js_to_body":"false","output_type":"none","debug_mode":"false","refresh_images":"false"}'
		));
		}
	
	}



	function sample_data_import_menu()
			{			
				$page = add_submenu_page(
				"dynamo-menu",
				__("Sample Data Import",'dp-theme'),
				__("Sample Data Import",'dp-theme'),
				"manage_options",
				"sample_data_import",
				array($this,'dynamo_generate_import_page')
				);
			}
    function dynamo_generate_import_page() {
	global $dynamo_tpl;
	global $wpdb;
	wp_register_style('dp-import-export-css', get_template_directory_uri().'/css/back-end/importexport.css');
	wp_enqueue_style('dp-import-export-css');
    wp_register_style('dp-metabox-css', dynamo_file_uri('css/back-end/metabox.css'));
	wp_enqueue_style('dp-metabox-css');
    wp_register_style('dp-jquery-ui-css', dynamo_file_uri('css/back-end/jquery-ui.min.css'));
	wp_enqueue_style('dp-jquery-ui-css');
	wp_register_script('dp-jquery-ui-js', get_template_directory_uri().'/js/back-end/jquery-ui.min.js', array('jquery'),  false,true);
	wp_enqueue_script('dp-jquery-ui-js');
        ?>
		<div class="dpWrap wrap">
        <h1><big><?php echo $dynamo_tpl->full_name; ?></big><small><?php _e('Based on the Dynamo WP framework', 'dp-theme'); ?></small></h1>
            <h3><?php _e('Import Demo Content', 'dp-theme') ?></h3>
            <form method="post" action="" id="importContentForm">
                    <div class="clearfix">
                    <div class="col_onefourth">
                    <p><?php _e('<b>Import</b><br/>Choose demo content you want to import</p>', 'dp-theme'); ?>
                    </div>
                    <div class="col_onefourth">
                    <p>
                                            <em>Demo Site</em><br/>
                                            <select class="width100" name="import_example" id="import_example">
												<option value="evolve1">Evolve 1 - Standard</option>
                                            </select>
                           </p>
                     	</div>
                                        <div class="col_onefourth">
                                        <p>
                                            <em>Import Type</em><br/>
                                            <select class="width100" name="import_option" id="import_option" class="form-control dynamof-form-element">
                                                <option value="">Please Select</option>
                                                <option value="complete_content">All</option>
                                                <option value="content">Content</option>
                                                <option value="widgets">Widgets</option>
                                                <option value="menus">Menus</option>
                                                <option value="options">Options</option>
                                                <option value="revsliders">Revsliders</option>
                                                <option value="showbiz">Showbiz sliders</option>
                                            </select>
                                         </p>
                                        </div>
						<div class="clearboth"></div>
                        <div class="col_onefourth" >
                            <p>
                                <b><?php esc_html_e('Import attachments', 'dp-theme'); ?></b><br/>
								<?php esc_html_e('Do you want to import media files?', 'dp-theme'); ?>
                            </p>
                        </div>
						<div class="col_onefourth" >
                        <p><input type="checkbox" value="1" name="import_attachments" id="import_attachments" /></p>
                        </div>
						<div class="clearboth"></div>
                        <p>
                        <input type="submit" class="dpMedia" value="<?php esc_html_e('Import Demo Content', 'dp-theme'); ?>" name="import" id="import_demo_data" />
                        </p>

                        <div class="dp_import_load col_onefourth"><span><?php _e('The import process may take some time. Please be patient.', 'dp-theme') ?> </span><br />
                            <div class="dynamo-progress-bar-wrapper">
                                <div class="progress-bar-wrapper">
                                    <div id="progressbar" class="dynamo-progress-bar"></div>
                                </div>
                                <div class="progress-value">1%</div>
                                <div class="progress-bar-message">
                                </div>
                            </div>
                        </div>
                        <div class="clearboth"></div>
                        <div class="notification warning"><p><i class="icon-warning-empty"></i><span><?php _e('Important notes:', 'dp-theme') ?></span><br/>                            <ol>
                                <li><?php _e('Please note that import process will take time needed to download all attachments from demo web site.', 'dp-theme'); ?></li>
                                <li> <?php _e('If you plan to import Revsliders and Showbiz Sliders, please install this plugins before you run import.', 'dp-theme')?></li>
                                <li> <?php _e('If you plan to use shop, please install WooCommerce before you run import.', 'dp-theme')?></li>
                            </ol>
</p></div>
                        

                    </div>

            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(document).on('click', '#import_demo_data', function(e) {
                    e.preventDefault(); 
					var import_opt = jQuery( "#import_option" ).val();
					if(import_opt == '') {
							alert ("Please select import type");
							return false;
                        }
                    if (confirm('Are you sure, you want to import Demo Data now?')) {
                        jQuery('.dp_import_load').css('display','block');
                        var dp_progressbar = jQuery('#progressbar')
                       
                        var import_expl = jQuery( "#import_example" ).val();
                        var p = 0;
						dp_progressbar.progressbar({
							value: 1
						});
						if(import_opt == 'content'){
                            for(var i=1;i<10;i++){
                                var str;
                                if (i < 10) str = 'evolve_content_0'+i+'.xml';
                                else str = 'evolve_content_'+i+'.xml';
                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: {
                                        action: 'dynamo_dataImport',
                                        xml: str,
                                        example: import_expl,
                                        import_attachments: (jQuery("#import_attachments").is(':checked') ? 1 : 0)
                                    },
                                    success: function(data, textStatus, XMLHttpRequest){
                                        p+= 10;
                                        jQuery('.progress-value').html((p) + '%');
										dp_progressbar.progressbar("value", p)
                                        if (p == 90) {
                                            str = 'evolve_content_10.xml';
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: ajaxurl,
                                                data: {
                                                    action: 'dynamo_dataImport',
                                                    xml: str,
                                                    example: import_expl,
                                                    import_attachments: (jQuery("#import_attachments").is(':checked') ? 1 : 0)
                                                },
                                                success: function(data, textStatus, XMLHttpRequest){
                                                    p+= 10;
                                                    jQuery('.progress-value').html((p) + '%');
                                                    dp_progressbar.progressbar("value", p)
                                                    jQuery('.progress-bar-message').html('<div class="notification success"><p><i class="icon-thumbs-up-2"></i><span>Success!</span><br/>Demo content is imported.</p></div>');
                                                },
                                                error: function(MLHttpRequest, textStatus, errorThrown){
                                                }
                                            });
                                        }
                                    },
                                    error: function(MLHttpRequest, textStatus, errorThrown){
                                    }
                                });
                            }
                        } else if(import_opt == 'widgets') {
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'dynamo_widgetsImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    jQuery('.progress-value').html((100) + '%');
                                    dp_progressbar.progressbar("value", 100)
                                    jQuery('.progress-bar-message').html('<div class="notification success"><p><i class="icon-thumbs-up-2"></i><span>Success!</span><br/>Demo content is imported.</p></div>');
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        } else if(import_opt == 'options'){
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'dynamo_optionsImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    jQuery('.progress-value').html((100) + '%');
                                    dp_progressbar.progressbar("value", 100)
                                    jQuery('.progress-bar-message').html('<div class="notification success"><p><i class="icon-thumbs-up-2"></i><span>Success!</span><br/>Demo content is imported.</p></div>');
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        }else if(import_opt == 'revsliders'){
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'dynamo_revImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    jQuery('.progress-value').html((100) + '%');
                                    dp_progressbar.progressbar("value", 100)
                                    jQuery('.progress-bar-message').html('<div class="notification success"><p><i class="icon-thumbs-up-2"></i><span>Success!</span><br/>Demo content is imported.</p></div>');
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        }else if(import_opt == 'showbiz'){
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'dynamo_showbizImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    jQuery('.progress-value').html((100) + '%');
                                    dp_progressbar.progressbar("value", 100)
                                    jQuery('.progress-bar-message').html('<div class="notification success"><p><i class="icon-thumbs-up-2"></i><span>Success!</span><br/>Demo content is imported.</p></div>');
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        }else if(import_opt == 'menus'){
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'dynamo_menuImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    jQuery('.progress-value').html((100) + '%');
                                    dp_progressbar.progressbar("value", 100)
                                },
								success: function(data, textStatus, XMLHttpRequest){
                                                            jQuery('.progress-value').html((100) + '%');
                                                            dp_progressbar.progressbar("value", 100)
                                                            jQuery('.progress-bar-message').html('<div class="notification success"><p><i class="icon-thumbs-up-2"></i><span>Success!</span><br/>Menus are imported.</p></div>');
                                                        },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        }else if(import_opt == 'complete_content'){
                            for(var i=1;i<10;i++){
                                var str;
                                if (i < 10) str = 'evolve_content_0'+i+'.xml';
                                else str = 'evolve_content_'+i+'.xml';
                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: {
                                        action: 'dynamo_dataImport',
                                        xml: str,
                                        example: import_expl,
                                        import_attachments: (jQuery("#import_attachments").is(':checked') ? 1 : 0)
                                    },
                                    success: function(data, textStatus, XMLHttpRequest){
                                        p+= 10;
                                        jQuery('.progress-value').html((p) + '%');
                                        dp_progressbar.progressbar("value", p)
                                        if (p == 90) {
                                            str = 'evolve_content_10.xml';
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: ajaxurl,
                                                data: {
                                                    action: 'dynamo_dataImport',
                                                    xml: str,
                                                    example: import_expl,
                                                    import_attachments: (jQuery("#import_attachments").is(':checked') ? 1 : 0)
                                                },
                                                success: function(data, textStatus, XMLHttpRequest){
                                                    jQuery.ajax({
                                                        type: 'POST',
                                                        url: ajaxurl,
                                                        data: {
                                                            action: 'dynamo_otherImport',
                                                            example: import_expl
                                                        },
                                                        success: function(data, textStatus, XMLHttpRequest){
                                                            jQuery('.progress-value').html((100) + '%');
                                                            dp_progressbar.progressbar("value", 100)
                                                            jQuery('.progress-bar-message').html('<div class="notification success"><p><i class="icon-thumbs-up-2"></i><span>Success!</span><br/>Demo content is imported.</p></div>');
                                                        },
                                                        error: function(MLHttpRequest, textStatus, errorThrown){
                                                        }
                                                    });
                                                },
                                                error: function(MLHttpRequest, textStatus, errorThrown){
                                                }
                                            });
                                        }
                                    },
                                    error: function(MLHttpRequest, textStatus, errorThrown){
                                    }
                                });
                            }
                        }
                    }
                    return false;
                });
            });
        </script>

        </div>

    <?php	}

}
global $my_Dynamo_Import;
$my_Dynamo_Import = new Dynamo_Import();



if(!function_exists('dynamo_dataImport')){
    function dynamo_dataImport(){
        global $my_Dynamo_Import;

        if ($_POST['import_attachments'] == 1)
            $my_Dynamo_Import->attachments = true;
        else
            $my_Dynamo_Import->attachments = false;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Dynamo_Import->import_content($folder.$_POST['xml']);

        die();
    }

    add_action('wp_ajax_dynamo_dataImport', 'dynamo_dataImport');
}

if(!function_exists('dynamo_widgetsImport')){
    function dynamo_widgetsImport(){
        global $my_Dynamo_Import;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Dynamo_Import->import_widgets($folder.'widgets.wie');

        die();
    }

    add_action('wp_ajax_dynamo_widgetsImport', 'dynamo_widgetsImport');
}
if(!function_exists('dynamo_showbizImport')){
    function dynamo_showbizImport(){
        global $my_Dynamo_Import;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Dynamo_Import->import_showbiz();

        die();
    }

    add_action('wp_ajax_dynamo_showbizImport', 'dynamo_showbizImport');
}

if(!function_exists('dynamo_optionsImport')){
    function dynamo_optionsImport(){
        global $my_Dynamo_Import;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Dynamo_Import->import_options($folder.'options.json',$folder.'settings.php');

        die();
    }

    add_action('wp_ajax_dynamo_optionsImport', 'dynamo_optionsImport');
}

if(!function_exists('dynamo_otherImport')){
    function dynamo_otherImport(){
        global $my_Dynamo_Import;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";
        $my_Dynamo_Import->import_menus($folder);
        $my_Dynamo_Import->import_options($folder.'options.json',$folder.'settings.php');
        $my_Dynamo_Import->import_widgets($folder.'widgets.wie');
        $my_Dynamo_Import->import_showbiz();
        $my_Dynamo_Import->import_revsliders($folder);
        die();
    }

    add_action('wp_ajax_dynamo_otherImport', 'dynamo_otherImport');
}
if(!function_exists('dynamo_menuImport')){
    function dynamo_menuImport(){
        global $my_Dynamo_Import;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Dynamo_Import->import_menus($folder);

        die();
    }

 add_action('wp_ajax_dynamo_menuImport', 'dynamo_menuImport');
}
if(!function_exists('dynamo_revImport')){
    function dynamo_revImport(){
        global $my_Dynamo_Import;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Dynamo_Import->import_revsliders($folder);

        die();
    }

    add_action('wp_ajax_dynamo_revImport', 'dynamo_revImport');
}

if(!function_exists('dynamo_showbizImport')){
    function dynamo_gridImport(){
        global $my_Dynamo_Import;

        $folder = "evolve1/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Dynamo_Import->import_showbiz();

        die();
    }

    add_action('wp_ajax_dynamo_showbizImport', 'dynamo_showbizImport');
}
