<div id="galleryHolder">
	<div id='imgSpinner'><div></div></div>
	<div id="imageHolder">
		<?php 
			$posts_counter = 0;
			query_posts("post_type=slider&posts_per_page=-1&post_status=publish");
			while ( have_posts() ) : the_post();
				if($posts_counter==0){
					$sl_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slider-post-thumbnail');
					$tab_title = get_post_custom_values("tab-title");
					echo "<img src='".$sl_image_url[0]."' alt='".$tab_title[0]."'>";
				}
				$posts_counter++;
			endwhile;
		?>
	</div>
</div>
