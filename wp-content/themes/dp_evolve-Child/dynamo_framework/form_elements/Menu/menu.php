<?php 	
	
// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Class of the menu field
 *
 **/
	
class DPFormInputMenu extends DPFormInput {
	/**
	 *
	 * Function used to override the getValue function
	 *
	 * @param default - default value - not used here
	 *
	 * @return null
	 *
	 **/
	
	public function getValue($default) {
		$this->value = '';
	}
	
	/**
	 *
	 * Function used to generate output of the field
	 *
	 * @return HTML output of the field
	 *
	 **/
	
	public function output() {
		// load and parse XML file.
		$json_data = $this->tpl->get_json('config', 'menus');
		//
		$output = '';
		// prepare parser object
		$parser = new DynamoWPFormParser($this->tpl);
		// iterate through all menus in the file
		foreach ($json_data as $menu) {			
			$temp_json = '[
				{
					"groupname": "'.($menu->name).'",
					"groupdesc": "'.($menu->description).'",
					"fields": [
						{
							"name": "navigation_menu_state_'.($menu->location).'",
							"type": "Select",
							"label": "'.__('Enable', DPTPLNAME).' '.($menu->name).'",
							"tooltip": "'.__('You can enable or disable showing the menu in the template.', DPTPLNAME).'",
							"default": "Y",
							"other": {
								"options": {
									"Y": "'.__('Enabled', DPTPLNAME).'",
									"N": "'.__('Disabled', DPTPLNAME).'",
									"rule": "'.__('Conditional rule', DPTPLNAME).'"
								}
							}
						},
						{
							"name": "navigation_menu_staterule_'.($menu->location).'",
							"type": "Text",
							"label": "'.__('Conditional rule', DPTPLNAME).'",
							"tooltip": "'.__('You can enable showing the menu in the specific pages.', DPTPLNAME).'",
							"default": "",
							"class": "",
							"visibility": "navigation_menu_state_'.($menu->location).'=rule"
						},
						{
							"name": "navigation_menu_depth_'.($menu->location).'",
							"type": "Select",
							"label": "'.__('Depth of ', DPTPLNAME).' '.($menu->name).'",
							"tooltip": "'.__('You can specify the menu depth.', DPTPLNAME).'",
							"default": "0",
							"other": {
								"options": {
									"0": "'.__('All levels', DPTPLNAME).'",
									"1": "1",
									"2": "2",
									"3": "3",
									"4": "4",
									"5": "5"
								}
							}
						}
					]
				}
			]';	
			// parse the generated JSON
			$output .= $parser->generateForm($temp_json, true);
		}
		
		return $output;
	}
}

// EOF