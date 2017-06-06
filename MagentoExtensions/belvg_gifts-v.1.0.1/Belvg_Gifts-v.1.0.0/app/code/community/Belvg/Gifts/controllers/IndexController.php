<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 *****************************************/
 /* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
 /***************************************
 *         DISCLAIMER   *
 *****************************************/
 /* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Belvg
 * @package    Belvg_Gifts
 * @copyright  Copyright (c) 2010 - 2012 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_Gifts_IndexController extends Mage_Core_Controller_Front_Action {
	public function addAction()
    {	
        if ($this->getRequest()->isGet()) {
            $gift_id = $this->getRequest()->getParam('gift', 0);
            if($gift_id){
            	if(!Mage::helper('gifts')->isGiftUsed() && in_array($gift_id, Mage::helper('gifts')->getGiftsIds())){
            		$quote = Mage::getSingleton('checkout/session')->getQuote();
                	$cart = Mage::getModel('checkout/cart');
        			$product = new Mage_Catalog_Model_Product();
        			$product->load($gift_id);

        			$cart->addProduct($product, 1);
        			$cart->save();
        		
        			foreach ($quote->getAllItems() as $item) {
                                    
                   		if ($item->getProductId() == $gift_id) {
                       		$item->setCustomPrice(0);
                       		$item->setOriginalCustomPrice(0);
                   		}
                	}
                	
                	$cart->init();
                	$cart->save();          
        		
        			Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
        		}
            }
        }
        $this->_redirect('checkout/cart');
    }
}