(function() {
    tinymce.PluginManager.add('DPWPShortcodes', function( editor, url ) {
        editor.addButton( 'dp_style', {
			icon: 'dp-style',
			title: 'Add Popular HTML Tags Shortcode',
            icon: false,
            onclick: function() {
				editor.windowManager.open({
					file : url + '/style_dialog.php',
					width : 430 + editor.getLang('dp_style.delta_width', 0),
					height : 320 + editor.getLang('dp_style.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url 
				});
            }
        });

        editor.addButton( 'dp_columns', {
			icon: 'dp-columns',
			title: 'Add Columns Shortcode',
            onclick: function() {
				editor.windowManager.open({
					file : url + '/columns_dialog.php',
					width : 590 + editor.getLang('dp_columns.delta_width', 0),
					height : 370 + editor.getLang('dp_columns.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url 
				});
            }
        });

        editor.addButton( 'dp_button', {
			icon: 'dp-button',
			title: 'Add Button Shortcode',
            onclick: function() {
				editor.windowManager.open({
					file : url + '/buttons_dialog.php',
					width : 750 + editor.getLang('dp_columns.delta_width', 0),
					height : 340 + editor.getLang('dp_columns.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url 
				});
            }
        });

        editor.addButton( 'dp_icon', {
			icon: 'dp-icon',
			title: 'Add Icon Shortcode',
            onclick: function() {
				editor.windowManager.open({
					file : url + '/icon_dialog.php',
					width : 750 + editor.getLang('dp_columns.delta_width', 0),
					height : 340 + editor.getLang('dp_columns.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url 
				});
            }
        });

        editor.addButton( 'dp_map', {
			icon: 'dp-map',
			title: 'Add Google Map Shortcode',
            onclick: function() {
				editor.windowManager.open({
					file : url + '/map_dialog.php',
					width : 570 + editor.getLang('dp_maps.delta_width', 0),
					height : 555 + editor.getLang('dp_maps.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url 
				});
            }
        });

        editor.addButton( 'dp_vimeo', {
			icon: 'dp-vimeo',
			title: 'Add Vimeo Video Shortcode',
            onclick: function() {
				editor.windowManager.open({
					file : url + '/vimeo_dialog.php',
					width : 380 + editor.getLang('dp_vimeo.delta_width', 0),
					height : 160 + editor.getLang('dp_vimeo.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url 
				});
            }
        });

        editor.addButton( 'dp_youtube', {
			icon: 'dp-youtube',
			title: 'Add Youtube Video Shortcode',
            onclick: function() {
				editor.windowManager.open({
					file : url + '/youtube_dialog.php',
					width : 380 + editor.getLang('dp_youtube.delta_width', 0),
					height : 160 + editor.getLang('dp_youtube.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url 
				});
            }
        });

    });
})();

