<?php
/**
 * Checkout object model.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Blueknow Recommender
 * extension to newer versions in the future. If you wish to customize it for
 * your needs please save your changes before upgrading.
 * 
 * @category	Blueknow
 * @package		Blueknow_Recommender
 * @copyright	Copyright (c) 2010 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * @since		1.0.0
 * 
 */
class Blueknow_Recommender_Model_Checkout extends Varien_Object {
	
	/**
	 * Collection of orders.
	 * @var array of Blueknow_Recommender_Model_Checkout_Order
	 */
	private $_orders = array();
	
	/**
	 * Get last successfull orders from current session.
	 * @return array of Blueknow_Recommender_Model_Checkout_Order
	 */
	public function getOrders() {
		if (empty($this->_orders)) {
			//get last quote identifier
			$quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
			if ($quoteId) {
				$store = Mage::app()->getStore();
				//orders registry
				$ordersId = array();
				//get quote
	            $quote = Mage::getModel('sales/quote')->load($quoteId);
	            //load orders (one or more orders according to the type of shipping used: single or mutiple)
	            $orders = Mage::getResourceModel('sales/order_collection')->addAttributeToFilter('quote_id', $quoteId)->load();
	            //orders iteration
	            foreach ($orders as $order) {
	            	$orderId = $order->getIncrementId();
	            	if (in_array($orderId, $ordersId)) {
	            		continue; //order already processed
	            	}
	            	//register order
	            	$ordersId[] = $orderId;
	            	//create our own order
	            	$bOrder = Mage::getModel('blueknow_recommender/Checkout_Order');
	            	$bOrder->setId($orderId);
	            	$bOrder->setTotal($store->roundPrice($order->getBaseGrandTotal()));
	            	$bOrder->setTax($store->roundPrice($order->getBaseTaxAmount()));
	            	$bOrder->setShipping($store->roundPrice($order->getBaseShippingAmount()));
	            	foreach ($order->getAllItems() as $item) {
	            		if ($item->getParentItemId()) {
	            			continue;
	            		}
	            		$bProduct = Mage::getModel('blueknow_recommender/Checkout_Product');
	            		$bProduct->setId($item->getProductId());
	            		$bProduct->setPrice($store->roundPrice($item->getBasePrice()));
	            		$bProduct->setQuantity(intval($item->getQtyOrdered()));
	            		//get saleable information
	            		$bProduct->setSaleable(Mage::getModel('catalog/product')->load($item->getProductId())->isSaleable());
	            		//add product
	            		$bOrder->addProduct($bProduct);
	            	}
	            	//add order
	            	$this->_orders[] = $bOrder;
				}
			}
		}
		return $this->_orders;
	}
}