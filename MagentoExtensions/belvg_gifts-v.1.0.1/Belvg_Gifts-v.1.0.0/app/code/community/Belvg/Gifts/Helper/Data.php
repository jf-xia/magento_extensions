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

class Belvg_Gifts_Helper_Data extends Mage_Core_Helper_Abstract{

	 public function getQuoteTotal(){
	 	$quote = Mage::getSingleton('checkout/session')->getQuote();
	 	return $quote->getGrandTotal();
	 }
	 
	 public function getQuoteQty(){
	 	$quote = Mage::getSingleton('checkout/session')->getQuote();
	 	$qty = !is_null($quote->getItemsSummaryQty()) ? $quote->getItemsSummaryQty() : 0;
	 	if($this->isGiftUsed())$qty-=1;
	 	return $qty; 
	 }
	 
	 public function getRules(){
	 	$store_id = Mage::app()->getStore()->getId();
	 	$collection = Mage::getModel('gifts/gifts')->getCollection();
	 	$total = !is_null($this->getQuoteTotal()) ? $this->getQuoteTotal() : 0;
        $collection ->addFieldToFilter('store', array('in' => array($store_id, 0)))
   					->addFilter('status', 1)
   					->addFieldToFilter('qty', array('lteq' => $this->getQuoteQty()))
   					->addFieldToFilter('amount', array('lteq' => $total));
   					//->getSelect()->where('qty <=' . $this->getQuoteQty() . ' OR amount <=' . $total);
   		return $collection;
	 }
	 
	 public function getAllRules(){
	 	$store_id = Mage::app()->getStore()->getId();
	 	$collection = Mage::getModel('gifts/gifts')->getCollection();
        $collection ->addFieldToFilter('store', array('in' => array($store_id, 0)))
   					->addFilter('status', 1);
   		return $collection;
	 }
	 
	 public function isGiftUsed(){
	 	$gift_ids = $this->getGiftsIds(1);
	 	if(is_array($gift_ids) && count($gift_ids)){
	 		$quote = Mage::getSingleton('checkout/session')->getQuote();
	 		foreach ($quote->getAllItems() as $item){
                if (in_array($item->getProductId(), $gift_ids)  && !floatval($item->getBasePrice())){
                    return $item->getProductId();
                }
            }
	 	}
	 	else return false;
	 }
	 
	 public function getGiftsIds($param = 0){
	 	$ids = array();
	 	if($param)$rules = $this->getAllRules();
	 	else $rules = $this->getRules();
   		if($rules->getSize()){
   			foreach($rules as $rule){
   				if(Mage::getModel('gifts/product')->getCollection()->addFilter('gift_id', $rule->getId())->getSize()){
   					$products_model = Mage::getModel('gifts/product')->getCollection()->addFieldToFilter('gift_id', $rule->getId())->load();
                    foreach ($products_model as $product) {
                    	if(Mage::getModel('catalog/product')->load($product->getProductId())->isSalable()){
                        	$quote = Mage::getSingleton('checkout/session')->getQuote();
                        	$gift_push = 1;
                        	if(!$param){
                        		foreach ($quote->getAllItems() as $item){
                        			if($item->getProductId() == $product->getProductId() && floatval($item->getPrice())){
                        				$gift_push = 0;
                        				break;
                        			} 
                        		}
                        	}
                        	if($gift_push)array_push($ids, $product->getProductId());
                        }
                    }
   				}
   			}
   		}
   		return array_unique($ids);	
	 }
}