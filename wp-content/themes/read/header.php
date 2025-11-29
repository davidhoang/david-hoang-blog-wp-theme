<!doctype html>

<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php echo get_bloginfo('charset'); ?>">
	
	<?php
		$mobile_zoom = get_option('mobile_zoom', 'No');
		
		if ($mobile_zoom == 'Yes')
		{
			?>

				<meta name="viewport" content="width=device-width, initial-scale=1">

			<?php
		}
		else
		{
			?>

				<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

			<?php
		}
	?>
	
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!--[if lte IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/selectivizr-min.js"></script>
    <![endif]-->
	
	<?php
		wp_head();
	?>
</head>

<body <?php body_class(); ?>>
    <div id="page" class="hfeed site"> 
        <header class="site-header wrapper" role="banner">
			<div class="row">
			    <hgroup>
					<h1 class="site-title">
						<?php
							$logo_type = get_option('logo_type', 'Text Logo');
							
							if ($logo_type == 'Text Logo')
							{
								$select_text_logo = get_option('select_text_logo', 'WordPress Site Title');
								
								if ($select_text_logo == 'WordPress Site Title')
								{
									$text_logo_out = get_bloginfo('name');
								}
								else
								{
									$text_logo_out = stripcslashes(get_option('theme_site_title', ""));
								}
								
								?>
									<a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo $text_logo_out; ?></a>
								<?php
							}
							else
							{
								$logo_image = get_option('logo_image', "");
								
								?>
									<a rel="home" href="<?php echo esc_url(home_url('/')); ?>">
										<img alt="<?php bloginfo('name'); ?>" src="<?php echo $logo_image; ?>">
									</a>
								<?php
							}
						?>
					</h1> <!-- .site-title -->
					
					<h2 class="site-description">
						<?php
							$select_tagline = get_option('select_tagline', 'WordPress Tagline');
							
							if ($select_tagline == 'WordPress Tagline')
							{
								$tagline_out = get_bloginfo('description');
							}
							else
							{
								$tagline_out = stripcslashes(get_option('theme_tagline', ""));
							}
							
							echo $tagline_out;
						?>
					</h2> <!-- .site-description -->
			    </hgroup>
				
				<?php
					if (! function_exists('dynamic_sidebar') || ! dynamic_sidebar('header_sidebar')) :
					endif;
				?>
				
			    <nav id="site-navigation" class="main-navigation" role="navigation">
					<?php
						wp_nav_menu(
							array(
								'menu'           => 'top_menu',
								'menu_id'        => 'nav',
								'menu_class'     => "menu-custom",
								'theme_location' => 'top_menu',
								'container'      => false,
								'depth'          => 0,
								'fallback_cb'    => 'wp_page_menu2'
							)
						);
						
						$nav_menu_search = get_option('nav_menu_search', 'No');
						
						if ($nav_menu_search == 'Yes')
						{
							?>
								<script>
									var nav_menu_search = '<li class="nav-menu-search"><form id="search-form" role="search" method="get" action="<?php echo home_url("/"); ?>"><label for="search"><?php echo __("Search", "read"); ?></label><input type="text" id="search" name="s" title="<?php echo __("Enter keyword", "read"); ?>" value="" required="required"><input type="submit" id="search-submit" title="<?php echo __("Search it", "read"); ?>" value="<?php echo __("&#8594;", "read"); ?>"></form></li>';
									
									(function($) {
										$('#site-navigation > ul').append(nav_menu_search);
									})(jQuery);
								</script>
							<?php
						}
					?>
			    </nav> <!-- #site-navigation -->
			</div> <!-- .row -->
        </header> <!-- .site-header -->
		
        <section id="main" class="middle wrapper">
			<div class="row row-fluid <?php echo read__get_sidebar_class(); ?>">
