<?php
	get_header();
?>

                <div id="primary" class="site-content">
                    <div id="content" role="main">
						<div class="readable-content row-fluid page">
							<?php
								if ( have_posts() ) :
									while ( have_posts() ) : the_post();
										?>
											<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
												<header class="entry-header">
													<h1 class="entry-title"><?php the_title(); ?></h1>
												</header>
												<!-- end .entry-header -->
												
												<nav class="nav-single row-fluid">
													<div class="nav-previous span6">
														<?php
															previous_image_link( false, '<span class="meta-nav">&#8592; ' . __( 'PREVIOUS IMAGE', 'read' ) . '</span>' );
														?>
													</div>
													
													<div class="nav-next span6">
														<?php
															next_image_link( false, '<span class="meta-nav">' . __( 'NEXT IMAGE', 'read' ) . ' &#8594;</span>' );
														?>
													</div>
												</nav>
												<!-- .nav-single -->
												
												<!-- .entry-content -->
												<div class="entry-content">
													<div class="entry-attachment">
														<div class="attachment">
															<?php
																/**
																 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
																 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
																 */
																 
																$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
																
																foreach ( $attachments as $k => $attachment ) :
																	if ( $attachment->ID == $post->ID )
																		break;
																endforeach;

																$k++;
																
																// If there is more than 1 attachment in a gallery
																
																if ( count( $attachments ) > 1 ) :
																	if ( isset( $attachments[ $k ] ) ) :
																		// get the URL of the next image attachment
																		$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
																	else :
																		// or get the URL of the first image attachment
																		$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
																	endif;
																else :
																	// or, if there's only 1 image, get the URL of the image
																	$next_attachment_url = wp_get_attachment_url();
																endif;
															?>
															
															<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
															$attachment_size = apply_filters( 'twentytwelve_attachment_size', array( 960, 960 ) );
															echo wp_get_attachment_image( $post->ID, $attachment_size );
															?></a>

															<?php
																if ( ! empty( $post->post_excerpt ) ) :
																	?>
																		<div class="entry-caption">
																			<?php
																				the_excerpt();
																			?>
																		</div>
																	<?php
																endif;
															?>
														</div>
														<!-- .attachment -->
													</div>
													<!-- .entry-attachment -->
													
													<div class="entry-description">
														<?php
															the_content();
														?>
														
														<?php
															wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'read' ), 'after' => '</div>' ) );
														?>
													</div>
													<!-- .entry-description -->
												</div>
												<!-- .entry-content -->
											</article>
											<!-- .hentry -->
										<?php
									endwhile;
								endif;
								wp_reset_query();
							?>
							<?php
								comments_template( "", true );
							?>
						</div>
						<!-- .row -->
                    </div>
                    <!-- #content -->
                </div>
                <!-- #primary -->
				
<?php
	get_footer();
?>