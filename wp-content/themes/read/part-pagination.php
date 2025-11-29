<?php

	$read__pagination = get_option('pagination', 'Yes');
	
	if ($read__pagination == 'No')
	{
		?>
			<nav class="navigation" role="navigation">
				<div class="nav-previous">
					<?php
						next_posts_link(__('&#8592; Older Posts', 'read'));
					?>
				</div> <!-- .nav-previous -->
				<div class="nav-next">
					<?php
						previous_posts_link(__('Newer Posts &#8594;', 'read'));
					?>
				</div> <!-- .nav-next -->
			</nav> <!-- .navigation -->
		<?php
	}
	else
	{
		the_posts_pagination(
			array(
				'screen_reader_text' => esc_html__('Posts Navigation', 'read'),
				'prev_text'          => esc_html__('Prev',             'read'),
				'next_text'          => esc_html__('Next',             'read'),
				'end_size'           => 1,
				'mid_size'           => 1,
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'read') . ' </span>'
			)
		);
	}
