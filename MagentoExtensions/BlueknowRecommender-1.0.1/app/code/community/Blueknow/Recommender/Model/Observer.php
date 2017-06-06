<?php
/**
 * Blueknow Recommender observer.
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
class Blueknow_Recommender_Model_Observer {
	
	/**
	 * Activate new-login flag from Blueknow Recommender session scope.
	 * @param Varien_Event_Observer $observer
	 */
	public function setNewLoginWhenCustomerLogIn(Varien_Event_Observer $observer) {
		$this->_getSession()->setNewLogin();
	}
	
	/**
	 * Deactivate new-login flag from Blueknow Recommender session scope.
	 * @param Varien_Event_Observer $observer
	 */
	public function unsetNewLoginAfterTracking(Varien_Event_Observer $observer) {
		$this->_getSession()->unsetNewLogin();
	}
	
	/**
	 * Retrieve Blueknow Recommender session object.
	 * @return Mage_Core_Model_Session_Abstract
     */
	protected function _getSession() {
		return Mage::getSingleton('blueknow_recommender/session');
	}
}