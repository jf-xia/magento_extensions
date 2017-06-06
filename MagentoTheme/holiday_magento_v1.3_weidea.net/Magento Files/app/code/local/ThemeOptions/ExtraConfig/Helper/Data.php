<?php 
class ThemeOptions_ExtraConfig_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    function is_mobile()
    {
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	$mobiles = array("android", "iphone", "ipod", "ipad",
		"blackberry", "palm", "mobile", "mini", "kindle");
	foreach($mobiles as $mobile)
	{
		if(strpos($ua,$mobile)) return true;
	}
	return false;
    }
    
    function jsString($str='')
    { 
        return trim(preg_replace("/('|\"|\r?\n)/", '', $str)); 
    }
    
    function themeOptions ($themeOption)
    {
	switch ($themeOption)
	{
            
        /* background color and Pattern */
	    
	    case 'main_color':
		return Mage::getStoreConfig('mygeneral/background/main_color', Mage::app()->getStore()->getId());
	    break; 
	    case 'background_pattern':
		return Mage::getStoreConfig('mygeneral/background/background_pattern', Mage::app()->getStore()->getId());
	    break; 
	    case 'background_color':
		return Mage::getStoreConfig('mygeneral/background/background_color', Mage::app()->getStore()->getId());
	    break;
	    case 'background_image':
		return Mage::getStoreConfig('mygeneral/background/background_image', Mage::app()->getStore()->getId());
	    break;
	    case 'bg_repeat':
		return Mage::getStoreConfig('mygeneral/background/bg_repeat', Mage::app()->getStore()->getId());
	    break;  
	    case 'bg_attachment':
		return Mage::getStoreConfig('mygeneral/background/bg_attachment', Mage::app()->getStore()->getId());
	    break;  
	    case 'bg_position_x':
		return Mage::getStoreConfig('mygeneral/background/bg_position_x', Mage::app()->getStore()->getId());
	    break; 
	    case 'bg_position_y':
		return Mage::getStoreConfig('mygeneral/background/bg_position_y', Mage::app()->getStore()->getId());
	    break;   
	
            
         /* PRODUCT LIST */
     
	    case 'column_count':
		return Mage::getStoreConfig('mygeneral/product_list/column_count', Mage::app()->getStore()->getId());
	    break;
	    case 'productlayout':
		return Mage::getStoreConfig('mygeneral/product_list/productlayout', Mage::app()->getStore()->getId());
	    break;
	    case 'ajaxcart':
                return Mage::getStoreConfig('mygeneral/product_list/ajaxcart', Mage::app()->getStore()->getId());
             break;
	     case 'grayscale':
                return Mage::getStoreConfig('mygeneral/product_list/grayscale', Mage::app()->getStore()->getId());
             break;
	     case 'newsaleicon':
                return Mage::getStoreConfig('mygeneral/product_list/newsaleicon', Mage::app()->getStore()->getId());
             break;
	     case 'product_bg':
                return Mage::getStoreConfig('mygeneral/product_list/product_bg', Mage::app()->getStore()->getId());
             break;
	   
	    
            /* COLORS */
            
            case 'primary_color':
                return Mage::getStoreConfig('mygeneral/colors/primary_color');
             break;
	     case 'secondary_color':
                return Mage::getStoreConfig('mygeneral/colors/secondary_color');
             break;
	     
	     case 'buttonaerrow_color':
                return Mage::getStoreConfig('mygeneral/colors/buttonaerrow_color');
             break;
	     case 'buttonhover_color':
                return Mage::getStoreConfig('mygeneral/colors/buttonhover_color');
             break;
	     case 'border_color':
                return Mage::getStoreConfig('mygeneral/colors/border_color');
             break;
	     case 'table_color':
                return Mage::getStoreConfig('mygeneral/colors/table_color');
             break;
	

	
	/* Menu */
            
            case 'menu_background':
                return Mage::getStoreConfig('mygeneral/menu/menu_background');
             break;
	     
	     case 'menu_fonts':
                return Mage::getStoreConfig('mygeneral/menu/menu_fonts');
             break;
	     case 'menu_fonts_color':
                return Mage::getStoreConfig('mygeneral/menu/menu_fonts_color');
             break;
	     case 'menu_fontshover_color':
                return Mage::getStoreConfig('mygeneral/menu/menu_fontshover_color');
             break;
	     case 'menu_fontshover_backgroundcolor':
                return Mage::getStoreConfig('mygeneral/menu/menu_fontshover_backgroundcolor');
             break;
	     case 'homelink':
                return Mage::getStoreConfig('mygeneral/menu/homelink');
             break;
	     
	     
	
	/* Sidebar */
            
            case 'sidebar_background_color':
                return Mage::getStoreConfig('mygeneral/sidebar/sidebar_background_color');
             break;
	     case 'sidebar_title_fonts':
                return Mage::getStoreConfig('mygeneral/sidebar/sidebar_title_fonts');
             break;
	     case 'sidebar_title_fonts_color':
                return Mage::getStoreConfig('mygeneral/sidebar/sidebar_title_fonts_color');
             break;
	     case 'sidebar_linkhover_bg_color':
                return Mage::getStoreConfig('mygeneral/sidebar/sidebar_linkhover_bg_color');
             break;
	     case 'sidebar_fonts_color':
                return Mage::getStoreConfig('mygeneral/sidebar/sidebar_fonts_color');
             break;
	     case 'sidebar_seperator_color':
                return Mage::getStoreConfig('mygeneral/sidebar/sidebar_seperator_color');
             break;
	
	
	/* header */
        
	     case 'sticky_header':
                return Mage::getStoreConfig('mygeneral/header/sticky_header');
             break;
	    
            
        /* home*/
        
            case 'default_product':
		return Mage::getStoreConfig('mygeneral/home/default_product');
	    break;
            case 'loader':
                return Mage::getStoreConfig('mygeneral/home/loader');
             break;
	    case 'homesidebar':
                return Mage::getStoreConfig('mygeneral/home/homesidebar');
             break;
	    
	/* footer */
            
            
	     case 'footer_link_font_color':
                return Mage::getStoreConfig('mygeneral/footer/footer_link_font_color');
             break;
	     case 'footer_linkhover_font_color':
                return Mage::getStoreConfig('mygeneral/footer/footer_linkhover_font_color');
             break;
	     
	
	/* Category */
		
	    case 'displaycategorysidebar':
                return Mage::getStoreConfig('mygeneral/category/displaycategorysidebar');
             break;
	
	
	/*  Extra settings */
	
	     case 'backtotop':
                return Mage::getStoreConfig('mygeneral/extra_settings/backtotop');
             break;
	     case 'instock':
                return Mage::getStoreConfig('mygeneral/extra_settings/instock');
             break;
	     case 'responsiveness':
                return Mage::getStoreConfig('mygeneral/extra_settings/responsiveness');
             break;
            
	     
	    
	
		/* Theme Fonts Setting */
		
	     case 'titlefont_color':
                return Mage::getStoreConfig('mygeneral/themefont/titlefont_color');
             break;
	     case 'titlefont_size':
                return Mage::getStoreConfig('mygeneral/themefont/titlefont_size');
             break;
	     case 'titlefont':
                return Mage::getStoreConfig('mygeneral/themefont/titlefont');
             break;
	     case 'bodyfont_color':
                return Mage::getStoreConfig('mygeneral/themefont/bodyfont_color');
             break;
	     case 'bodyfont_size':
                return Mage::getStoreConfig('mygeneral/themefont/bodyfont_size');
             break;
	     case 'bodyfont':
                return Mage::getStoreConfig('mygeneral/themefont/bodyfont');
             break;
	     
	    
		
	}
    }
    
    
    
}
?>