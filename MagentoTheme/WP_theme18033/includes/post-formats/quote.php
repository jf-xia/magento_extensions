			<article id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
			
					<header class="entry-header">
			
					<?php if(is_singular()) : ?>
					
					<h1 class="entry-title"><?php the_title(); ?></h1>
					
					<?php endif; ?>
					
					<?php get_template_part('includes/post-formats/post-meta'); ?>
					
					</header>
					
					<?php $quote =  get_post_meta(get_the_ID(), 'tz_quote', true); ?>
								
					<div class="quote-wrap clearfix">
							
							<blockquote>
									<?php echo $quote; ?>
							</blockquote>
							
					</div>
			
					<p class="post-content">
							<?php the_content(''); ?>
					<!--// .post-content -->
					</p>
					
					<?php get_template_part('includes/post-meta'); ?>
			
			<!--//.post-holder-->  
			</article>