<?php
	get_header();
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<div class="row-fluid page">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php single_cat_title(); ?></h1>
				</header>
				<!-- end .entry-header -->
				
				<div class="entry-content clearfix">
					<ul id="filters">
						<?php
							$all_departments = get_categories( array( 'type' => 'portfolio', 'taxonomy' => 'department' ) );
							
							$parent_department_slug = get_query_var( 'term' );
							
							$parent_department_id = "";
							
							foreach ( $all_departments as $one_department ) :
							
								if ( $one_department->slug == $parent_department_slug )
								{
									$parent_department_id = $one_department->term_id;
								}
							
							endforeach;
							
							$pf_terms = get_categories( array( 'type' => 'portfolio', 'taxonomy' => 'department', 'child_of' => $parent_department_id ) );
							
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
					</ul>
					<!-- end #filters -->
					
					<?php
						$pf_ajax = get_option( 'pf_ajax', 'Yes' );
						
						if ( ( $pf_ajax == 'No' ) || ( isset( $_GET['pf_ajax'] ) ) )
						{
							?>
								<div class="portfolio-items">
									<?php
										$args_department = array( 'post_type' => 'portfolio', 'department' => $parent_department_slug, 'posts_per_page' => -1 );
										$loop_department = new WP_Query( $args_department );
										
										if ( $loop_department->have_posts() ) :
											while ( $loop_department->have_posts() ) : $loop_department->the_post();
											
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
								</div>
								<!-- end .portfolio-items -->
							<?php
						}
						else
						{
							$pf_item_per_page = get_option( 'pf_item_per_page', '5' );
							
							?>
								<div class="portfolio-items loading" data-itemPerPage="<?php echo $pf_item_per_page; ?>"></div>
								
								<a id="loadmore" class="loadmore" href="?pf_ajax"><?php echo __( 'MORE ITEMS', 'read' ); ?></a>	
							<?php
						}
						// end if
					?>
					<!-- end PORTFOLIO -->
				</div>
				<!-- end .entry-content -->
			</article>
			<!-- end .hentry -->
			
			<?php
				comments_template( "", true );
			?>
		</div>
		<!-- end .row -->
	</div>
	<!-- end #content -->
</div>
<!-- end #primary -->

<?php
	get_footer();
?>