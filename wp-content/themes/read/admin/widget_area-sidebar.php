<?php

	function read__get_page_id()
	{
		$page_id = "";
		
		if (is_home()) // Blog page.
		{
			$page_id = get_option('page_for_posts'); // Reading Settings > Posts page.
		}
		elseif (is_post_type_archive('product') || is_tax('product_cat') || is_singular('product')) // WooCommerce plugin pages. (shop page, product category page, product page)
		{
			$page_id = get_option('woocommerce_shop_page_id');
		}
		elseif (is_singular())
		{
			$page_id = get_the_ID(); // Pages, Posts, Custom Post Types.
		}
		
		return $page_id;
	}


/* ============================================================================================================================================= */


	function read__get_individual_sidebar_area()
	{
		$page_id = read__get_page_id();
		$sidebar = get_option($page_id . 'my_sidebar', 'inherit');
		
		return $sidebar;
	}


/* ============================================================================================================================================= */
/* ============================================================================================================================================= */


	function read__is_archive_sidebar_active()
	{
		$is_archive_sidebar_active = false;
		$blog_sidebar              = get_option('blog_sidebar', 'Yes');
		
		if ($blog_sidebar != 'No')
		{
			if (is_active_sidebar('blog_sidebar'))
			{
				$is_archive_sidebar_active = true;
			}
		}
		
		return $is_archive_sidebar_active;
	}


/* ============================================================================================================================================= */


	function read__is_singular_sidebar_active()
	{
		$is_singular_sidebar_active = false;
		$individual_sidebar_area    = read__get_individual_sidebar_area();
		
		if ($individual_sidebar_area == 'no_sidebar')
		{
			$is_singular_sidebar_active = false;
		}
		elseif (is_active_sidebar($individual_sidebar_area))
		{
			$is_singular_sidebar_active = true;
		}
		else  // inherit.
		{
			if (is_singular('page'))
			{
				$page_sidebar = get_option('page_sidebar', 'No');
				
				if ($page_sidebar == 'Yes')
				{
					if (is_active_sidebar('page_sidebar'))
					{
						$is_singular_sidebar_active = true;
					}
				}
			}
			elseif (is_singular('post'))
			{
				$post_sidebar = get_option('post_sidebar', 'Yes');
				
				if ($post_sidebar != 'No')
				{
					if (is_active_sidebar('blog_sidebar'))
					{
						$is_singular_sidebar_active = true;
					}
				}
			}
		}
		
		return $is_singular_sidebar_active;
	}


/* ============================================================================================================================================= */


	function read__is_sidebar_active()
	{
		$is_sidebar_active = false;
		
		if (isset($_GET['sidebar']))
		{
			if ($_GET['sidebar'] == 'yes')
			{
				$is_sidebar_active = true;
			}
			elseif ($_GET['sidebar'] == 'no')
			{
				$is_sidebar_active = false;
			}
		}
		else
		{
			if (is_404())
			{
				$is_sidebar_active = false;
			}
			elseif (! have_posts())
			{
				$is_sidebar_active = false;
			}
			else
			{
				if (is_singular()) // Any single.
				{
					$is_sidebar_active = read__is_singular_sidebar_active();
				}
				else // Blog page and Archive pages.
				{
					if (is_home() || is_archive())
					{
						$is_sidebar_active = read__is_archive_sidebar_active();
					}
				}
			}
		}
		
		return $is_sidebar_active;
	}


/* ============================================================================================================================================= */
/* ============================================================================================================================================= */


	function read__get_sidebar_width()
	{
		$sidebar_width = 'default';
		
		if (isset($_GET['sidebar_width']))
		{
			if ($_GET['sidebar_width'] == 'default')
			{
				$sidebar_width = 'default';
			}
			elseif ($_GET['sidebar_width'] == 'narrow')
			{
				$sidebar_width = 'narrow';
			}
			elseif ($_GET['sidebar_width'] == 'extra_narrow')
			{
				$sidebar_width = 'extra_narrow';
			}
		}
		else
		{
			$sidebar_width = get_option('read__sidebar_width', 'default');
		}
		
		return $sidebar_width;
	}


/* ============================================================================================================================================= */


	function read__get_sidebar_class()
	{
		$sidebar_class = "";
		
		if (read__is_sidebar_active())
		{
			$sidebar_class = 'blog-with-sidebar';
		}
		
		return esc_attr($sidebar_class);
	}


/* ============================================================================================================================================= */


	function read__get_layout_class()
	{
		$layout_class = "";
		
		if (read__is_sidebar_active())
		{
			$sidebar_width = read__get_sidebar_width();
			
			if ($sidebar_width == 'narrow')
			{
				$layout_class = 'span8';
			}
			elseif ($sidebar_width == 'extra_narrow')
			{
				$layout_class = 'span9';
			}
			else
			{
				$layout_class = 'span7';
			}
		}
		
		return esc_attr($layout_class);
	}


/* ============================================================================================================================================= */
/* ============================================================================================================================================= */


	function read__archive_sidebar_content()
	{
		dynamic_sidebar('blog_sidebar');
	}


/* ============================================================================================================================================= */


	function read__singular_sidebar_content()
	{
		$individual_sidebar_area = read__get_individual_sidebar_area();
		
		if (is_active_sidebar($individual_sidebar_area))
		{
			dynamic_sidebar($individual_sidebar_area);
		}
		else // inherit.
		{
			if (is_singular('page'))
			{
				dynamic_sidebar('page_sidebar');
			}
			elseif (is_singular('post'))
			{
				dynamic_sidebar('blog_sidebar');
			}
		}
	}


/* ============================================================================================================================================= */


	function read__sidebar()
	{
		if (read__is_sidebar_active())
		{
			$sidebar_width = read__get_sidebar_width();
			
			if ($sidebar_width == 'narrow')
			{
				$sidebar_width = 'span4';
			}
			elseif ($sidebar_width == 'extra_narrow')
			{
				$sidebar_width = 'span3';
			}
			else
			{
				$sidebar_width = 'span5';
			}
			
			?>
				<div id="secondary" class="widget-area <?php echo esc_attr($sidebar_width); ?>" role="complementary">
					<?php
						if (is_singular()) // Any single.
						{
							read__singular_sidebar_content();
						}
						else // Any archive.
						{
							read__archive_sidebar_content();
						}
					?>
				</div> <!-- #secondary .widget-area .span5 -->
			<?php
		}
	}
