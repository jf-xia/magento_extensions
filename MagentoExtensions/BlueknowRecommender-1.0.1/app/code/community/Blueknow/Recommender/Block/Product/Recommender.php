<?php
/**
 * Product recommender block.
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
class Blueknow_Recommender_Block_Product_Recommender extends Blueknow_Recommender_Block_Product_Base {
	
	public function _toHtml() {
		//the block is rendered only if cross-sell is enabled (recommendations in product detail page)
		if ($this->_configuration->isCrossSellEnabled()) {
			return parent::_toHtml();
		}
		return '';
	}
}