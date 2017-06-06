<?php
/**
 * @package Wordgento
 */
/*
Plugin Name: Wordgento
Plugin URI: http://www.wordgento-pro.com
Description: Wordgento allows you to seamlessly integrate blocks from your Magento installation into your Wordpress theme
Version: 1.0.0
Author: James C Kemp
Author URI: http://www.jckemp.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/


function wordgento_admin() {  
    include('wordgento_admin.php');  
}

function wordgento_admin_actions() {
	add_menu_page('Wordgento', 'Wordgento', 'administrator', __FILE__, 'wordgento_admin',plugins_url('/images/icon.png', __FILE__));
}

add_action('admin_menu', 'wordgento_admin_actions');

function wordgento_setoptions() {
	add_option('wordgento_magepath', '/your-magento');
	add_option('wordgento_theme', 'default');
	add_option('wordgento_store', 'default');
}

function wordgento_unsetoptions() {
	delete_option('wordgento_magepath');
	delete_option('wordgento_theme');
	delete_option('wordgento_store');
}

register_activation_hook(__FILE__,'wordgento_setoptions');
// register_deactivation_hook(__FILE__,'wordgento_unsetoptions');

function wordgento_magento() {
	
	$wordgento_mage = get_option('wordgento_magepath');
	$wordgento_theme = strtolower(get_option('wordgento_theme'));
	$wordgento_store = strtolower(get_option('wordgento_store'));
	$filename = $_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/Mage.php';
	
	if(file_exists($filename)) {
	
		$wordgento_mage = get_option('wordgento_magepath');
		$wordgento_theme = strtolower(get_option('wordgento_theme'));
		$wordgento_store = strtolower(get_option('wordgento_store'));
		
		require_once($_SERVER['DOCUMENT_ROOT'].$wordgento_mage.'/app/Mage.php');
		umask(0);
		// Mage::run('default');
		
		$app = Mage::app($wordgento_store);		
		Mage::getSingleton('core/session', array('name'=>'frontend'));
		$session = Mage::getSingleton('customer/session', array('name'=>'frontend'));
		
		$Block = Mage::getSingleton('core/layout');
		Mage::getDesign()->setTheme($wordgento_theme);
		$app->getTranslator()->init('frontend'); 	
		
		# Init Blocks
		$linksBlock = $Block->createBlock("page/template_links");
		
		$checkoutLinksBlock = $Block->createBlock("checkout/links");
		$checkoutLinksBlock->setParentBlock($linksBlock);
		
		// Wishlist Link in top.links
		
		if ($linksBlock && $linksBlock->helper('wishlist')->isAllow()) {
		
		$count = $linksBlock->helper('wishlist')->getItemCount();
		
		if ($count > 1) {
		
		$text = $linksBlock->__('My Wishlist (%d items)', $count);
		
		}
		
		else if ($count == 1) {
		
		$text = $linksBlock->__('My Wishlist (%d item)', $count);
		
		}
		
		else {
		
		$text = $linksBlock->__('My Wishlist');
		
		}
		
		$linksBlock->addLink($text, 'wishlist', $text, true, array(), 30, null, 'class="top-link-wishlist"');
		
		}
	
		// End Wishlist Link in top.links
		
		# Add Links
		$linksBlock->addLink($linksBlock->__('My Account'), 'customer/account', $linksBlock->__('My Account'), true, array(), 10, 'class="first"');
		
		// $wishlistLinksBlock->addWishlistLink();
		$checkoutLinksBlock->addCartLink();
		$checkoutLinksBlock->addCheckoutLink();
		
		if ($session->isLoggedIn()) {
		$linksBlock->addLink($linksBlock->__('Log Out'), 'customer/account/logout', $linksBlock->__('Log Out'), true, array(), 100, 'class="last"');
		} else {
		$linksBlock->addLink($linksBlock->__('Log In'), 'customer/account/login', $linksBlock->__('Log In'), true, array(), 100, 'class="last"');
		}
		
		$toplinks = $linksBlock->toHtml();
		
		// Create head.phtml block
		$head = $Block->createBlock('Page/Html_Head');
		// Add Js	
		$head->addJs('prototype/prototype.js');
		$head->addJs('lib/ccard.js');
		$head->addJs('prototype/validation.js');
		$head->addJs('scriptaculous/builder.js');
		$head->addJs('scriptaculous/effects.js');
		$head->addJs('scriptaculous/dragdrop.js');
		$head->addJs('scriptaculous/controls.js');
		$head->addJs('scriptaculous/slider.js');
		$head->addJs('varien/js.js');
		$head->addJs('varien/form.js');
		$head->addJs('varien/menu.js');
		$head->addJs('mage/translate.js');
		$head->addJs('mage/cookies.js');
		// Add CSS
		$head->addCss('css/styles.css');
		
		// Activate and Convert head.phtml html
		$getcss = $head->getCssJsHtml();
		$getinc = $head->getIncludes();
		
		// And the footer's HTML as well
		$footer = $Block->createBlock('Page/Html_Footer')->setTemplate('page/html/footer.phtml');
		$getfooter = $footer->toHtml();
		
		// And the footer's HTML as well
		$header = $Block->createBlock('Page/Html_Header');
		$getwelcome = $header->getWelcome();
		$getlogosrc = $header->getLogoSrc();
		$getlogoalt = $header->getLogoAlt();
		$geturl = $header->getUrl();
		
		$logo = "<img src='".$getlogosrc."' alt='".$getlogoalt."' />";
		
		
		// Add topSearch
		$block_topsearch = $Block->createBlock('core/template')->setTemplate("catalogsearch/form.mini.phtml")->toHtml();
		
		// Add cart_sidebar
		$block_sidecart = $Block->createBlock('checkout/cart_sidebar')->setTemplate("checkout/cart/sidebar.phtml")->toHtml();
		
		// Add cart_sidebar
		$block_topcart = $Block->createBlock('checkout/cart_sidebar')->setTemplate("checkout/cart/topcart.phtml")->toHtml();
		
		// Add catalog.compare.sidebar
		$block_compare = $Block->createBlock('catalog/product_compare_sidebar')->setTemplate("catalog/product/compare/sidebar.phtml")->toHtml();
		
		// Add right.reports.product.viewed
		$block_viewed = $Block->createBlock('reports/product_viewed')->setTemplate("reports/product_viewed.phtml")->toHtml();
		
		// Add right.reports.product.viewed
		$block_newsletter = $Block->createBlock('newsletter/subscribe')->setTemplate("newsletter/subscribe.phtml")->toHtml();
		
		// Add topMenu
		$block_topmenu = $Block->createBlock('catalog/navigation')->setTemplate("catalog/navigation/top.phtml")->toHtml();
		
		// Add wishlist_sidebar
		$block_wishlist = $Block->createBlock('wishlist/customer_sidebar')->setTemplate("wishlist/sidebar.phtml")->toHtml();
		
		define("MAGE_CSSJS", $getcss);
		define("MAGE_INC", $getinc);
		define("MAGE_WISHLIST", $block_wishlist);
		define("MAGE_SEARCH", $block_topsearch);
		define("MAGE_TOPMENU", $block_topmenu);
		define("MAGE_NEWSLETTER", $block_newsletter);
		define("MAGE_VIEWED", $block_viewed);
		define("MAGE_TOPLINKS", $toplinks);
		define("MAGE_SIDECART", $block_sidecart);
		define("MAGE_TOPCART", $block_topcart);
		define("MAGE_COMPARE", $block_compare);
		define("MAGE_WELCOME", $getwelcome);
		define("MAGE_LOGO", $logo);
		define("MAGE_URL", $geturl);
	
	} else {
		
		$error = "The path you entered to your magento installation folder was incorrect! Please go to the Wordgento settings and enter the right path.";
		
		define("MAGE_CSSJS", $error);
		define("MAGE_INC", $error);
		define("MAGE_WISHLIST", $error);
		define("MAGE_SEARCH", $error);
		define("MAGE_TOPMENU", $error);
		define("MAGE_NEWSLETTER", $error);
		define("MAGE_VIEWED", $error);
		define("MAGE_TOPLINKS", $error);
		define("MAGE_SIDECART", $error);
		define("MAGE_TOPCART", $error);
		define("MAGE_COMPARE", $error);
		define("MAGE_WELCOME", $error);
		define("MAGE_LOGO", $error);
		define("MAGE_URL", $error);
	}
	
}

add_action('get_header', 'wordgento_magento', 1);

function wordgento($vwg_var) {

	switch ($vwg_var) {
		case 'cssjs':
			return MAGE_CSSJS;
			break;
		case 'inc':
			return MAGE_INC;
			break;
		case 'wishlist':
			return MAGE_WISHLIST;
			break;
		case 'search':
			return MAGE_SEARCH;
			break;
		case 'topmenu':
			return MAGE_TOPMENU;
			break;
		case 'newsletter':
			return MAGE_NEWSLETTER;
			break;
		case 'recently_viewed':
			return MAGE_VIEWED;
			break;
		case 'toplinks':
			return MAGE_TOPLINKS;
			break;
		case 'compare':
			return MAGE_COMPARE;
			break;
		case 'sidecart':
			return MAGE_SIDECART;
			break;
		case 'topcart':
			return MAGE_TOPCART;
			break;
		case 'welcome':
			return MAGE_WELCOME;
			break;
		case 'logo':
			return MAGE_LOGO;
			break;
		case 'url':
			return MAGE_URL;
			break;
		default:
       		return "This is not currently available.";
			break;
	}

}