								<article id="post-0" class="post no-results not-found clearfix">
									<?php
										if ( current_user_can( 'edit_posts' ) ) :
											?>
												<header class="entry-header">
													<h1 class="entry-title"><?php _e( 'No posts to display', 'read' ); ?></h1>
												</header>
												<!-- end .entry-header -->
												
												<div class="entry-content">
													<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'read' ), admin_url( 'post-new.php' ) ); ?></p>
												</div>
												<!-- end .entry-content -->
											<?php
										else :
											?>
												<header class="entry-header">
													<h1 class="entry-title"><?php _e( 'Nothing Found', 'read' ); ?></h1>
												</header>
												<!-- end .entry-header -->
												
												<div class="entry-content">
													<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'read' ); ?></p>
													
													<?php
														get_search_form();
													?>
												</div>
												<!-- end .entry-content -->
											<?php
										endif;
									?>
                                </article>