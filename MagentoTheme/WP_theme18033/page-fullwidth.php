<?php
	/**
	 * Template Name: Fullwidth Page
	 */
?>
<?php if($_GET['ajaxRequest']!=true){get_header();} ?>
<div class="contentCol">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    	<?php 
    		if(has_post_thumbnail()) {
          		echo '<a href="'.the_permalink().'">';
          		echo '<figure class="thumbnail">'.the_post_thumbnail().'</figure>';
          		echo '</a>';
          	}
        ?>
    	<?php the_content(); ?>
    <?php endwhile; ?>
</div>