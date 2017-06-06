<?php
/**
 * Shopping cart recommender block.
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
class Blueknow_Recommender_Block_Cart_Recommender extends Blueknow_Recommender_Block_Base {
	
	/**
	 * Cart domain object.
	 * @var Blueknow_Recommender_Model_Cart
	 */
	protected $_cart;
	
	public function _beforeToHtml() {
		parent::_beforeToHtml();
		$this->_cart = Mage::getModel('blueknow_recommender/Cart');
	}
	
	public function _toHtml() {
		//the block is rendered only if up-sell is enabled and there are one or more products inside shopping cart
		if ($this->_configuration->isUpSellEnabled() && count($this->_cart->getProducts()) > 0) {
			return parent::_toHtml();
		}
		return '';
	}
	
	/**
	 * Get current shopping cart.
	 */
	public function getCart() {
		return $this->_cart;
	}
}