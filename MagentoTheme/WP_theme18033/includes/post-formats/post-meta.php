<?php $post_meta = of_get_option('post_meta'); ?>
	<?php if ($post_meta=='true' || $post_meta=='') { ?>
		<div class="post-meta">
			<?php 
			 	$category = get_the_category();
				$category_id = get_cat_ID($category[0]->cat_name);
				$category_link = get_category_link($category_id);
			 	if(strlen($category_link)!=0){ 
			?>
			<time class="blogDate" datetime="<?php the_time('Y-m-d\TH:i'); ?>">
				<?php 
					$theTime = get_the_time('j M');
					$dataLenght = stripos($theTime, " ");
					$data=substr($theTime, 0, $dataLenght)."<span>".substr($theTime, $dataLenght, $dataLenght+3)."</span>";
					echo $data;
				 ?>
		 	</time>
		 	<h3><a class="animate" href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
		 	<div class="post-meta-date">
		 		<div class="post-meta-div">Posted by <?php the_author_posts_link() ?></div>
		 		<div class="comments"><?php comments_popup_link('0 Comment(s)', '1 Comment(s)', '% Comment(s)', 'comment_link', 'Comments are closed'); ?></div>
		 	</div>
		 	<?php }; ?>
	 	</div>
	<?php } ?>