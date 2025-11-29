<?php
/*
Template Name: Homepage
*/
?>

<?php
	get_header();
?>


<div id="primary" class="site-content">
	<div id="content" role="main">
		<div class="readable-content">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							
							the_content();
							
							wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'read' ), 'after' => '</div>' ) );
							
						endwhile;
					endif;
					wp_reset_query();
				?>
			</article>
			<!-- end Page Content -->
			
			
			<div class="post-list">
				<h2><?php echo get_the_title(); ?></h2>
				
				<ul>
					<?php
						$args_homepage = array( 'post_type' => 'post', 'posts_per_page' => -1 );
						$loop_homepage = new WP_Query( $args_homepage );
						
						if ( $loop_homepage->have_posts() ) :
							while ( $loop_homepage->have_posts() ) : $loop_homepage->the_post();
							
								$all_formats_homepage = get_option( 'all_formats_homepage', 'No' );
								
								if ( $all_formats_homepage == 'No' )
								{
									$format = get_post_format();
									
									if ( $format == false )
									{
										?>
											<li>
												<h3>
													<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
												</h3>
												
												<span class="date"><?php echo get_the_date(); ?></span>
											</li>
										<?php
									}
								}
								else
								{
									?>
										<li>
											<h3>
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											
											<span class="date"><?php echo get_the_date(); ?></span>
										</li>
									<?php
								}
							
							endwhile;
						endif;
						wp_reset_query();
					?>
				</ul>
			</div>
			
			
			<?php
				comments_template( "", true );
			?>
		</div>
	</div>
</div>


<?php
	get_footer();
?>