<?php
/*
Template Name: Archive 2
*/
?>

<?php
	get_header();
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<div class="readable-content">
			<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'page hentry clearfix' ); ?>>
								<header class="entry-header">
									<h1 class="entry-title"><?php the_title(); ?></h1>
								</header>
								<!-- end .entry-header -->
								
								<div class="entry-content">
									<?php
										the_content();
									?>
									
									<?php
										wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'read' ), 'after' => '</div>' ) );
									?>
									
									<div class="post-list archives-list last-30-posts">
										<h2><?php echo __( 'Last 30 posts', 'read' ); ?></h2>
										
										<ul>
											<?php
												$args_homepage = array( 'post_type' => 'post', 'posts_per_page' => 30 );
												$loop_homepage = new WP_Query( $args_homepage );
												
												if ( $loop_homepage->have_posts() ) :
													while ( $loop_homepage->have_posts() ) : $loop_homepage->the_post();
													
														$format = get_post_format();
														
														if ( $format == false )
														{
															?>
																<li>
																	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
																	
																	<span class="date"><?php echo get_the_date(); ?></span>
																</li>
															<?php
														}
														// end if
													
													endwhile;
												endif;
												wp_reset_query();
											?>
										</ul>
									</div>
									<!-- end Last 30 posts -->
									
								    <div class="post-list archives-list archives-tag archives-by-month">
										<h2><?php echo __( 'Archives by month', 'read' ); ?></h2>
										
										<ul>
											<?php
												$args = array(  'format' => 'custom',
																'before' => '<li>',
																'after' => '</li>' );
												
												wp_get_archives( $args );
											?>
										</ul>
								    </div>
								    <!-- end Archives by month -->
									
								    <div class="post-list archives-list archives-tag archives-by-category">
										<h2><?php echo __( 'Archives by category', 'read' ); ?></h2>
										
										<ul>
											<?php
												$categories = get_categories();
												
												foreach ( $categories as $category )
												{
													echo '<li><a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a></li> ';
												}
											?>											
										</ul>
								    </div>
								    <!-- end Archives by category -->
									
								    <div class="post-list archives-list archives-tag archives-by-tag">
										<h2><?php echo __( 'Archives by tag', 'read' ); ?></h2>
										
										<ul>
											<?php
												$tags = get_tags();
												
												foreach ( $tags as $tag )
												{
													echo '<li><a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a></li> ';
												}
											?>											
										</ul>
								    </div>
								    <!-- end Archives by tag -->
								    
								    <div class="post-list archives-list archives-tag archives-by-format">
										<h2><?php echo __( 'Archives by format', 'read' ); ?></h2>
										
										<ul>
											<?php
												$post_formats = get_theme_support( 'post-formats' );
												
												foreach ( $post_formats[0] as $post_format )
												{
													$format_link = get_post_format_link( $post_format );
													
													echo '<li><a href="' . $format_link . '">' . $post_format . '</a></li> ';
												}
											?>
										</ul>
								    </div>
								    <!-- end Archives by format -->
								</div>
								<!-- end .entry-content -->
							</article>
							<!-- end .hentry -->
						<?php
					endwhile;
				endif;
				wp_reset_query();
			?>
			
			<?php
				comments_template( "", true );
			?>
		</div>
		<!-- end .readable-content -->
	</div>
	<!-- end #content -->
</div>
<!-- end #primary -->

<?php
	get_footer();
?>