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

/*super fish menu*/
<?php if ( $config['navsetting']['fishnavbox']==1) : ?>
<?php if ( $config['navsetting']['superfishmenu'] ) : ?>
#nav.sf-menu a{color:<?php echo $config['navsetting']['superfishmenu']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishmenuHoverBorderColor'] ) : ?>
.sf-menu li ul{border-bottom-color:<?php echo $config['navsetting']['superfishmenuHoverBorderColor']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishmenuHoverBackground'] ) : ?>
#nav.sf-menu li > a:hover, .sf-menu li.level-top.over > a{background-color:<?php echo $config['navsetting']['superfishmenuHoverBackground']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishmenuHoverTextColor'] ) : ?>
#nav.sf-menu li.over.parent a, #nav.sf-menu li > a:hover, .sf-menu li.level-top.over > a, #nav.sf-menu li.level-top.over{color:<?php echo $config['navsetting']['superfishmenuHoverTextColor']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishSubmenu'] ) : ?>
#nav.sf-menu li ul{background-color:<?php echo $config['navsetting']['superfishSubmenu']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishSubmenuTextColor'] ) : ?>
#nav.sf-menu li.over ul li a span{color:<?php echo $config['navsetting']['superfishSubmenuTextColor']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishSubmenuhoverColor'] ) : ?>
#nav.sf-menu li ul li a:hover, .sf-menu li ul li.over.parent > a{background-color:<?php echo $config['navsetting']['superfishSubmenuhoverColor']; ?>; border-color:<?php echo $config['navsetting']['superfishSubmenuhoverColor']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishSubmenuhoverTextColor'] ) : ?>
#nav.sf-menu li.over ul li a:hover span{color:<?php echo $config['navsetting']['superfishSubmenuhoverTextColor']; ?>; }
<?php endif; ?>
<?php if ( $config['navsetting']['superfishmenu_bg'] ) : ?>
.nav-container.span12, .nav-width.scrollNav, .mobMenu h1{background-color:<?php echo $config['navsetting']['superfishmenu_bg']; ?>; }
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
