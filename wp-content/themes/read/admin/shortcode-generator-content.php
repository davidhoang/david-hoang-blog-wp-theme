<form class="sh-wrap">
	<br>
	<label for="sh_list">Shortcodes</label>
	<br>
	
	<select id="sh_list" name="sh_list" class="widefat sh-list" style="width: 50%;">
		<option></option>
		<option value="row">Row</option>
		<option value="column">Column</option>
		<option value="tabs">Tabs</option>
		<option value="tab_head">Tab Head</option>
		<option value="tab_title">Tab Title</option>
		<option value="tab_content">Tab Content</option>
		<option value="tab_pane">Tab Pane</option>
		<option value="accordions">Accordions</option>
		<option value="accordion">Accordion</option>
		<option value="toggles">Toggles</option>
		<option value="toggle">Toggle</option>
		<option value="tagline">Tagline</option>
		<option value="intro">Intro</option>
		<option value="label">Label</option>
		<option value="inline_media">Inline Media</option>
		<option value="call_to_action">Call To Action</option>
		<option value="cta_button_wrap">CTA Button Wrap</option>
		<option value="lead_p">Lead P</option>
		<option value="portfolio_text">Portfolio Text</option>
		<option value="launch_button">Launch Button</option>
		<option value="project_action">Project Action</option>
		<option value="view_button">View Button</option>
		<option value="syntax_prettify">Syntax Prettify</option>
		<option value="alert">Alert</option>
		<option value="lists">Lists</option>
		<option value="list_item">List Item</option>
		<option value="social_icons">Social Icons</option>
		<option value="social_icon">Social Icon</option>
		<option value="my_button">Button</option>
		<option value="inline_slider">Inline Slider</option>
		<option value="slide">Slide</option>
		<option value="divider">Divider</option>
		<option value="lightbox_audio">Lightbox Audio</option>
		<option value="lightbox_video">Lightbox Video</option>
		<option value="lightbox_image">Lightbox Image</option>
		
	</select>
	<!-- end shortcodes -->
	
	<br>
	<br>
	
	
	<!-- shortcodes fields ====================================== -->
	
	
	<!-- column -->
	<div class="column" style="display: none;">
		<label for="column_width">Width</label>
		<br>
		<select id="column_width" name="column_width" class="widefat" style="width: 30%;">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
			<option>6</option>
			<option>7</option>
			<option>8</option>
			<option>9</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
		</select>
		<br>
		<br>
		<label for="column_offset">Offset</label>
		<br>
		<select id="column_offset" name="column_offset" class="widefat" style="width: 30%;">
			<option></option>
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
			<option>6</option>
			<option>7</option>
			<option>8</option>
			<option>9</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
		</select>
		<br>
		<br>
		<label for="column_content">Content</label>
		<br>
		<textarea id="column_content" name="column_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end column -->
	
	
	<!-- tab_title -->
	<div class="tab_title" style="display: none;">
		<label for="tab_title_active">Active</label>
		<br>
		<select id="tab_title_active" name="tab_title_active" class="widefat" style="width: 30%;">
			<option></option>
			<option>active</option>
		</select>
		<br>
		<br>
		<label for="tab_title_title">Title</label>
		<br>
		<input type="text" id="tab_title_title" name="tab_title_title" class="widefat" style="width: 30%;">
		<br>
		<br>
	</div>
	<!-- end tab_title -->
	
	
	<!-- tab_pane -->
	<div class="tab_pane" style="display: none;">
		<label for="tab_pane_content">Content</label>
		<br>
		<textarea id="tab_pane_content" name="tab_pane_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end tab_pane -->
	
	
	<!-- accordion -->
	<div class="accordion" style="display: none;">
		<label for="accordion_active">Active</label>
		<br>
		<select id="accordion_active" name="accordion_active" class="widefat" style="width: 30%;">
			<option></option>
			<option>active</option>
		</select>
		<br>
		<br>
		<label for="accordion_title">Title</label>
		<br>
		<input type="text" id="accordion_title" name="accordion_title" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="accordion_content">Content</label>
		<br>
		<textarea id="accordion_content" name="accordion_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end accordion -->
	
	
	<!-- toggle -->
	<div class="toggle" style="display: none;">
		<label for="toggle_active">Active</label>
		<br>
		<select id="toggle_active" name="toggle_active" class="widefat" style="width: 30%;">
			<option></option>
			<option>active</option>
		</select>
		<br>
		<br>
		<label for="toggle_title">Title</label>
		<br>
		<input type="text" id="toggle_title" name="toggle_title" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="toggle_content">Content</label>
		<br>
		<textarea id="toggle_content" name="toggle_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end toggle -->
	
	
	<!-- tagline -->
	<div class="tagline" style="display: none;">
		<label for="tagline_content">Content</label>
		<br>
		<textarea id="tagline_content" name="tagline_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end tagline -->
	
	
	<!-- intro -->
	<div class="intro" style="display: none;">
		<label for="intro_content">Content</label>
		<br>
		<textarea id="intro_content" name="intro_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end intro -->
	
	
	<!-- label -->
	<div class="label" style="display: none;">
		<label for="label_content">Content</label>
		<br>
		<textarea id="label_content" name="label_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end label -->
	
	
	<!-- inline_media -->
	<div class="inline_media" style="display: none;">
		<label for="inline_media_content">Content</label>
		<br>
		<textarea id="inline_media_content" name="inline_media_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end inline_media -->
	
	
	<!-- call_to_action -->
	<div class="call_to_action" style="display: none;">
		<label for="call_to_action_content">Content</label>
		<br>
		<textarea id="call_to_action_content" name="call_to_action_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end call_to_action -->
	
	
	<!-- lead_p -->
	<div class="lead_p" style="display: none;">
		<label for="lead_p_content">Content</label>
		<br>
		<textarea id="lead_p_content" name="lead_p_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end lead_p -->
	
	
	<!-- portfolio_text -->
	<div class="portfolio_text" style="display: none;">
		<label for="portfolio_text_content">Content</label>
		<br>
		<textarea id="portfolio_text_content" name="portfolio_text_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end portfolio_text -->
	
	
	<!-- launch_button -->
	<div class="launch_button" style="display: none;">
		<label for="launch_button_text">Text</label>
		<br>
		<input type="text" id="launch_button_text" name="launch_button_text" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="launch_button_url">URL</label>
		<br>
		<input type="text" id="launch_button_url" name="launch_button_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end launch_button -->
	
	
	<!-- project_action -->
	<div class="project_action" style="display: none;">
		<label for="project_action_content">Content</label>
		<br>
		<textarea id="project_action_content" name="project_action_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end project_action -->
	
	
	<!-- view_button -->
	<div class="view_button" style="display: none;">
		<label for="view_button_text">Text</label>
		<br>
		<input type="text" id="view_button_text" name="view_button_text" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="view_button_url">URL</label>
		<br>
		<input type="text" id="view_button_url" name="view_button_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end view_button -->
	
	
	<!-- syntax_prettify -->
	<div class="syntax_prettify" style="display: none;">
		<label for="syntax_prettify_linenums">Line Numbers</label>
		<br>
		<select id="syntax_prettify_linenums" name="syntax_prettify_linenums" class="widefat" style="width: 30%;">
			<option></option>
			<option>linenums</option>
		</select>
		<br>
		<br>
		<label for="syntax_prettify_content">Content</label>
		<br>
		<textarea id="syntax_prettify_content" name="syntax_prettify_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end syntax_prettify -->
	
	
	<!-- alert -->
	<div class="alert" style="display: none;">
		<label for="alert_type">Type</label>
		<br>
		<select id="alert_type" name="alert_type" class="widefat" style="width: 30%;">
			<option value="">standard</option>
			<option>info</option>
			<option>success</option>
			<option>error</option>
		</select>
		<br>
		<br>
		<label for="alert_content">Content</label>
		<br>
		<textarea id="alert_content" name="alert_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end alert -->
	
	
	<!-- lists -->
	<div class="lists" style="display: none;">
		<label for="lists_type">Type</label>
		<br>
		<select id="lists_type" name="lists_type" class="widefat" style="width: 30%;">
			<option>ok</option>
			<option>plus</option>
			<option>minus</option>
			<option>hand-right</option>
			<option>thumbs-up</option>
			<option>star</option>
			<option>star-empty</option>
			<option>chevron-right</option>
			<option>th-large</option>
		</select>
		<br>
		<br>
	</div>
	<!-- end lists -->
	
	
	<!-- list_item -->
	<div class="list_item" style="display: none;">
		<label for="list_item_content">Content</label>
		<br>
		<textarea id="list_item_content" name="list_item_content" rows="4" cols="50" class="widefat" style="width: 50%;"></textarea>
		<br>
		<br>
	</div>
	<!-- end list_item -->
	
	
	<!-- social_icon -->
	<div class="social_icon" style="display: none;">
		<label for="social_icon_type">Type</label>
		<br>
		<select id="social_icon_type" name="social_icon_type" class="widefat" style="width: 30%;">
			<option>facebook</option>
			<option>twitter</option>
			<option>linkedin</option>
			<option>flickr</option>
			<option>dribble</option>
			<option>lastfm</option>
			<option>rss</option>
			<option>forrst</option>
			<option>skype</option>
			<option>picassa</option>
			<option>youtube</option>
			<option>google</option>
			<option>vimeo</option>
			<option>behance</option>
			<option>tumblr</option>
			<option>blogger</option>
			<option>delicious</option>
			<option>digg</option>
			<option>friendfeed</option>
			<option>github</option>
			<option>wordpress</option>
			<option>pinterest</option>
		</select>
		<br>
		<br>
		<label for="social_icon_title">Title</label>
		<br>
		<input type="text" id="social_icon_title" name="social_icon_title" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="social_icon_url">URL</label>
		<br>
		<input type="text" id="social_icon_url" name="social_icon_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end social_icon -->
	
	
	<!-- my_button -->
	<div class="my_button" style="display: none;">
		<table style="width: 50%;">
			<tr>
				<td style="width: 50%;">
					<label for="my_button_color">Color</label>
					<br>
					<select id="my_button_color" name="my_button_color" class="widefat">
						<option value="">black</option>
						<option>blue</option>
						<option>navy</option>
						<option>grass</option>
						<option>red</option>
						<option>green</option>
						<option>yellow</option>
						<option>light</option>
						<option>orange</option>
					</select>
					<br>
					<br>
				</td>
				<td>
					<label for="my_button_size">Size</label>
					<br>
					<select id="my_button_size" name="my_button_size" class="widefat">
						<option value="">normal</option>
						<option>small</option>
						<option>big</option>
					</select>
					<br>
					<br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="my_button_type">Type</label>
					<br>
					<select id="my_button_type" name="my_button_type" class="widefat">
						<option value="">minimal</option>
						<option>fancy</option>
						<option value="d3">3D</option>
					</select>
					<br>
					<br>
				</td>
				<td>
					<label for="my_button_icon">Icon</label>
					<br>
					<select id="my_button_icon" name="my_button_icon" class="widefat">
						<option></option>
						<option>linkedin</option>
						<option>github</option>
						<option>buy</option>
						<option>eye</option>
						<option>beaker</option>
						<option>download</option>
						<option>download2</option>
						<option>rss</option>
						<option>like</option>
						<option>heart</option>
						<option>cup</option>
						<option>plus</option>
					</select>
					<br>
					<br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="my_button_target">Target</label>
					<br>
					<select id="my_button_target" name="my_button_target" class="widefat">
						<option></option>
						<option>_blank</option>
					</select>
					<br>
					<br>
				</td>
				<td>
					<label for="my_button_text">Text</label>
					<br>
					<input type="text" id="my_button_text" name="my_button_text" class="widefat">
					<br>
					<br>
				</td>
			</tr>
		</table>
		
		<label for="my_button_url">URL</label>
		<br>
		<input type="text" id="my_button_url" name="my_button_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end my_button -->
	
	
	<!-- inline_slider -->
	<div class="inline_slider" style="display: none;">
		<table style="width: 50%;">
			<tr>
				<td style="width: 50%;">
					<label for="inline_slider_autoplay">Autoplay</label>
					<br>
					<select id="inline_slider_autoplay" name="inline_slider_autoplay" class="widefat">
						<option>false</option>
						<option>true</option>
					</select>
					<br>
					<br>
				</td>
				<td>
					<label for="inline_slider_interval">Interval (miliseconds, default 3000)</label>
					<br>
					<input type="text" id="inline_slider_interval" name="inline_slider_interval" class="widefat" value="3000">
					<br>
					<br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="inline_slider_animation">Animation</label>
					<br>
					<select id="inline_slider_animation" name="inline_slider_animation" class="widefat">
						<option>slide</option>
						<option>fade</option>
					</select>
					<br>
					<br>
				</td>
				<td>
					<label for="inline_slider_direction">Direction</label>
					<br>
					<select id="inline_slider_direction" name="inline_slider_direction" class="widefat">
						<option>horizontal</option>
						<option>vertical</option>
					</select>
					<br>
					<br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="inline_slider_animationspeed">Animation Speed (miliseconds, default 800)</label>
					<br>
					<input type="text" id="inline_slider_animationspeed" name="inline_slider_animationspeed" class="widefat" value="800">
					<br>
					<br>
				</td>
				<td>
					<label for="inline_slider_pauseonhover">Pause On Hover</label>
					<br>
					<select id="inline_slider_pauseonhover" name="inline_slider_pauseonhover" class="widefat">
						<option>true</option>
						<option>false</option>
					</select>
					<br>
					<br>
				</td>
			</tr>
		</table>
	</div>
	<!-- end inline_slider -->
	
	
	<!-- slide -->
	<div class="slide" style="display: none;">
		<label for="slide_alt">Alt</label>
		<br>
		<input type="text" id="slide_alt" name="slide_alt" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="slide_title">Title</label>
		<br>
		<input type="text" id="slide_title" name="slide_title" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="slide_src">Src</label>
		<br>
		<input type="text" id="slide_src" name="slide_src" class="widefat" style="width: 50%;">
		<br>
		<br>
		<label for="slide_url">URL</label>
		<br>
		<input type="text" id="slide_url" name="slide_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end slide -->
	
	
	<!-- lightbox_audio -->
	<div class="lightbox_audio" style="display: none;">
		<label for="lightbox_audio_title">Title</label>
		<br>
		<input type="text" id="lightbox_audio_title" name="lightbox_audio_title" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="lightbox_audio_url">Url</label>
		<br>
		<input type="text" id="lightbox_audio_url" name="lightbox_audio_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end lightbox_audio -->
	
	
	<!-- lightbox_video -->
	<div class="lightbox_video" style="display: none;">
		<label for="lightbox_video_title">Title</label>
		<br>
		<input type="text" id="lightbox_video_title" name="lightbox_video_title" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="lightbox_video_url">Url</label>
		<br>
		<input type="text" id="lightbox_video_url" name="lightbox_video_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end lightbox_video -->
	
	
	<!-- lightbox_image -->
	<div class="lightbox_image" style="display: none;">
		<label for="lightbox_image_first_image">First Image</label>
		<br>
		<select id="lightbox_image_first_image" name="lightbox_image_first_image" class="widefat" style="width: 30%;">
			<option>yes</option>
			<option>no</option>
		</select>
		<br>
		<br>
		<label for="lightbox_image_title">Title</label>
		<br>
		<input type="text" id="lightbox_image_title" name="lightbox_image_title" class="widefat" style="width: 30%;">
		<br>
		<br>
		<label for="lightbox_image_url">Url</label>
		<br>
		<input type="text" id="lightbox_image_url" name="lightbox_image_url" class="widefat" style="width: 50%;">
		<br>
		<br>
	</div>
	<!-- end lightbox_image -->
	
	<!-- end shortcodes fields ====================================== -->
	
	<button type="button" class="button button-primary btn-sh-insert">Insert Shortcode</button>
</form>

<script>
	jQuery(document).ready(function($)
	{
		var sh_selected = 'row';
		
		$('.sh-list').change( function()
		{
			$('.sh-wrap div').hide();
			
			sh_selected = $('.sh-list').val();
			
			$('.' + sh_selected).show();
		});
		
		
		// insert button
		$('.btn-sh-insert').click(function()
		{
			// sh_out
			var sh_out = "";
			
			if ( sh_selected == 'row' )
			{
				sh_out = '[row][/row]';
			}
			else if ( sh_selected == 'column' )
			{
				var column_width = $( '#column_width' ).val();
				var column_offset = $( '#column_offset' ).val();
				var column_content = $( '#column_content' ).val();
				
				sh_out = '[column width="' + column_width + '" offset="' + column_offset + '"]' + column_content + '[/column]';
			}
			else if ( sh_selected == 'tabs' )
			{
				sh_out = '[tabs][/tabs]';
			}
			else if ( sh_selected == 'tab_head' )
			{
				sh_out = '[tab_head][/tab_head]';
			}
			else if ( sh_selected == 'tab_title' )
			{
				var tab_title_active = $( '#tab_title_active' ).val();
				var tab_title_title = $( '#tab_title_title' ).val();
				
				sh_out = '[tab_title active="' + tab_title_active + '" title="' + tab_title_title + '"]';
			}
			else if ( sh_selected == 'tab_content' )
			{
				sh_out = '[tab_content][/tab_content]';
			}
			else if ( sh_selected == 'tab_pane' )
			{
				var tab_pane_content = $( '#tab_pane_content' ).val();
				
				sh_out = '[tab_pane]' + tab_pane_content + '[/tab_pane]';
			}
			else if ( sh_selected == 'accordions' )
			{
				sh_out = '[accordions][/accordions]';
			}
			else if ( sh_selected == 'accordion' )
			{
				var accordion_active = $( '#accordion_active' ).val();
				var accordion_title = $( '#accordion_title' ).val();
				var accordion_content = $( '#accordion_content' ).val();
				
				sh_out = '[accordion active="' + accordion_active + '" title="' + accordion_title + '"]' + accordion_content + '[/accordion]';
			}
			else if ( sh_selected == 'toggles' )
			{
				sh_out = '[toggles][/toggles]';
			}
			else if ( sh_selected == 'toggle' )
			{
				var toggle_active = $( '#toggle_active' ).val();
				var toggle_title = $( '#toggle_title' ).val();
				var toggle_content = $( '#toggle_content' ).val();
				
				sh_out = '[toggle active="' + toggle_active + '" title="' + toggle_title + '"]' + toggle_content + '[/toggle]';
			}
			else if ( sh_selected == 'tagline' )
			{
				var tagline_content = $( '#tagline_content' ).val();
				
				sh_out = '[tagline]' + tagline_content + '[/tagline]';
			}
			else if ( sh_selected == 'intro' )
			{
				var intro_content = $( '#intro_content' ).val();
				
				sh_out = '[intro]' + intro_content + '[/intro]';
			}
			else if ( sh_selected == 'label' )
			{
				var label_content = $( '#label_content' ).val();
				
				sh_out = '[label]' + label_content + '[/label]';
			}
			else if ( sh_selected == 'inline_media' )
			{
				var inline_media_content = $( '#inline_media_content' ).val();
				
				sh_out = '[inline_media]' + inline_media_content + '[/inline_media]';
			}
			else if ( sh_selected == 'call_to_action' )
			{
				var call_to_action_content = $( '#call_to_action_content' ).val();
				
				sh_out = '[call_to_action]' + call_to_action_content + '[/call_to_action]';
			}
			else if ( sh_selected == 'cta_button_wrap' )
			{
				sh_out = '[cta_button_wrap][/cta_button_wrap]';
			}
			else if ( sh_selected == 'lead_p' )
			{
				var lead_p_content = $( '#lead_p_content' ).val();
				
				sh_out = '[lead_p]' + lead_p_content + '[/lead_p]';
			}
			else if ( sh_selected == 'portfolio_text' )
			{
				var portfolio_text_content = $( '#portfolio_text_content' ).val();
				
				sh_out = '[portfolio_text]' + portfolio_text_content + '[/portfolio_text]';
			}
			else if ( sh_selected == 'launch_button' )
			{
				var launch_button_text = $( '#launch_button_text' ).val();
				var launch_button_url = $( '#launch_button_url' ).val();
				
				sh_out = '[launch_button text="' + launch_button_text + '" url="' + launch_button_url + '"]';
			}
			else if ( sh_selected == 'project_action' )
			{
				var project_action_content = $( '#project_action_content' ).val();
				
				sh_out = '[project_action]' + project_action_content + '[/project_action]';
			}
			else if ( sh_selected == 'view_button' )
			{
				var view_button_text = $( '#view_button_text' ).val();
				var view_button_url = $( '#view_button_url' ).val();
				
				sh_out = '[view_button text="' + view_button_text + '" url="' + view_button_url + '"]';
			}
			else if ( sh_selected == 'syntax_prettify' )
			{
				var syntax_prettify_linenums = $( '#syntax_prettify_linenums' ).val();
				var syntax_prettify_content = $( '#syntax_prettify_content' ).val();
				
				sh_out = '[syntax_prettify linenums="' + syntax_prettify_linenums + '"]' + syntax_prettify_content + '[/syntax_prettify]';
			}
			else if ( sh_selected == 'alert' )
			{
				var alert_type = $( '#alert_type' ).val();
				var alert_content = $( '#alert_content' ).val();
				
				sh_out = '[alert type="' + alert_type + '"]' + alert_content + '[/alert]';
			}
			else if ( sh_selected == 'lists' )
			{
				var lists_type = $( '#lists_type' ).val();
				
				sh_out = '[lists type="' + lists_type + '"][/lists]';
			}
			else if ( sh_selected == 'list_item' )
			{
				var list_item_content = $( '#list_item_content' ).val();
				
				sh_out = '[list_item]' + list_item_content + '[/list_item]';
			}
			else if ( sh_selected == 'social_icons' )
			{
				sh_out = '[social_icons][/social_icons]';
			}
			else if ( sh_selected == 'social_icon' )
			{
				var social_icon_type = $( '#social_icon_type' ).val();
				var social_icon_title = $( '#social_icon_title' ).val();
				var social_icon_url = $( '#social_icon_url' ).val();
				
				sh_out = '[social_icon type="' + social_icon_type + '" title="' + social_icon_title + '" url="' + social_icon_url + '"]';
			}
			else if ( sh_selected == 'my_button' )
			{
				var my_button_color = $( '#my_button_color' ).val();
				var my_button_size = $( '#my_button_size' ).val();
				var my_button_type = $( '#my_button_type' ).val();
				var my_button_icon = $( '#my_button_icon' ).val();
				var my_button_target = $( '#my_button_target' ).val();
				var my_button_url = $( '#my_button_url' ).val();
				var my_button_text = $( '#my_button_text' ).val();
				
				sh_out = '[button color="' + my_button_color + '" size="' + my_button_size + '" type="' + my_button_type + '" icon="' + my_button_icon + '" target="' + my_button_target + '" url="' + my_button_url + '"]' + my_button_text + '[/button]';
			}
			else if ( sh_selected == 'inline_slider' )
			{
				var inline_slider_autoplay = $( '#inline_slider_autoplay' ).val();
				var inline_slider_interval = $( '#inline_slider_interval' ).val();
				var inline_slider_animation = $( '#inline_slider_animation' ).val();
				var inline_slider_direction = $( '#inline_slider_direction' ).val();
				var inline_slider_animationspeed = $( '#inline_slider_animationspeed' ).val();
				var inline_slider_pauseonhover = $( '#inline_slider_pauseonhover' ).val();
				
				sh_out = '[inline_slider autoplay="' + inline_slider_autoplay + '" interval="' + inline_slider_interval + '" animation="' + inline_slider_animation + '" direction="' + inline_slider_direction + '" animationspeed="' + inline_slider_animationspeed + '" pauseonhover="' + inline_slider_pauseonhover + '"][/inline_slider]';
			}
			else if ( sh_selected == 'slide' )
			{
				var slide_alt = $( '#slide_alt' ).val();
				var slide_src = $( '#slide_src' ).val();
				var slide_url = $( '#slide_url' ).val();
				var slide_title = $( '#slide_title' ).val();
				
				sh_out = '[slide alt="' + slide_alt + '" src="' + slide_src + '" url="' + slide_url + '"]' + slide_title + '[/slide]';
			}
			else if ( sh_selected == 'divider' )
			{
				sh_out = '[divider]';
			}
			else if ( sh_selected == 'lightbox_audio' )
			{
				var lightbox_audio_title = $( '#lightbox_audio_title' ).val();
				var lightbox_audio_url = $( '#lightbox_audio_url' ).val();
				
				sh_out = '[lightbox_audio title="' + lightbox_audio_title + '" url="' + lightbox_audio_url + '"]';
			}
			else if ( sh_selected == 'lightbox_video' )
			{
				var lightbox_video_title = $( '#lightbox_video_title' ).val();
				var lightbox_video_url = $( '#lightbox_video_url' ).val();
				
				sh_out = '[lightbox_video title="' + lightbox_video_title + '" url="' + lightbox_video_url + '"]';
			}
			else if ( sh_selected == 'lightbox_image' )
			{
				var lightbox_image_title = $( '#lightbox_image_title' ).val();
				var lightbox_image_url = $( '#lightbox_image_url' ).val();
				var lightbox_image_first_image = $( '#lightbox_image_first_image' ).val();
				
				sh_out = '[lightbox_image first_image="' + lightbox_image_first_image + '" title="' + lightbox_image_title + '" url="' + lightbox_image_url + '"]';
			}
			// end sh_out
			
			
			// add to editor
			if ( window.tinyMCE )
			{
				var tmce_ver = window.tinyMCE.majorVersion;
				
				if ( tmce_ver < "4" )
				{
					window.tinyMCE.execInstanceCommand( 'content', 'mceInsertContent', false, sh_out );
				}
				else
				{
					parent.tinyMCE.execCommand( 'mceInsertContent', false, sh_out );
				}
				// end if
				
				tb_remove();
			}
			// end add to editor
		
		});
		// end insert button
	});
</script>