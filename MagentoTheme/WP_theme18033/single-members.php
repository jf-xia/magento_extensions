<?php if($_GET['ajaxRequest']!=true){get_header();} ?>
<div id="content">
	<div class="indent">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
      <article class="single-post">
        <header>
          <h2><?php the_title(); ?></h2>
        </header>
        <?php if(has_post_thumbnail()) {
					echo '<div class="thumbnail no-hover"><div class="img-wrap">'; the_post_thumbnail(''); echo '</div></div>';
					}
				?>
        <div class="post-content extra-wrap">
          <?php the_content(); ?>
        </div><!--.post-content-->
      </article>

    </div><!-- #post-## -->

  <?php endwhile; /* end loop */ ?>
	</div>
</div><!--#content-->