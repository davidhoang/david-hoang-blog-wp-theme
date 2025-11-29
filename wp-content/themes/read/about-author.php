<aside class="about-author">
	<h3><?php echo __( 'ABOUT THE AUTHOR', 'read' ); ?></h3>
	
	<div class="row-fluid author-bio">
		<div class="span2 author-img">
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
				<?php
					echo get_avatar( get_the_author_meta( 'user_email' ), 226, "", get_the_author_meta( 'display_name' ) );
				?>
			</a>
		</div>
		<!-- end .author-img -->
		
		<div class="span10 author-info">
			<h4 class="author-name"><?php the_author(); ?></h4>
			
			<p>
				<?php	
					echo get_the_author_meta( 'description' );
				?>
			</p>
		</div>
		<!-- end .author-info -->
	</div>
	<!-- end .author-bio -->
</aside>
<!-- end .about-author -->