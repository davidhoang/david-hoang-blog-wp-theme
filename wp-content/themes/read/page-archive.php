<?php
/*
Template Name: Archive
*/
?>

<?php
	get_header();
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<div class="row-fluid page">
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php single_post_title(); ?></h1> <!-- .entry-title -->
				</header> <!-- .entry-header -->
				
				<div class="entry-content">
					<div class="row-fluid archives">
						<div class="span4">
							<h3><?php echo __('Post Archives', 'read'); ?></h3>
							
							<ul class="icons icon-plus-list">
								<?php
									wp_get_archives(
										array(
											'show_post_count' => 0
										)
									);
								?>
							</ul> <!-- .icons .icon-plus-list -->
						</div> <!-- .span4 -->
						
						<div class="span4">
							<h3><?php echo __('Pages', 'read'); ?></h3>
							
							<ul class="icons icon-plus-list">
								<?php
									wp_list_pages(
										'title_li='
									);
								?>
							</ul> <!-- .icons .icon-plus-list -->
						</div> <!-- .span4 -->
						
						<div class="span4">
							<h3><?php echo __('Categories', 'read'); ?></h3>
							
							<ul class="icons icon-plus-list">
								<?php
									wp_list_categories(
										array(
											'title_li'   => "",
											'show_count' => 0
										)
									);
								?>
							</ul> <!-- .icons .icon-plus-list -->
						</div> <!-- .span4 -->
					</div> <!-- .row-fluid .archives -->
					
					<div>
						<?php
							if (have_posts()) :
								while (have_posts()) : the_post();
								
									the_content();
									
									wp_link_pages(
										array(
											'before' => '<div class="page-links">' . __('Pages:', 'read'),
											'after'  => '</div>'
										)
									);
								
								endwhile;
							endif;
							wp_reset_query();
						?>
					</div>
				</div> <!-- .entry-content -->
			</article> <!-- .hentry -->
			
			<?php
				comments_template("", true);
			?>
		</div> <!-- .row-fluid .page -->
	</div> <!-- #content -->
</div> <!-- #primary .site-content -->

<?php
	get_footer();
?>