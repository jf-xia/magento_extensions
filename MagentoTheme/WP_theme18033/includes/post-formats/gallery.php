
			<!--BEGIN .hentry -->
			<article id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
			
				<header class="entry-header">
				
				<?php if(!is_singular()) : ?>
				
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'theme_5819');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				
				<?php else :?>
				
				<h1 class="entry-title"><?php the_title(); ?></h1>
				
				<?php endif; ?>
				
				<?php get_template_part('includes/post-formats/post-meta'); ?>
				
				</header>
				
				
				<?php $random = my_framework_random(10); ?>
			
				<script type="text/javascript">
					jQuery(function(){
						jQuery('#gallery_post_<?php echo $random; ?>').cycle({
							pause: 1,
							fx: 'fade',
							timeout: 5000,
							pager:   '#g_pagination_<?php echo $random; ?>',
							pagerAnchorBuilder: pagerFactory
						});
						
						function pagerFactory(idx, slide) {
							return '<li><a href="#">'+(idx+1)+'</a></li>';
						};
						
					});
				</script>
			
				<!--BEGIN .slider -->
					
					<div id="gallery_post_<?php echo $random ?>" class="gallery_post">
					
					<?php 
							$args = array(
									'orderby'		 => 'menu_order',
									'post_type'      => 'attachment',
									'post_parent'    => get_the_ID(),
									'post_mime_type' => 'image',
									'post_status'    => null,
									'numberposts'    => -1,
							);
							$attachments = get_posts($args);
					?>
							
							<?php if ($attachments) : ?>
							
							<?php foreach ($attachments as $attachment) : ?>
									
									<?php 
									$attachment_url = wp_get_attachment_image_src( $attachment->ID, 'full' );
									$url = $attachment_url['0'];
									$image = aq_resize($url, 600, 300, true);
									?>
									
									<div class="g_item">
									<figure class="featured-thumbnail">
										<img 
										alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>"
										src="<?php echo $image ?>"
										width="600"
										height="300"
										/>
									</figure>
									</div>
							
							<?php endforeach; ?>
							
							<?php endif; ?>

					<!--END .slider -->
					</div>
					
					<div class="g_pagination">
						<ul id="g_pagination_<?php echo $random ?>"></ul>
					</div>
			
					<!--BEGIN .entry-content -->
					<div class="entry-content">
							<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
			
			<!--END .hentry-->  
			</article>