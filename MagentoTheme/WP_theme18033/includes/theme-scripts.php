<?php
/*	Register and load javascript
/*-----------------------------------------------------------------------------------*/
function my_script() {
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', get_bloginfo('template_url').'/js/jquery-1.7.2.min.js', false, '1.7.2');
		wp_enqueue_script('jquery');
	
		wp_enqueue_script('modernizr', get_bloginfo('template_url').'/js/modernizr.js', array('jquery'), '2.0.6');
		wp_enqueue_script('easing', get_bloginfo('template_url').'/js/jquery.easing.js', array('jquery'), '1.3');
		wp_enqueue_script('tools', get_bloginfo('template_url').'/js/jquery.tools.min.js', array('jquery'), '1.2.6');
		wp_enqueue_script('loader', get_bloginfo('template_url').'/js/jquery.loader_img.js', array('jquery'), '1.0');
		wp_enqueue_script('swfobject', get_bloginfo('url').'/wp-includes/js/swfobject.js', array('jquery'), '2.2');
		wp_enqueue_script('twitter', get_bloginfo('template_url').'/js/jquery.twitter.js', array('jquery'), '1.0');
		wp_enqueue_script('flickr', get_bloginfo('template_url').'/js/jquery.flickrush.js', array('jquery'), '1.0');
		wp_enqueue_script('audiojs', get_bloginfo('template_url').'/js/audiojs/audio.js', array('jquery'), '1.0');
		
		wp_enqueue_script('animate', get_bloginfo('template_url').'/js/jquery.animate-colors-min.js', array('jquery'), '1.2.1');
		wp_enqueue_script('backgroundPosition', get_bloginfo('template_url').'/js/jquery.backgroundPosition.js', array('jquery'), '1.2.1');
		wp_enqueue_script('fancybox', get_bloginfo('template_url').'/js/jquery.fancybox-1.3.4.pack.js', array('jquery'), '1.2.1');
		wp_enqueue_script('transform', get_bloginfo('template_url').'/js/jquery.transform-0.9.3.min.js', array('jquery'), '1.2.1');
		
		wp_enqueue_script('forms', get_bloginfo('template_url').'/js/ajax.forms.js', array('jquery'), '1.2.1');
		wp_enqueue_script('switcher', get_bloginfo('template_url').'/js/ajax.switcher.js', array('jquery'), '1.1.10');
		wp_enqueue_script('gallery', get_bloginfo('template_url').'/js/gallery.js', array('jquery'), '1');
		wp_enqueue_script('main', get_bloginfo('template_url').'/js/main.js', array('jquery'), '0');
	}
}
add_action('init', 'my_script');


/*	Register and load admin javascript
/*-----------------------------------------------------------------------------------*/

function tz_admin_js($hook) {
	if ($hook == 'post.php' || $hook == 'post-new.php') {
		wp_register_script('tz-admin', get_template_directory_uri() . '/js/jquery.custom.admin.js', 'jquery');
		wp_enqueue_script('tz-admin');
	}
}
add_action('admin_enqueue_scripts','tz_admin_js',10,1);
?>