<?php

	function read__post_type__portfolio()
	{
		register_post_type(
			'portfolio',
			array(
				'label'         => esc_html__('Portfolio', 'read'),
				'public'        => true,
				'menu_position' => 5,
				'supports'      => array('title', 'editor', 'thumbnail', 'comments', 'revisions')
			)
		);
	}
	
	add_action('init', 'read__post_type__portfolio');


/* ============================================================================================================================================= */


	function portfolio_updated_messages( $messages )
	{
		global $post, $post_ID;
		
		$messages['portfolio'] = array( 0 => "", // Unused. Messages start at index 1.
										1 => sprintf( __( '<strong>Updated.</strong> <a target="_blank" href="%s">View</a>', 'read' ), esc_url( get_permalink( $post_ID) ) ),
										2 => __( 'Custom field updated.', 'read' ),
										3 => __( 'Custom field deleted.', 'read' ),
										4 => __( 'Updated.', 'read' ),
										// translators: %s: date and time of the revision
										5 => isset( $_GET['revision'] ) ? sprintf( __( 'Restored to revision from %s', 'read' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
										6 => sprintf( __( '<strong>Published.</strong> <a target="_blank" href="%s">View</a>', 'read' ), esc_url( get_permalink( $post_ID) ) ),
										7 => __( 'Saved.', 'read' ),
										8 => sprintf( __( 'Submitted. <a target="_blank" href="%s">Preview</a>', 'read' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
										9 => sprintf( __( 'Scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview</a>', 'read' ),
										// translators: Publish box date format, see http://php.net/date
										date_i18n( __( 'M j, Y @ G:i', 'read' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID) ) ),
										10 => sprintf( __( '<strong>Item draft updated.</strong> <a target="_blank" href="%s">Preview</a>', 'read' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID) ) ) ) );
	
		return $messages;
	}
	// end portfolio_updated_messages
	
	add_filter( 'post_updated_messages', 'portfolio_updated_messages' );
	
	
	function portfolio_columns( $pf_columns )
	{
		$pf_columns = array('cb' => '<input type="checkbox">',
							'title' => __( 'Title', 'read' ),
							'pf_featured_image' => __( 'Featured Image', 'read' ),
							'departments' => __( 'Departments', 'read' ),
							'pf_short_description' => __( 'Short Description', 'read' ),
							'portfolio_type' => __( 'Type', 'read' ),
							'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>',
							'date' => __( 'Date', 'read' ) );
		
		return $pf_columns;
	}
	// end portfolio_columns
	
	add_filter( 'manage_edit-portfolio_columns', 'portfolio_columns' );
	
	
	function portfolio_custom_columns( $pf_column )
	{
		global $post, $post_ID;
		
		switch ( $pf_column )
		{
			case 'pf_featured_image':
			
				if ( has_post_thumbnail() )
				{
					the_post_thumbnail( 'thumbnail' );
				}
				
			break;
			
			case 'departments':
			
				$taxon = 'department';
				$terms_list = get_the_terms( $post_ID, $taxon );
				
				if ( ! empty( $terms_list ) )
				{
					$out = array();
					
					foreach ( $terms_list as $term_list )
					{
						$out[] = '<a href="edit.php?post_type=portfolio&department=' .$term_list->slug .'">' .$term_list->name .' </a>';
					}
					
					echo join( ', ', $out );
				}
				
			break;
			
			case 'pf_short_description':
			
				$pf_short_description = stripcslashes( get_option( $post->ID . 'pf_short_description', "" ) );
				
				echo $pf_short_description;
				
			break;
			
			case 'portfolio_type':
			
				$pf_type = get_option( $post->ID . 'pf_type', 'Standard' );
				
				if ( $pf_type == 'Lightbox Image' )
				{
					echo 'Lightbox Gallery';
				}
				else
				{
					echo $pf_type;
				}
				
			break;
		}
		// end switch
	}
	// end portfolio_custom_columns
	
	add_action( 'manage_posts_custom_column',  'portfolio_custom_columns' );
	
	
	function portfolio_taxonomy()
	{
		$labels_dep = array('name' => __( 'Departments', 'read' ),
							'singular_name' => __( 'Department', 'read' ),
							'search_items' =>  __( 'Search', 'read' ),
							'all_items' => __( 'All', 'read' ),
							'parent_item' => __( 'Parent Department', 'read' ),
							'parent_item_colon' => __( 'Parent Department:', 'read' ),
							'edit_item' => __( 'Edit', 'read' ),
							'update_item' => __( 'Update', 'read' ),
							'add_new_item' => __( 'Add New', 'read' ),
							'new_item_name' => __( 'New Department Name', 'read' ),
							'menu_name' => __( 'Departments', 'read' ) );

		register_taxonomy(  'department',
							array( 'portfolio' ),
							array( 'hierarchical' => true,
							'labels' => $labels_dep,
							'show_ui' => true,
							'public' => true,
							'query_var' => true,
							'rewrite' => array( 'slug' => 'department' ) ) );
							
							
		$labels_tag = array('name' => __( 'Portfolio Tags', 'read' ),
							'singular_name' => __( 'Portfolio Tag', 'read' ),
							'search_items' =>  __( 'Search', 'read' ),
							'all_items' => __( 'All', 'read' ),
							'parent_item' => __( 'Parent Portfolio Tag', 'read' ),
							'parent_item_colon' => __( 'Parent Portfolio Tag:', 'read' ),
							'edit_item' => __( 'Edit', 'read' ),
							'update_item' => __( 'Update', 'read' ),
							'add_new_item' => __( 'Add New', 'read' ),
							'new_item_name' => __( 'New Portfolio Tag Name', 'read' ),
							'menu_name' => __( 'Portfolio Tags', 'read' ) );

		register_taxonomy(  'portfolio_tags',
							array( 'portfolio' ),
							array( 'hierarchical' => false,
							'labels' => $labels_tag,
							'show_ui' => true,
							'public' => true,
							'query_var' => true,
							'rewrite' => array( 'slug' => 'portfolio_tags' ) ) );
	}
	// end portfolio_taxonomy
	
	add_action( 'init', 'portfolio_taxonomy' );
	
	
	function only_show_departments()
	{
		global $typenow;
		
		if ( $typenow == 'portfolio' )
		{
			$filters = array( 'department' );
			
			foreach ( $filters as $tax_slug )
			{
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms( $tax_slug );
			
				echo '<select name="' .$tax_slug .'" id="' .$tax_slug .'" class="postform">';
				echo '<option value="">' . __( 'Show All', 'read' ) . ' ' .$tax_name .'</option>';
				
				foreach ( $terms as $term )
				{
					echo '<option value='. $term->slug, @$_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				
				echo '</select>';
			}
			// end foreach
		}
		// end if
	}
	// end only_show_departments

	add_action( 'restrict_manage_posts', 'only_show_departments' );

?>