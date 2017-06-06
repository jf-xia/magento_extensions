			<article id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
			
				<header class="entry-header">
				
				<?php if(!is_singular()) : ?>
				
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'theme_5819');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				
				<?php else :?>
				
				<h1 class="entry-title"><?php the_title(); ?></h1>
				
				<?php endif; ?>
				
				<?php get_template_part('includes/post-formats/post-meta'); ?>
				
				</header>
			
				<?php 
			
				if (has_post_thumbnail() ):
				
				$lightbox = get_post_meta(get_the_ID(), 'tz_image_lightbox', TRUE); 
				
				if($lightbox == 'yes') {
					$lightbox = TRUE;
				} else {
					$lightbox = FALSE;
				}
				
				$src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( '9999','9999' ), false, '' ); 
				
			 ?>
			
			<div class="post-thumb clearfix">
				
				<?php
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
				$image = aq_resize( $img_url, 600, 300, true ); //resize & crop img
				?>
			
				<?php if($lightbox) : ?>
					
					<figure class="featured-thumbnail large ">
						<a class="image-wrap thumbnail" rel="fancybox" title="<?php the_title(); ?>" href="<?php echo $src[0]; ?>"><?php the_post_thumbnail("post-thumbnail-xl") ?><span class="zoom-icon"></span></a>
					</figure>
					<div class="clear"></div>
                    
						<span class="overlay">
							<span class="arrow"></span>
						</span>
					
				<?php else: ?>
				
					<figure class="featured-thumbnail large">
						<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" />
					</figure>
					<div class="clear"></div>
					
				<?php endif; ?>
				
			</div>
            
				<?php endif; ?>
				
				<!--BEGIN .entry-content -->
				<div class="entry-content">
						<?php the_content(''); ?>
				<!--END .entry-content -->
				</div>
        
			<!--// .post-holder-->  
			</article>