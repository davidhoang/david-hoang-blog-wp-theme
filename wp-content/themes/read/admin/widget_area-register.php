<?php

	function read__widgets_init()
	{
		register_sidebar(
			array(
				'name'          => __('Blog Sidebar', 'read'),
				'id'            => 'blog_sidebar',
				'description'   => 'Add widgets.',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(
			array(
				'name'          => __('Post Sidebar', 'read'),
				'id'            => 'post_sidebar',
				'description'   => 'Add widgets.',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(
			array(
				'name'          => __('Page Sidebar', 'read'),
				'id'            => 'page_sidebar',
				'description'   => 'Add widgets.',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(
			array(
				'name'          => __('Header Social Icons', 'read'),
				'id'            => 'header_sidebar',
				'description'   => 'Use social icon shortcodes with the "Custom HTML" widget.',
				'before_widget' => "",
				'after_widget'  => "",
				'before_title'  => '<span style="display: none;">',
				'after_title'   => '</span>'
			)
		);
		
		register_sidebar(
			array(
				'name'          => __('Footer 1', 'read'),
				'id'            => 'footer_1',
				'description'   => 'Add widgets.',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(
			array(
				'name'          => __('Footer 2', 'read'),
				'id'            => 'footer_2',
				'description'   => 'Add widgets.',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(
			array(
				'name'          => __('Footer 3', 'read'),
				'id'            => 'footer_3',
				'description'   => 'Add widgets.',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(
			array(
				'name'          => __('Footer 4', 'read'),
				'id'            => 'footer_4',
				'description'   => 'Add widgets.',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
		
		
		$sidebars_with_commas = get_option('sidebars_with_commas');
		
		if ($sidebars_with_commas != "")
		{
			$sidebars = preg_split("/[\s]*[,][\s]*/", $sidebars_with_commas);
			
			foreach ($sidebars as $sidebar_name)
			{
				register_sidebar(
					array(
						'name'          => $sidebar_name,
						'id'            => $sidebar_name,
						'description'   => 'Add widgets.',
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h3 class="widget-title">',
						'after_title'   => '</h3>'
					)
				);
			}
		}
	}
	
	add_action('widgets_init', 'read__widgets_init');
