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
 
class Belvg_Gifts_Block_Gifts extends Mage_Core_Block_Template {

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    public function getQuoteQty() {
        return Mage::helper('gifts')->getQuoteQty();
    }
    
    public function getQuoteTotal() {
        return Mage::helper('gifts')->getQuoteTotal();
    }
    
    public function getGiftProducts(){
    	$ids = Mage::helper('gifts')->getGiftsIds();
    	$products = array();
    	if(is_array($ids) && count($ids)){
    		foreach($ids as $id){
    			array_push($products, Mage::getModel('catalog/product')->load($id));
    		}
    	}
    	return $products;
    }    
}