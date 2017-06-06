<?php
// =============================== My Flickr widget  ======================================
class MY_FlickrWidget extends WP_Widget {
    /** constructor */
    function MY_FlickrWidget() {
        parent::WP_Widget(false, $name = 'My - Flickr');
    }

  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$flickr_id = apply_filters('flickr_id', $instance['flickr_id']);
		$amount = apply_filters('flickr_image_amount', $instance['image_amount']);
		$linktext = apply_filters('widget_linktext', $instance['linktext']);
		$suf = rand(100000,999999);		
        ?>
              <?php echo $before_widget; ?>
                 
			
                        
<div class="flickrImages-<?php echo $suf ?> widget flickrImages">
    <h3 class="widget"><?php echo $title ?></h3>
    <script>
			jQuery(function(){
			 jQuery('.flickrImages-<?php echo $suf ?>').flickrush({
					id: '<?php echo $flickr_id ?>',  // the ID of your flickr username
					limit: <?php echo $amount ?>,    // the number of photos to display
					random: true         						 // randomly select photos to be displayed
			 });
			});
    </script>
</div>
<a href="http://flickr.com/photos/<?php echo $flickr_id ?>" class="link"><?php echo $linktext; ?></a>
                        
    
                        <?php wp_reset_query(); ?>
								
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
			$flickr_id = esc_attr($instance['flickr_id']);
			$amount = esc_attr($instance['image_amount']);
			$linktext = esc_attr($instance['linktext']);
			
        ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', $domain); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

      <p><label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID:', $domain); ?> <input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo $flickr_id; ?>" /></label></p>
	  	<p><label for="<?php echo $this->get_field_id('image_amount'); ?>"><?php _e('Images count:', $domain); ?> <input class="widefat" id="<?php echo $this->get_field_id('image_amount'); ?>" name="<?php echo $this->get_field_name('image_amount'); ?>" type="text" value="<?php echo $amount; ?>" /></label></p>	
      <p><label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Link Text:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo $linktext; ?>" /></label></p>	
			
        <?php 
    }

} // class  Widget
?>