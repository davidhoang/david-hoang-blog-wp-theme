<?php
	$footer_widget_columns = get_option( 'footer_widget_columns', '4 Columns' );
	
	if ( $footer_widget_columns == '3 Columns' )
	{
		get_template_part( 'footer', '3_columns' );
	}
	else
	{
		get_template_part( 'footer', '4_columns' );
	}
?>