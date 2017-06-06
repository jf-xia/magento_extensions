<?php
/**
 * Customer tracker block.
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
class Blueknow_Recommender_Block_Customer_Tracker extends Blueknow_Recommender_Block_Base {
	
	/**
	 * Customer domain object.
	 * @var Blueknow_Recommender_Model_Customer
	 */
	protected $_customer;
	
	public function _beforeToHtml() {
		parent::_beforeToHtml();
		$this->_customer = Mage::getModel('blueknow_recommender/Customer');
	}
	
	public function _toHtml() {
		//the block is rendered only on first customer login (once per session)
		if ($this->_getSession()->isNewLogin()) {
			Mage::dispatchEvent('customer_login_tracked');
			return parent::_toHtml();
		}
		return '';
	}
	
	/**
	 * Get current customer (user) logged.
	 * @return Blueknow_Recommender_Model_Customer
	 */
	public function getCustomer() {
		return $this->_customer;
	}
}