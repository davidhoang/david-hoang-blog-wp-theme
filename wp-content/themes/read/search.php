<?php
	get_header();
?>
                <!-- #primary -->
                <div id="primary" class="site-content">
                    <!-- #content -->
                    <div id="content" role="main">
						<!-- .search-results -->
                        <div class="post-archive blog-posts readable-content">
                            <!-- .page-header -->	
                            <header class="page-header">
								<h1 class="page-title"><?php echo __( 'Search Results', 'read' ); ?> <span class="on"><?php echo __( '&#8594;', 'read' ); ?></span> <span><?php echo get_search_query(); ?></span></h1>
                            </header>
                            <!-- .page-header -->
							<?php
								if ( have_posts() ) :
									while ( have_posts() ) : the_post();
									
										$format = get_post_format();
										
										if ( $format != false )
										{
											get_template_part( 'format', $format );
										}
										else
										{
											?>
												<!-- .post -->
												<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
													<!-- .entry-header -->
													<header class="entry-header">
														<h1 class="entry-title">
															<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
														</h1>
													</header>
													<!-- .entry-header -->
													<!-- .entry-meta --> 
													<footer class="entry-meta">
														<?php echo __( 'posted in', 'read' ); ?>
														<?php the_category( ', ' ); ?>
														<?php echo __( 'on', 'read' ); ?> <a href="<?php the_permalink(); ?>" title="<?php the_time(); ?>" rel="bookmark"><time class="entry-date" datetime="2012-11-09T23:15:57+00:00"><?php echo get_the_date(); ?></time></a>
														<span class="comments-link">
															<?php comments_popup_link( __( '0 Comment', 'read' ), __( '1 Comment', 'read' ), __( '% Comments', 'read' ) ); ?>
														</span>
														<span class="by-author"> <?php echo __( 'by', 'read' ); ?>
															<span class="author vcard">
																<a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo __( 'View all posts by ', 'read' ) . get_the_author(); ?>" rel="author"><?php the_author(); ?></a>
															</span>
														</span>				
													</footer>
													<!-- .entry-meta -->
												</article>
												<!-- .post -->
											<?php
										}
									endwhile;
								else :
									?>
										<div class="not-found">
											<h2><?php echo __( 'Nothing Found', 'read' ); ?></h2>
											<p><?php echo __( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'read' ); ?></p>
											<?php
												get_search_form();
											?>
										</div>
									<?php
								endif;
								wp_reset_query();
							?>
							<?php
								get_template_part( 'part', 'pagination' );
							?>
						</div>
						<!-- .search-results -->
                    </div>
                    <!-- #content -->
                </div>
                <!-- #primary -->
<?php
	get_footer();
?>