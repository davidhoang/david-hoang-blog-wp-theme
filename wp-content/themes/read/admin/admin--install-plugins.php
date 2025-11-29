<?php

	require_once get_template_directory() . '/admin/admin--class-tgm-plugin-activation.php';
	
	
	function read_plugins()
	{
		$message  = '<div class="notice notice-warning read-install-plugins-notice">';
		$message .= 	'<p><b>' . esc_html__('Note:', 'read') . '</b> ' . '<span>' . esc_html__('If you encounter any update issue for a plugin, just deactivate it from Plugins page and delete, then reinstall from here.', 'read') . '</span>' . '</p>';
		$message .= '</div>';
		
		$config = array(
			'id'           => 'read_tgmpa',
			'default_path' => "",
			'menu'         => 'read-install-theme-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '<h2>' . esc_html__('Theme Plugins', 'read') . '</h2>',
			'is_automatic' => true,
			'message'      => $message,
			'strings'      => array(
				'menu_title' => esc_html__('Install Theme Plugins', 'read'),
				'page_title' => esc_html__('Install Theme Plugins', 'read')
			)
		);
		
		$plugins = array(
			array(
				'name'     => esc_html__('One Click Demo Import', 'read'),
				'slug'     => 'one-click-demo-import',
				'required' => false
			),
			array(
				'name'     => esc_html__('Regenerate Thumbnails', 'read'),
				'slug'     => 'regenerate-thumbnails',
				'required' => false
			),
			array(
				'name'     => esc_html__('Loco Translate', 'read'),
				'slug'     => 'loco-translate',
				'required' => false
			),
			array(
				'name'     => esc_html__('Instagram Feed Gallery', 'read'),
				'slug'     => 'insta-gallery',
				'required' => false
			),
			array(
				'name'     => esc_html__('YARPP Related Posts', 'read'),
				'slug'     => 'yet-another-related-posts-plugin',
				'required' => false
			),
			array(
				'name'     => esc_html__('Twitter Widget Pro', 'read'),
				'slug'     => 'twitter-widget-pro',
				'required' => false
			),
			array(
				'name'     => esc_html__('Contact Form 7', 'read'),
				'slug'     => 'contact-form-7',
				'required' => false
			)
		);
		
		tgmpa($plugins, $config);
	}
	
	add_action('tgmpa_register', 'read_plugins');
