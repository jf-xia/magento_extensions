<?php 
	$webSiteUrl = get_bloginfo('url')."/";
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	};
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	};
	if($webSiteUrl!=$pageURL){
		$pageHash = substr($pageURL, strlen($webSiteUrl), strlen($pageURL));
		header("location:".$webSiteUrl."#!/".$pageHash."");
		exit;
	};
 ?>
 
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes();?>> <!--<![endif]-->
<head>
	<title><?php if ( is_category() ) {
		echo __('Category Archive for &quot;', 'theme_5819'); single_cat_title(); echo __('&quot; | ', 'theme_5819'); bloginfo( 'name' );
	} elseif ( is_tag() ) {
		echo __('Tag Archive for &quot;', 'theme_5819'); single_tag_title(); echo __('&quot; | ', 'theme_5819'); bloginfo( 'name' );
	} elseif ( is_archive() ) {
		wp_title(''); echo __(' Archive | ', 'theme_5819'); bloginfo( 'name' );
	} elseif ( is_search() ) {
		echo __('Search for &quot;', 'theme_5819').wp_specialchars($s).__('&quot; | ', 'theme_5819'); bloginfo( 'name' );
	} elseif ( is_home() || is_front_page()) {
		bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
	}  elseif ( is_404() ) {
		echo __('Error 404 Not Found | ', 'theme_5819'); bloginfo( 'name' );
	} elseif ( is_single() ) {
		wp_title('');
	} else {
		echo wp_title( ' | ', false, right ); bloginfo( 'name' );
	} ?></title>
	<meta name="description" content="<?php wp_title(); echo ' | '; bloginfo( 'description' ); ?>" />
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="icon" href="<?php bloginfo( 'template_url' ); ?>/favicon.ico" type="image/x-icon" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />
	<?php /* The HTML5 Shim is required for older browsers, mainly older versions IE */ ?>
  <!--[if lt IE 8]>
    <div style=' clear: both; text-align:center; position: relative;'>
    	<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" alt="" /></a>
    </div>
  <![endif]-->
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css/reset.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
 	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css/grid.css" />
	<?php
		/* We add some JavaScript to pages with the comment form
		 * to support sites with threaded comments (when in use).
		 */
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	
		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head();
	?>
  <!--[if lt IE 9]>
  <style type="text/css">
    .border{
      behavior:url(<?php bloginfo('stylesheet_directory'); ?>/PIE.php);
      position: relative;
    }
    .button, .buttonSearh, .buttonInput {
      behavior:url(<?php bloginfo('stylesheet_directory'); ?>/PIE.php);
      position: relative;
   	}
   	.wp-pagenavi a{
      behavior:url(<?php bloginfo('stylesheet_directory'); ?>/PIE.php);
      position: relative;
   	}
  </style>
  	<script src="<?php bloginfo('template_url'); ?>/js/html5.js"></script>
  <![endif]-->
  
  <!-- Custom CSS -->
	<?php if(of_get_option('custom_css') != ''){?>
  <style type="text/css">
  	<?php echo of_get_option('custom_css' ) ?>
  </style>
  <?php }?>
  <style type="text/css">
		/* Body styling options */
		<?php $background = of_get_option('body_background');
			if ($background != '') {
				if ($background['image'] != '') {
					echo 'body { background-image:url('.$background['image']. '); background-repeat:'.$background['repeat'].'; background-position:'.$background['position'].';  background-attachment:'.$background['attachment'].'; }';
				}
				if($background['color'] != '') {
					echo 'body { background-color:'.$background['color']. '}';
				}
			};
		?>
		
  	/* Header styling options */
		<?php $header_styling = of_get_option('header_color'); 
			if($header_styling != '') {
				echo '#header {background-color:'.$header_styling.'}';
			}
		?>
		
		/* Links and buttons color */
		<?php $links_styling = of_get_option('links_color'); 
			if($links_styling) {
				echo 'a{color:'.$links_styling.'}';
				echo '.button {background:'.$links_styling.'}';
			}
		?>
		
		/* Body typography */
		<?php $body_typography = of_get_option('body_typography'); 
			if($body_typography) {
				echo 'body {font-family:'.$body_typography['face'].'; color:'.$body_typography['color'].'; font-size:'.$body_typography['size'].'; font-style:'.$body_typography['style'].';}';
			}
		?>
  </style>
    <script type="text/javascript">
		$(window).load(function() {
			$("#galleryHolder").gallerySplash({
				autoPlayState:'<?php echo of_get_option('auto_play'); ?>',
				autoPlayTime:'<?php echo of_get_option('change_delay'); ?>',
				alignIMG:'<?php echo of_get_option('align_img'); ?>',
				controlDisplay:'<?php echo of_get_option('display_navigation'); ?>',
				paginationDisplay:'<?php echo of_get_option('display_pagination'); ?>',
				animationSpeed:'<?php echo of_get_option('animation_speed'); ?>'
			});		
		});
	</script>
</head>

<body <?php body_class(); ?>>
<div id="spinnerBG"></div>
<div id="backToTop"><div></div></div>
<?php include_once(TEMPLATEPATH . '/slider-controls.php'); ?>
<div id="bg_grid"></div>
<div id="page_layout_cover"></div>
<?php include_once(TEMPLATEPATH . '/slider.php'); ?>
<div id="wrapper" class="container_12">
	<div id='pageSpinner'><div></div></div>
    <div id="leftSide" class="grid_4 alpha omega">
    	<header>
    		<h1 id="logo">
    			<?php if(of_get_option('logo_type') == 'text_logo'){?>
    				<a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>" id="logoText" class="<?php bloginfo( 'template_url' );?>"><?php bloginfo( 'name', 'display' ); ?></a>
       				<p id="logoSlogan"><?php bloginfo('description'); ?></p>
    			  <?php } else { ?>
              		<?php if(of_get_option('logo_url') != ''){ ?>
                		<a href="<?php bloginfo('url'); ?>/" class="<?php bloginfo( 'template_url' );?>" id="logoImg"><img src="<?php echo of_get_option('logo_url', "" ); ?>" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('description'); ?>"></a>
                	<?php } else { ?>
                		<a href="<?php bloginfo('url'); ?>/" class="<?php bloginfo( 'template_url' );?>" id="logoImg"><img src="<?php bloginfo('template_url'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('description'); ?>"></a>
                	<?php } ?>
              	<?php }?>
    		</h1>
            <div id="menuSlider">
                <div></div>
                <span>menu</span>
        		<?php wp_nav_menu( array(
        			'menu'			=> 'menu',
        			'menu_id'         => 'headerMenu',
           			'menu_class'      => 'menu', 
        	        'container'       => 'nav',
        	        'container_id'    => 'menuWrapper',
        	        'depth'           => 0,
        	        'theme_location' => 'header_menu' 
        	        )); 
        		?>
            </div>
    	</header>
    </div>