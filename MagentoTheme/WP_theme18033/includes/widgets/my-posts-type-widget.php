<?php
/*
// =============================== My advanced cycle widget ======================================*/
class MY_PostsTypeWidget extends WP_Widget {

function MY_PostsTypeWidget() {
		$widget_ops = array('classname' => 'my_posts_type_widget', 'description' => __('Show custom posts'));
		$control_ops = array('width' => 500, 'height' => 350);
	    parent::WP_Widget(false, __('My - Advanced Cycle'), $widget_ops, $control_ops);
}

/**
 * Displays custom posts widget on blog.
 */
function widget($args, $instance) {
	global $post;
	$post_old = $post; // Save the post object.
	
	extract( $args );
	$limit = apply_filters('widget_title', $instance['excerpt_length']);
	
  $valid_sort_orders = array('date', 'title', 'comment_count', 'rand');
  if ( in_array($instance['sort_by'], $valid_sort_orders) ) {
    $sort_by = $instance['sort_by'];
    $sort_order = (bool) $instance['asc_sort_order'] ? 'ASC' : 'DESC';
  } else {
    // by default, display latest first
    $sort_by = 'date';
    $sort_order = 'DESC';
  }
	
	// Get array of post info.
	
	$args = array(
		'showposts' => $instance["num"],
		'post_type' => $instance['posttype'],
		'orderby' => $sort_by,
		'order' => $sort_order,
		'category_name' => $category,
		'tax_query' => array(
		 'relation' => 'AND',
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => array('post-format-aside', 'post-format-gallery', 'post-format-link', 'post-format-image', 'post-format-quote', 'post-format-audio', 'post-format-video'),
				'operator' => 'NOT IN'
			)
		)
	);
	
  $cat_posts = new WP_Query($args);
	
	echo $before_widget;
	
	// Widget title
	// If title exist.
	if( $instance["title"] ) {
	echo $before_title;
		echo $instance["title"];
	echo $after_title;
    }

	// Posts list
    if($instance['container_class']==""){
	echo "<ul class='post_list'>\n";
	}else{
    echo "<ul class='post_list " .$instance['container_class'] ."'>\n";
    }
	
	$limittext = $limit;
	$posts_counter = 0;
	while ( $cat_posts->have_posts() )
	{
		$cat_posts->the_post(); $posts_counter++;
	?>
    <?php if ($instance['posttype'] == "testi") {
      $custom = get_post_custom($post->ID);
      $testiname = $custom["testimonial-name"][0];
      $testiurl = $custom["testimonial-url"][0];
    }
    $thumb = get_post_thumbnail_id();
      $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL	
      $image = aq_resize( $img_url, $instance['thumb_w'], $instance['thumb_h'], true ); //resize & crop img
    ?>
		<li class="cat_post_item-<?php echo $posts_counter; ?> clearfix">
			<?php if ($instance["thumb"]) : ?>
				<figure class="featured-thumbnail">
			  <?php if ( $instance['thumb_as_link'] ) : ?>
						<a href="<?php the_permalink() ?>">
					<?php endif; ?>
					<?php if($instance['thumb_w']!=="" || $instance['thumb_h']!==""){ ?>
						<img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" />
					<?php }else{?>
						<?php the_post_thumbnail(); ?>
					<?php }?>
					<?php if ( $instance['thumb_as_link'] ) : ?>
						</a>
					<?php endif; ?>
				</figure>
			<?php endif; ?>	
            <?php if ( $instance['show_title'] ) : ?>
			  <?php echo $instance["before_post_title"]; ?><a class="post-title" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php if ( $instance['show_title_date'] ) {?>[<?php the_time('m-d-Y'); ?>]<?php }else{?><?php the_title(); ?><?php }?></a><?php echo $instance["after_post_title"]; ?>
			<?php endif; ?>
			  <?php if ( $instance['comment_num'] ) : ?>
                <div class="fright">(<?php comments_number(); ?>)</div>
              <?php endif; ?>
              <?php if ( $instance['date'] ) : ?>
                <div class="post_meta">Written by <?php the_author_posts_link() ?> <time datetime="<?php the_time('Y-m-d\TH:i'); ?>"><?php the_time('l, j F Y'); ?> <?php the_time() ?></time></div>
              <?php endif; ?>
			<div class="post_content">
            <?php if ( $instance['excerpt'] ) : ?>
			  <?php if($limittext=="" || $limittext==0){ ?>
				  <?php if ( $instance['excerpt_as_link'] ) : ?>
                    <a href="<?php the_permalink() ?>">
                  <?php endif; ?>
                <?php the_excerpt(); ?>
				  <?php if ( $instance['excerpt_as_link'] ) : ?>
                    </a>
                  <?php endif; ?>
              <?php }else{ ?>
				  <?php if ( $instance['excerpt_as_link'] ) : ?>
                    <a href="<?php the_permalink() ?>">
                  <?php endif; ?>
                <?php $excerpt = get_the_excerpt(); echo my_string_limit_words($excerpt,$limittext);?>
				  <?php if ( $instance['excerpt_as_link'] ) : ?>
                    </a>
                  <?php endif; ?>
              <?php } ?>
            <?php endif; ?>
            </div>
			<?php if ($instance['posttype'] == "testi") { ?>
              <div class="name-testi"><span class="user"><?php echo $testiname; ?></span>, <a href="http://<?php echo $testiurl; ?>"><?php echo $testiurl; ?></a></div>
            <?php }?>
            <?php if ( $instance['more_link'] ) : ?>
              <a href="<?php the_permalink() ?>" class="<?php if($instance['more_link_class']!="") {echo $instance['more_link_class'];}else{ ?>link<?php } ?>"><?php if($instance['more_link_text']==""){ ?>Read more<?php }else{ ?><?php echo $instance['more_link_text']; ?><?php } ?></a>
            <?php endif; ?>
		</li>
	<?php } ?>
	<?php echo "</ul>\n"; ?>
	<?php if ( $instance['global_link'] ) : ?>
	  <a href="<?php echo $instance['global_link_href']; ?>" class="link_show_all"><?php if($instance['global_link_text']==""){ ?>View all<?php }else{ ?><?php echo $instance['global_link_text']; ?><?php } ?></a>
	<?php endif; ?>
	
<?php 	
	echo $after_widget;
	
	$post = $post_old; // Restore the post object.
}

/**
 * Form processing.
 */
function update($new_instance, $old_instance) {
	return $new_instance;
}

/**
 * The configuration form.
 */
function form($instance) {
?>
<p>
    <label for="<?php echo $this->get_field_id("title"); ?>">
        <?php _e( 'Title' ); ?>:
        <input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
    </label>
</p>
<div style="width:230px; float:left; padding-right:20px; border-right:1px solid #c7c7c7;">
  <p>
      <label>
          <?php _e( 'Posts type' ); ?>:
          <?php
          $args=array(
          );
          ?>
          <select id="<?php echo $this->get_field_id('posttype'); ?>" name="<?php echo $this->get_field_name('posttype'); ?>" class="widefat" style="width:150px;">
              <?php foreach(get_post_types($args,'names') as $key => $post_type) { 
			  
			  $label_obj = get_post_type_object($post_type); 
              $labels = $label_obj->labels->name;
			  ?>
              
              <?php if ($key=='page' || $key=='revision' || $key=='attachment' || $key=='nav_menu_item' || $key=='optionsframework'){continue;} ?>
              <option<?php selected( $instance['posttype'], $post_type ); ?> value="<?php echo $post_type; ?>"><?php echo $labels; ?></option>
              <?php } ?>
          </select>
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("num"); ?>">
          <?php _e('Number of posts to show'); ?>:
          <input style="text-align: center;" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo absint($instance["num"]); ?>" size='4' />
      </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id("sort_by"); ?>">
  <?php _e('Sort by'); ?>:
  <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">
    <option value="date"<?php selected( $instance["sort_by"], "date" ); ?>>Date</option>
    <option value="title"<?php selected( $instance["sort_by"], "title" ); ?>>Title</option>
    <option value="comment_count"<?php selected( $instance["sort_by"], "comment_count" ); ?>>Number of comments</option>
    <option value="rand"<?php selected( $instance["sort_by"], "rand" ); ?>>Random</option>
  </select>
      </label>
</p>
  
<p>
  <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
  <input type="checkbox" class="checkbox" 
    id="<?php echo $this->get_field_id("asc_sort_order"); ?>" 
    name="<?php echo $this->get_field_name("asc_sort_order"); ?>"
    <?php checked( (bool) $instance["asc_sort_order"], true ); ?> />
          <?php _e( 'Reverse sort order (ascending)' ); ?>
      </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id("comment_num"); ?>">
      <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>"<?php checked( (bool) $instance["comment_num"], true ); ?> />
      <?php _e( 'Show number of comments' ); ?>
  </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id("date"); ?>">
      <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>"<?php checked( (bool) $instance["date"], true ); ?> />
      <?php _e( 'Show meta' ); ?>
  </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id("container_class"); ?>">
    <?php _e( 'Container class' ); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("container_class"); ?>" name="<?php echo $this->get_field_name("container_class"); ?>" type="text" value="<?php echo esc_attr($instance["container_class"]); ?>" /> <span style="font-size:11px; color:#999;"><?php _e( '(default: "featured_custom_posts")' ); ?></span>
  </label>
</p>

  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php _e('Post title'); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("show_title"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_title"); ?>" name="<?php echo $this->get_field_name("show_title"); ?>"<?php checked( (bool) $instance["show_title"], true ); ?> />
          <?php _e( 'Show post title' ); ?>
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("show_title_date"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_title_date"); ?>" name="<?php echo $this->get_field_name("show_title_date"); ?>"<?php checked( (bool) $instance["show_title_date"], true ); ?> />
          <?php _e( 'Date as title <span style="font-size:11px; color:#999;">("[mm-dd-yyyy]")</span>' ); ?>
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("before_post_title"); ?>">
          <?php _e( 'Before title' ); ?>:
          <input class="widefat" style="width:40%" id="<?php echo $this->get_field_id("before_post_title"); ?>" name="<?php echo $this->get_field_name("before_post_title"); ?>" type="text" value="<?php echo esc_attr($instance["before_post_title"]); ?>" />
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("after_post_title"); ?>">
          <?php _e( 'After title' ); ?>:&nbsp;&nbsp;
          <input class="widefat" style="width:40%" id="<?php echo $this->get_field_id("after_post_title"); ?>" name="<?php echo $this->get_field_name("after_post_title"); ?>" type="text" value="<?php echo esc_attr($instance["after_post_title"]); ?>" />
      </label>
  </p>

  </fieldset>

  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php _e('Excerpt'); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("excerpt"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("excerpt"); ?>" name="<?php echo $this->get_field_name("excerpt"); ?>"<?php checked( (bool) $instance["excerpt"], true ); ?> />
          <?php _e( 'Show post excerpt' ); ?>
      </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("excerpt_length"); ?>">
          <?php _e( 'Excerpt length (words):' ); ?>
      </label>
      <input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("excerpt_length"); ?>" name="<?php echo $this->get_field_name("excerpt_length"); ?>" value="<?php echo $instance["excerpt_length"]; ?>" size="3" />
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("excerpt_as_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("excerpt_as_link"); ?>" name="<?php echo $this->get_field_name("excerpt_as_link"); ?>"<?php checked( (bool) $instance["excerpt_as_link"], true ); ?> />
          <?php _e( 'Excerpt as link' ); ?>
      </label>
  </p>
  </fieldset>
</div>
<div style="width:230px; float:left; padding-left:20px;">
  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php _e('More link'); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("more_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("more_link"); ?>" name="<?php echo $this->get_field_name("more_link"); ?>"<?php checked( (bool) $instance["more_link"], true ); ?> />
          <?php _e( 'Show "More link"' ); ?>
      </label>
  </p>
  
  <p>
  <label for="<?php echo $this->get_field_id("more_link_text"); ?>">
    <?php _e( 'Link text' ); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("more_link_text"); ?>" name="<?php echo $this->get_field_name("more_link_text"); ?>" type="text" value="<?php echo esc_attr($instance["more_link_text"]); ?>" /> <span style="font-size:11px; color:#999;"><?php _e( '(default: "Read more")' ); ?></span>
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id("more_link_class"); ?>">
    <?php _e( 'Link class' ); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("more_link_class"); ?>" name="<?php echo $this->get_field_name("more_link_class"); ?>" type="text" value="<?php echo esc_attr($instance["more_link_class"]); ?>" /> <span style="font-size:11px; color:#999;"><?php _e( '(default: "link")' ); ?></span>
  </label>
  </p>
  </fieldset>
  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php _e('Thumbnail dimensions'); ?>:</legend>
  <?php if ( function_exists('the_post_thumbnail') && current_theme_supports("post-thumbnails") ) : ?>
  <p>
      <label for="<?php echo $this->get_field_id("thumb"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("thumb"); ?>" name="<?php echo $this->get_field_name("thumb"); ?>"<?php checked( (bool) $instance["thumb"], true ); ?> />
          <?php _e( 'Show post thumbnail' ); ?>
      </label>
  </p>
  <p>
          <label for="<?php echo $this->get_field_id("thumb_w"); ?>">
              Width: &nbsp;<input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_w"); ?>" name="<?php echo $this->get_field_name("thumb_w"); ?>" value="<?php echo $instance["thumb_w"]; ?>" />
          </label>
  </p>
  <p>
          <label for="<?php echo $this->get_field_id("thumb_h"); ?>">
              Height: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_h"); ?>" name="<?php echo $this->get_field_name("thumb_h"); ?>" value="<?php echo $instance["thumb_h"]; ?>" />
          </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("thumb_as_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("thumb_as_link"); ?>" name="<?php echo $this->get_field_name("thumb_as_link"); ?>"<?php checked( (bool) $instance["thumb_as_link"], true ); ?> />
          <?php _e( 'Thumbnail as link' ); ?>
      </label>
  </p>
  </fieldset>
  <fieldset style="border:1px solid #F1F1F1; padding:10px 10px 0; margin-bottom:1em;">
  <legend style="padding:0 5px;"><?php _e('Link to all posts'); ?>:</legend>
  <p>
      <label for="<?php echo $this->get_field_id("global_link"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("global_link"); ?>" name="<?php echo $this->get_field_name("global_link"); ?>"<?php checked( (bool) $instance["global_link"], true ); ?> />
          <?php _e( 'Show global link to all posts' ); ?>
      </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id("global_link_text"); ?>">
    <?php _e( 'Link text' ); ?>:
    <input class="widefat" id="<?php echo $this->get_field_id("global_link_text"); ?>" name="<?php echo $this->get_field_name("global_link_text"); ?>" type="text" value="<?php echo esc_attr($instance["global_link_text"]); ?>" /> <span style="font-size:11px; color:#999;"><?php _e( '(default: "View all")' ); ?></span>
  </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id("global_link_href"); ?>">
          <?php _e( 'Link URL' ); ?>:
          <input class="widefat" id="<?php echo $this->get_field_id("global_link_href"); ?>" name="<?php echo $this->get_field_name("global_link_href"); ?>" type="text" value="<?php echo esc_attr($instance["global_link_href"]); ?>" />
      </label>
  </p>
  </fieldset>
</div>
<div style="clear:both;"></div>



		<?php endif; ?>

<?php

}

}
?>