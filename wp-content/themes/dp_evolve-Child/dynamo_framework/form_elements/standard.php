<?php
	
// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Base class for all the back-end fields
 *
 **/

class DPFormInput {
	// access to the template object
	protected $dynamo_tpl;
	// name of the field (without the template name prefix)
	protected $name;
	// label of the field
	protected $label;
	// tooltip for the field
	protected $tooltip;
	// default value of the field
	protected $value;
	// class attribute for the field
	protected $class;
	// format for validation as regular expression
	protected $format;
	// flag to mark the field as required field
	protected $required;
	// visibility of the field
	protected $visibility;
	
	/**
	 *
	 * Constructor
	 *
	 * @param tpl - handler for the template object
	 * @param name - to fill the name class field
	 * @param label - to fill the label class field
	 * @param tooltip - to fill the tooltip class field
	 * @param default - to fill the value class field
	 * @param class - to fill the class class field
	 * @param format - to fill the format class field
	 * @param required - to fill the required class field
	 * @param visibility - to fill the visibility class field
	 * @param other - additional arguments for the constructor
	 *
	 * @return null
	 *
	 **/
	function __construct($dynamo_tpl, $name, $label, $tooltip = '', $default = '', $class = '', $format = '', $required = false, $visibility = '', $other = null) {
		// get the Template main object handler
		$this->tpl = $dynamo_tpl;
		// name for the field used in the storage
		$this->name = $name;
		// label for the input
		$this->label = $label;
		// tooltip content for the label
		$this->tooltip = $tooltip;
		// read the value
		$this->getValue($default);
		// check if it is necessarry to generate the class attribute
		$this->class = $class;
		//
		$this->format = ' data-format="'.$format.'"';
		$this->required = ' data-required="'.$required.'"';
		$this->visibility = ' data-visibility="'.$visibility.'"';
		$this->other = $other;
	} 
	
	/**
	 *
	 * Function to get the field value - it is usually overrided in the more complex fields
	 *
	 * @param default - default value of the field
	 *
	 * @return null
	 *
	 **/
	
	public function getValue($default) {
		// get the option value from database or if it doesn't exist get the default value
		$this->value = get_option($this->tpl->name . "_" . $this->name, $default);
	}
}

/**
 *
 *
 * Standard elements used in the panel
 *
 *
 **/

/**
 *
 * Text block - used as a description
 *
 **/

class DPFormInputTextBlock extends DPFormInput {
	public function output() {
		
		$output = '<p class="dpTextBlock '.($this->class).'" data-visible="true" '.($this->visibility).'">
		<span 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpTextarea '.($this->class).'"
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).' 
					data-name="'.($this->name).'"
				>'.($this->label).'</span>
		</p>';
		
		return $output;
	}
}

/**
 *
 * HTML block - used for description with HTML formating
 *
 **/

class DPFormInputHTML extends DPFormInput {
	public function output() {
		$output = '<div class="dpHTML ">'.$this->other->html.'</div>';
		
		return $output;
	}
}

/**
 *
 * Save button. 
 *
 **/

class DPFormInputSave extends DPFormInput {
	public function output() {
		$output = '<div class="dpSaveSettings">
					<img src="'. site_url().'/wp-admin/images/wpspin_light.gif" class="dpAjaxLoading" alt="Loading">
					<button class="button-primary dpSave" data-loading="';
		$output .= 'Saving&hellip;';
		$output .= '" data-loaded="';
		$output .= 'Save settings';
		$output .='" data-wrong="';
		$output .='Please check the form!';
		$output .='">';
		$output .='Save settings';
		$output .='</button></div>';
				
		
		return $output;
	}
}

/**
 *
 * Raw Text field - basic input field extended with support for removing slashes before apostrophes
 *
 **/


class DPFormInputRawText extends DPFormInput {
	public function output() {
		return '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<input 
					type="text" 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpText '.($this->class).'"
					value="'.str_replace('\&#039;', "'", $this->value).'"
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).' 
					data-name="'.($this->name).'"
				/></p>';
	}
}


/**
 *
 * Text field - basic input field
 *
 **/

class DPFormInputText extends DPFormInput {
	public function output() {
		return '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<input 
					type="text" 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpText '.($this->class).'"
					value="'.($this->value).'"
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).' 
					data-name="'.($this->name).'"
				/></p>';
	}
}

/**
 *
 * Textarea
 *
 **/

class DPFormInputTextarea extends DPFormInput {	
	public function output() {
		return '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'"
				>'.$this->label.'</label>
				<textarea 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpTextarea '.($this->class).'"
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).' 
					data-name="'.($this->name).'"
				>'.(str_replace("\\", "", $this->value)).'</textarea></p>';
	}
}

/**
 *
 * Select - the dropdown list
 *
 **/

class DPFormInputSelect extends DPFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpSelect '.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
				
		foreach($this->other->options as $value => $label) {		
			$output .= '<option value="'.$value.'"'.selected($this->value, $value, false).'>'.$label.'</option>'; 
		}
		
		$output .= '</select></p>';
		
		return $output;
	}
}

/**
 *
 * Select opacity - the dropdown list with slider
 *
 **/

class DPFormInputSlider extends DPFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpSelect dpSlider'.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
				
		foreach($this->other->options as $value => $label) {		
			$output .= '<option value="'.$value.'"'.selected($this->value, $value, false).'>'.$label.'</option>';
		}
		
		$output .= '</select></p>';
		
		return $output;
	}
}


/**
 *
 * Switcher - the Select with only two states - enabled/disabled
 *
 **/

class DPFormInputSwitcher extends DPFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
					<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpSwitcher '.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
		$output .= '<option value="N"'.selected($this->value, 'N', false).'>'.__('Disabled', DPTPLNAME).'</option>';
		$output .= '<option value="Y"'.selected($this->value, 'Y', false).'>'.__('Enabled', DPTPLNAME).'</option>';
		$output .= '</select><span id="'.($this->tpl->name).'_'.($this->name).'_dpswitch" class="dpSwitcher1"><span class="switcher-handle"></span></span></p>';
		
		return $output;
	}
}

/**
 *
 * Media - field to select an image
 *
 **/

class DPFormInputMedia extends DPFormInput {
	public function output() {
		
		$output = '<p data-visible="true"><label 
			for="'.($this->tpl->name).'_'.($this->name).'"
			title="'.($this->tooltip).'"
			>
				'.$this->label.'
			</label>
			<input 
				id="'.($this->tpl->name).'_'.($this->name).'" 
				type="text" 
				size="36" 
				name="'.($this->tpl->name).'_'.($this->name).'" 
				value="'.($this->value).'" 
 				class="dpInput dpMediaInput" 				
				'.($this->format).' 
				'.($this->required).' 
				'.($this->visibility).'
				data-name="'.($this->name).'"
			/>
			<input id="'.($this->tpl->name).'_'.($this->name).'_button" class="dpMedia" type="button" value="'.__('Upload Image', DPTPLNAME).'" />
			<input id="'.($this->tpl->name).'_'.($this->name).'_button1" class="dpMedia" type="button" value="'.__('Remove Image', DPTPLNAME).'" />
			<small>'.__('Enter an URL or upload an image.', DPTPLNAME).'</small>
			<img class="dpMediaImage" src="'.($this->value).'" id="'.($this->tpl->name).'_'.($this->name).'_image">
			</p>
		';
		
		return $output;
	}
}

/**
 *
 * Color - field to select a color
 *
 **/

class DPFormInputColor extends DPFormInput {
	public function output() {
		
		$output = '<p data-visible="true"><label 
			for="'.($this->tpl->name).'_'.($this->name).'"
			title="'.($this->tooltip).'"
			>
				'.$this->label.'
			</label>
			<input 
				id="'.($this->tpl->name).'_'.($this->name).'" 
				type="text" 
				size="13" 
				name="'.($this->tpl->name).'_'.($this->name).'" 
				value="'.($this->value).'" 
 				class="dpInput dpColor" 				
				'.($this->format).' 
				'.($this->required).' 
				'.($this->visibility).'
				data-name="'.($this->name).'"
			/><input type="text" class="colorSelector"  />
			</p>
		';
		
		return $output;
	}
}

/**
 *
 * Select background image from specified directory - the dropdown list
 *
 **/

class DPFormInputBackground extends DPFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpSelect dpBackground'.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
					$output .= '<option value="none"'.selected($this->value,'none', false).'>none</option>';
		$dirPath = dir( get_template_directory().'/images/'.$this->other->folder);
					while (($file = $dirPath->read()) !== false)
					{if (trim($file)!='.' && trim($file)!='..')	{
					$value = current(explode(".", $file));
					if ($value != "none" && $value != "Thumbs") {
					$output .= '<option value="'.$value.'"'.selected($this->value, $value, false).'>'.$value.'</option>';
					}}
					}
		$dirPath->close();
		$output .= '</select>
		<input 
				type="hidden" 
				id="'.($this->tpl->name).'_'.($this->name).'_path" 
				value="'.get_template_directory_uri().'/images/'.$this->other->folder.'/" 
		/>
		<br/>
		<span id="'.($this->tpl->name).'_'.($this->name).'_bg" class="dpPattern" style="background: url('.get_template_directory_uri().'/images/'.$this->other->folder.'/'.$this->value.'.png)"></span>
		</p>';
		
		return $output;
	}
}

/**
 *
 * Select taxonomy , category slug of post defined type
 *
 **/

class DPFormInputTaxonomy extends DPFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpSelect dpTaxonomy'.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
				$output .= '<option value="none"'.selected($this->value, 'not', false).'>'.__('--Select--', DPTPLNAME).'</option>';
				$slug = 'slideshows';
				$terms = get_terms($slug);
				foreach($terms as $term) {
            	$optionData = $term->name;
           		$optionValue = $term->slug;
				$output .= '<option value="'.$optionValue.'"'.selected($this->value,$optionValue, false).'>'.$optionData.'</option>';
		}
		$output .= '</select></p>';
		
		return $output;
	}
}

/**
 *
 * Select menu
 *
 **/

class DPFormInputMenu extends DPFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="dpInput dpSelect dpMenu'.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
				$menus = wp_get_nav_menus();
				$output .= '<option value="none"'.selected($this->value, 'not', false).'>'.__('--Select--', DPTPLNAME).'</option>';
				$count = count($menus);
				if ( $count > 0 ):
				foreach ( $menus as $menu ):
            	$optionData = $menu->name;
           		$optionValue = $menu->slug;
				$output .= '<option value="'.$optionValue.'"'.selected($this->value,$optionValue, false).'>'.$optionData.'</option>';
				endforeach;
				endif;
				$output .= '</select></p>';
				return $output;
	}
}


/**
 *
 * Width/Height - used to specify size of the rectangle area (i.e. for specify image dimensions)
 *
 **/

class DPFormInputWidthHeight extends DPFormInput {
	public function getValue($default) {
		// get the option value from database or if it doesn't exist get the default value
		$this->value = array(
			"height" => get_option($this->tpl->name . "_" . str_replace('_width', '', $this->name), $default),
			"width" => get_option($this->tpl->name . "_" . str_replace('_height', '', $this->name), $default)
		);
	} 
	
	public function output() {	
		$output = '<p data-visible="true"><label 
			for="'.($this->tpl->name).'_'.($this->name).'"
			title="'.($this->tooltip).'"
			>
				'.$this->label.'
			</label>
			
			<input 
				type="text" 
				size="'.($this->other->size).'" 
				class="dpInput dpWidthHeight"
				id="'.($this->tpl->name . "_" . str_replace('_height', '', $this->name)).'" 
				name="'.($this->tpl->name . "_" . str_replace('_height', '', $this->name)).'" 
				value="'.($this->value['width']).'" 
				'.($this->format).' 
				'.($this->required).' 
				'.($this->visibility).'
			/>
			 &times; 
			<input 
				type="text" 
				class="dpInput dpWidthHeight"
				size="'.($this->other->size).'" 
				id="'.($this->tpl->name . "_" . str_replace('_width', '', $this->name)).'" 
				name="'.($this->tpl->name . "_" . str_replace('_width', '', $this->name)).'" 
				value="'.($this->value['height']).'" 
				'.($this->format).' 
				'.($this->required).' 
				'.($this->visibility).'
			/> '.($this->other->unit).'</p>
		';
		
		return $output;
	}
}

// EOF