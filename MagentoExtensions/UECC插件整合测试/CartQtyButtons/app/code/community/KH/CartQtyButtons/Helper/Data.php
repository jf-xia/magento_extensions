<?php
/**
 * KH_CartQtyButtons_Helper_Data
 * 
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category  	KH
 * @package    	KH_CartQtyButtons
 * @copyright  	Copyright (c) 2011 <info@kevinhorst.de> - KevinHorst IT
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      KevinHorst IT <info@kevinhorst.de>
 */
class KH_CartQtyButtons_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * get js selector configuration value
	 * 
	 * @return string
	 */
	public function getJsSelector ()
	{
		$selector = Mage::getStoreConfig('cartqtybuttons/settings/js_selector');
		
		return $selector;
	}
	
	/**
	 * get button position configuration value
	 * 
	 * @return string
	 */
	public function getBtnPosition ()
	{
		$position = Mage::getStoreConfig('cartqtybuttons/settings/button_position');
		
		return $position;
	}
	
	/**
	 * get null behavior configuration value
	 * 
	 * @return bool
	 */
	public function getNullBehavior ()
	{
		$remove = Mage::getStoreConfig('cartqtybuttons/settings/null_behavior');
		
		return $remove;
	}
	
	/**
	 * get increase step configuration value
	 *
	 * @return int
	 */
	public function getIncreaseStep ()
	{
		$step = Mage::getStoreConfig('cartqtybuttons/settings/increase_step');
		
		return (int) $step;
	}
	
	/**
	 * get increase decimal step configuration value
	 *
	 * @return float
	 */
	public function getIncreaseDecimalStep ()
	{
		$step = Mage::getStoreConfig('cartqtybuttons/settings/increase_decimal_step');
		
		return (float) $step;
	}
	
	/**
	 * fetch cart-item informations for increase/decrease steps
	 * 
	 * @return array
	 */
	public function getStepInformation ()
	{
		/* @var $cart Mage_Checkout_Model_Cart */
		$cart = Mage::helper('checkout/cart')->getCart();
		
		$items = array();
		
		/* @var $item Mage_Sales_Model_Quote_Item */
		foreach ($cart->getItems() as $item) {
			/* @var $stockItem Mage_CatalogInventory_Model_Stock_Item */
			$stockItem = Mage::getModel('cataloginventory/stock_item')
						     ->loadByProduct($item->getProduct());
			
			if (! $stockItem->getQtyIncrements()) {
				if (is_int($item->getQty())) {
					$increaseStep = $this->getIncreaseStep();
				} else {
					$increaseStep = $this->getIncreaseDecimalStep();
				}
			} else {
				$increaseStep = $stockItem->getQtyIncrements();
			}
			
			$items[] = array(
				'increaseQty' => $item->getQty() + $increaseStep,
				'decreaseQty' => $item->getQty() - $increaseStep <= 0 ? 0 : $item->getQty() - $increaseStep,
			);
		}
		
		return $items;
	}
}