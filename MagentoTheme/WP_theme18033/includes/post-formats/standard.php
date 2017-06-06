<article id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
	<div class="content">
	<?php get_template_part('includes/post-formats/post-meta'); ?>
	<?php 
		$post_image_size = of_get_option('post_image_size');
		if($post_image_size=='' || $post_image_size=='normal'){
	  		if(has_post_thumbnail()) {
	    		echo '<figure class="thumbnail"><a href="'; the_permalink(); echo '" >';
	    		echo the_post_thumbnail();
	    		echo '</a></figure>';
	    	}
	 	} else {
	  		if(has_post_thumbnail()) {
	    		echo '<figure class="thumbnail"><a href="'; the_permalink(); echo '">';
				echo the_post_thumbnail();
	    		echo '</a></figure>';
	    	}
		} ?>

	<div class="post-content">
		<?php 
			$post_excerpt = of_get_option('post_excerpt');
		 	if ($post_excerpt=='true' || $post_excerpt=='') { 
	 	?>
	    	<div class="excerpt"><?php $content = get_the_content(); echo my_string_limit_words($content,33);?></div>
    		<a href="<?php the_permalink() ?>" class="button"><?php _e('read more', 'theme_18033'); ?></a>
	  	<?php } ?>
	</div>
	</div>         	
	
	<footer>
	<?php //the_tags('Tags: ', ', ', ''); ?>
	</footer>
</article>