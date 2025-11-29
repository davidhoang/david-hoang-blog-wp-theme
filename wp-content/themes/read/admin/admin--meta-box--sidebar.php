<?php

	function read__meta_box__sidebar_html($post)
	{
		?>
			<div class="admin-inside-box read--meta-box">
				<?php
					wp_nonce_field(
						'read__meta_box__sidebar_html',
						'read__meta_box__sidebar_nonce'
					);
				?>
				
				<p>
					<?php
						$my_sidebar = get_option(get_the_ID() . 'my_sidebar', 'inherit');
					?>
					<select id="my_sidebar" name="my_sidebar">
						<option <?php if ($my_sidebar == 'inherit')      { echo 'selected="selected"'; } ?> value="inherit"><?php      esc_html_e('Inherit from Theme Options', 'read'); ?></option>
						<option <?php if ($my_sidebar == 'no_sidebar')   { echo 'selected="selected"'; } ?> value="no_sidebar"><?php   esc_html_e('No Sidebar', 'read'); ?></option>
						<option <?php if ($my_sidebar == 'blog_sidebar') { echo 'selected="selected"'; } ?> value="blog_sidebar"><?php esc_html_e('Blog Sidebar', 'read'); ?></option>
						<option <?php if ($my_sidebar == 'post_sidebar') { echo 'selected="selected"'; } ?> value="post_sidebar"><?php esc_html_e('Post Sidebar', 'read'); ?></option>
						<option <?php if ($my_sidebar == 'page_sidebar') { echo 'selected="selected"'; } ?> value="page_sidebar"><?php esc_html_e('Page Sidebar', 'read'); ?></option>
						
						<?php
							$sidebars_with_commas = get_option('sidebars_with_commas');
							
							if ($sidebars_with_commas != "")
							{
								$sidebars = preg_split("/[\s]*[,][\s]*/", $sidebars_with_commas);
								
								foreach ($sidebars as $sidebar_name)
								{
									$selected = "";
									
									if ($my_sidebar == $sidebar_name)
									{
										$selected = 'selected="selected"';
									}
									
									echo '<option ' . $selected . ' value="' . $sidebar_name . '">' . $sidebar_name . '</option>';
								}
							}
						?>
					</select>
					
					<?php
						$current_screen = get_current_screen();
						
						if ($current_screen->id === 'post')
						{
							?>
								<span class="howto">
									<?php
										esc_html_e('"Inherit from Theme Options": Appearance > Theme Options > Sidebar > Post Sidebar.', 'read');
									?>
								</span>
							<?php
						}
						elseif ($current_screen->id === 'page')
						{
							?>
								<span class="howto">
									<?php
										esc_html_e('"Inherit from Theme Options": Appearance > Theme Options > Sidebar > Page Sidebar.', 'read');
									?>
								</span>
							<?php
						}
					?>
					<span class="howto">
						<?php
							esc_html_e('Sidebar is a widget area. You can find all available sidebars in your Widgets page under Appearance menu and Widgets section in Customizer.', 'read');
						?>
					</span>
					<span class="howto">
						<?php
							esc_html_e('You can create new sidebars from Appearance > Theme Options > Widget Areas.', 'read');
						?>
					</span>
				</p>
			</div>
		<?php
	}
	
	
	function read__save_meta_box__sidebar($post_id)
	{
		if (! isset($_POST['read__meta_box__sidebar_nonce']))
		{
			return $post_id;
		}
		
		$nonce = $_POST['read__meta_box__sidebar_nonce'];
		
		if (! wp_verify_nonce($nonce, 'read__meta_box__sidebar_html'))
        {
			return $post_id;
		}
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
        {
			return $post_id;
		}
		
		if ('page' == $_POST['post_type'])
		{
			if (! current_user_can('edit_page', $post_id))
			{
				return $post_id;
			}
		}
		else
		{
			if (! current_user_can('edit_post', $post_id))
			{
				return $post_id;
			}
		}
		
		update_option($post_id . 'my_sidebar', $_POST['my_sidebar']);
	}
	
	add_action('save_post', 'read__save_meta_box__sidebar');
	
	
	function read__add_meta_boxes__sidebar($post_type, $post)
	{
		add_meta_box(
			'read__meta_box__sidebar',
			esc_html__('Sidebar', 'read'),
			'read__meta_box__sidebar_html',
			array('post', 'page'),
			'side',
			'high'
		);
	}
	
	add_action('add_meta_boxes', 'read__add_meta_boxes__sidebar', 10, 2);
