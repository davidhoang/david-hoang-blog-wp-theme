<?php

	$layout = 'Regular';
	
	if (isset($_GET['layout']))
	{
		if ($_GET['layout'] == 'regular')
		{
			$layout = 'Regular';
		}
		elseif ($_GET['layout'] == 'masonry')
		{
			$layout = 'Masonry';
		}
	}
	else
	{
		if (is_archive())
		{
			$archive_type = get_option('category_archive_type', 'Masonry');
			
			if ($archive_type == 'Regular')
			{
				$layout = 'Regular';
			}
			else
			{
				$layout = 'Masonry';
			}
		}
		else
		{
			$blog_type = get_option('blog_type', 'Regular');
			
			if ($blog_type == 'Masonry')
			{
				$layout = 'Masonry';
			}
			else
			{
				$layout = 'Regular';
			}
		}
	}
	
	
	if ($layout == 'Masonry')
	{
		get_template_part('blog', 'masonry');
	}
	else
	{
		get_template_part('blog', 'regular');
	}
