<?php

	function read__login_enqueue_scripts()
	{
		wp_enqueue_script('jquery');
	}
	
	add_action('login_enqueue_scripts', 'read__login_enqueue_scripts');


/* ============================================================================================================================================= */


	function read__admin_enqueue_scripts()
	{
		wp_enqueue_style('adminstyle',  get_template_directory_uri() . '/admin/css/admin.css');
		wp_enqueue_style('colorpicker', get_template_directory_uri() . '/admin/colorpicker/colorpicker.css');
		wp_enqueue_style('thickbox');
		
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
	}
	
	add_action('admin_enqueue_scripts', 'read__admin_enqueue_scripts');


/* ============================================================================================================================================= */


	function read__enqueue_scripts()
	{
		$extra_char_set = false;
		global $subset;
		$subset = '&subset=';
		
		if (get_option('char_set_latin',        false)) { $subset .= 'latin,';        $extra_char_set = true; }
		if (get_option('char_set_latin_ext',    false)) { $subset .= 'latin-ext,';    $extra_char_set = true; }
		if (get_option('char_set_cyrillic',     false)) { $subset .= 'cyrillic,';     $extra_char_set = true; }
		if (get_option('char_set_cyrillic_ext', false)) { $subset .= 'cyrillic-ext,'; $extra_char_set = true; }
		if (get_option('char_set_greek',        false)) { $subset .= 'greek,';        $extra_char_set = true; }
		if (get_option('char_set_greek_ext',    false)) { $subset .= 'greek-ext,';    $extra_char_set = true; }
		if (get_option('char_set_vietnamese',   false)) { $subset .= 'vietnamese,';   $extra_char_set = true; }
		
		if ($extra_char_set == false) { $subset  = ""; } else { $subset = substr($subset, 0, -1); }
		
		
		// Enqueue style
		wp_enqueue_style('unifrakturmaguntia',   '//fonts.googleapis.com/css?family=UnifrakturMaguntia' . $subset);
		wp_enqueue_style('coustard',             '//fonts.googleapis.com/css?family=Coustard' . $subset);
		wp_enqueue_style('lora',                 '//fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' . $subset);
		wp_enqueue_style('print',                get_template_directory_uri() . '/css/print.css', null, null, 'print');
		wp_enqueue_style('grid',                 get_template_directory_uri() . '/css/grid.css');
		wp_enqueue_style('normalize',            get_template_directory_uri() . '/css/normalize.css');
		wp_enqueue_style('font-awesome',         get_template_directory_uri() . '/css/font-awesome.css');
		wp_enqueue_style('google-code-prettify', get_template_directory_uri() . '/js/google-code-prettify/prettify.css');
		wp_enqueue_style('uniform',              get_template_directory_uri() . '/css/uniform.default.css');
		wp_enqueue_style('flexslider',           get_template_directory_uri() . '/css/flexslider.css');
		wp_enqueue_style('gamma-gallery',        get_template_directory_uri() . '/css/gamma-gallery.css');
		wp_enqueue_style('main',                 get_template_directory_uri() . '/css/main.css');
		wp_enqueue_style('fancybox',             get_template_directory_uri() . '/css/jquery.fancybox-1.3.4.css');
		wp_enqueue_style('wp-fix',               get_template_directory_uri() . '/css/wp-fix.css');
		wp_enqueue_style('read-style',           get_stylesheet_uri());
		
		
		// Enqueue script
		if (is_singular() && comments_open() && get_option('thread_comments'))
		{
			wp_enqueue_script('comment-reply');
		}
		
		wp_enqueue_script('jquery' );
		wp_enqueue_script('detectmobilebrowser',  get_template_directory_uri() . '/js/detectmobilebrowser.js',           null, null, true);
		wp_enqueue_script('modernizr',            get_template_directory_uri() . '/js/modernizr.js',                     null, null, true);
		wp_enqueue_script('imagesloaded',         get_template_directory_uri() . '/js/jquery.imagesloaded.min.js',       null, null, true);
		wp_enqueue_script('fitvids',              get_template_directory_uri() . '/js/jquery.fitvids.js',                null, null, true);
		wp_enqueue_script('google-code-prettify', get_template_directory_uri() . '/js/google-code-prettify/prettify.js', null, null, true);
		wp_enqueue_script('uniform',              get_template_directory_uri() . '/js/jquery.uniform.min.js',            null, null, true);
		wp_enqueue_script('flexslider',           get_template_directory_uri() . '/js/jquery.flexslider-min.js',         null, null, true);
		wp_enqueue_script('isotope',              get_template_directory_uri() . '/js/jquery.isotope.min.js',            null, null, true);
		wp_enqueue_script('fancybox',             get_template_directory_uri() . '/js/jquery.fancybox-1.3.4.pack.js',    null, null, true);
		wp_enqueue_script('jquery-masonry',       null,                                                                  null, null, true);
		wp_enqueue_script('history',              get_template_directory_uri() . '/js/jquery.history.js',                null, null, true);
		wp_enqueue_script('js-url',               get_template_directory_uri() . '/js/js-url.min.js',                    null, null, true);
		wp_enqueue_script('jquerypp-custom',      get_template_directory_uri() . '/js/jquerypp.custom.js',               null, null, true);
		wp_enqueue_script('gamma',                get_template_directory_uri() . '/js/gamma.js',                         null, null, true);
		wp_enqueue_script('main',                 get_template_directory_uri() . '/js/main.js',                          null, null, true);
		wp_enqueue_script('validate',             get_template_directory_uri() . '/js/jquery.validate.min.js',           null, null, true);
		wp_enqueue_script('send-mail',            get_template_directory_uri() . '/js/send-mail.js',                     null, null, true);
	}


/* ============================================================================================================================================= */


	function read__after_setup_theme()
	{
		load_theme_textdomain(
			'read',
			get_template_directory() . '/languages'
		);
		
		register_nav_menus(
			array(
				'top_menu' => __('Navigation Menu', 'read')
			)
		);
		
		add_theme_support('title-tag');
		add_theme_support('automatic-feed-links');
		add_theme_support('post-formats', array('image', 'gallery', 'audio', 'video', 'quote', 'link', 'chat', 'status', 'aside'));
		add_theme_support('post-thumbnails', array('post', 'portfolio'));
		
		add_editor_style('custom-editor-style.css');
		
		add_action('wp_enqueue_scripts', 'read__enqueue_scripts');
		
		remove_theme_support('widgets-block-editor');
	}
	
	add_action('after_setup_theme', 'read__after_setup_theme');


/* ============================================================================================================================================= */


	include_once(get_template_directory() . '/admin/admin--meta-box--title-visibility.php');
	include_once(get_template_directory() . '/admin/admin--meta-box--sidebar.php');
	
	include_once(get_template_directory() . '/admin/widget_area-register.php');
	include_once(get_template_directory() . '/admin/widget_area-sidebar.php');


/* ============================================================================================================================================= */


	function custom_login_logo_url( $url )
	{
		return esc_url( home_url( '/' ) );
	}
	
	
	function theme_login_logo()
	{
		$logo_login_hide = get_option( 'logo_login_hide', false );
		$logo_login = get_option( 'logo_login', "" );
		
		if ( $logo_login_hide )
		{
			echo '<style type="text/css"> h1 { display: none; } </style>';
		}
		else
		{
			if ( $logo_login != "" )
			{
				add_filter( 'login_headerurl', 'custom_login_logo_url' );
				
				echo '<style type="text/css">
						h1 a
						{
							background-image: url( "' . $logo_login . '" ) !important;
							cursor: default;
						}
					</style>';
				
				echo '<script>
						jQuery(document).ready(function($)
						{
							$( "h1 a" ).removeAttr( "title" );
						});
					</script>';
			}
		}
	}
	
	add_action( 'login_head', 'theme_login_logo' );


/* ============================================================================================================================================= */


	function theme_wp_title( $title, $sep )
	{
		global $paged, $page;

		if ( is_feed() )
		{
			return $title;
		}

		$title .= get_bloginfo( 'name' );
		
		$site_description = get_bloginfo( 'description', 'display' );
		
		if ( $site_description && ( is_home() || is_front_page() ) )
		{
			$title = "$title $sep $site_description";
		}
		
		if ( $paged >= 2 || $page >= 2 )
		{
			$title = "$title $sep " . sprintf( __( 'Page %s', 'read' ), max( $paged, $page ) );
		}
		
		return $title;
	}
	
	add_filter( 'wp_title', 'theme_wp_title', 10, 2 );


/* ============================================================================================================================================= */


	if ( ! isset( $content_width ) )
	{
		$content_width = 780;
	}


/* ============================================================================================================================================= */


	add_image_size( 'pixelwars__image_size_1', 500 ); // blog-masonry feat-img
	
	add_image_size( 'featured_image', 150 );
	
	add_image_size( 'blog_feat_img', 1200 );
	
	add_image_size( 'portfolio_image_1x', 400 );
	add_image_size( 'portfolio_image_2x', 800 );
	
	add_image_size( 'gallery_image_1x', 400 );
	add_image_size( 'gallery_image_2x', 800 );
	
	add_image_size( 'gallery_image_200', 200 );
	add_image_size( 'gallery_image_400', 400 );
	add_image_size( 'gallery_image_800', 800 );
	add_image_size( 'gallery_image_1200', 1200 );
	
	add_image_size( 'apple_touch_icon_57', 57, 57, true );
	add_image_size( 'apple_touch_icon_72', 72, 72, true );
	add_image_size( 'apple_touch_icon_114', 114, 114, true );
	add_image_size( 'apple_touch_icon_144', 144, 144, true );


/* ============================================================================================================================================= */

	function theme_new_post_column_add( $columns )
	{
		return array_merge( $columns, array( 'post_feat_img' => __( 'Featured Image', 'read' ) ) );
	}
	
	add_filter( 'manage_posts_columns' , 'theme_new_post_column_add' );
	
	
	function theme_new_post_column_show( $column, $post_id )
	{
		if ( $column == 'post_feat_img' )
		{
			if ( has_post_thumbnail() )
			{
				the_post_thumbnail( 'thumbnail' );
			}
		}
	}
	
	add_action( 'manage_posts_custom_column' , 'theme_new_post_column_show', 10, 2 );


/* ============================================================================================================================================= */


	function wp_page_menu2( $args = array() )
	{
		$defaults = array(  'sort_column' => 'menu_order, post_title',
							'menu_class' => 'menu',
							'echo' => true,
							'link_before' => "",
							'link_after' => "" );
							
		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'wp_page_menu_args', $args );
		
		$menu = "";
		
		$list_args = $args;
		
		// Show Home in the menu
		if ( ! empty( $args['show_home'] ) )
		{
			if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			{
				$text = __( 'Home', 'read' );
			}
			else
			{
				$text = $args['show_home'];
			}
			
			$class = "";
			
			if ( is_front_page() && !is_paged() )
			{
				$class = 'class="current_page_item"';
			}
			
			$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '" title="' . esc_attr( $text ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
			
			// If the front page is a page, add it to the exclude list
			if ( get_option( 'show_on_front' ) == 'page' )
			{
				if ( ! empty( $list_args['exclude'] ) )
				{
					$list_args['exclude'] .= ',';
				}
				else
				{
					$list_args['exclude'] = '';
				}
				
				$list_args['exclude'] .= get_option('page_on_front');
			}
			// end if
		}
		// end if
		
		$list_args['echo'] = false;
		$list_args['title_li'] = "";
		$menu .= str_replace( array( "\r", "\n", "\t" ), "", wp_list_pages( $list_args ) );
		
		if ( $menu )
		{
			$menu = '<ul class="menu-default">' . $menu . '</ul>';
		}
		
		$menu = $menu . "\n";
		$menu = apply_filters( 'wp_page_menu', $menu, $args );
		
		if ( $args['echo'] )
		{
			echo $menu;
		}
		else
		{
			return $menu;
		}
		// end if
	}
	//end wp_page_menu2


/* ============================================================================================================================================= */


	function theme_excerpt_more( $more )
	{
		return '... <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . __( 'Continue reading <span class="meta-nav">&#8594;</span>', 'read' ) . '</a>';
	}
	
	add_filter( 'excerpt_more', 'theme_excerpt_more' );


/* ============================================================================================================================================= */


	if ( ! function_exists( 'theme_comments' ) ) :
	
		/*
			Template for comments and pingbacks.
			
			To override this walker in a child theme without modifying the comments template
			simply create your own theme_comments(), and that function will be used instead.
			
			Used as a callback by wp_list_comments() for displaying the comments.
		*/
		
		function theme_comments( $comment, $args, $depth )
		{
			$GLOBALS['comment'] = $comment;
			
			switch ( $comment->comment_type ) :
			
				case 'pingback' :
				
				case 'trackback' :
					
					// Display trackbacks differently than normal comments.
					?>
						<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
							<p>
								<?php
									_e( 'Pingback:', 'read' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'read' ), '<span class="edit-link">', '</span>' );
								?>
							</p>
					<?php
				break;
				
				default :
				
					// Proceed with normal comments.
					global $post;
					
					?>
					
					<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
						<article id="comment-<?php comment_ID(); ?>" class="comment">
							<header class="comment-meta comment-author vcard">
								<?php
									echo get_avatar( $comment, 75 );
									
									printf( '<cite class="fn">%1$s %2$s</cite>',
											get_comment_author_link(),
											// If current post author is also comment author, make it known visually.
											( $comment->user_id === $post->post_author ) ? '<span></span>' : "" );
									
									printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
											esc_url( get_comment_link( $comment->comment_ID ) ),
											get_comment_time( 'c' ),
											/* translators: 1: date, 2: time */
											sprintf( __( '%1$s at %2$s', 'read' ), get_comment_date(), get_comment_time() ) );
								?>
							</header>
							<!-- end .comment-meta -->
							
							<?php
								if ( '0' == $comment->comment_approved ) :
									?>
										<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'read' ); ?></p>
									<?php
								endif;
							?>
							
							<section class="comment-content comment">
								<?php
									comment_text();
								?>
								
								<?php
									edit_comment_link( __( 'Edit', 'read' ), '<p class="edit-link">', '</p>' );
								?>
							</section>
							<!-- end .comment-content -->
							
							<div class="reply">
								<?php
									comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'read' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
								?>
							</div>
							<!-- end .reply -->
						</article>
						<!-- end #comment-## -->
					<?php
				break;
				
			endswitch;
		}
		// end theme_comments
		
	endif;


/* ============================================================================================================================================= */


	function portfolio_metabox()
	{
		global $post, $post_ID;
		
		?>
			<p>
				<input type="hidden" name="portfolio_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">
			</p>
			
			<h4><?php echo __( 'Type', 'read' ); ?></h4>
			
			<p class="pf-type-wrap">
				<?php
					$pf_type = get_option( $post->ID . 'pf_type', 'Standard' );
				?>
				<label style="display: inline-block; margin-bottom: 5px;">
					<input type="radio" name="pf_type" <?php if ( $pf_type == 'Standard' ) { echo 'checked="checked"'; } ?> value="Standard"> <?php echo __( 'Standard', 'read' ); ?>
				</label>
				<br>
				<label style="display: inline-block; margin-bottom: 5px;">
					<input type="radio" name="pf_type" <?php if ( $pf_type == 'Lightbox Featured Image' ) { echo 'checked="checked"'; } ?> value="Lightbox Featured Image"> <?php echo __( 'Lightbox Featured Image', 'read' ); ?>
				</label>
				<br>
				<label style="display: inline-block; margin-bottom: 5px;">
					<input type="radio" name="pf_type" <?php if ( $pf_type == 'Lightbox Image' ) { echo 'checked="checked"'; } ?> value="Lightbox Image"> <?php echo __( 'Lightbox Gallery', 'read' ); ?>
				</label>
				<br>
				<label style="display: inline-block; margin-bottom: 5px;">
					<input type="radio" name="pf_type" <?php if ( $pf_type == 'Lightbox Video' ) { echo 'checked="checked"'; } ?> value="Lightbox Video"> <?php echo __( 'Lightbox Video', 'read' ); ?>
				</label>
				<br>
				<label style="display: inline-block; margin-bottom: 5px;">
					<input type="radio" name="pf_type" <?php if ( $pf_type == 'Lightbox Audio' ) { echo 'checked="checked"'; } ?> value="Lightbox Audio"> <?php echo __( 'Lightbox Audio', 'read' ); ?>
				</label>
				<br>
				<label style="display: inline-block;">
					<input type="radio" name="pf_type" <?php if ( $pf_type == 'Direct URL' ) { echo 'checked="checked"'; } ?> class="pf-type-direct-url" value="Direct URL"> <?php echo __( 'Direct URL', 'read' ); ?>
				</label>
			</p>
			
			<p class="direct-url-wrap" style="<?php if ( $pf_type == 'Direct URL' ) { echo 'display: block;'; } else { echo 'display: none;'; } ?>">
				<?php
					$pf_direct_url = stripcslashes( get_option( $post->ID . 'pf_direct_url' ) );
					$pf_link_new_tab = get_option( $post->ID . 'pf_link_new_tab', true );
				?>
				<label for="pf_direct_url"><?php echo __( 'Direct URL:', 'read' ); ?></label>
				<input type="text" id="pf_direct_url" name="pf_direct_url" class="widefat code2" placeholder="Link Url" value="<?php echo $pf_direct_url; ?>">
				<label style="display: inline-block; margin-top: 5px;"><input type="checkbox" id="pf_link_new_tab" name="pf_link_new_tab" <?php if ( $pf_link_new_tab ) { echo 'checked="checked"'; } ?>> <?php echo __( 'Open link in new tab', 'read' ); ?></label>
			</p>
			
			<script>
				jQuery(document).ready(function($)
				{
					jQuery( '.pf-type-wrap label' ).click(function()
					{
						if ( jQuery( this ).find( 'input' ).hasClass( 'pf-type-direct-url' ) )
						{
							jQuery( '.direct-url-wrap' ).show();
						}
						else
						{
							jQuery( '.direct-url-wrap' ).hide();
						}
					});
				});
			</script>
			
			<hr>
			
			<h4><?php echo __( 'Thumbnail Size', 'read' ); ?></h4>
			
			<p>
				<?php
					$pf_thumb_size = get_option( $post->ID . 'pf_thumb_size', 'x1' );
				?>
				<label style="display: inline-block; margin-bottom: 5px;"><input type="radio" name="pf_thumb_size" <?php if ( $pf_thumb_size == 'x1' ) { echo 'checked="checked"'; } ?> value="x1"> <?php echo __( '1x', 'read' ); ?></label>
				<br>
				<label style="display: inline-block; margin-bottom: 5px;"><input type="radio" name="pf_thumb_size" <?php if ( $pf_thumb_size == 'x2' ) { echo 'checked="checked"'; } ?> value="x2"> <?php echo __( '2x', 'read' ); ?></label>
			</p>
			
			<hr>
			
			<h4><?php echo __( 'Short Description', 'read' ); ?></h4>
			
			<p>
				<?php
					$pf_short_description = stripcslashes( get_option( $post->ID . 'pf_short_description' ) );
				?>
				<textarea id="pf_short_description" name="pf_short_description" rows="4" cols="46" class="widefat"><?php echo $pf_short_description; ?></textarea>
			</p>
		<?php
	}
	// end portfolio_metabox
	
	
	function add_portfolio_metabox()
	{
		add_meta_box( 'portfolio_metabox', __( 'Details', 'read' ), 'portfolio_metabox', 'portfolio', 'side', 'low' );
	}
	// end add_portfolio_metabox
	
	add_action( 'admin_init', 'add_portfolio_metabox' );
	
	
	function save_portfolio_details( $post_id )
	{
		global $post, $post_ID;
	
		if ( ! wp_verify_nonce( @$_POST['portfolio_nonce'], basename(__FILE__) ) )
		{
			return $post_id;
		}

		
		if ( $_POST['post_type'] == 'portfolio' )
		{
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		
	
		if ( $_POST['post_type'] == 'portfolio' )
		{
			update_option( $post->ID . 'pf_type', $_POST['pf_type'] );
			update_option( $post->ID . 'pf_direct_url', $_POST['pf_direct_url'] );
			update_option( $post->ID . 'pf_link_new_tab', $_POST['pf_link_new_tab'] );
			update_option( $post->ID . 'pf_thumb_size', $_POST['pf_thumb_size'] );
			update_option( $post->ID . 'pf_short_description', $_POST['pf_short_description'] );
		}
		// end if
	}
	// end save_portfolio_details
	
	add_action( 'save_post', 'save_portfolio_details' );	


/* ============================================================================================================================================= */
/* ============================================================================================================================================= */

	/**
	 * This function filters the post content when viewing a post with the "chat" post format.  It formats the 
	 * content with structured HTML markup to make it easy for theme developers to style chat posts.  The 
	 * advantage of this solution is that it allows for more than two speakers (like most solutions).  You can 
	 * have 100s of speakers in your chat post, each with their own, unique classes for styling.
	 *
	 * @author David Chandra
	 * @link http://www.turtlepod.org
	 * @author Justin Tadlock
	 * @link http://justintadlock.com
	 * @copyright Copyright (c) 2012
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
	 *
	 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
	 * @param string $content The content of the post.
	 * @return string $chat_output The formatted content of the post.
	 */
	function my_format_chat_content( $content )
	{
		global $_post_format_chat_ids;

		/* If this is not a 'chat' post, return the content. */
		if ( !has_post_format( 'chat' ) )
			return $content;

		/* Set the global variable of speaker IDs to a new, empty array for this chat. */
		$_post_format_chat_ids = array();

		/* Allow the separator (separator for speaker/text) to be filtered. */
		$separator = apply_filters( 'my_post_format_chat_separator', ':' );

		/* Open the chat transcript div and give it a unique ID based on the post ID. */
		$chat_output = "\n\t\t\t" . '<div id="chat-transcript-' . esc_attr( get_the_ID() ) . '" class="chat-transcript">';

		/* Split the content to get individual chat rows. */
		$chat_rows = preg_split( "/(\r?\n)+|(<br\s*\/?>\s*)+/", $content );

		/* Loop through each row and format the output. */
		foreach ( $chat_rows as $chat_row )
		{

			/* If a speaker is found, create a new chat row with speaker and text. */
			if ( strpos( $chat_row, $separator ) )
			{

				/* Split the chat row into author/text. */
				$chat_row_split = explode( $separator, trim( $chat_row ), 2 );

				/* Get the chat author and strip tags. */
				$chat_author = strip_tags( trim( $chat_row_split[0] ) );

				/* Get the chat text. */
				$chat_text = trim( $chat_row_split[1] );

				/* Get the chat row ID (based on chat author) to give a specific class to each row for styling. */
				$speaker_id = my_format_chat_row_id( $chat_author );

				/* Open the chat row. */
				$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

				/* Add the chat row author. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-author ' . sanitize_html_class( strtolower( "chat-author-{$chat_author}" ) ) . ' vcard"><cite class="fn">' . apply_filters( 'my_post_format_chat_author', $chat_author, $speaker_id ) . '</cite>' . $separator . '</div>';

				/* Add the chat row text. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'my_post_format_chat_text', $chat_text, $chat_author, $speaker_id ) ) . '</div>';

				/* Close the chat row. */
				$chat_output .= "\n\t\t\t\t" . '</div><!-- .chat-row -->';
			}

			/**
			 * If no author is found, assume this is a separate paragraph of text that belongs to the
			 * previous speaker and label it as such, but let's still create a new row.
			 */
			else
			{

				/* Make sure we have text. */
				if ( !empty( $chat_row ) )
				{

					/* Open the chat row. */
					$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

					/* Don't add a chat row author.  The label for the previous row should suffice. */

					/* Add the chat row text. */
					$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'my_post_format_chat_text', $chat_row, $chat_author, $speaker_id ) ) . '</div>';

					/* Close the chat row. */
					$chat_output .= "\n\t\t\t</div><!-- .chat-row -->";
				}
			}
		}

		/* Close the chat transcript div. */
		$chat_output .= "\n\t\t\t</div><!-- .chat-transcript -->\n";

		/* Return the chat content and apply filters for developers. */
		return apply_filters( 'my_post_format_chat_content', $chat_output );
	}
	// end my_format_chat_content

	/**
	 * This function returns an ID based on the provided chat author name.  It keeps these IDs in a global 
	 * array and makes sure we have a unique set of IDs.  The purpose of this function is to provide an "ID"
	 * that will be used in an HTML class for individual chat rows so they can be styled.  So, speaker "John" 
	 * will always have the same class each time he speaks.  And, speaker "Mary" will have a different class 
	 * from "John" but will have the same class each time she speaks.
	 *
	 * @author David Chandra
	 * @link http://www.turtlepod.org
	 * @author Justin Tadlock
	 * @link http://justintadlock.com
	 * @copyright Copyright (c) 2012
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
	 *
	 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
	 * @param string $chat_author Author of the current chat row.
	 * @return int The ID for the chat row based on the author.
	 */
	
	
	function my_format_chat_row_id( $chat_author )
	{
		global $_post_format_chat_ids;

		/* Let's sanitize the chat author to avoid craziness and differences like "John" and "john". */
		$chat_author = strtolower( strip_tags( $chat_author ) );

		/* Add the chat author to the array. */
		$_post_format_chat_ids[] = $chat_author;

		/* Make sure the array only holds unique values. */
		$_post_format_chat_ids = array_unique( $_post_format_chat_ids );

		/* Return the array key for the chat author and add "1" to avoid an ID of "0". */
		return absint( array_search( $chat_author, $_post_format_chat_ids ) ) + 1;
	}
	// end my_format_chat_row_id
	
	/* Filter the content of chat posts. */
	add_filter( 'the_content', 'my_format_chat_content' );

	/* Auto-add paragraphs to the chat text. */
	add_filter( 'my_post_format_chat_text', 'wpautop' );

/* ============================================================================================================================================= */

	add_filter( 'the_excerpt', 'do_shortcode' );
	add_filter( 'widget_text', 'do_shortcode' );

/* ============================================================================================================================================= */

	// Actual processing of the shortcode happens here
	function my_run_shortcode( $content )
	{
		global $shortcode_tags;
		
		// Backup current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		remove_all_shortcodes();
		
		add_shortcode( 'tabs', 'tabs' );
		add_shortcode( 'tab_head', 'tab_head' );
		add_shortcode( 'tab_title', 'tab_title' );
		add_shortcode( 'tab_content', 'tab_content' );
		add_shortcode( 'tab_pane', 'tab_pane' );
		add_shortcode( 'row', 'row' );
		add_shortcode( 'column', 'column' );
		add_shortcode( 'accordions', 'accordions' );
		add_shortcode( 'accordion', 'accordion' );
		add_shortcode( 'toggles', 'toggles' );
		add_shortcode( 'toggle', 'toggle' );
		add_shortcode( 'tagline', 'tagline' );
		add_shortcode( 'intro', 'intro' );
		add_shortcode( 'label', 'label' );
		add_shortcode( 'inline_media', 'inline_media' );
		add_shortcode( 'call_to_action', 'call_to_action' );
		add_shortcode( 'cta_button_wrap', 'cta_button_wrap' );
		add_shortcode( 'lead_p', 'lead_p' );
		add_shortcode( 'portfolio_text', 'portfolio_text' );
		add_shortcode( 'launch_button', 'launch_button' );
		add_shortcode( 'project_action', 'project_action' );
		add_shortcode( 'view_button', 'view_button' );
		add_shortcode( 'syntax_prettify', 'syntax_prettify' );
		add_shortcode( 'alert', 'alert' );
		add_shortcode( 'lists', 'lists' );
		add_shortcode( 'list_item', 'list_item' );
		add_shortcode( 'social_icons', 'social_icons' );
		add_shortcode( 'social_icon', 'social_icon' );
		add_shortcode( 'button', 'button' );
		add_shortcode( 'inline_slider', 'inline_slider' );
		add_shortcode( 'slide', 'slide' );
		add_shortcode( 'divider', 'divider' );
		add_shortcode( 'lightbox_audio', 'lightbox_audio' );
		add_shortcode( 'lightbox_video', 'lightbox_video' );
		add_shortcode( 'lightbox_image', 'lightbox_image' );
		add_shortcode( 'aside_content', 'aside_content' );
		add_shortcode( 'link_content', 'link_content' );
		add_shortcode( 'inline_lightbox_wrap', 'inline_lightbox_wrap' );
		add_shortcode( 'inline_lightbox_image', 'inline_lightbox_image' );
		add_shortcode( 'inline_lightbox_video', 'inline_lightbox_video' );
		add_shortcode( 'inline_lightbox_audio', 'inline_lightbox_audio' );
		
		// Do the shortcode ( only the one above is registered )
		$content = do_shortcode( $content );
		
		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;
		
		return $content;
	}
	// end my_run_shortcode
	
	add_filter( 'the_content', 'my_run_shortcode', 7 );

/* ============================================================================================================================================= */

	function tabs( $atts, $content = "" )
	{
		$tabs = '<div class="tabs">' . do_shortcode( $content ) . '</div>';
		
		return $tabs;
	}
	// end tabs
  
	add_shortcode( 'tabs', 'tabs' );

/* ============================================================================================================================================= */

	function tab_head( $atts, $content = "" )
	{
		$tab_head = '<ul class="tab-titles">' . do_shortcode( $content ) . '</ul>';
		
		return $tab_head;
	}
	// end tab_head
  
	add_shortcode( 'tab_head', 'tab_head' );

/* ============================================================================================================================================= */

	function tab_title( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'active' => "",
										'title' => "" ), $atts ) );
		
		$tab_title = '<li><a class="' . $active . '">' . $title . '</a></li>';
		
		return $tab_title;
	}
	// end tab_title
  
	add_shortcode( 'tab_title', 'tab_title' );

/* ============================================================================================================================================= */

	function tab_content( $atts, $content = "" )
	{
		$tab_content = '<div class="tab-content">' . do_shortcode( $content ) . '</div>';
		
		return $tab_content;
	}
	// end tab_content
  
	add_shortcode( 'tab_content', 'tab_content' );

/* ============================================================================================================================================= */

	function tab_pane( $atts, $content = "" )
	{
		$tab_pane = '<div>' . do_shortcode( $content ) . '</div>';
		
		return $tab_pane;
	}
	// end tab_pane
  
	add_shortcode( 'tab_pane', 'tab_pane' );

/* ============================================================================================================================================= */

	function inline_slider( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'autoplay' => 'false',
										'interval' => '3000',
										'animation' => 'slide',
										'direction' => 'horizontal',
										'animationspeed' => '800',
										'pauseonhover' => 'true' ), $atts ) );
		
		
		$inline_slider = '<div class="flexslider" data-autoplay="' . $autoplay . '" data-interval="' . $interval . '" data-animation="' . $animation . '" data-direction="' . $direction . '" data-animationSpeed="' . $animationspeed . '"  data-pauseOnHover="' . $pauseonhover . '"><ul class="slides">' . do_shortcode( $content ) . '</ul></div>';
	 	
		
		return $inline_slider;
	}
	
	add_shortcode( 'inline_slider', 'inline_slider' );

/* ============================================================================================================================================= */

	function slide( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'alt' => "",
										'src' => "",
										'url' => "" ), $atts ) );
		
		
		if ( $content != "" )
		{
			$content_out = '<p class="flex-title">' . do_shortcode( $content ) . '</p>';
		}
		else
		{
			$content_out = "";
		}
		
		
		if ( $url != "" )
		{
			$url_out = '<a href="' . $url . '"><img alt="' . $alt . '" src="' . $src . '"></a>';
		}
		else
		{
			$url_out = '<img alt="' . $alt . '" src="' . $src . '">';
		}
		
		
		$slide = '<li>' . $url_out . $content_out . '</li>';
	 	
		
		return $slide;
	}
	
	add_shortcode( 'slide', 'slide' );

/* ============================================================================================================================================= */

	function inline_media( $atts, $content = "" )
	{
		$inline_media = '<div class="media-wrap">' . do_shortcode( $content ) . '</div>';
	 	
		return $inline_media;
	}
	// end inline_media
  
	add_shortcode( 'inline_media', 'inline_media' );

/* ============================================================================================================================================= */

	function row( $atts, $content = "" )
	{
		$row = '<div class="row-fluid">' . do_shortcode( $content ) . '</div>';
		
		return $row;
	}
	// end row
  
	add_shortcode( 'row', 'row' );

/* ============================================================================================================================================= */

	function column( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'width' => "",
										'offset' => "" ), $atts ) );
		
		$column = '<div class="span' . $width . ' offset' . $offset . '">' . do_shortcode( $content ) . '</div>';
		
		return $column;
	}
	// end column
  
	add_shortcode( 'column', 'column' );

/* ============================================================================================================================================= */

	function portfolio_text( $atts, $content = "" )
	{
		$portfolio_text = '<div class="portfolio-text">' . do_shortcode( $content ) . '</div>';
		
		return $portfolio_text;
	}
	// end portfolio_text
  
	add_shortcode( 'portfolio_text', 'portfolio_text' );

/* ============================================================================================================================================= */

	function launch_button( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'text' => "",
										'url' => "" ), $atts ) );
		
		$launch_button = '<p class="launch-wrap"><a href="' . $url . '">' . $text . '</a></p>';
		
		return $launch_button;
	}
	// end launch_button
  
	add_shortcode( 'launch_button', 'launch_button' );

/* ============================================================================================================================================= */

	function toggles( $atts, $content = "" )
	{
		$toggles = '<div class="toggle-group">' . do_shortcode( $content ) . '</div>';
		
		return $toggles;
	}
	// end toggles
  
	add_shortcode( 'toggles', 'toggles' );

/* ============================================================================================================================================= */

	function toggle( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'active' => "",
										'title' => "" ), $atts ) );
		
		$toggle = '<div class="toggle"><h4 class="' . $active . '">' . $title . '</h4><div class="toggle-content">' . do_shortcode( $content ) . '</div></div>';
		
		return $toggle;
	}
	// end toggle
  
	add_shortcode( 'toggle', 'toggle' );

/* ============================================================================================================================================= */

	function accordions( $atts, $content = "" )
	{
		$accordions = '<div class="toggle-group accordion">' . do_shortcode( $content ) . '</div>';
		
		return $accordions;
	}
	// end accordions
  
	add_shortcode( 'accordions', 'accordions' );

/* ============================================================================================================================================= */

	function accordion( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'active' => "",
										'title' => "" ), $atts ) );
		
		$accordion = '<div class="toggle"><h4 class="' . $active . '">' . $title . '</h4><div class="toggle-content">' . do_shortcode( $content ) . '</div></div>';
		
		return $accordion;
	}
	// end accordion
  
	add_shortcode( 'accordion', 'accordion' );

/* ============================================================================================================================================= */

	function alert( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'type' => "" ), $atts ) );
		
		$alert = '<div class="alert ' . $type . '">' . do_shortcode( $content ) . '</div>';
		
		return $alert;
	}
	// end alert
  
	add_shortcode( 'alert', 'alert' );

/* ============================================================================================================================================= */

	function call_to_action( $atts, $content = "" )
	{
		$call_to_action = '<div class="cta row-fluid">' . do_shortcode( $content ) . '</div>';
		
		return $call_to_action;
	}
	// end call_to_action
  
	add_shortcode( 'call_to_action', 'call_to_action' );

/* ============================================================================================================================================= */

	function cta_button_wrap( $atts, $content = "" )
	{
		$cta_button_wrap = '<div class="cta-button">' . do_shortcode( $content ) . '</div>';
		
		return $cta_button_wrap;
	}
	// end cta_button_wrap
  
	add_shortcode( 'cta_button_wrap', 'cta_button_wrap' );

/* ============================================================================================================================================= */

	function project_action( $atts, $content = "" )
	{
		$project_action = '<div class="project-action row-fluid">' . do_shortcode( $content ) . '</div>';
		
		return $project_action;
	}
	// end project_action
  
	add_shortcode( 'project_action', 'project_action' );

/* ============================================================================================================================================= */

	function button( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'target' => "",
										'color' => "",
										'size' => "",
										'type' => "",
										'icon' => "",
										'url' => "" ), $atts ) );
										
		if ( $icon == 'linkedin' )
		{
			$icon_out = '<i class="icon-linkedin-sign"></i>';
		}
		elseif ( $icon == 'github' )
		{
			$icon_out = '<i class="icon-github-sign"></i>';
		}
		elseif ( $icon == 'buy' )
		{
			$icon_out = '<i class="icon-shopping-cart"></i>';
		}
		elseif ( $icon == 'eye' )
		{
			$icon_out = '<i class="icon-eye-open"></i>';
		}
		elseif ( $icon == 'beaker' )
		{
			$icon_out = '<i class="icon-beaker"></i>';
		}
		elseif ( $icon == 'download' )
		{
			$icon_out = '<i class="icon-download"></i>';
		}
		elseif ( $icon == 'download2' )
		{
			$icon_out = '<i class="icon-download-alt"></i>';
		}
		elseif ( $icon == 'rss' )
		{
			$icon_out = '<i class="icon-rss"></i>';
		}
		elseif ( $icon == 'like' )
		{
			$icon_out = '<i class="icon-thumbs-up"></i>';
		}
		elseif ( $icon == 'heart' )
		{
			$icon_out = '<i class="icon-heart-empty"></i>';
		}
		elseif ( $icon == 'cup' )
		{
			$icon_out = '<i class="icon-trophy"></i>';
		}
		elseif ( $icon == 'plus' )
		{
			$icon_out = '<i class="icon-plus-sign"></i>';
		}
		else
		{
			$icon_out = "";
		}
		
		$button = '<a target="' . $target . '" class="button ' . $color . ' ' . $size . ' ' . $type . '" href="' . $url . '">' . $icon_out . do_shortcode( $content ) . '</a>';
		
		return $button;
	}
	// end button
  
	add_shortcode( 'button', 'button' );

/* ============================================================================================================================================= */

	function lead_p( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'drop_caps' => "" ), $atts ) );
		
		if ( $drop_caps == 'yes' )
		{
			$drop_caps_out = 'drop-cap';
		}
		else
		{
			$drop_caps_out = "";
		}
		
		$lead_p = '<p class="lead ' . $drop_caps_out . '">' . do_shortcode( $content ) . '</p>';
		
		return $lead_p;
	}
	// end lead_p
	
	add_shortcode( 'lead_p', 'lead_p' );

/* ============================================================================================================================================= */

	function drop_caps( $atts, $content = "" )
	{
		$drop_caps = '<p class="drop-cap">' . do_shortcode( $content ) . '</p>';
		
		return $drop_caps;
	}
	// end drop_caps
	
	add_shortcode( 'drop_caps', 'drop_caps' );

/* ============================================================================================================================================= */

	function tagline( $atts, $content = "" )
	{
		$tagline = '<div class="tagline">' . do_shortcode( $content ) . '</div>';
		
		return $tagline;
	}
	// end tagline
	
	add_shortcode( 'tagline', 'tagline' );

/* ============================================================================================================================================= */

	function syntax_prettify( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'linenums' => "" ), $atts ) );
		
		$syntax_prettify = '<pre class="prettyprint ' . $linenums . '">' . do_shortcode( $content ) . '</pre>';
		
		return $syntax_prettify;
	}
	// end syntax_prettify
  
	add_shortcode( 'syntax_prettify', 'syntax_prettify' );

/* ============================================================================================================================================= */

	function lists( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'type' => "" ), $atts ) );
		
		$lists = '<ul class="icons icon-' . $type . '-list">' . do_shortcode( $content ) . '</ul>';
		
		return $lists;
	}
	// end lists
  
	add_shortcode( 'lists', 'lists' );

/* ============================================================================================================================================= */

	function list_item( $atts, $content = "" )
	{
		$list_item = '<li>' . do_shortcode( $content ) . '</li>';
		
		return $list_item;
	}
	// end list_item
  
	add_shortcode( 'list_item', 'list_item' );

/* ============================================================================================================================================= */

	function social_icons( $atts, $content = "" )
	{
		$social_icons = '<ul class="social">' . do_shortcode( $content ) . '</ul>';
		
		return $social_icons;
	}
	// end social_icons
	
	add_shortcode( 'social_icons', 'social_icons' );

/* ============================================================================================================================================= */

	function social_icon( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'type' => "",
										'title' => "",
										'url' => "" ), $atts ) );
		
		if ( $type == 'facebook' ) { $type_icon = '&#88;'; }
		elseif ( $type == 'twitter' ) { $type_icon = '&#95;'; }
		elseif ( $type == 'linkedin' ) { $type_icon = '&#118;'; }
		elseif ( $type == 'google' ) { $type_icon = ""; }
		elseif ( $type == 'vimeo' ) { $type_icon = '&#33;'; }
		elseif ( $type == 'pinterest' ) { $type_icon = ""; }
		elseif ( $type == 'flickr' ) { $type_icon = '&#98;'; }
		elseif ( $type == 'dribble' ) { $type_icon = '&#83;'; }
		elseif ( $type == 'dribbble' ) { $type_icon = '&#83;'; }
		elseif ( $type == 'lastfm' ) { $type_icon = '&#117;'; }
		elseif ( $type == 'rss' ) { $type_icon = '&#42;'; }
		elseif ( $type == 'forrst' ) { $type_icon = '&#100;'; }
		elseif ( $type == 'skype' ) { $type_icon = '&#58;'; }
		elseif ( $type == 'picassa' ) { $type_icon = '&#56;'; }
		elseif ( $type == 'youtube' ) { $type_icon = '&#39;'; }
		elseif ( $type == 'behance' ) { $type_icon = '&#71;'; }
		elseif ( $type == 'tumblr' ) { $type_icon = '&#92;'; }
		elseif ( $type == 'blogger' ) { $type_icon = '&#74;'; }
		elseif ( $type == 'delicious' ) { $type_icon = '&#76;'; }
		elseif ( $type == 'digg' ) { $type_icon = '&#81;'; }
		elseif ( $type == 'friendfeed' ) { $type_icon = '&#102;'; }
		elseif ( $type == 'github' ) { $type_icon = '&#106;'; }
		elseif ( $type == 'wordpress' ) { $type_icon = '$'; }
		elseif ( $type == 'instagram' ) { $type_icon = ""; }
		else { $type_icon = ""; }
		
		$social_icon = '<li><a target="_blank" class="' . $type . '" href="' . $url . '">' . $type_icon . '</a></li>';
		
		return $social_icon;
	}
	// end social_icon
  
	add_shortcode( 'social_icon', 'social_icon' );

/* ============================================================================================================================================= */

	function intro( $atts, $content = "" )
	{
		$intro = '<div class="intro">' . do_shortcode( $content ) . '</div>';
		
		return $intro;
	}
	// end intro
  
	add_shortcode( 'intro', 'intro' );

/* ============================================================================================================================================= */

	function label( $atts, $content = "" )
	{
		$label = '<span class="label">' . do_shortcode( $content ) . '</span>';
		
		return $label;
	}
	// end label
  
	add_shortcode( 'label', 'label' );

/* ============================================================================================================================================= */

	function view_button( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'text' => "",
										'url' => "" ), $atts ) );
		
		$view_button = '<p class="launch-wrap"><a href="' . $url . '">' . $text . '</a></p>';
		
		return $view_button;
	}
	// end view_button
  
	add_shortcode( 'view_button', 'view_button' );

/* ============================================================================================================================================= */

	function divider( $atts, $content = "" )
	{
		$divider = '<hr>';
		
		return $divider;
	}
	// end divider
  
	add_shortcode( 'divider', 'divider' );

/* ============================================================================================================================================= */

	function lightbox_audio( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'title' => "",
										'url' => "" ), $atts ) );
									
									
		if ( is_single() )
		{
			$lightbox_audio = '<iframe style="width: 100%;" src="' . $url . '"></iframe>';
		}
		else
		{
			$lightbox_audio = '<a class="lightbox iframe" data-lightbox-gallery="fancybox-item-' . get_the_ID() . '" title="' . $title . '" href="' . $url . '"></a>';
		}
		
		return $lightbox_audio;
	}
	// end lightbox_audio
  
	add_shortcode( 'lightbox_audio', 'lightbox_audio' );

/* ============================================================================================================================================= */

	function lightbox_video( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'title' => "",
										'url' => "" ), $atts ) );
										
										
		if ( is_single() )
		{
			$lightbox_video = '<div class="media-wrap"><iframe src="' . $url . '"></iframe></div>';
		}
		else
		{
			$lightbox_video = '<a class="lightbox iframe" data-lightbox-gallery="fancybox-item-' . get_the_ID() . '" title="' . $title . '" href="' . $url . '"></a>';
		}
		
		return $lightbox_video;
	}
	// end lightbox_video
	
	add_shortcode( 'lightbox_video', 'lightbox_video' );

/* ============================================================================================================================================= */

	function lightbox_image( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'title' => "",
										'url' => "",
										'first_image' => "yes" ), $atts ) );
										
		if ( $first_image == "yes" )
		{
			$first_image_out = "";
		}
		else
		{
			$first_image_out = 'hidden';
		}
		
		
		if ( is_single() )
		{
			$lightbox_image .= '<img style="display: block; margin-left: auto; margin-right: auto;" alt="' . $title . '" src="' . $url . '">';
		}
		else
		{
			$lightbox_image = '<a class="lightbox ' . $first_image_out . '" data-lightbox-gallery="fancybox-item-' . get_the_ID() . '" title="' . $title . '" href="' . $url . '"></a>';
		}
		
		return $lightbox_image;
	}
	// end lightbox_image
  
	add_shortcode( 'lightbox_image', 'lightbox_image' );

/* ============================================================================================================================================= */

	function aside_content( $atts, $content = "" )
	{
		$aside_content = '<div class="aside-content">' . do_shortcode( $content ) . '</div>';
		
		return $aside_content;
	}
	// end aside_content
  
	add_shortcode( 'aside_content', 'aside_content' );

/* ============================================================================================================================================= */

	function link_content( $atts, $content = "" )
	{
		$link_content = '<div class="link-content">' . do_shortcode( $content ) . '</div>';
		
		return $link_content;
	}
	// end link_content
  
	add_shortcode( 'link_content', 'link_content' );

/* ============================================================================================================================================= */

	function inline_lightbox_wrap( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'type' => "",
										'thumbnail' => "",
										'alt' => "" ), $atts ) );
		
		$inline_lightbox_wrap = '<div class="inline-lightbox ' . $type . '">';
		$inline_lightbox_wrap .= '<div class="media-box">';
		$inline_lightbox_wrap .= '<img  alt="' . $alt . '" src="' . $thumbnail . '">';
		$inline_lightbox_wrap .= '<div class="mask">';
		$inline_lightbox_wrap .= '<div class="portfolio-info"></div>';
		$inline_lightbox_wrap .= do_shortcode( $content );
		$inline_lightbox_wrap .= '</div>';
		$inline_lightbox_wrap .= '</div>';
		$inline_lightbox_wrap .= '</div>';
		
		return $inline_lightbox_wrap;
	}
	// end inline_lightbox_wrap
  
	add_shortcode( 'inline_lightbox_wrap', 'inline_lightbox_wrap' );

/* ============================================================================================================================================= */

	function inline_lightbox_image( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'first_image' => "",
										'gallery' => "",
										'title' => "",
										'url' => "" ), $atts ) );
										
		if ( $first_image == 'yes' )
		{
			$first_image_out = "";
		}
		else
		{
			$first_image_out = 'hidden';
		}
		
		$inline_lightbox_image = '<a class="lightbox ' . $first_image_out . '" data-lightbox-gallery="fancybox-item-' . $gallery . '" title="' . $title . '" href="' . $url . '"></a>';
		
		return $inline_lightbox_image;
	}
	// end inline_lightbox_image
  
	add_shortcode( 'inline_lightbox_image', 'inline_lightbox_image' );

/* ============================================================================================================================================= */

	function inline_lightbox_video( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'gallery' => "",
										'title' => "",
										'url' => "" ), $atts ) );
										
		
		$inline_lightbox_video = '<a class="lightbox iframe" data-lightbox-gallery="fancybox-item-' . $gallery . '" title="' . $title . '" href="' . $url . '"></a>';
		
		return $inline_lightbox_video;
	}
	// end inline_lightbox_video
  
	add_shortcode( 'inline_lightbox_video', 'inline_lightbox_video' );

/* ============================================================================================================================================= */

	function inline_lightbox_audio( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'gallery' => "",
										'title' => "",
										'url' => "" ), $atts ) );
										
		
		$inline_lightbox_audio = '<a class="lightbox iframe" data-lightbox-gallery="fancybox-item-' . $gallery . '" title="' . $title . '" href="' . $url . '"></a>';
		
		return $inline_lightbox_audio;
	}
	// end inline_lightbox_audio
  
	add_shortcode( 'inline_lightbox_audio', 'inline_lightbox_audio' );

/* ============================================================================================================================================= */

	function theme_customize_register( $wp_customize )
	{
		$wp_customize->add_section( 'section_colors' , array( 'title' => __( 'Colors', 'read' ), 'priority' => 30 ) );
		
		/* ========================= */
		
		$wp_customize->add_setting( 'setting_link_color', array( 'default' => '#ce6607', 'transport' => 'refresh' ) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'control_link_color', array( 'label' => __( 'Link Color', 'read' ),
																												'section' => 'section_colors',
																												'settings' => 'setting_link_color' ) ) );
		
		/* ========================= */
		
		$wp_customize->add_setting( 'setting_link_hover_color', array( 'default' => '#a35208', 'transport' => 'refresh' ) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'control_link_hover_color', array(   'label' => __( 'Link Hover Color', 'read' ),
																														'section' => 'section_colors',
																														'settings' => 'setting_link_hover_color' ) ) );
		
		/* ========================= */
		
		$wp_customize->add_setting( 'setting_menu_active_color', array(	'default' => '#cc3300', 'transport' => 'refresh' ) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'control_menu_active_color', array(  'label' => __( 'Menu Active Color', 'read' ),
																														'section' => 'section_colors',
																														'settings' => 'setting_menu_active_color' ) ) );
		
		/* ================================================== */
		/* ================================================== */
		
		$wp_customize->add_section( 'section_fonts' , array( 'title' => __( 'Fonts', 'read' ), 'priority' => 30 ) );
		
		/* ========================= */
		
		include_once 'fonts.php';
		
		/* ========================= */
		
		$wp_customize->add_setting( 'setting_text_logo_font', array( 'default' => 'UnifrakturMaguntia', 'transport' => 'refresh' ) );
		
		$wp_customize->add_control( 'control_text_logo_font', array(    'label' => 'Text Logo Font',
																		'section' => 'section_fonts',
																		'settings' => 'setting_text_logo_font',
																		'type' => 'select',
																		'choices' => $all_fonts ) );
		
		/* ========================= */
		
		$wp_customize->add_setting( 'setting_heading_font', array( 'default' => 'Coustard', 'transport' => 'refresh' ) );
		
		$wp_customize->add_control( 'control_heading_font', array(	'label' => 'Heading Font',
																	'section' => 'section_fonts',
																	'settings' => 'setting_heading_font',
																	'type' => 'select',
																	'choices' => $all_fonts ) );
		
		/* ========================= */
		
		$wp_customize->add_setting( 'setting_menu_font', array(	'default' => 'Coustard', 'transport' => 'refresh' ) );
		
		$wp_customize->add_control( 'control_menu_font', array(	'label' => 'Menu Font',
																'section' => 'section_fonts',
																'settings' => 'setting_menu_font',
																'type' => 'select',
																'choices' => $all_fonts ) );
		
		/* ========================= */
		
		$wp_customize->add_setting( 'setting_content_font', array( 'default' => 'Lora', 'transport' => 'refresh' ) );
		
		$wp_customize->add_control( 'control_content_font', array(	'label' => 'Content Font',
																	'section' => 'section_fonts',
																	'settings' => 'setting_content_font',
																	'type' => 'select',
																	'choices' => $all_fonts ) );
		
		/* ========================= */
		
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		
		$wp_customize->get_setting( 'setting_link_color' )->transport = 'postMessage';
		$wp_customize->get_setting( 'setting_link_hover_color' )->transport = 'postMessage';
		$wp_customize->get_setting( 'setting_menu_active_color' )->transport = 'postMessage';
		
		$wp_customize->get_setting( 'setting_text_logo_font' )->transport = 'postMessage';
		$wp_customize->get_setting( 'setting_heading_font' )->transport = 'postMessage';
		$wp_customize->get_setting( 'setting_menu_font' )->transport = 'postMessage';
		$wp_customize->get_setting( 'setting_content_font' )->transport = 'postMessage';
	}
	// end theme_customize_register
	
	add_action( 'customize_register', 'theme_customize_register' );


	function theme_customize_css()
	{
		global $subset;
		
		$extra_font_styles = get_option( 'extra_font_styles', 'No' );
		
		if ( $extra_font_styles == 'Yes' )
		{
			$font_styles = ':300,400,600,700,800,900,300italic,400italic,600italic,700italic,800italic,900italic';
		}
		else
		{
			$font_styles = "";
		}
		// end if
		
		?>

<?php
	$setting_text_logo_font = get_theme_mod( 'setting_text_logo_font', "" );
	
	if ( $setting_text_logo_font != "" )
	{
		echo '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $setting_text_logo_font . $font_styles . $subset . '">';
	}
?>

<?php
	$setting_heading_font = get_theme_mod( 'setting_heading_font', "" );
	
	if ( $setting_heading_font != "" )
	{
		echo '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $setting_heading_font . $font_styles . $subset . '">';
	}
?>

<?php
	$setting_menu_font = get_theme_mod( 'setting_menu_font', "" );
	
	if ( $setting_menu_font != "" )
	{
		echo '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $setting_menu_font . $font_styles . $subset . '">';
	}
?>

<?php
	$setting_content_font = get_theme_mod( 'setting_content_font', "" );
	
	if ( $setting_content_font != "" )
	{
		echo '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $setting_content_font . $font_styles . $subset . '">';
	}
?>

<style type="text/css">
<?php
	$setting_link_color = get_theme_mod( 'setting_link_color', "" );
	
	if ( $setting_link_color != "" )
	{
		echo 'a { color: ' . $setting_link_color . '; }' . "\n";
	}
?>

<?php
	$setting_link_hover_color = get_theme_mod( 'setting_link_hover_color', "" );
	
	if ( $setting_link_hover_color != "" )
	{
		echo 'a:hover { color: ' . $setting_link_hover_color . '; }' . "\n";
	}
?>

<?php
	$setting_menu_active_color = get_theme_mod( 'setting_menu_active_color', "" );
	
	if ( $setting_menu_active_color != "" )
	{
		echo '.main-navigation ul .current_page_item > a, .main-navigation ul .current-menu-item > a { color: ' . $setting_menu_active_color . '; }' . "\n";
	}
?>

<?php
	$setting_text_logo_font = get_theme_mod( 'setting_text_logo_font', "" );
	
	if ( $setting_text_logo_font != "" )
	{
		echo 'h1.site-title, h1.site-title a { font-family: "' . $setting_text_logo_font . '", Georgia, serif; }' . "\n";
	}
?>

<?php
	$setting_heading_font = get_theme_mod( 'setting_heading_font', "" );
	
	if ( $setting_heading_font != "" )
	{
		echo 'h1, h2, h3, h4, h5, h6 { font-family: "' . $setting_heading_font . '", Georgia, serif; }' . "\n";
	}
?>

<?php
	$setting_menu_font = get_theme_mod( 'setting_menu_font', "" );
	
	if ( $setting_menu_font != "" )
	{
		echo '.main-navigation ul li { font-family: "' . $setting_menu_font . '", Georgia, serif; }' . "\n";
	}
?>

<?php
	$setting_content_font = get_theme_mod( 'setting_content_font', "" );
	
	if ( $setting_content_font != "" )
	{
		echo 'html { font-family: "' . $setting_content_font . '", Georgia, serif; }' . "\n";
	}
?>
</style>
		<?php
	}
	// end theme_customize_css
	
	add_action( 'wp_head', 'theme_customize_css' );
	
	
	function theme_customize_preview_js()
	{
		wp_enqueue_script( 'my-customizer', get_template_directory_uri() . '/js/wp-theme-customizer.js', array( 'customize-preview' ), '1.0', true );
	}
	
	add_action( 'customize_preview_init', 'theme_customize_preview_js' );

/* ============================================================================================================================================= */

	function options_wp_head()
	{
		$custom_css = stripcslashes( get_option( 'custom_css', "" ) );
	
		if ( $custom_css != "" )
		{
			echo '<style type="text/css">' . "\n";
			
				echo $custom_css;
			
			echo "\n" . '</style>' . "\n";
		}
		// end if
		
		
		$external_css = stripcslashes( get_option( 'external_css', "" ) );
		echo $external_css;
	}
	
	add_action( 'wp_head', 'options_wp_head' );


/* ============================================================================================================================================= */


	function options_wp_footer()
	{
		$external_js = stripcslashes( get_option( 'external_js', "" ) );
		echo $external_js;
	}
	
	add_action( 'wp_footer', 'options_wp_footer' );


/* ============================================================================================================================================= */


	function read__archive_title()
	{
		?>
			<header class="page-header">
				<h1 class="page-title">
					<?php
						if (is_category())
						{
							_e('Post Category', 'read');
							
							?>
								<span class="on"><?php _e('&#8594;', 'read'); ?></span>
								
								<span><?php echo single_cat_title(); ?></span>
							<?php
						}
						elseif (is_tag())
						{
							_e('Posts Tagged', 'read');
							
							?>
								<span class="on"><?php _e('&#8594;', 'read'); ?></span>
								
								<span><?php echo single_tag_title(); ?></span>
							<?php
						}
						elseif (is_author())
						{
							_e('Author Archives', 'read');
							
							?>
								<span class="on"><?php _e('&#8594;', 'read'); ?></span>
								
								<span><?php the_author(); ?></span>
							<?php
						}
						elseif (is_date())
						{
							_e('Date Archives', 'read');
							
							?>
								<span class="on"><?php _e('&#8594;', 'read'); ?></span>
								
								<span>
									<?php
										if (is_day()) :
										
											printf(get_the_date());
										
										elseif (is_month()) :
										
											printf(get_the_date(_x('F Y', 'monthly archives date format', 'read')));
										
										elseif (is_year()) :
										
											printf(get_the_date(_x('Y', 'yearly archives date format', 'read')));
										
										else :
										
											_e('Archives', 'read');
										
										endif;
									?>
								</span>
							<?php
						}
					?>
				</h1>
				
				<?php
					the_archive_description('<div class="archive-description">', '</div> <!-- .archive-description -->');
				?>
			</header>
		<?php
	}


/* ============================================================================================================================================= */


	if (is_admin())
	{
		include_once(get_template_directory() . '/admin/theme-options.php');
	}
	
	include_once 'shortcode-generator.php';
	include_once(get_template_directory() . '/admin/post-type-portfolio.php');
	include_once(get_template_directory() . '/admin/admin--install-plugins.php');
	include_once(get_template_directory() . '/admin/admin--demo-import.php');
