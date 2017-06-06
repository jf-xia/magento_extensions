<?php
// =============================== My Popular Post widget ======================================
class MY_PopularPostsWidget extends WP_Widget {
    /** constructor */
    function MY_PopularPostsWidget() {
        parent::WP_Widget(false, $name = 'My - Popular Posts');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
				$count = apply_filters('widget_count', $instance['count']);
				$linktext = apply_filters('widget_linktext', $instance['linktext']);
				$linkurl = apply_filters('widget_linkurl', $instance['linkurl']);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
						
							
              
              <?php global $wpdb; ?>
              <ul class="popular-posts">
								<?php
                  $pop_posts = $count;
                    $popularposts = "SELECT $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_content, $wpdb->posts.post_excerpt, COUNT($wpdb->comments.comment_post_ID) AS 'stammy' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY stammy DESC LIMIT ".$pop_posts;
                    $posts = $wpdb->get_results($popularposts);
                    if($posts){
                      foreach($posts as $post){
                        $post_title = stripslashes($post->post_title);
                        $guid = get_permalink($post->ID);
                        $thumb = get_post_meta($post->ID,'_thumbnail_id',false);
                        $thumb = wp_get_attachment_image_src($thumb[0], 'small-post-thumbnail', false);
                        $thumb = $thumb[0];
  						$content = stripslashes($post->post_content);
						$excerpt = stripslashes($post->post_excerpt);
                			?>
                        <li>
                          <?php if ($thumb) { ?>
                            <figure class="post-thumb">
                              <a href="<?php echo $guid; ?>"><img class="thumbnail" src="<?php echo $thumb; ?>" /></a>
                            </figure>
                          <?php } else { ?>
                          	<figure class="post-thumb empty-thumb"></figure>
                          <?php } ?>
                          
                          <?php if ($excerpt!="") { ?>
					 	<h4 class="excerpt"><?php echo my_string_limit_char($excerpt,52);?>...</h4>
					 <?php } else { ?>	
					 	<h4 class="excerpt"><?php echo my_string_limit_char($content,52);?>...</h4>
					 <?php } ?>
					 	<div class="postDataPost"><?php the_time('d/m/Y'); ?></div>
                        </li>
                    <?php
                        }
                    }
                    ?>
              </ul>
              <!-- Link under post cycle -->
		    <?php if($linkurl !=""){?>
                <a href="<?php echo $linkurl; ?>" class="button"><?php echo $linktext; ?></a>
              <?php } ?>
              
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
			$title = esc_attr($instance['title']);
			$count = esc_attr($instance['count']);
			$linktext = esc_attr($instance['linktext']);
			$linkurl = esc_attr($instance['linkurl']);
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
      
      <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Posts per page:', 'theme_5819'); ?><input class="widefat" style="width:30px; display:block; text-align:center" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label></p>
      
      <p><label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Link Text:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo $linktext; ?>" /></label></p>
			 
			 <p><label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php _e('Link Url:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" name="<?php echo $this->get_field_name('linkurl'); ?>" type="text" value="<?php echo $linkurl; ?>" /></label></p>

      </label></p>
      <?php 
    }

} // class Cycle Widget


?>