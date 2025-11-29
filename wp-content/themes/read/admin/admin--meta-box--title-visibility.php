<?php

	function my_meta_box_hide_post_title()
	{
		global $post, $post_ID;
		
		?>
			<div class="admin-inside-box">
				<input type="hidden" name="my_meta_box_hide_post_title_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">
				
				<p>
					<?php
						$hide_post_title = get_option($post->ID . 'hide_post_title', false);
						
						if ( $hide_post_title )
						{
							$hide_post_title_out = 'checked="checked"';
						}
						else
						{
							$hide_post_title_out = "";
						}
					?>
					<label for="hide_post_title"><input type="checkbox" id="hide_post_title" name="hide_post_title" <?php echo $hide_post_title_out; ?>> Hide title</label>
				</p>
			</div>
		<?php
	}
	
	
	function my_save_meta_box_hide_post_title($post_id)
	{
		global $post, $post_ID;
		
		if (! wp_verify_nonce(@$_POST['my_meta_box_hide_post_title_nonce'], basename(__FILE__)))
		{
			return $post_id;
		}
		
		if ($_POST['post_type'] == 'post')
		{
			if (! current_user_can('edit_page', $post_id))
			{
				return $post_id;
			}
		}
		else
		{
			if (! current_user_can('edit_post', $post_id))
			
			return $post_id;
		}
		
		if ($_POST['post_type'] == 'post')
		{
			update_option($post->ID . 'hide_post_title', $_POST['hide_post_title']);
		}
	}
	
	add_action('save_post', 'my_save_meta_box_hide_post_title');
	
	
	function my_add_meta_box_hide_post_title()
	{
		add_meta_box(
			'my_meta_box_hide_post_title',
			esc_html__('Title Visibility', 'read'),
			'my_meta_box_hide_post_title',
			'post',
			'side',
			'high'
		);
	}
	
	add_action('admin_init', 'my_add_meta_box_hide_post_title');
