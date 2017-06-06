<?php
	/**
	 * Template Name: Portfolio 1 column
	 */
?>
<?php if($_GET['ajaxRequest']!=true){get_header();} ?>
<div class="contentCol">
<?php include_once (TEMPLATEPATH . '/title.php');?>  
<?php global $more;	$more = 0;?>
<?php $values = get_post_custom_values("category-include"); $cat=$values[0];  ?>
<?php $catinclude = 'portfolio_category='. $cat ;?>
<?php 
	$temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query(); 
?>
<?php $wp_query->query("post_type=portfolio&". $catinclude ."&paged=".$paged.'&showposts=4'); ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h2 class="entry-title"><?php _e( 'Not Found', 'theme_18033' ); ?></h2>
		<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'theme_18033' ); ?></p>
		<?php get_search_form(); ?>
	</div><!-- #post-0 -->
<?php endif; ?>

<ul class="portfolio">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <?php
      $custom = get_post_custom($post->ID);
      $lightbox = $custom["lightbox-url"][0];
    ?>
	<li class="folio_1">
		<div class="clearfix folioThumbnail">
			<?php if($lightbox!=""){ ?>
				<a class="thumbnail" href="<?php echo $lightbox;?>" rel="fancybox" title="<?php the_title();?>"><?php the_post_thumbnail( 'portfolio-post-thumbnail-xl' ); ?><span class="zoom-icon"></span></a>
			<?php }else{ ?>
				<a class="thumbnail" href="<?php the_permalink() ?>" title="<?php _e('Permanent Link to', 'theme_18033');?> <?php the_title_attribute(); ?>" ><?php the_post_thumbnail( 'portfolio-post-thumbnail-xl' ); ?></a>
			<?php } ?>
			<h4><a href="<?php the_permalink(); ?>" class="animate"><?php $title = the_title('','',FALSE); echo substr($title, 0, 50); ?></a></h4>
			<p><?php $excerpt = get_the_excerpt(); echo my_string_limit_words($excerpt,30);?></p>
            <a href="<?php the_permalink() ?>" class="button animate"><?php _e('read more', 'theme_18033'); ?></a>
		</div>
	</li>
	<?php endwhile; ?>
</ul>
<div class="clear"></div>

<!-- Page navigation -->
<?php if(function_exists('wp_pagenavi')) : ?>
	<?php wp_pagenavi(); ?>
<?php else : ?>
  <?php if ( $wp_query->max_num_pages > 1 ) : ?>
    <nav class="oldernewer">
      <div class="older">
        <?php next_posts_link( __('&laquo; Older Entries', 'theme_18020')) ?>
      </div><!--.older-->
      <div class="newer">
        <?php previous_posts_link(__('Newer Entries &raquo;', 'theme_18020')) ?>
      </div><!--.newer-->
    </nav><!--.oldernewer-->
  <?php endif; ?>
<?php endif; ?>
<!-- Page navigation -->

<?php $wp_query = null; $wp_query = $temp;?>
<!-- end #main -->
</div>