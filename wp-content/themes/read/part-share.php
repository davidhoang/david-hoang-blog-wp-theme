<div class="share-links">
	<a><?php echo __( 'SHARE', 'read' ); ?></a>
	
	
	<div class="share-wrap">
		<div class="facebook-wrap">
			<div id="fb-root"></div>
			
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.3";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			
			<div class="fb-share-button" data-layout="button_count"></div>
		</div>
		
		
		<div class="twitter-wrap">
			<?php
				$url_twitter_share = 'https://twitter.com/share';
			?>
			
			<a href="<?php echo $url_twitter_share; ?>" class="twitter-share-button"><?php echo __( 'Tweet', 'read' ); ?></a>
			
			<script>
				!function(d, s, id)
				{
					var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}
				}(document, "script", "twitter-wjs");
			</script>
		</div>
		
		
		<div class="google-wrap">
			<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" data-size="medium"></div>
			
			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
			  (function()
			  {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</div>
		
		
		<div class="pinterest-wrap">
			<?php
				if ( has_post_thumbnail() )
				{
					$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				}
				else
				{
					$full_image_url[0] = "";
				}
			?>
			
			<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $full_image_url[0]; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal"><img src="//assets.pinterest.com/images/PinExt.png" alt="Pin It" title="Pin It"></a>
			
			<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
		</div>
	</div>
</div>