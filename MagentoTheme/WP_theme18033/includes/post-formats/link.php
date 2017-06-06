 			<article id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
			
				<?php $url =  get_post_meta(get_the_ID(), 'tz_link_url', true); ?>
							
					<header class="entry-header">
					
						<h2 class="entry-title">
								<a target="_blank" href="<?php echo $url; ?>" title="<?php _e('Permalink to:', 'framework');?> <?php echo $url; ?>"><span><?php the_title(); ?></span></a>
						</h2>
						
						<?php get_template_part('includes/post-formats/post-meta'); ?>
						
					</header>
					
					
			
					<div class="content">
							<?php the_content(''); ?>
					<!--// .content -->
					</div>
			
			<!--//.post-holder-->  
			</article>