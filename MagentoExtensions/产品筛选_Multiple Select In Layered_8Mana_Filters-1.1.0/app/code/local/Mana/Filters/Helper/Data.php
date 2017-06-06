<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/* BASED ON SNIPPET: New Module/Helper/Data.php */
/**
 * Generic helper functions for Mana_Filters module. This class is a must for any module even if empty.
 * @author Mana Team
 */
class Mana_Filters_Helper_Data extends Mage_Core_Helper_Abstract {
	/**
	 * Recognizes block type based on its class. 
	 * OO purists would say that kind of ifs should be done using virtual functions. Here we ignore OO-ness and 
	 * micro performance penalty for the sake of clarity and keeping logic in one file.
	 * @param Mage_Catalog_Block_Layer_Filter_Abstract $block
	 * @return string
	 */
	public function getBlockType($block) {
		if ($block instanceof Mana_Filters_Block_Filter_Attribute) return 'attribute';
		elseif ($block instanceof Mana_Filters_Block_Filter_Category) return 'category';
		elseif ($block instanceof Mana_Filters_Block_Filter_Decimal) return 'decimal';
		elseif ($block instanceof Mana_Filters_Block_Filter_Price) return 'price';
		else throw new Exception('Not implemented');
	}
	/**
	 * Return unique filter name. 
	 * OO purists would say that kind of ifs should be done using virtual functions. Here we ignore OO-ness and 
	 * micro performance penalty for the sake of clarity and keeping logic in one file.
	 * @param Mage_Catalog_Model_Layer_Filter_Abstract $model
	 * @return string
	 */
	public function getFilterName($model) {
		if ($model instanceof Mana_Filters_Model_Filter_Category) return 'category';
		else return $model->getAttributeModel()->getAttributeCode();
	}
	// INSERT HERE: helper functions that should be available from any other place in the system
	public function getJsPriceFormat() {
		return $this->formatPrice(0);
	}
	public function formatPrice($price) {
		$store = Mage::app()->getStore();
        if ($store->getCurrentCurrency()) {
            return $store->getCurrentCurrency()->formatPrecision($price, 0, array(), false, false);
        }
        return $price;
	}
}