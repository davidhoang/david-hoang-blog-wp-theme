<?php
	get_header();
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<div class="row-fluid page portfolio-single">
			<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
					
						$terms = get_the_terms( $post->ID, 'department' );
						$on_draught = "";
						
						if ( $terms && ! is_wp_error( $terms ) ) :
							
							$draught_links = array();
							
							foreach ( $terms as $term )
							{
								$draught_links[] = $term->name;
							}
							
							$on_draught = join( ', ', $draught_links );
							
						endif;
						
						?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
								<header class="entry-header">
									<h1 class="entry-title"><?php the_title(); ?></h1>
									
									<p class="department"><?php echo $on_draught; ?></p>
									
									<?php
										$pf_share_links_single = get_option( 'pf_share_links_single', 'Yes' );
										
										if ( $pf_share_links_single == 'Yes' )
										{
											get_template_part( 'part', 'share' );
										}
									?>
								</header>
								
								<div class="entry-content">
									<?php
										the_content();
									?>
									
									<?php
										wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'read' ), 'after' => '</div>' ) );
									?>
								</div>
							</article>
							
							<?php
								$portfolio_slug = get_option( 'portfolio_slug', "" );
								
								if ( $portfolio_slug != "" )
								{
									$back_to_portfolio_url = get_home_url() . '/' . $portfolio_slug;
									
									?>
										<div class="back-to-portfolio portfolio-text">
											<p class="launch-wrap">
												<a href="<?php echo $back_to_portfolio_url; ?>"><?php echo __( 'BACK TO PORTFOLIO', 'read' ); ?></a>
											</p>
										</div>
									<?php
								}
							?>
							
							<nav class="nav-single row-fluid">
								<div class="nav-previous span6">
									<?php
										previous_post_link( '<h4>' . __( 'PREVIOUS PROJECT', 'read' ) . '</h4>%link', '<span class="meta-nav">&#8592;</span> %title' );
									?>
								</div>
								
								<div class="nav-next span6">
									<?php
										next_post_link( '<h4>' . __( 'NEXT PROJECT', 'read' ) . '</h4>%link', '%title <span class="meta-nav">&#8594;</span>' );
									?>
								</div>
							</nav>
							
							<?php
								comments_template( "", true );
							?>
						<?php
					endwhile;
				endif;
				wp_reset_query();
			?>
		</div>
	</div>
</div>

<?php
	get_footer();
?>