<?php
/**
 * @package WordPress
 * @subpackage theme_18020
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
  	<?php echo '<p class="nocomments">' . __('This post is password protected. Enter the password to view comments.', 'theme_18033') . '</p>'; ?>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h2 class="space" id="comments"><?php printf( _n( '1 comment', '%1$s comments', get_comments_number(), 'theme_18033' ),
			number_format_i18n( get_comments_number() ), '<span class="normal">&quot;'.get_the_title().'&quot;</span>' );?></h2>

	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
    <?php echo '<h2 class="nocomments">' . __('no comments yet.', 'theme_18033') . '</h2>'; ?>
	<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
    <?php echo '<h2 class="nocomments">' . __('comments are closed.', 'theme_18033') . '</h2>'; ?>

	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<div id="respond">

<h2><?php comment_form_title( _e('leave a comment','theme_18033')); ?></h2>
<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php _e('You must be', 'theme_18033'); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('logged in', 'theme_18033'); ?></a> <?php _e('to post a comment.', 'theme_18020'); ?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( is_user_logged_in() ) : ?>

<p><?php _e('Logged in as', 'theme_18033'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a class="logOutPage" href="<?php echo wp_logout_url(get_permalink()."?ajaxRequest=true"); ?>" title="<?php _e('Log out of this account', 'theme_18020'); ?>"><?php _e('Log out &raquo;', 'theme_18020'); ?></a></p>

<?php else : ?>
<p class="field"><label class="nWidth">Name *</label><br><input type="text" name="author" id="author" value="" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> /></p>

<p class="field"><label class="nWidth">Email (will not be published) *</label><br><input type="text" name="email" id="email" value="" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> /></p>

<p class="field"><label class="nWidth">Website</label><br><input type="text" name="url" id="url" value="" size="22" tabindex="3" /></p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p><label class="nWidth">Your comment *</label><br><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit comment" class="buttonInput animate" /></p>
<p id="msg"></p>
<?php comment_id_fields(); ?>
<?php do_action('comment_form', $post->ID); ?>
</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>