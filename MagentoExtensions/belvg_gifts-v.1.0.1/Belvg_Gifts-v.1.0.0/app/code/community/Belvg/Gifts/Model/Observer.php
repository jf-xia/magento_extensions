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
 
class Belvg_Gifts_Model_Observer extends Mage_Core_Model_Abstract
{

	public function checkGiftQty(&$event){		
		$quote = Mage::getSingleton('checkout/session')->getQuote();
        if(Mage::helper('gifts')->isGiftUsed() && !Mage::helper('gifts')->getQuoteQty()){
        	foreach ($quote->getAllItems() as $item) {
        		Mage::getSingleton('checkout/cart')->removeItem($item->getId());
        	}
        	Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
        	return true;
        }
        foreach ($quote->getAllItems() as $item) {             
            if(!floatval($item->getPrice()) && in_array($item->getProductId(), Mage::helper('gifts')->getGiftsIds(1))){
				if($item->getQty() > 1){
					$item->setQty(1);
				}
			}
			if(!count(Mage::helper('gifts')->getGiftsIds()) && Mage::helper('gifts')->isGiftUsed()){
        		if(Mage::helper('gifts')->isGiftUsed() == $item->getProductId())Mage::getSingleton('checkout/cart')->removeItem($item->getId());
        	}
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);    
	}

}