<?php
/**
 * Shopping cart object model.
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
class Blueknow_Recommender_Model_Cart extends Varien_Object {
	
	/**
	 * Products (identifiers) inside shopping cart.
	 * @var array
	 */
	private $_products;
	
	/**
	 * Get products form current shopping cart.
	 * @return array
	 */
	public function getProducts() {
		if (!$this->_products) { //empty cart is directly returned
			$cart = Mage::helper('checkout/cart')->getCart();
			$this->_products = $cart ? $cart->getProductIds() : array();
		}
		return $this->_products;
	}
}