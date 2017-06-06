<?php
/**
 * Currency helper.
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
class Blueknow_Recommender_Helper_Currency extends Mage_Core_Helper_Abstract {
	
	/**
	 * Get current currency.
	 * @return Blueknow_Recommender_Model_Currency.
	 */
	public function getCurrentCurrency() {
		$code = Mage::app()->getStore()->getCurrentCurrencyCode();
		return $this->_getCurrency($code);
	}
	
	/**
	 * Get default currency.
	 * @return Blueknow_Recommender_Model_Currency.
	 */
	public function getDefaultCurrency() {
		$code = Mage::app()->getStore()->getDefaultCurrencyCode();
		return $this->_getCurrency($code);
	}
	
	/**
	 * Get currency from ISO code. 
	 * @param string $code
	 */
	protected function _getCurrency($code) {
		$zcurrency = Mage::app()->getLocale()->currency($code);
		$tcurrency = Mage::getModel('blueknow_recommender/Currency');
		$tcurrency->setCode($zcurrency->getShortName());
		$tcurrency->setSymbol($zcurrency->getSymbol());
		return $tcurrency;
	}
}