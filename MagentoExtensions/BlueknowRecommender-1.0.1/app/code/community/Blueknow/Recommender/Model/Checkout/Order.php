<?php
/**
 * Order object model.
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
class Blueknow_Recommender_Model_Checkout_Order extends Varien_Object {
	
	/**
	 * Order identifier.
	 * @var string
	 */
	private $_id;
	
	/**
	 * Total amount including taxes and shipping.
	 * @var number|string
	 */
	private $_total;
	
	/**
	 * Taxes amount.
	 * @var number|string
	 */
	private $_tax;
	
	/**
	 * Shipping amount.
	 * @var number|string
	 */
	private $_shipping;
	
	/**
	 * List of ordered products.
	 * @var Blueknow_Recommender_Model_Checkout_Product
	 */
	private $_products = array();
	
	/**
	 * Get order identifier.
	 * @return string
	 */
	public function getId() {
		return $this->_id;
	}
	
	/**
	 * Set order identifier.
	 * @param string $id
	 */
	public function setId($id) {
		$this->_id = $id;
	}
	
	/**
	 * Get total amount.
	 * @return number|string
	 */
	public function getTotal() {
		return $this->_total;
	}
	
	/**
	 * Set total amount.
	 * @param number|string $total
	 */
	public function setTotal($total) {
		$this->_total = $total;
	}
	
	/**
	 * Get taxes amount.
	 * @return number|string
	 */
	public function getTax() {
		return $this->_tax;
	}
	
	/**
	 * Set taxes amount.
	 * @param number|string $tax
	 */
	public function setTax($tax) {
		$this->_tax = $tax;
	}
	
	/**
	 * Get shipping amount.
	 * @return number|string
	 */
	public function getShipping() {
		return $this->_shipping;
	}
	
	/**
	 * Set shipping amount.
	 * @param number|string $shipping
	 */
	public function setShipping($shipping) {
		$this->_shipping = $shipping;
	}
	
	/**
	 * Get ordered products.
	 * @return array
	 * @see Blueknow_Recommender_Model_Checkout_Product
	 */
	public function getProducts() {
		return $this->_products;
	}
	
	/**
	 * Add a new product to the current order.
	 * @param Blueknow_Recommender_Model_Checkout_Product $product
	 */
	public function addProduct($product) {
		$this->_products[] = $product;
	}
}