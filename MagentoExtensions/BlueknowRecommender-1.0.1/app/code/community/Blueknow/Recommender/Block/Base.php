<?php
/**
 * Base block.
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
class Blueknow_Recommender_Block_Base extends Mage_Core_Block_Template {
	
	/**
	 * Configuration domain object.
	 * @var Blueknow_Recommender_Model_Configuration
	 */
	protected $_configuration;
	
	public function _beforeToHtml() {
		parent::_beforeToHtml();
		$this->_configuration = Mage::getModel('blueknow_recommender/Configuration');
	}
	
	public function _toHtml() {
		//the block is rendered only if module is enabled
		if ($this->_configuration->isEnabled()) {
			return parent::_toHtml();
		}
		return '';
	}
	
	/**
	 * Get Blueknow service configuration.
	 * @return Blueknow_Recommender_Model_Configuration
	 */
	public function getConfig() {
		return $this->_configuration;
	}
	
	/**
	 * Get current session.
	 * @return Blueknow_Recommender_Model_Session
	 */
	protected function _getSession() {
		return Mage::getSingleton('blueknow_recommender/session');
	}
}