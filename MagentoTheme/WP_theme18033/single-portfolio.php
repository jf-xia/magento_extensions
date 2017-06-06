<?php if($_GET['ajaxRequest']!=true){get_header();} ?>
<div class="portfolio contentCol">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php
  $custom = get_post_custom($post->ID);
  $lightbox = $custom["lightbox-url"][0];
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<article class="single-post">
	<header>
		<h2><?php the_title(); ?></h2>
	</header>
	<?php if($lightbox!=""){ ?>
		<figure class="thumbnail marginB"><a class="thumbnailGallery" href="<?php echo $lightbox;?>" title="<?php the_title();?>" rel="fancybox"><?php the_post_thumbnail( 'post-thumbnail-xl' ); ?><span class="zoom-icon"></span></a></figure>
	<?php }else{ ?>
		<div class="thumbnail"><?php the_post_thumbnail( 'post-thumbnail-xl' ); ?></div>
	<?php } ?>
	<div class="post-content">
		<?php the_content(); ?>
		<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
	</div><!--.post-content-->
	</article>
	</div><!-- #post-## -->
<?php comments_template( '', true ); ?>
  <?php endwhile; /* end loop */ ?>
</div>
  