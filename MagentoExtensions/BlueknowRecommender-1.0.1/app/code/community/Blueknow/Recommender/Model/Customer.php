<?php
/**
 * Customer object model.
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
class Blueknow_Recommender_Model_Customer extends Varien_Object {
	
	/**
	 * Customer identifier.
	 * @var mixed|null
	 */
	private $_customerId;
	
	/**
	 * Get the identifier of the current user logged, if any.
	 * @return mixed|null
	 */
	public function getIdentifier() {
		if (empty($this->_customerId)) {
			$session = Mage::getSingleton('customer/session');
			if ($session->isLoggedIn()) {
				$this->_customerId = $session->getCustomer()->getId();
			} else {
				$this->_customerId = null; //not logged
			}
		}
		return $this->_customerId;
	}
}