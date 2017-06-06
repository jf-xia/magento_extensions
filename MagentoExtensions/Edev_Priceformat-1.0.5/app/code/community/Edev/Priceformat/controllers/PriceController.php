<?php
/**
 *  ajax price update for configurabale product
 */
class Edev_Priceformat_PriceController extends Mage_Core_Controller_Front_Action
{
	public function ajaxAction(){
		$price = $this->getRequest()->getPost('price');
		$result = Edev_Priceformat_Model_Formater::getFormat(array(),$price);
		echo Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->toCurrency($price,$result);	
	}
}
