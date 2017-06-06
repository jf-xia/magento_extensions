</div>
<footer id="footer">
	<div id="footerText">
		<?php $myfooter_text = of_get_option('footer_text'); ?>
		
		<?php if($myfooter_text){?>
			<?php echo $myfooter_text; ?>
		<?php } else { ?>
			<a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>" class="site-name"><?php bloginfo('name'); ?></a> <?php _e('is proudly powered by', 'theme_5819'); ?> <a href="http://wordpress.org">WordPress</a> <a href="<?php if ( of_get_option('feed_url') != '' ) { echo of_get_option('feed_url'); } else bloginfo('rss2_url'); ?>" rel="nofollow" title="<?php _e('Entries (RSS)', 'theme_5819'); ?>"><?php _e('Entries (RSS)', 'theme_5819'); ?></a> and <a href="<?php bloginfo('comments_rss2_url'); ?>" rel="nofollow"><?php _e('Comments (RSS)', 'theme_5819'); ?></a>
			<a href="<?php bloginfo('url'); ?>/privacy-policy/" title="Privacy Policy"><?php _e('Privacy Policy', 'theme_5819'); ?></a>
		<?php } ?>
		<?php if( is_front_page() ) { ?>
		More Photographer Portfolio WordPress Themes at <a rel="nofollow" href="http://www.templatemonster.com/category/photographer-portfolio-wordpress-themes/" target="_blank">TemplateMonster.com</a>
		<?php } ?>
	</div>
	<?php
		if ( ! is_404() ){
			echo ('<div id="footer_block_1">');
				dynamic_sidebar( 'Footer' );
			echo ('</div>');
		}
	?>
	More Photographer Portfolio WordPress Themes at <a rel="nofollow" href="http://www.templatemonster.com/category/photographer-portfolio-wordpress-themes/" target="_blank">TemplateMonster.com</a>
</footer>
<!-- this is used by many Wordpress features and for plugins to work properly -->
<?php wp_footer(); ?> 
<!-- Show Google Analytics -->
<?php if(of_get_option('ga_code')) { ?>
	<script type="text/javascript">
		<?php echo stripslashes(of_get_option('ga_code')); ?>
	</script>
  <!-- Show Google Analytics -->	
<?php } ?>
</body>
</html>