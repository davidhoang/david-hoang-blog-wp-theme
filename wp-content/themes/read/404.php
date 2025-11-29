<?php
	/* 404 Page Template */
?>

<?php
	get_header();
?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<div class="not-found">
			<h1><?php echo __( '404', 'read' ); ?></h1>
			
			<p><?php echo __( 'The page you are looking for was not found!', 'read' ); ?></p>
		</div>
		<!-- end .not-found -->
	</div>
	<!-- end #content -->
</div>
<!-- end #primary -->

<?php
	get_footer();
?>