<?php
	$footer_widget_columns = get_option( 'footer_widget_columns', '4 Columns' );
	
	echo '<div class="footer-widgets-container" style="display: flex; flex-wrap: wrap; gap: 20px;">';
	
	if ( $footer_widget_columns == '3 Columns' )
	{
		get_template_part( 'footer', '3_columns' );
	}
	else
	{
		get_template_part( 'footer', '4_columns' );
	}
	
	echo '</div>';
?>