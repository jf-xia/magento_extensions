<?php if($_GET['ajaxRequest']!=true){get_header();} ?>
<div class="singl_blog contentCol">
<?php //include_once (TEMPLATEPATH . '/title.php'); ?>  
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
      <article class="post-holder single-post">
		<?php get_template_part('includes/post-formats/post-meta'); ?>
        <?php $single_image_size = of_get_option('single_image_size'); ?>
				<?php if($single_image_size=='' || $single_image_size=='normal'){ ?>
          <?php if(has_post_thumbnail()) {
            echo '<figure class="singleThumbnail">'; the_post_thumbnail("post-thumbnail-xl"); echo '</figure>';
            }
          ?>
        <?php } else { ?>
          <?php if(has_post_thumbnail()) {
            echo '<figure class="singleThumbnail">'; the_post_thumbnail("post-thumbnail-xl"); echo '</figure>';
            }
          ?>
        <?php } ?>
        <div class="post-content">
          <?php the_content(); ?>
          <?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
        </div><!--.post-content-->
      </article>
    </div><!-- #post-## -->    
    <nav class="oldernewer">
      <div class="older">
        <?php //previous_post_link('%link', __('&laquo; Previous post', 'theme_5819')) ?>
      </div><!--.older-->
      <div class="newer">
        <?php //next_post_link('%link', __('Next Post &raquo;', 'theme_5819')) ?>
      </div><!--.newer-->
    </nav><!--.oldernewer-->

    <?php comments_template( '', true ); ?>
  <?php endwhile; /* end loop */ ?>
</div><!--#content-->
</div>
</div>
<?php get_sidebar(); ?>