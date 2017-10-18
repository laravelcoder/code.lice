<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	


class DP_custom_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

	
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'dp_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'dp_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'dp_edit_walker'), 10, 2 );

	} // end constructor
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function dp_add_custom_nav_fields( $menu_item ) {
	
	    $menu_item->subtitle = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
	    $menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
	    $menu_item->itemwidth = get_post_meta( $menu_item->ID, '_menu_item_itemwidth', true );
	    $menu_item->itemcss = get_post_meta( $menu_item->ID, '_menu_item_itemcss', true );
	    $menu_item->menutype = get_post_meta( $menu_item->ID, '_menu_item_menutype', true );
	    $menu_item->columns = get_post_meta( $menu_item->ID, '_menu_item_columns', true );
	    $menu_item->asheader = get_post_meta( $menu_item->ID, '_menu_item_asheader', true );
	    $menu_item->image = get_post_meta( $menu_item->ID, '_menu_item_image', true );
	    $menu_item->html = get_post_meta( $menu_item->ID, '_menu_item_html', true );
	    $menu_item->widget = get_post_meta( $menu_item->ID, '_menu_item_widget', true );
	    $menu_item->megaalign = get_post_meta( $menu_item->ID, '_menu_item_megaalign', true );
	    return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function dp_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if ( isset($_REQUEST['menu-item-subtitle']) && is_array( $_REQUEST['menu-item-subtitle']) ) {
	        $subtitle_value = $_REQUEST['menu-item-subtitle'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_subtitle', $subtitle_value );
	    }
	    if ( isset( $_REQUEST['menu-item-icon']) && is_array( $_REQUEST['menu-item-icon']) ) {
	        $icon_value = $_REQUEST['menu-item-icon'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_icon', $icon_value );
	    }
	    if ( isset( $_REQUEST['menu-item-itemwidth']) && is_array( $_REQUEST['menu-item-itemwidth']) ) {
	        $itemwidth_value = $_REQUEST['menu-item-itemwidth'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_itemwidth', $itemwidth_value );
	    }
		if ( isset( $_REQUEST['menu-item-itemcss']) && is_array( $_REQUEST['menu-item-itemcss']) ) {
	        $itemcss_value = $_REQUEST['menu-item-itemcss'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_itemcss', $itemcss_value );
	    }
	    if ( isset( $_REQUEST['menu-item-menutype']) && is_array( $_REQUEST['menu-item-menutype']) ) {
	        $menutype_value = $_REQUEST['menu-item-menutype'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_menutype', $menutype_value );
	    }
	    if ( isset( $_REQUEST['menu-item-megaalign']) && is_array( $_REQUEST['menu-item-megaalign']) ) {
	        $megaalign_value = $_REQUEST['menu-item-megaalign'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_megaalign', $megaalign_value );
	    }
	    if ( isset( $_REQUEST['menu-item-columns']) && is_array( $_REQUEST['menu-item-columns']) ) {
	        $columns_value = $_REQUEST['menu-item-columns'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_columns', $columns_value );
	    }
	    if ( isset( $_REQUEST['menu-item-asheader']) && is_array( $_REQUEST['menu-item-asheader']) ) {
	        $asheader_value = $_REQUEST['menu-item-asheader'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_asheader', $asheader_value );
	    }
	    if ( isset( $_REQUEST['menu-item-image']) && is_array( $_REQUEST['menu-item-image']) ) {
	        $image_value = $_REQUEST['menu-item-image'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_image', $image_value );
	    }
	    if ( isset( $_REQUEST['menu-item-html']) && is_array( $_REQUEST['menu-item-html']) ) {
	        $html_value = $_REQUEST['menu-item-html'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_html', $html_value );
	    }
		if ( isset( $_REQUEST['menu-item-widget']) && is_array( $_REQUEST['menu-item-widget']) ) {
	        $widget_value = $_REQUEST['menu-item-widget'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_widget', $widget_value );
	    }


	}	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function dp_edit_walker($walker,$menu_id) {
	
	    return 'Walker_Nav_Menu_Edit_Custom';
	    
	}

}
// instantiate plugin's class
$GLOBALS['DP_custom_menu'] = new DP_custom_menu();
	

// EOF