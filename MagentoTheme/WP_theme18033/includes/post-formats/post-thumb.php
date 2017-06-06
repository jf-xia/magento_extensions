<?php if(!is_singular()) : ?>

<?php $post_image_size = of_get_option('post_image_size'); ?>
<?php if($post_image_size=='' || $post_image_size=='normal'){ ?>
	<?php if(has_post_thumbnail()) { ?>
		<figure class="featured-thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></figure>
	<?php } ?>
	<?php } else { ?>
	<?php if(has_post_thumbnail()) { ?>
		<?php
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
		$image = aq_resize( $img_url, 600, 300, true ); //resize & crop img
		?>
		<figure class="featured-thumbnail large">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /></a>
		</figure>
		<div class="clear"></div>
	<?php } ?>
<?php } ?>

<?php else :?>

<?php $single_image_size = of_get_option('single_image_size'); ?>
<?php if($single_image_size=='' || $single_image_size=='normal'){ ?>
	<?php if(has_post_thumbnail()) { ?>
		<figure class="featured-thumbnail"><?php the_post_thumbnail(); ?></figure>
	<?php } ?>
	<?php } else { ?>
	<?php if(has_post_thumbnail()) { ?>
		<?php
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
		$image = aq_resize( $img_url, 600, 300, true ); //resize & crop img
		?>
		<figure class="featured-thumbnail large">
			<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" />
		</figure>
		<div class="clear"></div>
	<?php } ?>
<?php } ?>

<?php endif; ?>