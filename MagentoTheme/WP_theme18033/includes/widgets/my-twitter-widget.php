<?php
// =============================== My Twitter Wiget ======================================
class MY_TwitterWidget extends WP_Widget {
    /** constructor */
    function MY_TwitterWidget() {
        parent::WP_Widget(false, $name = 'My - Twitter');
    }
	
	
  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$twitter_name = apply_filters('twitter_name', $instance['twitter_name']);
		$amount = apply_filters('twitter_twitts_amount', $instance['twitts_amount']);
		$suf = rand(100000,999999);		
        ?>
        <?php echo $before_widget; ?>
			
<div id="twitter-<?php echo $suf; ?>" class="twitter"></div>
	<script>
      jQuery("#twitter-<?php echo $suf; ?>").getTwitter({
        userName: "<?php echo $twitter_name; ?>",
        numTweets: <?php echo $amount; ?>,
        loaderText: "Loading tweets...",
        slideIn: true,
        showHeading: true,
				beforeHeading: "<?php echo $before_title; ?>",
				afterHeading: "<?php echo $after_title; ?>",
        headingText: "<?php echo $title; ?>",
        id:"#twitter-<?php echo $suf; ?>",
        showProfileLink: false
      });
  </script>      
    
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
			$twitter_name = esc_attr($instance['twitter_name']);
			$amount = esc_attr($instance['twitts_amount']);
			$proflink = esc_attr($instance['twitter_proflink']);
			
        ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

      <p><label for="<?php echo $this->get_field_id('twitter_name'); ?>"><?php _e('Twitter Name:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('twitter_name'); ?>" name="<?php echo $this->get_field_name('twitter_name'); ?>" type="text" value="<?php echo $twitter_name; ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('twitts_amount'); ?>"><?php _e('Twitts number:', 'theme_5819'); ?> <input class="widefat" id="<?php echo $this->get_field_id('twitts_amount'); ?>" name="<?php echo $this->get_field_name('twitts_amount'); ?>" type="text" value="<?php echo $amount; ?>" /></label></p>	
			
        <?php 
    }

} // class  Widget
?>