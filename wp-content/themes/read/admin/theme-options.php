<?php

	function create_tabs($current = 'general')
	{
		$tabs = array(
			'general'      => 'General',
			'style'        => 'Style',
			'blog'         => 'Blog',
			'sidebar'      => 'Sidebar',
			'portfolio'    => 'Portfolio',
			'contact'      => 'Contact',
			'widget-areas' => 'Widget Areas'
		);
		
		?>
			<h1>Theme Options</h1>
			
			<h2 class="nav-tab-wrapper">
				<?php
					foreach ($tabs as $tab => $name)
					{
						$class = ($tab == $current) ? ' nav-tab-active' : "";
						
						echo "<a class='nav-tab$class' href='?page=theme-options&tab=$tab'>$name</a>";
					}
				?>
			</h2>
		<?php
	}


/* ============================================================================================================================================ */


	function theme_options_page()
	{
		global $pagenow;
		
		?>
			<div class="wrap wrap2">
				<script src="<?php echo get_template_directory_uri(); ?>/admin/colorpicker/colorpicker.js"></script>
				
				<div class="status">
					<img height="20" width="20" alt="..." src="<?php echo get_template_directory_uri(); ?>/admin/ajax-loader.gif">
					
					<strong></strong>
				</div>
				
				<script>
					jQuery(document).ready(function($) {
						
						var uploadID = '',
							uploadImg = '';
						
						jQuery('.upload-button').click(function() {
							
							uploadID = jQuery(this).prev('input');
							uploadImg = jQuery(this).next('img');
							formfield = jQuery('.upload').attr('name');
							tb_show("", 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
							return false;
						});
						
						
						window.send_to_editor = function(html) {
							
							imgurl = jQuery('img', html).attr('src');
							uploadID.val(imgurl);
							uploadImg.attr('src', imgurl);
							tb_remove();
						}
						
						
						// -------------------------------------------------------------------------
						
						
						$(".alert-success p").click(function() {
							
							$(this).fadeOut("slow", function()
							{
								$(".alert-success").slideUp("slow");
							});
						});
						
						
						// -------------------------------------------------------------------------
						
						
						$('.color-selector').each(function() {
							
							var cp = $(this);
							
							cp.ColorPicker({
								
								color: '#ffffff',
								
								onBeforeShow: function() {
									
									var myColor = $(this).next('input').val();
									
									if (myColor != "")
									{
										$(this).ColorPickerSetColor( myColor );
										// cp.find('div').css('backgroundColor', '#' + myColor);
									}
								},
								onChange: function (hsb, hex, rgb) {
									
									cp.find( 'div' ).css( 'backgroundColor', '#' + hex );
									cp.next( 'input' ).val( hex );
								},
								onSubmit: function(hsb, hex, rgb, el) {
									
									$( el ).val( hex );
									$( el ).ColorPickerHide();
								}
							});
						});
						
						
						$( '.color' ).change( function() {
							
							var myColor = $( this ).val();
							
							$( this ).prev( 'div' ).find( 'div' ).css( 'backgroundColor', '#' + myColor );
						});
						
						
						$( '.color' ).keypress( function() {
							
							var myColor = $( this ).val();
							
							$( this ).prev( 'div' ).find( 'div' ).css( 'backgroundColor', '#' + myColor );
						});
						
						
						// -------------------------------------------------------------------------
						
						
						$( 'form.ajax-form' ).submit(function() {
							
							$.ajax(
							{
								data : $(this).serialize(),
								type: "POST",
								beforeSend: function()
								{
									$('.status img').show();
									$('.status strong').html('Saving...');
									$('.status').fadeIn();
								},
								success: function(data)
								{
									$('.status img').hide();
									$('.status strong').html('Done');
									$('.status').delay(1000).fadeOut();
								}
							});
							
							return false;
						});
						
						
						// -------------------------------------------------------------------------
						
						
						/*
						var calcHeight = function() {
							
							$( "#preview-frame" ).height($(window).height() - 100);
						}
						
						
						$(document).ready(function() {
							
							calcHeight();
						});
						
						
						$(window).resize(function() {
							
							calcHeight();
							
						}).load(function() {
							
							calcHeight();
						});
						*/
					});
				</script>
				
				<?php
					if (isset($_GET['tab']))
					{
						create_tabs($_GET['tab']);
					}	
					else
					{
						create_tabs('general');
					}
				?>
				
				<div id="poststuff">
					<?php
						if ($pagenow == 'themes.php' && $_GET['page'] == 'theme-options')
						{
							if (isset($_GET['tab']))
							{
								$tab = $_GET['tab'];
							}
							else
							{
								$tab = 'general'; 
							}
							
							switch ($tab)
							{
								case 'general':
									
									if (esc_attr(@$_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form method="post" class="ajax-form" action="<?php echo admin_url('themes.php?page=theme-options'); ?>">
													<?php
														wp_nonce_field("settings-page");
													?>
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Logo Type</h4>
																<?php
																	$logo_type = get_option('logo_type', 'Text Logo');
																?>
																<select id="logo_type" name="logo_type" style="width: 100%;">
																	<option <?php if ($logo_type == 'Text Logo')  { echo 'selected="selected"'; } ?>>Text Logo</option>
																	<option <?php if ($logo_type == 'Image Logo') { echo 'selected="selected"'; } ?>>Image Logo</option>
																</select>
															</td>
															<td class="option-right">
																Select logo type.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Image Logo</h4>
																<?php
																	$logo_image = get_option('logo_image');
																?>
																<input type="text" id="logo_image" name="logo_image" class="upload code2" style="width: 100%;" value="<?php echo $logo_image; ?>">
																<input type="button" class="button upload-button" style="margin-top: 10px;" value="Browse">
																<img style="display: block; margin-top: 10px; max-height: 50px;" alt="" src="<?php echo $logo_image; ?>">
															</td>
															<td class="option-right">
																Upload an image file or choose from your media library.
																<ul style="list-style: disc; margin-left: 25px;">
																	<li>Click on the "Browse" button.</li>
																	<li>Upload your image.</li>
																	<li>Select "Link URL > File URL".</li>
																	<li>Select "Size > Large".</li>
																	<li>Use "Insert into" button to add your image.</li>
																</ul>
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Text Logo</h4>
																<?php
																	$select_text_logo = get_option('select_text_logo', 'WordPress Site Title');
																?>
																<select id="select_text_logo" name="select_text_logo" style="width: 100%;">
																	<option <?php if ($select_text_logo == 'WordPress Site Title') { echo 'selected="selected"'; } ?>>WordPress Site Title</option>
																	<option <?php if ($select_text_logo == 'Theme Site Title')     { echo 'selected="selected"'; } ?>>Theme Site Title</option>
																</select>
																
																<h4>Theme Site Title</h4>
																<?php
																	$theme_site_title = stripcslashes(get_option('theme_site_title', ""));
																?>
																<input type="text" id="theme_site_title" name="theme_site_title" class="upload code2" style="width: 100%;" value="<?php echo $theme_site_title; ?>">
															</td>
															<td class="option-right">
																Site title.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Tagline</h4>
																<?php
																	$select_tagline = get_option('select_tagline', 'WordPress Tagline');
																?>
																<select id="select_tagline" name="select_tagline" style="width: 100%;">
																	<option <?php if ($select_tagline == 'WordPress Tagline') { echo 'selected="selected"'; } ?>>WordPress Tagline</option>
																	<option <?php if ($select_tagline == 'Theme Tagline')     { echo 'selected="selected"'; } ?>>Theme Tagline</option>
																</select>
																
																<h4>Theme Tagline</h4>
																<?php
																	$theme_tagline = stripcslashes(get_option('theme_tagline', ""));
																?>
																<textarea id="theme_tagline" name="theme_tagline" rows="3" cols="50"><?php echo $theme_tagline; ?></textarea>
															</td>
															<td class="option-right">
																In a few words, explain what this site is about.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Login Logo</h4>
																<?php
																	$logo_login = get_option('logo_login');
																?>
																<input type="text" id="logo_login" name="logo_login" class="upload code2" style="width: 100%;" value="<?php echo $logo_login; ?>">
																<input type="button" class="button upload-button" style="margin-top: 10px;" value="Browse">
																<img style="display: block; margin-top: 10px; max-height: 50px;" alt="" src="<?php echo $logo_login; ?>">
																
																<br>
																
																<?php
																	$logo_login_hide = get_option('logo_login_hide', false);
																?>
																<label><input type="checkbox" id="logo_login_hide" name="logo_login_hide" <?php if ($logo_login_hide) { echo 'checked="checked"'; } ?>> Hide login logo</label>
															</td>
															<td class="option-right">
																Upload an image file or choose from your media library.
																<ul style="list-style: disc; margin-left: 25px;">
																	<li>Click on the "Browse" button.</li>
																	<li>Upload your image.</li>
																	<li>Select "Link URL > File URL".</li>
																	<li>Select "Size > Medium".</li>
																	<li>Use "Insert into" button to add your image.</li>
																</ul>
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Copyright Text</h4>
																<?php
																	$copyright_text = stripcslashes(get_option('copyright_text'));
																?>
																<textarea id="copyright_text" name="copyright_text" rows="4" cols="50"><?php echo $copyright_text; ?></textarea>
															</td>
															<td class="option-right">
																Copyright text in your site's footer.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
									
								case 'style' :
									
									if (esc_attr(@$_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php echo admin_url('themes.php?page=theme-options'); ?>">
													<?php
														wp_nonce_field( "settings-page" );
													?>
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Fonts and Colors</h4>
																<?php
																	echo '<a href="' . admin_url('customize.php') . '">Customize</a>';
																?>
															</td>
															<td class="option-right">
																Select from theme customizer.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Character Sets</h4>
																<label><input type="checkbox" id="char_set_latin" name="char_set_latin" <?php if ( get_option( 'char_set_latin', true ) ) { echo 'checked="checked"'; } ?>> Latin</label>
																<br>
																<label><input type="checkbox" id="char_set_latin_ext" name="char_set_latin_ext" <?php if ( get_option( 'char_set_latin_ext' ) ) { echo 'checked="checked"'; } ?>> Latin Extended</label>
																<br>
																<label><input type="checkbox" id="char_set_cyrillic" name="char_set_cyrillic" <?php if ( get_option( 'char_set_cyrillic' ) ) { echo 'checked="checked"'; } ?>> Cyrillic</label>
																<br>
																<label><input type="checkbox" id="char_set_cyrillic_ext" name="char_set_cyrillic_ext" <?php if ( get_option( 'char_set_cyrillic_ext' ) ) { echo 'checked="checked"'; } ?>> Cyrillic Extended</label>
																<br>
																<label><input type="checkbox" id="char_set_greek" name="char_set_greek" <?php if ( get_option( 'char_set_greek' ) ) { echo 'checked="checked"'; } ?>> Greek</label>
																<br>
																<label><input type="checkbox" id="char_set_greek_ext" name="char_set_greek_ext" <?php if ( get_option( 'char_set_greek_ext' ) ) { echo 'checked="checked"'; } ?>> Greek Extended</label>
																<br>
																<label><input type="checkbox" id="char_set_vietnamese" name="char_set_vietnamese" <?php if ( get_option( 'char_set_vietnamese' ) ) { echo 'checked="checked"'; } ?>> Vietnamese</label>
															</td>
															<td class="option-right">
																Select any of them to include to the Google Fonts if the selected fonts have ones of them in their family. To see the supported character sets visit Google Fonts online.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Font Styles</h4>
																<?php
																	$extra_font_styles = get_option( 'extra_font_styles', 'No' );
																?>
																<select id="extra_font_styles" name="extra_font_styles" style="width: 100%;">
																	<option <?php if ( $extra_font_styles == 'No' )  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ( $extra_font_styles == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Include bold and italic styles.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Menu Search</h4>
																<?php
																	$nav_menu_search = get_option( 'nav_menu_search', 'No' );
																?>
																<select id="nav_menu_search" name="nav_menu_search" style="width: 100%;">
																	<option <?php if ( $nav_menu_search == 'No' )  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ( $nav_menu_search == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Add a search field in your site's main navigation menu.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Footer Widget Areas</h4>
																<?php
																	$footer_widget_locations = get_option( 'footer_widget_locations', 'No' );
																?>
																<select id="footer_widget_locations" name="footer_widget_locations" style="width: 100%;">
																	<option <?php if ( $footer_widget_locations == 'No' )  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ( $footer_widget_locations == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Enable widget areas in your site's footer.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Footer Widget Columns</h4>
																<?php
																	$footer_widget_columns = get_option( 'footer_widget_columns', '4 Columns' );
																?>
																<select id="footer_widget_columns" name="footer_widget_columns" style="width: 100%;">
																	<option <?php if ( $footer_widget_columns == '4 Columns' ) { echo 'selected="selected"'; } ?>>4 Columns</option>
																	<option <?php if ( $footer_widget_columns == '3 Columns' ) { echo 'selected="selected"'; } ?>>3 Columns</option>
																</select>
															</td>
															<td class="option-right">
																Number of columns in your site's footer.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Mobile Zoom</h4>
																<?php
																	$mobile_zoom = get_option( 'mobile_zoom', 'No' );
																?>
																<select id="mobile_zoom" name="mobile_zoom" style="width: 100%;">
																	<option <?php if ( $mobile_zoom == 'No' )  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ( $mobile_zoom == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Enable zoom in mobile devices.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Custom CSS (Deprecated)</h4>
																<?php
																	$custom_css = stripcslashes(get_option('custom_css', ""));
																?>
																<textarea id="custom_css" name="custom_css" class="code2" rows="8" cols="50"><?php echo $custom_css; ?></textarea>
																<small>This feature has been deprecated. Use "Additional CSS" instead.</small>
															</td>
															<td class="option-right">
																Use new WordPress core feature <a href="<?php echo admin_url('customize.php?autofocus%5Bsection%5D=custom_css'); ?>">Additional CSS</a> instead of this field.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>External CSS</h4>
																<?php
																	$external_css = stripcslashes( get_option( 'external_css', "" ) );
																?>
																<textarea id="external_css" name="external_css" class="code2" rows="4" cols="50"><?php echo $external_css; ?></textarea>
																<small>Sample:</small>
																<br>
																<code><small>&lt;link rel="stylesheet" type="text/css" href="your-style.css"&gt;</small></code>
															</td>
															<td class="option-right">
																Add your custom external CSS file.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>External JS</h4>
																<?php
																	$external_js = stripcslashes( get_option( 'external_js', "" ) );
																?>
																<textarea id="external_js" name="external_js" class="code2" rows="4" cols="50"><?php echo $external_js; ?></textarea>
																<small>Sample:</small>
																<br>
																<code><small>&lt;script src="your-script.js"&gt;&lt;/script&gt;</small></code>
															</td>
															<td class="option-right">
																Add your custom external JS file.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								
								break;
								
								case 'blog' :
									
									if (esc_attr(@$_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php echo admin_url('themes.php?page=theme-options'); ?>">
													<?php
														wp_nonce_field('settings-page');
													?>
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Blog Layout</h4>
																<?php
																	$blog_type = get_option('blog_type', 'Regular');
																?>
																<select name="blog_type" style="width: 100%;">
																	<option <?php if ($blog_type == 'Regular') { echo 'selected="selected"'; } ?>>Regular</option>
																	<option <?php if ($blog_type == 'Masonry') { echo 'selected="selected"'; } ?>>Masonry</option>
																</select>
															</td>
															<td class="option-right">
																Select layout for blog page.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Archive Layout</h4>
																<?php
																	$category_archive_type = get_option('category_archive_type', 'Masonry');
																?>
																<select name="category_archive_type" style="width: 100%;">
																	<option <?php if ($category_archive_type == 'Masonry') { echo 'selected="selected"'; } ?>>Masonry</option>
																	<option <?php if ($category_archive_type == 'Regular') { echo 'selected="selected"'; } ?>>Regular</option>
																</select>
															</td>
															<td class="option-right">
																Select layout for archive pages.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Automatic Excerpt</h4>
																<?php
																	$theme_excerpt = get_option('theme_excerpt', 'standard');
																?>
																<select name="theme_excerpt" style="width: 100%;">
																	<option <?php if ($theme_excerpt == 'standard') { echo 'selected="selected"'; } ?> value="standard">Yes - Only for standard format</option>
																	<option <?php if ($theme_excerpt == 'Yes')      { echo 'selected="selected"'; } ?> value="Yes">Yes - For all post formats</option>
																	<option <?php if ($theme_excerpt == 'No')       { echo 'selected="selected"'; } ?> value="No">No</option>
																</select>
															</td>
															<td class="option-right">
																Creates an excerpt using the first words of the post.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Blog Navigation</h4>
																<?php
																	$pagination = get_option('pagination', 'Yes');
																?>
																<select id="pagination" name="pagination" style="width: 100%;">
																	<option <?php if ($pagination == 'Yes') { echo 'selected="selected"'; } ?> value="Yes">Numbered Pagination</option>
																	<option <?php if ($pagination == 'No')  { echo 'selected="selected"'; } ?> value="No">Older/Newer Links</option>
																</select>
															</td>
															<td class="option-right">
																Posts navigation for blog page and archive pages.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Author Info Box</h4>
																<?php
																	$about_the_author_module = get_option('about_the_author_module', 'Yes');
																?>
																<select id="about_the_author_module" name="about_the_author_module" style="width: 100%;">
																	<option <?php if ($about_the_author_module == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ($about_the_author_module == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															<td class="option-right">
																About post author module under post content.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Share Links</h4>
																<?php
																	$post_share_links_single = get_option('post_share_links_single', 'Yes');
																?>
																<select name="post_share_links_single" style="width: 100%;">
																	<option <?php if ($post_share_links_single == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ($post_share_links_single == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															<td class="option-right">
																Activate or deactivate share links.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>All Post Formats on Homepage</h4>
																<?php
																	$all_formats_homepage = get_option('all_formats_homepage', 'No');
																?>
																<select id="all_formats_homepage" name="all_formats_homepage" style="width: 100%;">
																	<option <?php if ($all_formats_homepage == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ($all_formats_homepage == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Show all post formats or just standard.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
								
								case 'sidebar' :
									
									if (esc_attr( @$_GET['saved'] ) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php echo admin_url('themes.php?page=theme-options'); ?>">
													<?php
														wp_nonce_field('settings-page');
													?>
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Blog Sidebar</h4>
																<?php
																	$blog_sidebar = get_option('blog_sidebar', 'Yes');
																?>
																<select id="blog_sidebar" name="blog_sidebar" style="width: 100%;">
																	<option <?php if ($blog_sidebar == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ($blog_sidebar == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															<td class="option-right">
																Activate or deactivate sidebar for blog page and archive pages.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Post Sidebar</h4>
																<?php
																	$post_sidebar = get_option('post_sidebar', 'Yes');
																?>
																<select id="post_sidebar" name="post_sidebar" style="width: 100%;">
																	<option <?php if ($post_sidebar == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ($post_sidebar == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															<td class="option-right">
																Activate or deactivate sidebar for blog posts. This setting may be overridden in post edit screen for individual posts.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Page Sidebar</h4>
																<?php
																	$page_sidebar = get_option('page_sidebar', 'No');
																?>
																<select id="page_sidebar" name="page_sidebar" style="width: 100%;">
																	<option <?php if ($page_sidebar == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ($page_sidebar == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Activate or deactivate sidebar for pages. This setting may be overridden in page edit screen for individual pages.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Sidebar Width</h4>
																<?php
																	$read__sidebar_width = get_option('read__sidebar_width', 'default');
																?>
																<select id="read__sidebar_width" name="read__sidebar_width" style="width: 100%;">
																	<option <?php if ($read__sidebar_width == 'default')      { echo 'selected="selected"'; } ?> value="default">Default</option>
																	<option <?php if ($read__sidebar_width == 'narrow')       { echo 'selected="selected"'; } ?> value="narrow">Narrow</option>
																	<option <?php if ($read__sidebar_width == 'extra_narrow') { echo 'selected="selected"'; } ?> value="extra_narrow">Extra Narrow</option>
																</select>
															</td>
															<td class="option-right">
																Select sidebar width.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
								
								case 'portfolio':
									
									if (esc_attr(@$_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php echo admin_url('themes.php?page=theme-options'); ?>">
													<?php
														wp_nonce_field('settings-page');
													?>
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Page Content Location</h4>
																<?php
																	$pf_content_editor = get_option('pf_content_editor', 'Bottom');
																?>
																<select id="pf_content_editor" name="pf_content_editor" style="width: 100%;">
																	<option <?php if ($pf_content_editor == 'Bottom') { echo 'selected="selected"'; } ?>>Bottom</option>
																	<option <?php if ($pf_content_editor == 'Top')    { echo 'selected="selected"'; } ?>>Top</option>
																</select>
															</td>
															<td class="option-right">
																Select display location for portfolio page content.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Share Links</h4>
																<?php
																	$pf_share_links_single = get_option('pf_share_links_single', 'Yes');
																?>
																<select id="pf_share_links_single" name="pf_share_links_single" style="width: 100%;">
																	<option <?php if ($pf_share_links_single == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ($pf_share_links_single == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															<td class="option-right">
																Activate or deactivate share links.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
								
								case 'widget-areas' :
								
									if (esc_attr(@$_GET['saved']) == 'true')
									{
										$no_sidebar_name = get_option('no_sidebar_name');
										
										if ($no_sidebar_name == "")
										{
											echo '<div class="alert-success" title="Click to close"><p><strong>Enter a text for new sidebar name.</strong></p></div>';
										}
										else
										{
											echo '<div class="alert-success" title="Click to close"><p><strong>Created.</strong></p></div>';
										}
									}
									elseif (esc_attr(@$_GET['deleted']) == 'true')
									{
										delete_option('sidebars_with_commas');
										
										echo '<div class="alert-success" title="Click to close"><p><strong>Deleted.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<?php
													$wp_admin_url = admin_url('themes.php?page=theme-options&tab=widget-areas');
												?>
												
												<form method="post" action="<?php echo $wp_admin_url; ?>">
													<?php
														wp_nonce_field("settings-page");
													?>
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Widget Area</h4>
																
																<input type="text" id="new_sidebar_name" name="new_sidebar_name" required="required" style="width: 100%;" value="">
																<small>Widget area names can have only alphanumeric characters, underscores and hyphens.</small>
																
																<input type="submit" name="submit" class="button button-primary button-large" value="Create Widget Area">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															<td class="option-right">
																Enter text for a new widget area name.
																<br>
																New widget area name must be different from existing widget area names.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Widget Areas</h4>
																
																<select id="sidebars" name="sidebars" style="width: 100%;" size="10" disabled="disabled">
																	<?php
																		$sidebars_with_commas = get_option('sidebars_with_commas');
																		
																		if ($sidebars_with_commas != "")
																		{
																			$sidebars = preg_split("/[\s]*[,][\s]*/", $sidebars_with_commas);

																			foreach ($sidebars as $sidebar_name)
																			{
																				echo '<option>' . $sidebar_name . '</option>';
																			}
																		}
																	?>
																</select>
																
																<?php
																	$wp_admin_url = admin_url('themes.php?page=theme-options&tab=widget-areas&deleted=true');
																?>
																<a href="<?php echo $wp_admin_url; ?>" class="button button-large" style="margin-top: 20px;">Delete Widget Areas</a>
															</td>
															<td class="option-right">
																You can use new widget areas in your <a href="<?php echo admin_url('widgets.php'); ?>">Widgets</a> page.
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
								
								case 'contact':
									
									if (esc_attr(@$_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php echo admin_url('themes.php?page=theme-options'); ?>">
													<?php
														wp_nonce_field("settings-page");
													?>
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Contact Form Email Address</h4>
																<?php
																	$contact_form_email = stripcslashes(get_option('contact_form_email', ""));
																?>
																<input type="text" id="contact_form_email" name="contact_form_email" style="width: 100%;" value="<?php echo $contact_form_email; ?>">
															</td>
															<td class="option-right">
																Enter which email address will be sent from contact form.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Disable Contact Form</h4>
																<?php
																	$disable_contact_form = get_option('disable_contact_form', 'No');
																?>
																<select id="disable_contact_form" name="disable_contact_form" style="width: 100%;">
																	<option <?php if ($disable_contact_form == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ($disable_contact_form == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Activate or deactivate contact form.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Captcha</h4>
																<?php
																	$contact_form_captcha = get_option('contact_form_captcha', 'No');
																?>
																<select id="contact_form_captcha" name="contact_form_captcha" style="width: 100%;">
																	<option <?php if ($contact_form_captcha == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ($contact_form_captcha == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
															</td>
															<td class="option-right">
																Activate or deactivate captcha module.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Show Map</h4>
																<?php
																	$enable_map = stripcslashes(get_option('enable_map', 'No'));
																?>
																<select id="enable_map" name="enable_map" style="width: 100%;">
																	<option <?php if ($enable_map == 'No')  { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ($enable_map == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																</select>
																
																<h4>Map Embed Code</h4>
																<?php
																	$map_embed_code = stripcslashes(get_option('map_embed_code', ""));
																?>
																<textarea id="map_embed_code" name="map_embed_code" class="code2" rows="6" cols="50"><?php echo $map_embed_code; ?></textarea>
															</td>
															<td class="option-right">
																Use an ifarme code.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
							}
						}
					?>
				</div>
			</div>
		<?php
	}


/* ============================================================================================================================================ */


	function theme_save_settings()
	{
		global $pagenow;
		
		if ($pagenow == 'themes.php' && $_GET['page'] == 'theme-options')
		{
			if (isset($_GET['tab']))
			{
				$tab = $_GET['tab'];
			}
			else
			{
				$tab = 'general';
			}
			
			switch ($tab)
			{
				case 'general' :
					
					update_option('logo_type',        $_POST['logo_type']);
					update_option('select_text_logo', $_POST['select_text_logo']);
					update_option('theme_site_title', $_POST['theme_site_title']);
					update_option('logo_image',       $_POST['logo_image']);
					update_option('select_tagline',   $_POST['select_tagline']);
					update_option('theme_tagline',    $_POST['theme_tagline']);
					update_option('logo_login',       $_POST['logo_login']);
					update_option('logo_login_hide',  $_POST['logo_login_hide']);
					update_option('copyright_text',   $_POST['copyright_text']);
					break;
				
				case 'style' :
					
					update_option('char_set_latin',          $_POST['char_set_latin']);
					update_option('char_set_latin_ext',      $_POST['char_set_latin_ext']);
					update_option('char_set_cyrillic',       $_POST['char_set_cyrillic']);
					update_option('char_set_cyrillic_ext',   $_POST['char_set_cyrillic_ext']);
					update_option('char_set_greek',          $_POST['char_set_greek']);
					update_option('char_set_greek_ext',      $_POST['char_set_greek_ext']);
					update_option('char_set_vietnamese',     $_POST['char_set_vietnamese']);
					update_option('nav_menu_search',         $_POST['nav_menu_search']);
					update_option('extra_font_styles',       $_POST['extra_font_styles']);
					update_option('footer_widget_locations', $_POST['footer_widget_locations']);
					update_option('footer_widget_columns',   $_POST['footer_widget_columns']);
					update_option('mobile_zoom',             $_POST['mobile_zoom']);
					update_option('custom_css',              $_POST['custom_css']);
					update_option('external_css',            $_POST['external_css']);
					update_option('external_js',             $_POST['external_js']);
					break;
				
				case 'blog' :
					
					update_option('blog_type',               $_POST['blog_type']);
					update_option('category_archive_type',   $_POST['category_archive_type']);
					update_option('theme_excerpt',           $_POST['theme_excerpt']);
					update_option('pagination',              $_POST['pagination']);
					update_option('all_formats_homepage',    $_POST['all_formats_homepage']);
					update_option('about_the_author_module', $_POST['about_the_author_module']);
					update_option('post_share_links_single', $_POST['post_share_links_single']);
					break;
				
				case 'sidebar' :
					
					update_option('blog_sidebar',        $_POST['blog_sidebar']);
					update_option('post_sidebar',        $_POST['post_sidebar']);
					update_option('page_sidebar',        $_POST['page_sidebar']);
					update_option('read__sidebar_width', $_POST['read__sidebar_width']);
					break;
				
				case 'portfolio' :
				
					update_option('pf_content_editor',     $_POST['pf_content_editor']);
					update_option('pf_share_links_single', $_POST['pf_share_links_single']);
					break;
				
				case 'contact' :
					
					update_option('map_embed_code',       $_POST['map_embed_code']);
					update_option('enable_map',           $_POST['enable_map']);
					update_option('contact_form_email',   $_POST['contact_form_email']);
					update_option('contact_form_captcha', $_POST['contact_form_captcha']);
					update_option('disable_contact_form', $_POST['disable_contact_form']);
					break;
				
				case 'widget-areas' :
					
					update_option('no_sidebar_name', esc_attr($_POST['new_sidebar_name']));
					
					if (esc_attr($_POST['new_sidebar_name']) != "")
					{
						$sidebars_with_commas = get_option('sidebars_with_commas', "");
						
						if ($sidebars_with_commas == "")
						{
							update_option('sidebars_with_commas', esc_attr($_POST['new_sidebar_name']));
						}
						else
						{
							update_option('sidebars_with_commas', get_option('sidebars_with_commas') . ',' . esc_attr($_POST['new_sidebar_name']));
						}
					}
					
					break;
			}
		}
	}


/* ============================================================================================================================================ */


	function load_settings_page()
	{
		if (isset($_POST["settings-submit"]) == 'Y')
		{
			check_admin_referer("settings-page");
			theme_save_settings();
			$url_parameters = isset($_GET['tab']) ? 'tab=' . $_GET['tab'] . '&saved=true' : 'saved=true';
			wp_redirect(admin_url('themes.php?page=theme-options&' . $url_parameters));
			exit;
		}
	}


/* ============================================================================================================================================ */


	function my_theme_menu()
	{
		$settings_page = add_theme_page(
			'Theme Options',
			'Theme Options',
			'edit_theme_options',
			'theme-options',
			'theme_options_page'
		);
		
		add_action("load-{$settings_page}", 'load_settings_page');
	}
	
	add_action('admin_menu', 'my_theme_menu');
