<?php
// =============================== My Recent Comments Widget ====================================== //
class MY_CommentWidget extends WP_Widget_Recent_Comments {

	function MY_CommentWidget() {
		$widget_ops = array('classname' => 'widget_my_recent_comments', 'description' => __('My - Recent Comments','theme_5819') );
		$this->WP_Widget('my-recent-comments', __('My - Recent Comments','theme_5819'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		global $wpdb, $comments, $comment;

		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('My Recent Comments','theme_5819') : $instance['title']);
		if ( !$number = (int) $instance['number'] )
			$number = 5;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
			
		$comment_len = 100;

		if ( !$comments = wp_cache_get( 'recent_comments', 'widget' ) ) {
			$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' and comment_type not in ('pingback','trackback') ORDER BY comment_date_gmt DESC LIMIT 15");
			wp_cache_add( 'recent_comments', $comments, 'widget' );
		}

		$comments = array_slice( (array) $comments, 0, $number );
?>
		<?php echo $before_widget; ?>
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<ul class="recentcomments"><?php
			if ( $comments ) : foreach ( (array) $comments as $comment) :?>
      
      <li class="recentcomments">
			
			<?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_email(), '58' ); /* This avatar is the user's gravatar (http://gravatar.com) based on their administrative email address */  } ?>
      	<h4><?php echo $comment->comment_author; ?></h4>
        <small><?php echo $comment->comment_date; ?></small>
      	<div class="clear"></div>
        <div class="comment-body">
          <a href="<?php echo get_comment_link( $comment->comment_ID ); ?>" title="<?php _e('Go to this comment', 'theme_5819'); ?>"><?php echo strip_tags(substr(apply_filters('get_comment_text', $comment->comment_content), 0, $comment_len)); if (strlen($comment->comment_content) > $comment_len) echo '...';?></a>
        </div>
			</li>
            <?php
			endforeach; endif;?></ul>
		<?php echo $after_widget; ?>
<?php
	}
}
?>