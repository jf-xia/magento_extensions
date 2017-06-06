<?php
// =============================== My Banners ======================================
class MY_BannersWidget extends WP_Widget {
    /** constructor */
    function MY_BannersWidget() {
        parent::WP_Widget(false, $name = 'My - Banners');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
				$url1 = apply_filters('widget_url1', $instance['url1']);
				$img1 = apply_filters('widget_img1', $instance['img1']);
				$url2 = apply_filters('widget_url2', $instance['url2']);
				$img2 = apply_filters('widget_img2', $instance['img2']);
				$url3 = apply_filters('widget_url3', $instance['url3']);
				$img3 = apply_filters('widget_img3', $instance['img3']);
				$url4 = apply_filters('widget_url4', $instance['url4']);
				$img4 = apply_filters('widget_img4', $instance['img4']);
        ?>
             
              <?php echo $before_widget; 
							
							if ( $title )
							echo $before_title . $title . $after_title;?>
              
              <ul class="banners-holder clearfix">
              	<?php if($img1=="") { ?>
                  <li><a href="<?php echo $url1; ?>" class="banner"><img src="<?php bloginfo('template_url'); ?>/images/empty_ad.gif" alt="" /></a></li>
                <?php } else { ?>
                  <li><a href="<?php echo $url1; ?>" class="banner"><img src="<?php echo $img1; ?>" alt="" /></a></li>
                <?php } ?>
                
                
                <?php if($img2=="") { ?>
                  <li><a href="<?php echo $url2; ?>" class="banner"><img src="<?php bloginfo('template_url'); ?>/images/empty_ad.gif" alt=""/></a></li>
                <?php } else { ?>
                  <li><a href="<?php echo $url2; ?>" class="banner"><img src="<?php echo $img2; ?>" alt="" /></a></li>
                <?php } ?>
                
                
                <?php if($img3=="") { ?>
                  <li><a href="<?php echo $url3; ?>" class="banner"><img src="<?php bloginfo('template_url'); ?>/images/empty_ad.gif" alt=""/></a></li>
                <?php } else { ?>
                  <li><a href="<?php echo $url3; ?>" class="banner"><img src="<?php echo $img3; ?>" alt="" /></a></li>
                <?php } ?>
                
                
                <?php if($img4=="") { ?>
                  <li><a href="<?php echo $url4; ?>" class="banner"><img src="<?php bloginfo('template_url'); ?>/images/empty_ad.gif" alt=""/></a></li>
                <?php } else { ?>
                  <li><a href="<?php echo $url4; ?>" class="banner"><img src="<?php echo $img4; ?>" alt="" /></a></li>
                <?php } ?>
              </ul>
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
				$url1 = esc_attr($instance['url1']);
				$img1 = esc_attr($instance['img1']);
				$url2 = esc_attr($instance['url2']);
				$img2 = esc_attr($instance['img2']);
				$url3 = esc_attr($instance['url3']);
				$img3 = esc_attr($instance['img3']);
				$url4 = esc_attr($instance['url4']);
				$img4 = esc_attr($instance['img4']);
        ?>
       <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
       <p><label for="<?php echo $this->get_field_id('img1'); ?>"><?php _e('1st banner path:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('img1'); ?>" name="<?php echo $this->get_field_name('img1'); ?>" type="text" value="<?php echo $img1; ?>" /></label></p>
       <p><label for="<?php echo $this->get_field_id('url1'); ?>"><?php _e('1st banner Link:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('url1'); ?>" name="<?php echo $this->get_field_name('url1'); ?>" type="text" value="<?php echo $url1; ?>" /></label></p>
       
       <p><label for="<?php echo $this->get_field_id('img2'); ?>"><?php _e('2nd banner path:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('img2'); ?>" name="<?php echo $this->get_field_name('img2'); ?>" type="text" value="<?php echo $img2; ?>" /></label></p>
       <p><label for="<?php echo $this->get_field_id('url2'); ?>"><?php _e('2nd banner Link:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('url2'); ?>" name="<?php echo $this->get_field_name('url2'); ?>" type="text" value="<?php echo $url2; ?>" /></label></p>
       
       <p><label for="<?php echo $this->get_field_id('img3'); ?>"><?php _e('3rd banner path:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('img3'); ?>" name="<?php echo $this->get_field_name('img3'); ?>" type="text" value="<?php echo $img3; ?>" /></label></p>
       <p><label for="<?php echo $this->get_field_id('url3'); ?>"><?php _e('3rd banner Link:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('url3'); ?>" name="<?php echo $this->get_field_name('url3'); ?>" type="text" value="<?php echo $url3; ?>" /></label></p>
       
       <p><label for="<?php echo $this->get_field_id('img4'); ?>"><?php _e('4th banner path:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('img4'); ?>" name="<?php echo $this->get_field_name('img4'); ?>" type="text" value="<?php echo $img4; ?>" /></label></p>
       <p><label for="<?php echo $this->get_field_id('url4'); ?>"><?php _e('4th banner Link:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('url4'); ?>" name="<?php echo $this->get_field_name('url4'); ?>" type="text" value="<?php echo $url4; ?>" /></label></p>
			 
        <?php 
    }

} // class Widget

?>