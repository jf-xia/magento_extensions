<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = 'theme_5819';
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
	// Logo type
	$logo_type = array("image_logo" => "Image Logo","text_logo" => "Text Logo");
	
	// Search box in the header
	$g_search_box = array("no" => "No","yes" => "Yes");
	
	// Background Defaults
	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
	
	// Superfish fade-in animation
	$sf_f_animation_array = array("show" => "Enable fade-in animation","false" => "Disable fade-in animation");
	
	// Superfish slide-down animation
	$sf_sl_animation_array = array("show" => "Enable slide-down animation","false" => "Disable slide-down animation");
	
	// Superfish animation speed
	$sf_speed_array = array("slow" => "Slow","normal" => "Normal", "fast" => "Fast");
	
	// Superfish arrows markup
	$sf_arrows_array = array("true" => "Yes","false" => "No");
	
	// Superfish shadows
	$sf_shadows_array = array("true" => "Yes","false" => "No");
	
	
	// Slider
	$align_img_array = array("center" => "Center", "top" => "Top", "bottom" => "Bottom", "left" => "Left", "top_left" => "Top left", "top_right" => "Top right", "bottom_left" => "Bottom left", "bottom_right" => "Bottom right");
	
	$autoplay_array = array("true" => "Yes","false" => "No");
	
	$navigation_array = array("true" => "Yes","false" => "No");
	
	$pagination_array = array("true" => "Yes","false" => "No");

	
	// Footer menu
	$footer_menu_array = array("true" => "Yes","false" => "No");
	
	// Featured image size on the blog.
	$post_image_size_array = array("normal" => "Normal size","large" => "Large size");
	
	// Featured image size on the single page.
	$single_image_size_array = array("normal" => "Normal size","large" => "Large size");
	
	// Meta for blog
	$post_meta_array = array("true" => "Yes","false" => "No");
	
	// Meta for blog
	$post_excerpt_array = array("true" => "Yes","false" => "No");
	
	
	
	
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('template_directory') . '/includes/images/';
		
	$options = array();
	
	$options[] = array( "name" => "General Settings",
						"type" => "heading");
	
	/**/$options[] = array( "name" =>  "Body styling",
						"desc" => "Change the background style.",
						"id" => "body_background",
						"std" => $background_defaults, 
						"type" => "background");

	
	$options[] = array( "name" => "Buttons and links color",
						"desc" => "Change the color of buttons and links.",
						"id" => "links_color",
						"std" => "",
						"type" => "color");
	
	$options[] = array( "name" => "Typography",
						"desc" => "Typography.",
						"id" => "body_typography",
						"std" => array('size' => '12px','face' => 'arial','style' => 'normal','color' => '#333'),
						"type" => "typography");

	$options[] = array( "name" => "Custom CSS",
						"desc" => "Want to add any custom CSS code? Put in here, and the rest is taken care of. This overrides any other stylesheets. eg: a.button{color:green}",
						"id" => "custom_css",
						"std" => "",
						"type" => "textarea");
	
	
	
	
	
	$options[] = array( "name" => "Logo",
						"type" => "heading");
	
	$options[] = array( "name" => "What kind of logo?",
						"desc" => "Select whether you want your main logo to be an image or text. If you select 'image' you can put in the image url in the next option, and if you select 'text' your Site Title will show instead.",
						"id" => "logo_type",
						"std" => "image_logo",
						"type" => "radio",
						"options" => $logo_type);
	
	$options[] = array( "name" => "Logo URL",
						"desc" => "Enter the direct path to your logo image. For example http://your_website_url_here/wp-content/themes/themeXXXX/images/logo.png",
						"id" => "logo_url",
						"type" => "upload");
	
	$options[] = array( "name" => "Slider Settings",
						"type" => "heading");
						
	$options[] = array( "name" => "Autoplay gallery",
						"desc" => "",
						"id" => "auto_play",
						"std" => "false",
						"type" => "radio",
						"options" => $autoplay_array);
						
	$options[] = array( "name" => "Pause time",
						"desc" => "Value in seconds.",
						"id" => "change_delay",
						"std" => "4",
						"type" => "text");
						
	$options[] = array( "name" => "Animation speed",
						"desc" => "Value in seconds.",
						"id" => "animation_speed",
						"std" => "0.7",
						"type" => "text");
						
	$options[] = array( "name" => "Image align",
						"desc" => "The alignment of an image according to the browser window",
						"id" => "align_img",
						"std" => "center",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $align_img_array);
						
	$options[] = array( "name" => "Display next & prev navigation?",
						"desc" => "",
						"id" => "display_navigation",
						"std" => "false",
						"type" => "radio",
						"options" => $navigation_array);
						
	$options[] = array( "name" => "Display pagination?",
						"desc" => "",
						"id" => "display_pagination",
						"std" => "false",
						"type" => "radio",
						"options" => $pagination_array);

	
	$options[] = array( "name" => "Blog section",
						"type" => "heading");
	
	$options[] = array( "name" => "Blog Title",
						"desc" => "Enter Your Blog Title used on Blog page.",
						"id" => "blog_text",
						"std" => "Blog",
						"type" => "text");
	
	$options[] = array( "name" => "Related Posts Title",
						"desc" => "Enter Your Title used on Single Post page for related posts.",
						"id" => "blog_related",
						"std" => "Related Posts",
						"type" => "text");
	
	
	$options[] = array( "name" => "Blog image size",
						"desc" => "Featured image size on the blog.",
						"id" => "post_image_size",
						"type" => "select",
						"std" => "normal",
						"class" => "small", //mini, tiny, small
						"options" => $post_image_size_array);
	
	$options[] = array( "name" => "Single post image size",
						"desc" => "Featured image size on the single page.",
						"id" => "single_image_size",
						"type" => "select",
						"std" => "normal",
						"class" => "small", //mini, tiny, small
						"options" => $single_image_size_array);
	
	$options[] = array( "name" => "Enable Meta for blog posts?",
						"desc" => "Enable or Disable meta information for blog posts.",
						"id" => "post_meta",
						"std" => "true",
						"type" => "radio",
						"options" => $post_meta_array);
	
	$options[] = array( "name" => "Enable excerpt for blog posts?",
						"desc" => "Enable or Disable excerpt for blog posts.",
						"id" => "post_excerpt",
						"std" => "true",
						"type" => "radio",
						"options" => $post_excerpt_array);
	
	
	
	
	$options[] = array( "name" => "Footer",
						"type" => "heading");
	
	$options[] = array( "name" => "Footer copyright text",
						"desc" => "Enter text used in the right side of the footer. HTML tags are allowed.",
						"id" => "footer_text",
						"std" => "",
						"type" => "textarea");
	
	$options[] = array( "name" => "Google Analytics Code",
						"desc" => "You can paste your Google Analytics or other tracking code in this box. This will be automatically added to the footer.",
						"id" => "ga_code",
						"std" => "",
						"type" => "textarea");
	
	$options[] = array( "name" => "Feedburner URL",
						"desc" => "Feedburner is a Google service that takes care of your RSS feed. Paste your Feedburner URL here to let readers see it in your website.",
						"id" => "feed_url",
						"std" => "",
						"type" => "text");
	
	/*$options[] = array( "name" => "Display Footer menu?",
						"desc" => "Do you want to display footer menu?",
						"id" => "footer_menu",
						"std" => "true",
						"type" => "radio",
						"options" => $footer_menu_array);*/
	
	return $options;
}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This example shows/hides an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#example_showhidden').click(function() {
  		$('#section-example_text_hidden').fadeToggle(400);
	});
	
	if ($('#example_showhidden:checked').val() !== undefined) {
		$('#section-example_text_hidden').show();
	}
	
});
</script>

<?php
}