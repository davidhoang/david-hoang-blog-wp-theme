<?php
/*
Template Name: Portfolio
*/
?>

<?php
	$page_all_info = get_page( get_the_ID() );
	
	$page_slug = $page_all_info->post_name;
	
	update_option( 'portfolio_slug', $page_slug );
?>

<?php
	get_header();
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<div class="row-fluid page">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php single_post_title(); ?></h1>
				</header> <!-- end .entry-header -->
				
				<div class="entry-content clearfix">
					<?php
						$pf_content_editor = get_option( 'pf_content_editor', 'Bottom' );
						
						if ( $pf_content_editor == 'Top' )
						{
							?>
								<div class="pf-content-editor clearfix">
									<?php
										if ( have_posts() ) :
											while ( have_posts() ) : the_post();
												
												the_content();
												
												wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'read' ), 'after' => '</div>' ) );
												
											endwhile;
										endif;
										wp_reset_query();
									?>
								</div> <!-- end .pf-content-editor -->
							<?php
						}
					?>
					
					<ul id="filters">
						<?php
							$pf_terms = get_categories( 'type=portfolio&taxonomy=department' );
							
							if ( count( $pf_terms ) >= 2 )
							{
								?>
									<li class="current pf-all-items">
										<a href="#" data-filter="*"><?php echo __( 'all', 'read' ); ?></a>
									</li>
								<?php
							}
							
							foreach ( $pf_terms as $pf_term ) :
								?>
									<li>
										<a href="<?php echo get_home_url() . '/department/' . $pf_term->slug; ?>" data-filter=".<?php echo $pf_term->slug; ?>"><?php echo $pf_term->name; ?></a>
									</li>
								<?php
							endforeach;
						?>
					</ul> <!-- end #filters -->
					
					<div class="portfolio-items">
						<?php
							$args_portfolio = array( 'post_type' => 'portfolio', 'posts_per_page' => -1 );
							$loop_portfolio = new WP_Query( $args_portfolio );
							
							if ( $loop_portfolio->have_posts() ) :
								while ( $loop_portfolio->have_posts() ) : $loop_portfolio->the_post();
								
									$pf_type = get_option( $post->ID . 'pf_type', 'Standard' );
									
									if ( $pf_type == 'Standard' )
									{
										get_template_part( 'portfolio', 'standard' );
									}
									elseif ( $pf_type == 'Lightbox Featured Image' )
									{
										get_template_part( 'portfolio', 'featured' );
									}
									elseif ( $pf_type == 'Lightbox Image' )
									{
										get_template_part( 'portfolio', 'gallery' );
									}
									elseif ( $pf_type == 'Lightbox Video' )
									{
										get_template_part( 'portfolio', 'video' );
									}
									elseif ( $pf_type == 'Lightbox Audio' )
									{
										get_template_part( 'portfolio', 'audio' );
									}
									elseif ( $pf_type == 'Direct URL' )
									{
										get_template_part( 'portfolio', 'url' );
									}
								
								endwhile;
							endif;
							wp_reset_query();
						?>
					</div> <!-- .portfolio-items -->
					
					<?php
						if ( $pf_content_editor == 'Bottom' )
						{
							?>
								<div class="pf-content-editor clearfix">
									<?php
										if ( have_posts() ) :
											while ( have_posts() ) : the_post();
												
												the_content();
												
												wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'read' ), 'after' => '</div>' ) );
												
											endwhile;
										endif;
										wp_reset_query();
									?>
								</div> <!-- end .pf-content-editor -->
							<?php
						}
					?>
				</div> <!-- end .entry-content -->
			</article> <!-- end .hentry -->
			
			<?php
				comments_template( "", true );
			?>
		</div> <!-- end .row -->
	</div> <!-- end #content -->
</div> <!-- end #primary -->

<?php
	get_footer();
?>