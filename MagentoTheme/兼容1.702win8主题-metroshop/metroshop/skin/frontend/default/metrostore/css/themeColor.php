<?php
define('MAGENTO_ROOT', (dirname(__FILE__).'../../../../../../'));
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require_once $mageFilename;
umask(0);
Mage::app();

$config = Mage::getStoreConfig('mdloption');
$color_helper = Mage::helper('mdloption/color');

header("Content-type: text/css; charset: UTF-8");
?>
<?php if ( $config['genral_theme_setting']['theme-color-option'] ) : ?>
<?php if ( $config['genral_theme_setting']['enable_font'] ) : ?>
body{font-family:"<?php echo $config['genral_theme_setting']['font']; ?>"} 
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['font_size'] ) : ?>
body{font-size:<?php echo $config['genral_theme_setting']['font_size']; ?>px;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['color'] ) : ?>
.nav-container.span12, .magicat-container, .sorter .view-mode a.list, .social-link a:hover, .headingBox .headingIcons, .bullet, .viewAll, .headingBox .headingIcons, ul.add-to-links li a.link-wishlist, ul.add-to-links li a.link-compare, button.button span, button.button span span, .metro-banner-control .control-seek-box, .mix_wrapper .jcarousel-prev, .mix_wrapper .jcarousel-next, .nav-width.scrollNav, .goTop a, .toggleBtn, .direction, .sbOptions, .form-subscribe button.button span, .dec.add, .add, .product_custom_content ul li span, .block-progress .block-title, .metro-banner-control .control-left, .metro-banner-control .control-right, .mobMenu h1, .flex-direction-nav a, .theme-default .nivo-directionNav a, .theme-default .nivo-controlNav a, .flexsliderSide .flex-control-paging li a.flex-active, .nivo-caption
{background-color:<?php echo $config['genral_theme_setting']['color']; ?>;}
.subtitle, .sub-title{color:<?php echo $config['genral_theme_setting']['color']; ?>;}
.flexsliderSide{border-color:<?php echo $config['genral_theme_setting']['color']; ?>;}
<?php endif; ?>

<?php if ( $config['genral_theme_setting']['hover_color'] ) : ?>
.mix_wrapper .jcarousel-prev:hover, .mix_wrapper .jcarousel-next:hover, ul.add-to-links li a.link-wishlist:hover, ul.add-to-links li a.link-compare:hover, .metro-banner-control .control-left:hover, .metro-banner-control .control-right:hover, .prod-next:hover, .prod-prev:hover, .prod-next:hover, .prod-prev:hover, .more-views .jcarousel-next.jcarousel-next-horizontal:hover, .more-views .jcarousel-prev.jcarousel-prev-horizontal:hover, .product_custom_content ul li:hover span, .viewAll:hover, .flexslider .flex-prev:hover, .theme-default a.nivo-prevNav:hover, .theme-default a.nivo-nextNav:hover, .flexslider .flex-next:hover{background-color:<?php echo $config['genral_theme_setting']['hover_color']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['body_font_color'] ) : ?>
body{color:<?php echo $config['genral_theme_setting']['body_font_color']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['color'] ) : ?>
ul.promo li, #nav li.level-top.active > a, #nav li.level-top > a:hover, .searchPan, .compare-content, .remain_cart, #nav li.level-top.over > a {border-bottom-color:<?php echo $config['genral_theme_setting']['color']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['color'] ) : ?>
.free-shipping span, .footer ul.footer_links.about li a {color:<?php echo $config['genral_theme_setting']['color']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['anchor_color'] ) : ?>
a{color:<?php echo $config['genral_theme_setting']['anchor_color']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['anchor_hover'] ) : ?>
a:hover{color:<?php echo $config['genral_theme_setting']['anchor_hover']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['left_menu_bg'] ) : ?>
.magicat-container{background-color:<?php echo $config['genral_theme_setting']['left_menu_bg']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['block_heading'] ) : ?>
.block .block-title strong{color:<?php echo $config['genral_theme_setting']['block_heading']; ?>; }
.block .block-title{border-bottom-color:<?php echo $config['genral_theme_setting']['block_heading']; ?>; }
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['content_heading'] ) : ?>
.page-title h1, .page-title h2{color:<?php echo $config['genral_theme_setting']['content_heading']; ?>; }
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['new_batch'] ) : ?>
.new strong{background-color:<?php echo $config['genral_theme_setting']['new_batch']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['new_batch_color'] ) : ?>
.new strong{color:<?php echo $config['genral_theme_setting']['new_batch_color']; ?>;}
<?php endif; ?>

<?php if ( $config['genral_theme_setting']['sale_batch'] ) : ?>
.sale strong{background-color:<?php echo $config['genral_theme_setting']['sale_batch']; ?>;}
<?php endif; ?>
<?php if ( $config['genral_theme_setting']['sale_batch_color'] ) : ?>
.sale strong{color:<?php echo $config['genral_theme_setting']['sale_batch_color']; ?>;}
<?php endif; ?>
<?php endif; ?>
/*----*/

<?php if ( $config['top_menu']['theme-color-option'] ) : ?>
<?php if ( $config['top_menu']['contact_bg_color'] ) : ?>
.header .links a.contact{background-color:<?php echo $config['top_menu']['contact_bg_color']; ?>;}
<?php endif; ?>
<?php if ( $config['top_menu']['my_account_bg'] ) : ?>
.header .links li.myaccount a{background-color:<?php echo $config['top_menu']['my_account_bg']; ?>;}
<?php endif; ?>
<?php if ( $config['top_menu']['my_wishlist_bg'] ) : ?>
.header .links li a.wishlist{background-color:<?php echo $config['top_menu']['my_wishlist_bg']; ?>;}
<?php endif; ?>
<?php if ( $config['top_menu']['checkout_bg_color'] ) : ?>
.header .links li a.top-link-checkout{background-color:<?php echo $config['top_menu']['checkout_bg_color']; ?>;}
<?php endif; ?>
<?php if ( $config['top_menu']['login_bg_color'] ) : ?>
.header .links li.last a{background-color:<?php echo $config['top_menu']['login_bg_color']; ?>;}
<?php endif; ?>
<?php if ( $config['top_menu']['shopping_cart_bg'] ) : ?>
.block-cart .summary{background-color:<?php echo $config['top_menu']['shopping_cart_bg']; ?>;}
<?php endif; ?>
<?php endif; ?>

/*super fish menu*/
<?php if ( $config['navsetting']['fishnavbox']==1) : ?>
<?php if ( $config['navsetting']['theme-color-option'] ) : ?>
	<?php if ( $config['navsetting']['superfishmenu'] ) : ?>
    #menu .sf-menu a{color:<?php echo $config['navsetting']['superfishmenu']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishmenuHoverBorderColor'] ) : ?>
    .sf-menu li ul{border-bottom-color:<?php echo $config['navsetting']['superfishmenuHoverBorderColor']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishmenuHoverBackground'] ) : ?>
    #menu .sf-menu li > a:hover, .sf-menu li.level-top.over > a{background-color:<?php echo $config['navsetting']['superfishmenuHoverBackground']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishmenuHoverTextColor'] ) : ?>
    #menu .sf-menu li.over.parent a, #nav.sf-menu li > a:hover, .sf-menu li.level-top.over > a, #nav.sf-menu li.level-top.over{color:<?php echo $config['navsetting']['superfishmenuHoverTextColor']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishSubmenu'] ) : ?>
    #menu .sf-menu li ul{background-color:<?php echo $config['navsetting']['superfishSubmenu']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishSubmenuTextColor'] ) : ?>
    #menu .sf-menu li.over ul li a span{color:<?php echo $config['navsetting']['superfishSubmenuTextColor']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishSubmenuhoverColor'] ) : ?>
    #menu .sf-menu li ul li a:hover, .sf-menu li ul li.over.parent > a{background-color:<?php echo $config['navsetting']['superfishSubmenuhoverColor']; ?>; border-color:<?php echo $config['navsetting']['superfishSubmenuhoverColor']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishSubmenuhoverTextColor'] ) : ?>
    #menu .sf-menu li.over ul li a:hover span{color:<?php echo $config['navsetting']['superfishSubmenuhoverTextColor']; ?>; }
    <?php endif; ?>
    <?php if ( $config['navsetting']['superfishmenu_bg'] ) : ?>
    .nav-container.span12, .nav-width.scrollNav, .mobMenu h1{background-color:<?php echo $config['navsetting']['superfishmenu_bg']; ?>; }
    <?php endif; ?>
<?php endif; ?>
<?php endif; ?>


/*button setting*/
<?php if ( $config['buttonSetting']['theme-color-option'] ) : ?>
<?php if ($config['buttonSetting']['enable_font']==1) :?>
<?php if ( $config['buttonSetting']['button_font'] ) : ?>
button.button span, .viewCart{font-family:"<?php echo $config['buttonSetting']['button_font']; ?>"} 
<?php endif; ?>
<?php endif; ?>
<?php if ( $config['buttonSetting']['button_color'] ) : ?>
button.button span, button.button span span, .viewCart, .form-subscribe button.button span, button.button.btn-mdlcart span, button.button.btn-mdlcart span span{background-color:<?php echo $config['buttonSetting']['button_color']; ?>;}
<?php endif; ?>
<?php if ( $config['buttonSetting']['button_hover'] ) : ?>
button.button:hover span, button.button:hover span span, .viewCart, .form-subscribe button.button:hover span, button.button.btn-mdlcart:hover span, button.button.btn-mdlcart:hover span span{background-color:<?php echo $config['buttonSetting']['button_hover']; ?>;}
<?php endif; ?>
<?php if ( $config['buttonSetting']['button_text'] ) : ?>
button.button span, button.button span span, .viewCart, .form-subscribe button.button span, button.button.btn-mdlcart span, button.button.btn-mdlcart span span{color:<?php echo $config['buttonSetting']['button_text']; ?>;}
<?php endif; ?>
<?php if ( $config['buttonSetting']['button_text_hover'] ) : ?>
button.button:hover span, button.button:hover span span, .viewCart, .form-subscribe button.button:hover span, button.button.btn-mdlcart:hover span, button.button.btn-mdlcart:hover span span{color:<?php echo $config['buttonSetting']['button_text_hover']; ?>;}
<?php endif; ?>
<?php if ( $config['buttonSetting']['next_pre_btn'] ) : ?>
.prod-prev, .prod-next{background-color:<?php echo $config['buttonSetting']['next_pre_btn']; ?>; }
<?php endif; ?>
<?php endif; ?>
/*-----------*/

/*price setting*/
<?php if ( $config['priceSetting']['theme-color-option'] ) : ?>
<?php if ($config['priceSetting']['enable_font']==1) :?>
<?php if ( $config['priceSetting']['price_font'] ) : ?>
.price-box{font-family:"<?php echo $config['priceSetting']['price_font']; ?>"} 
<?php endif; ?>
<?php endif; ?>

<?php if ( $config['priceSetting']['regular_price'] ) : ?>
.regular-price .price{color:<?php echo $config['priceSetting']['regular_price']; ?>;}
<?php endif; ?>

<?php if ( $config['priceSetting']['special_price'] ) : ?>
.special-price .price, .minimal-price-link .price{color:<?php echo $config['priceSetting']['special_price']; ?>;}
<?php endif; ?>

<?php if ( $config['priceSetting']['special_price_label'] ) : ?>
.special-price .price-label, .minimal-price-link .label{color:<?php echo $config['priceSetting']['special_price_label']; ?>;}
<?php endif; ?>
<?php endif; ?>

/*footer setting*/
<?php if ( $config['footer_setting']['theme-color-option'] ) : ?>
<?php if ( $config['footer_setting']['footer_bg'] ) : ?>
.footer{background-color:<?php echo $config['footer_setting']['footer_bg']; ?>; }
<?php endif; ?>
<?php if ( $config['footer_setting']['footer_bg_bottom'] ) : ?>
.copyright{background-color:<?php echo $config['footer_setting']['footer_bg_bottom']; ?>; }
<?php endif; ?>
<?php if ( $config['footer_setting']['footer_heading'] ) : ?>
.footer h3{color:<?php echo $config['footer_setting']['footer_heading']; ?>; }
<?php endif; ?>
<?php if ( $config['footer_setting']['footer_text'] ) : ?>
.footer p, .footer ul.connect li{color:<?php echo $config['footer_setting']['footer_text']; ?>; }
<?php endif; ?>
<?php if ( $config['footer_setting']['footer_anchor'] ) : ?>
.footer a, .footer ul.footer_links li a{color:<?php echo $config['footer_setting']['footer_anchor']; ?>; }
<?php endif; ?>
<?php if ( $config['footer_setting']['footer_hover'] ) : ?>
.footer a:hover, .footer ul.footer_links li a:hover{color:<?php echo $config['footer_setting']['footer_hover']; ?>; }
<?php endif; ?>
<?php endif; ?>

/*Mega menu*/
<?php if ( $config['navsetting']['fishnavbox']==0) : ?>
<?php if ( $config['mega_navsetting']['theme-color-option']) : ?>
<?php if ($config['mega_navsetting']['enable_font']) :?>
<?php if ( $config['mega_navsetting']['mega_font'] ) : ?>
#nav{font-family:"<?php echo $config['mega_navsetting']['mega_font']; ?>"} 
<?php endif; ?>
<?php endif; ?>
<?php if ( $config['mega_navsetting']['nav_color'] ) : ?>
#nav a{color:<?php echo $config['mega_navsetting']['nav_color']; ?>;}
<?php endif; ?>
<?php if ( $config['mega_navsetting']['nav_color_hover'] ) : ?>
#nav li.over a span{color:<?php echo $config['mega_navsetting']['nav_color_hover']; ?>;}
<?php endif; ?>
<?php if ( $config['mega_navsetting']['nav_submenu_color'] ) : ?>
#nav li.over ul li ul li a span{color:<?php echo $config['mega_navsetting']['nav_submenu_color']; ?>;}
<?php endif; ?>
<?php if ( $config['mega_navsetting']['nav_submenu_color_hover'] ) : ?>
#nav li.over ul li ul li a span:hover{color:<?php echo $config['mega_navsetting']['nav_submenu_color_hover']; ?>;}
<?php endif; ?>
<?php if ( $config['mega_navsetting']['nav_bg'] ) : ?>
.nav-container.span12, .nav-width.scrollNav, .mobMenu h1{background-color:<?php echo $config['mega_navsetting']['nav_bg']; ?>;}
<?php endif; ?>
<?php if ( $config['mega_navsetting']['sub_h_nav_bg'] ) : ?>
#nav li.over ul li a span{background-color:<?php echo $config['mega_navsetting']['sub_h_nav_bg']; ?>;}
<?php endif; ?>
<?php if ( $config['mega_navsetting']['sub_h_nav_color'] ) : ?>
#nav li.over ul li a span{color:<?php echo $config['mega_navsetting']['sub_h_nav_color']; ?>;}
<?php endif; ?>
<?php if ( $config['mega_navsetting']['sub_menu_bottom_border'] ) : ?>
#nav ul.level0{border-bottom-color:<?php echo $config['mega_navsetting']['sub_menu_bottom_border']; ?>;}
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
