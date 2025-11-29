<?php
/*
Template Name: Full width Page
*/
?>

<?php
	get_header();
?>

<div id="primary" class="site-content <?php echo read__get_layout_class(); ?>">
	<div id="content" role="main">
		<div class="row-fluid">
			<?php
				while (have_posts()) : the_post();
					?>
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
							<header class="entry-header">
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</header>
							
							<div class="entry-content clearfix">
								<?php
									the_content();
									
									wp_link_pages(
										array(
											'before' => '<div class="page-links">' . __('Pages:', 'read'),
											'after'  => '</div>'
										)
									);
								?>
							</div>
						</article>
					<?php
					
					comments_template("", true);
				endwhile;
			?>
		</div>
	</div>
</div>

<?php

	read__sidebar();
	
	get_footer();
