<div id="controls">
    <div id="controlsHolder">
		<a href="#" id="prev"><span></span></a>
		<a href="#" id="next"><span></span></a>
    </div>	
	<div id="previewHolder">
		<div id="inner">
			<ul>
				<?php $posts_counter = 0; ?>
				<?php
					query_posts("post_type=slider&posts_per_page=-1&post_status=publish");
					while ( have_posts() ) : the_post(); $posts_counter++;
				?>
				<?php
					$custom = get_post_custom($post->ID);
					$url = get_post_custom_values("slider-url");
					$sl_thumb = $custom["thumb"][0];
					$sl_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slider-post-thumbnail');
					$tab_title = get_post_custom_values("tab-title");
				?>
				<?php 
					if(has_post_thumbnail( $the_ID) || $sl_thumb!=""){	
						if($sl_thumb!=""){
							echo "<li><a href='".$sl_image_url[0]."' class='thumbnailGallery animate'><img class='animate' src='".$sl_thumb."' alt='".$tab_title[0]."' title='".$posts_counter."' ></a></li>";
						} else{
							echo "<li><a href='".$sl_image_url[0]."' class='thumbnailGallery animate'></a></li>";
						}	 
					} 
				?>
		  		<?php endwhile; ?>
			</ul>
		</div>	
	</div>
	<ul id='galleryDiscription'>
		<?php
			$posts_counter = 0;
			while ( have_posts() ) : the_post();
				echo "<li>".get_the_content()."</li>";
				$posts_counter++;
			endwhile;
	 	?>
	</ul>
</div>