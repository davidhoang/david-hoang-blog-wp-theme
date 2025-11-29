			</div>
        </section>
		
        <footer class="site-footer wrapper" role="contentinfo">
			<div class="row">
				<div id="supplementary" class="row-fluid">
					<?php
						$footer_widget_locations = get_option( 'footer_widget_locations', 'No' );
						
						if ( $footer_widget_locations == 'Yes' )
						{
							get_template_part( 'footer', 'widgets' );
						}
					?>
				</div>
				<div class="site-info">
					<?php
						$copyright_text = stripcslashes( get_option( 'copyright_text', "" ) );
						
						echo $copyright_text;
					?>
				</div>
			</div>
        </footer>
    </div>
	
	<?php
		wp_footer();
	?>
</body>
</html>