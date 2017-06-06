<?php
/**
 * Base product block.
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
class Blueknow_Recommender_Block_Product_Base extends Blueknow_Recommender_Block_Base {
	
	/**
	 * Product domain object.
	 * @var Blueknow_Recommender_Model_Product
	 */
	protected $_product;
	
	public function _beforeToHtml() {
		parent::_beforeToHtml();
		$this->_product = Mage::getModel('blueknow_recommender/Product');
	}
	
	/**
	 * Get current product.
	 * @return Blueknow_Recommender_Model_Product
	 */
	public function getProduct() {
		return $this->_product;
	}
}