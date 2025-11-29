( function( $ )
{
	wp.customize( 'blogname', function( value )
	{
		value.bind( function( to )
		{
			$( '.site-title a' ).html( to );
		} );
	} );
	
	
	wp.customize( 'blogdescription', function( value )
	{
		value.bind( function( to )
		{
			$( '.site-description' ).html( to );
		} );
	} );


	wp.customize( 'setting_link_color', function( value )
	{
		value.bind( function( to )
		{
			$( 'a' ).css( 'color', to );
		} );
	} );
	
	
	wp.customize( 'setting_link_hover_color', function( value )
	{
		value.bind( function( to )
		{
			$( 'a:hover' ).css( 'color', to );
		} );
	} );
	
	
	wp.customize( 'setting_menu_active_color', function( value )
	{
		value.bind( function( to )
		{
			$( '.main-navigation ul .current_page_item > a, .main-navigation ul .current-menu-item > a' ).css( 'color', to );
		} );

	} );
	
	
 	wp.customize( 'setting_text_logo_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			$( 'h1.site-title, h1.site-title a' ).css( 'font-family', '"' + to + '"' );
		} );
	} );
	
	
 	wp.customize( 'setting_heading_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			$( 'h1, h2, h3, h4, h5, h6' ).css( 'font-family', '"' + to + '"' );
		} );
	} );
	
	
 	wp.customize( 'setting_menu_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			$( '.main-navigation ul li' ).css( 'font-family', '"' + to + '"' );
		} );
	} );
	
	
 	wp.customize( 'setting_content_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			$( 'html' ).css( 'font-family', '"' + to + '"' );
		} );
	} );
	
} )( jQuery );