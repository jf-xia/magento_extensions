<?php
/**
 * Ordered product object model.
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
class Blueknow_Recommender_Model_Checkout_Product extends Varien_Object {
	
	/**
	 * Ordered product identifier.
	 * @var string
	 */
	private $_id;
	
	/**
	 * Ordered product price. It could be different from orginal product price due to discounts or something like this.
	 * @var number|string
	 */
	private $_price;
	
	/**
	 * Ordered product quantity.
	 * @var number
	 */
	private $_quantity;
	
	/**
	 * Product is-saleable flag.
	 * @var bool
	 */
	private $_saleable;
	
	/**
	 * Get ordered product identifier.
	 * @return string
	 */
	public function getId() {
		return $this->_id;
	}
	
	/**
	 * Set ordered product identifier.
	 * @param string $id
	 */
	public function setId($id) {
		$this->_id = $id;
	}
	
	/**
	 * Get ordered product price.
	 * @return number|string
	 */
	public function getPrice() {
		return $this->_price;
	}
	
	/**
	 * Set ordered product price. It should include taxes and currency conversion applied.
	 * Unitary price. No currency symbol.
	 * @param number|string $price
	 */
	public function setPrice($price) {
		$this->_price = $price;
	}
	
	/**
	 * Get ordered product quantity.
	 * @return number|string.
	 */
	public function getQuantity() {
		return $this->_quantity;
	}
	
	/**
	 * Set ordered product quantity.
	 * @param number|string $qty
	 */
	public function setQuantity($qty) {
		$this->_quantity = $qty;
	}
	
	/**
	 * Get if product is saleable (in stock)
	 * @return bool
	 */
	public function isSaleable() {
		return $this->_saleable;
	}
	
	/**
	 * Set if product is saleable (in stock) after to be ordered (successfull transaction).
	 * @param bool $saleable
	 */
	public function setSaleable($saleable) {
		$this->_saleable = $saleable;
	}
}