<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Menu Walker class
 *
 * Used to generate the proper menu structure
 *
 **/
	
class DPMenuWalker extends Walker {
	
	// tree types for the menu
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	// database fields map
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
	
		

	/**
	 *
	 * Function used to generate the start code of the submenu
	 *
	 * @param output - reference to the variable with the output
	 * @param depth - depth of the element
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
	function start_lvl(&$output, $depth = 0, $args = array()) {
		// generate the indent
		$indent = str_repeat("\t", $depth);
		// generate the opening tags
		$output .= "\n$indent<ul class=\"submenu\">\n";
	}

	/**
	 *
	 * Function used to generate the end code of the submenu
	 *
	 * @param output - reference to the variable with the output
	 * @param depth - depth of the element
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
	function end_lvl(&$output, $depth = 0, $args = array()) {
		// generate the indent
		$indent = str_repeat("\t", $depth);
		// generate the closing tag
		$output .= "$indent</ul>\n";
	}

	/**
	 *
	 * Function used to generate the start code of the menu element
	 *
	 * @param output - reference to the variable with the output
	 * @param item - the menu item to show
	 * @param depth - depth of the element
	 * @param args - additional arguments
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
function widgets_first_dropdown( &$output, $args ) {
            ob_start();
                dynamic_sidebar( MMPM_PREFIX . '_menu_first_widgets_area' );
                $output .= ob_get_contents();
            ob_end_clean();
        }	
	function start_el(&$output, $item, $depth = 0, $args = array(),  $current_object_id = 0) {
		// access to the WordPress query
		global $wp_query;
		global $dynamo_tpl;
		global $count;
		if ($count<1) $count =1;
		// generate indent
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		// variables for the value and class names
		$class_names = $value = '';
		// generate the list of classes (if the predefined classes exists)
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		// add the menu item class with ID
		$classes[] = 'menu-item-' . $item->ID;
		// filter all classes and join it with spaces
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		// generate the attribute class with all classes
		$menutype = ! empty( $item->menutype )        ? esc_attr( $item->menutype) : 'standard';
		$columns = ! empty( $item->columns )        ? esc_attr( $item->columns) : '';
		$asheader = ! empty( $item->asheader )        ? esc_attr( $item->asheader) : '';
		$itemwidth = ! empty( $item->itemwidth )        ? esc_attr( $item->itemwidth) : '';
		$itemcss = ! empty( $item->itemcss )        ? esc_attr( $item->itemcss) : '';		
		$class_names .= ' '.$itemcss;		
		$allignment = ! empty( $item->megaalign )        ?  esc_attr( $item->megaalign        ) : '';
		$class_names = ' class="' . esc_attr( $class_names );
		if ($menutype == 'megamenu-full') {$class_names .= ' '.$menutype. ' columns-'. $columns;}
		if ($menutype == 'megamenu') {
			$class_names .= ' '.$menutype;
			if ($allignment !='toleft') $class_names .= ' '.$allignment; 
		}
		$aclass = '';
		if ($asheader == 'yes') {$aclass = ' class="column-header"';} 
		if ($depth ==0) $class_names .= ' menutab'.$count. ' root"';
		if ($depth >0) $class_names .= ' childmenu';
		$class_names .= '"';
		// prepare the menu item ID
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		// generate the ID attribute
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		// add the indent to the LI element structure
		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		// check the attributes for the A element which will be put inside the create LI element 
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->custom )        ? ' rel="'    . esc_attr( $item->custom        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		// Has children
		$children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));
		$icon= ! empty( $item->icon )        ? esc_attr( $item->icon        ) : '';
		$subtext = ! empty( $item->subtitle )        ?  esc_attr( $item->subtitle        ) : '';
		$html = ! empty( $item->html )        ?  esc_attr( $item->html        ) : '';
		$image = ! empty( $item->image )        ?  esc_attr( $item->image        ) : '';
		$widget = ! empty( $item->widget )        ?  esc_attr( $item->widget        ) : '';
		$iscustomcontent = '';
		if ($image != '' || $html != '' || $widget != 'none') $iscustomcontent = 'custom-content';
		// add to the output also the before element if exists
		$item_output = $args->before;
		// create the A element
		$item_output .= '<div class="item-container '.$iscustomcontent.'" style="width:'.$itemwidth.'px">';
		if ($iscustomcontent == '') {
		$item_output .= '<a'. $attributes .$aclass.'>';
		// add icon if exist
		if ($icon != '') $item_output .='<div class="menu-icon"><i class="'.$icon.'"></i></div>';
		$item_output .= '<div class="menu-title">';
		
		// add the before and after elements for the link content if exists
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after.'';
		if (!empty($children)) {
		if ($depth == 0) {$item_output .= '<span class="sub-indicator-0"><i class="icon-angle-down"></i></span>';} else 
		{$item_output .= '<span class="sub-indicator-1"><i class="icon-angle-right"></i></span>';}
		}
		$item_output .='<div  class="menu-subtitle">'. $subtext.'</div></div>';
		// close the A element
		$item_output .='</a>';
		} else {
		if ($image!='') $item_output .='<a'. $attributes .$aclass.'><img class="mega-menu-img" src="'.$image.'" /></a>';
		if ($html!='') $item_output .= '<div class="mega-menu-html">'.html_entity_decode($html).'</div>';
		if ($widget!='none') {
			 ob_start();
                dynamic_sidebar( $widget );
                $item_output .= '<div class="mega-menu-html">'.ob_get_contents().'</div>';
            ob_end_clean();
			}	
		}
		$item_output .='</div>';
		// add the after element for the link
		$item_output .= $args->after;
		// generate the final output
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 *
	 * Function used to generate the end code of the menu element
	 *
	 * @param output - reference to the variable with the output
	 * @param item - menu item - here not used but required by API
	 * @param depth - depth of the element
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
	function end_el(&$output, $item, $depth = 0, $args = array()) {
		// close the LI tag
		global $count;
		if($depth ==0) $count=$count+1;
		$output .= "</li>\n";
	}
}

// EOF