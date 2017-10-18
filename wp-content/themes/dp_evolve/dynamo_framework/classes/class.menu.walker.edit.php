<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	
	function start_lvl(&$output, $depth = 0, $args = array()) {	
	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = array()) {
	}
	
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	    ob_start();
		add_thickbox(); 
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)', 'dp-theme' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)', 'dp-theme'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;
	
	    ?>
	    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, network_admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'dp-theme'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, network_admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'dp-theme'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item', 'dp-theme'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? network_admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, network_admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item' , 'dp-theme'); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
	                        <?php _e( 'URL', 'dp-theme' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Navigation Label', 'dp-theme' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Title Attribute' , 'dp-theme'); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php _e( 'Open link in a new window/tab', 'dp-theme' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
	                    <?php _e( 'CSS Classes (optional)', 'dp-theme' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
	                    <?php _e( 'Link Relationship (XFN)', 'dp-theme' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
	                    <?php _e( 'Description' , 'dp-theme'); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'dp-theme'); ?></span>
	                </label>
	            </p>        
	            <?php
	            /* New fields insertion starts here */
	            ?>
                    <div class="clearboth"></div>
				<h4><b><?php _e('Additional menu item setting', 'dp-theme'); ?></b></h4>
                   	<p class="field-subtitle description description-thin">
	                <label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
	                    <?php _e( 'Subtitle', 'dp-theme' ); ?><br />
	                    <input type="text" id="edit-menu-item-subtitle-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-subtitle[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->subtitle ); ?>" />
	                </label>
	            </p>
                
                <p class="field-itemwidth description description-thin">
                    <label for="edit-menu-item-itemwidth-<?php echo $item_id; ?>">
                    <?php _e('Item width (px)', 'dp-theme'); ?><br/>
                        <input type="text" id="edit-menu-item-itemwidth-<?php echo $item_id; ?>"
                               class="widefat code edit-menu-item-itemwidth"
                               name="menu-item-itemwidth[<?php echo $item_id; ?>]"
                               value="<?php echo esc_attr($item->itemwidth); ?>"/>
                    </label>
                </p>
               <div style="clear:both"></div>
                <p class="field-icon description description-thin">
                    <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
                    <?php _e('Icon', 'dp-theme'); ?><br/>
                        <input type="text" id="edit-menu-item-icon-<?php echo $item_id; ?>"
                               class="widefat code edit-menu-item-icon"
                               name="menu-item-icon[<?php echo $item_id; ?>]"
                               value="<?php echo esc_attr($item->icon); ?>"/>
                               
                    </label>
                                        

                </p> <p class="description-thin">
<a href="<?php echo get_template_directory_uri(); ?>/dynamo_framework/fontello/icons.php?parentid=edit-menu-item-icon-<?php echo $item_id; ?>&parentpreviewid=previewicon-<?php echo $item_id; ?>&TB_iframe=true&width=500&height=550" class="thickbox button">Select icon</a>
				<span class="previewicon" id="previewicon-<?php echo $item_id; ?>"></span></p>
                <p class="field-itemcss description description-thin">
                    <label for="edit-menu-item-itemcss-<?php echo $item_id; ?>">
                    <?php _e('Custom CSS class for menu item', 'dp-theme'); ?><br/>
                        <input type="text" id="edit-menu-item-itemcss-<?php echo $item_id; ?>"
                               class="widefat code edit-menu-item-itemcss"
                               name="menu-item-itemcss[<?php echo $item_id; ?>]"
                               value="<?php echo esc_attr($item->itemcss); ?>"/>
                    </label>
                </p>
<p class="description-wide"><br/><a class="button-primary megamenu-options-togle" data-id="<?php echo $item_id; ?>" id="megamenu-options-togle-<?php echo $item_id; ?>" ><span>Show mega menu options</span></a></p>                
<div class="mega-menu-options-panel" id="mega-menu-options-panel-<?php echo $item_id; ?>" >                
<p class="field-menutype description description-wide">
                    <label for="edit-menu-item-menutype-<?php echo $item_id; ?>"></label>
                    <?php _e('Menu item type', 'dp-theme'); ?><br/>
                        <select id="edit-menu-item-menutype-<?php echo $item_id; ?>" class="widefat edit-menu-item-menutype"
                                name="menu-item-menutype[<?php echo $item_id; ?>]">
                            <option value="standard" <?php echo (esc_attr($item->menutype)=='standard') ? ' selected="selected"' : ''; ?>><?php _e('Standard dropdown', 'dp-theme'); ?></option>
                            <option value="megamenu" <?php echo (esc_attr($item->menutype)=='megamenu') ? ' selected="selected"' : ''; ?>><?php _e('Megamenu', 'dp-theme'); ?></option>
                            <option value="megamenu-full" <?php echo (esc_attr($item->menutype)=='megamenu-full') ? ' selected="selected"' : ''; ?>><?php _e('Megamenu full width', 'dp-theme'); ?></option>
                        </select>
                    
              
                <p class="field-megaalign description description-wide">
                    <label for="edit-menu-item-megaalign-<?php echo $item_id; ?>">
                    <?php _e('Megamenu allignment (only for megamenu auto width)', 'dp-theme'); ?><br/>
                        <select id="edit-menu-item-megaalign-<?php echo $item_id; ?>" class="widefat edit-menu-item-megaalign"
                                name="menu-item-megaalign[<?php echo $item_id; ?>]">
                            <option value="toleft" <?php echo (esc_attr($item->megaalign)=='toleft') ? ' selected="selected"' : ''; ?>><?php _e('To left edge (default)', 'dp-theme'); ?></option>
                            <option value="tocenter" <?php echo (esc_attr($item->megaalign)=='tocenter') ? ' selected="selected"' : ''; ?>><?php _e('Center', 'dp-theme'); ?></option>
                            <option value="toright" <?php echo (esc_attr($item->megaalign)=='toright') ? ' selected="selected"' : ''; ?>><?php _e('To right edge', 'dp-theme'); ?></option>
                        </select>
                    </label>
                </p>

                <p class="field-columns description description-wide">
                    <label for="edit-menu-item-columns-<?php echo $item_id; ?>">
                    <?php _e('Columns (only for full width megamenu)', 'dp-theme'); ?><br/>
                        <select id="edit-menu-item-columns-<?php echo $item_id; ?>" class="widefat edit-menu-item-columns"
                                name="menu-item-columns[<?php echo $item_id; ?>]">
                            <option value="2" <?php echo (esc_attr($item->columns)=='2') ? ' selected="selected"' : ''; ?>>2</option>
                            <option value="3" <?php echo (esc_attr($item->columns)=='3') ? ' selected="selected"' : ''; ?>>3</option>
                            <option value="4" <?php echo (esc_attr($item->columns)=='4') ? ' selected="selected"' : ''; ?>>4</option>
                            <option value="5" <?php echo (esc_attr($item->columns)=='5') ? ' selected="selected"' : ''; ?>>5</option>
                            <option value="6" <?php echo (esc_attr($item->columns)=='6') ? ' selected="selected"' : ''; ?>>6</option>
                        </select>
                    </label>
                </p>
                <p class="field-asheader description description-wide">
                    <label for="edit-menu-item-asheader-<?php echo $item_id; ?>">
                    <?php _e('Display item as column header (only megamenu\'s)', 'dp-theme'); ?><br/>
                        <select id="edit-menu-item-asheader-<?php echo $item_id; ?>" class="widefat edit-menu-item-asheader"
                                name="menu-item-asheader[<?php echo $item_id; ?>]">
                            <option value="no" <?php echo (esc_attr($item->asheader)=='no') ? ' selected="selected"' : ''; ?>>No</option>
                            <option value="yes" <?php echo (esc_attr($item->asheader)=='yes') ? ' selected="selected"' : ''; ?>>Yes</option>
                        </select>
                    </label>
                </p>
	            
                <div class="clearboth"></div>
				<h4><b><?php _e('Custom Content <small><i>(only for 1st level in megemenu)</i></small>', 'dp-theme'); ?></b></h4>
                <p class="field-image description description-wide">
                    <label for="edit-menu-item-image-<?php echo $item_id; ?>">
                    <?php _e('Image', 'dp-theme'); ?><br/>
                        <input type="text" id="edit-menu-item-image-<?php echo $item_id; ?>"
                               class="widefat code edit-menu-item-image"
                               name="menu-item-image[<?php echo $item_id; ?>]"
                               value="<?php echo esc_attr($item->image); ?>"/>
                    </label>
                    <input  class="button uploadbtn menu-upload-image" name="menu-item-image-button-<?php echo $item_id; ?>" id="menu-item-image-button-<?php echo $item_id; ?>" data-parentid="<?php echo $item_id; ?>" value="<?php _e('Upload Image', 'dp-theme'); ?>" />
                    <small><a  href="#" class="menu-clear-image" id="menu-item-image-clear-<?php echo $item_id; ?>" data-parentid="<?php echo $item_id; ?>" /><?php _e('Remove Image', 'dp-theme'); ?></a></small>
                </p>
                <p class="field-html description description-wide">
                    <label for="edit-menu-item-html-<?php echo $item_id; ?>">
                    <?php _e('Content', 'dp-theme'); ?><br/>
                        <textarea id="edit-menu-item-html-<?php echo $item_id; ?>"
                               class="widefat" rows="3" cols="20"
                               name="menu-item-html[<?php echo $item_id; ?>]"
                              /><?php echo esc_html(esc_attr($item->html)); ?></textarea>
                    </label>
                </p>
                <p class="field-widget description description-wide">
                    <label for="edit-menu-item-widget-<?php echo $item_id; ?>">
                    <?php _e('Widget (select widget to display as custom content)', 'dp-theme'); ?><br/>
                        <select id="edit-menu-item-widget-<?php echo $item_id; ?>" class="widefat edit-menu-item-widget"
                                name="menu-item-widget[<?php echo $item_id; ?>]">
                            <option value="none" <?php echo (esc_attr($item->widget)=='none') ? ' selected="selected"' : ''; ?>></option>
                            <option value="megamenu1" <?php echo (esc_attr($item->widget)=='megamenu1') ? ' selected="selected"' : ''; ?>>Megamenu Widget 1</option>
                            <option value="megamenu2" <?php echo (esc_attr($item->widget)=='megamenu2') ? ' selected="selected"' : ''; ?>>Megamenu Widget 2</option>
                        </select>
                    </label>
                </p>
	            <?php
	            /* New fields insertion ends here */
	            ?>
                </div>
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s', 'dp-theme'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, network_admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php _e('Remove', 'dp-theme'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, network_admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'dp-theme'); ?></a>
                
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

	    }
}

// EOF