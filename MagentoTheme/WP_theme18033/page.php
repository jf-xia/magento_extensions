<?php if($_GET['ajaxRequest']!=true){get_header();} ?>
<div id="content" class="<?php echo of_get_option('blog_sidebar_pos') ?>">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
      <article class="post-holder">
        <h1><?php the_title(); ?></h1>
        <?php if(has_post_thumbnail()) {
					echo '<a href="'; the_permalink(); echo '">';
					echo '<figure class="featured-thumbnail"><span class="img-wrap">'; the_post_thumbnail(); echo '</span></figure>';
					echo '</a>';
					}
				?>
        <div id="page-content">
          <?php the_content(); ?>
          <div class="pagination">
            <?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
          </div><!--.pagination-->
        </div><!--#pageContent -->
      </article>
    </div><!--#post-# .post-->

  <?php endwhile; ?>
</div><!--#content-->
<?php //get_sidebar(); ?>
