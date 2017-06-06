<?php
/**
 * Checkout tracker block.
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
class Blueknow_Recommender_Block_Checkout_Tracker extends Blueknow_Recommender_Block_Base {
	
	/**
	 * Checkout domain object.
	 * @var Blueknow_Recommender_Model_Checkout
	 */
	protected $_checkout;
	
	public function _beforeToHtml() {
		parent::_beforeToHtml();
		$this->_checkout = Mage::getModel('blueknow_recommender/Checkout');
	}
	
	public function _toHtml() {
		$orders = $this->_checkout->getOrders();
		//the block is rendered only if there are one or more orders
		if (!empty($orders)) {
			return parent::_toHtml();
		}
		return '';
	}
	
	/**
	 * Get current checkout considering both one page and multishipping checkout.
	 * @return Blueknow_Recommender_Model_Checkout
	 */
	public function getCheckout() {
		return $this->_checkout;
	}
}