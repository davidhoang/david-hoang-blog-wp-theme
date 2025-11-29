<?php

	function read_ocdi_import_files()
	{
		$theme_directory     = trailingslashit(get_template_directory());
		$theme_directory_url = trailingslashit(get_template_directory_uri());
		
		return array(
			array(
				'import_file_name'             => esc_html__('Demo', 'read'),
				'local_import_file'            => $theme_directory     . 'admin/demo-data/content.xml',
				'local_import_widget_file'     => $theme_directory     . 'admin/demo-data/widgets.wie',
				'local_import_customizer_file' => $theme_directory     . 'admin/demo-data/customizer.dat',
				'import_preview_image_url'     => $theme_directory_url . 'admin/demo-data/screenshot.jpg'
			)
		);
	}
	
	add_filter('pt-ocdi/import_files', 'read_ocdi_import_files');
	
	
	function read_ocdi_time_for_one_ajax_call()
	{
		return 10;
	}
	
	add_action('pt-ocdi/time_for_one_ajax_call', 'read_ocdi_time_for_one_ajax_call');


/* ============================================================================================================================================= */


	function read_after_import()
	{
		// Assign menus to their locations.
		
		$main_menu = get_term_by('name', 'My Menu', 'nav_menu');
		
		set_theme_mod(
			'nav_menu_locations',
			array(
				'top_menu' => $main_menu->term_id,
			)
		);
	}
	
	add_action('pt-ocdi/after_import', 'read_after_import');


/* ============================================================================================================================================= */


	add_filter('pt-ocdi/disable_pt_branding', '__return_true');
	
	add_filter('pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false');

?>