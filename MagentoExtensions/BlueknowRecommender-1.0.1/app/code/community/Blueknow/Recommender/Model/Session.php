<?php
/**
 * Blueknow Recommender session.
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
class Blueknow_Recommender_Model_Session extends Mage_Core_Model_Session_Abstract {
	
	public function __construct() {
		$this->init('blueknow_recommender');
	}
	
	/**
	 * Activate the new-login flag.
	 */
	public function setNewLogin() {
		$this->setData('newLogin', true);
	}
	
	/**
	 * Deactivate the new-login flag.
	 */
	public function unsetNewLogin() {
		$this->setData('newLogin', false);
	}
	
	/**
	 * Get the new-login flag.
	 * @return bool
	 */
	public function isNewLogin() {
		return $this->getData('newLogin');
	}
}