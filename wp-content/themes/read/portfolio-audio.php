										<!-- portfolio-item - lightbox audio -->
										<?php
											$terms = get_the_terms( $post->ID, 'department' );
											$on_draught = "";
											
											if ( $terms && ! is_wp_error( $terms ) ) :
												
												$draught_links = array();
												
												foreach ( $terms as $term )
												{
													$draught_links[] = $term->slug;
												}
												
												$on_draught = join( " ", $draught_links );
												
											endif;
											
											$pf_thumb_size = get_option( $post->ID . 'pf_thumb_size' );
										?>
										
										<div id="post-<?php the_ID(); ?>" class="audio hentry <?php echo $on_draught; ?> <?php echo $pf_thumb_size; ?>">
											<div class="media-box">
												<?php
													if ( has_post_thumbnail() )
													{
														if ( $pf_thumb_size == 'x2' )
														{
															the_post_thumbnail( 'portfolio_image_2x', array( 'alt' => get_the_title(), 'title' => "" ) );
														}
														else
														{
															the_post_thumbnail( 'portfolio_image_1x', array( 'alt' => get_the_title(), 'title' => "" ) );
														}
														// end if
													}
													// end if
												?>
												
												<div class="mask">
													<div class="portfolio-info">
														<h3><?php the_title(); ?></h3>
														
														<?php
															$pf_short_description = stripcslashes( get_option( $post->ID . 'pf_short_description' ) );
														?>
														
														<p class="category"><?php echo $pf_short_description; ?></p>
													</div>
													<!-- end .portfolio-info -->
													
													<!-- lightbox - audio --> 
													<?php
														the_content();
													?>
													<!-- end lightbox - audio -->
												</div>
												<!-- end .mask -->
											</div>
											<!-- end .media-box -->
										</div>
										<!-- end portfolio-item - lightbox audio -->