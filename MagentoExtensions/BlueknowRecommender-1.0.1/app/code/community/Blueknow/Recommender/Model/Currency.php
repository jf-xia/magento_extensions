<?php
/**
 * Currency object model.
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
class Blueknow_Recommender_Model_Currency extends Varien_Object {
	
	/**
	 * Currency ISO code (http://es.wikipedia.org/wiki/ISO_4217).
	 * @var string
	 */
	private $_code;
	
	/**
	 * Currency symbol.
	 * @var string
	 */
	private $_symbol;
	
	/**
	 * Get currency ISO code.
	 * @return string
	 */
	public function getCode() {
		return $this->_code;
	}
	
	/**
	 * Set currency ISO code. It must be a valid ISO 4217 code name.
	 * @param string $code
	 */
	public function setCode($code) {
		$this->_code = $code;
	}
	
	/**
	 * Get currency symbol.
	 * @return string
	 */
	public function getSymbol() {
		return $this->_symbol;
	}
	
	/**
	 * Set currency symbol (e.g. EURO, Û, eur)
	 * @param string $symbol
	 */
	public function setSymbol($symbol) {
		$this->_symbol = $symbol;
	}
}