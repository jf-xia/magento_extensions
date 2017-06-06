       
        <!--BEGIN .hentry -->
        <article id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
				
						<header class="entry-header">
						
						<?php get_template_part('includes/post-formats/post-meta'); ?>
						
						</header>
				
            <!--BEGIN .entry-content -->
            <div class="entry-content">
                <?php the_content('<span>Continue Reading</span>'); ?>
            <!--END .entry-content -->
            </div>
        
        <!--END .hentry-->  
        </article>