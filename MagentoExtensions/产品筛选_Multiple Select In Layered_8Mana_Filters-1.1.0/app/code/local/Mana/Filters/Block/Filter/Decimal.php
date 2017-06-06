<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Block type for showing options for filter based on custom number attribute
 * @author Mana Team
 * Injected into layout instead of standard catalog/layer_filter_decimal in Mana_Filters_Block_View_Category::_initBlocks.
 */
class Mana_Filters_Block_Filter_Decimal extends Mage_Catalog_Block_Layer_Filter_Decimal {
	// NO CHANGES HERE, BUT PROBABLY THEY WILL COME IN NEAR FUTURE
    
    /**
     * Returns underlying model object which contains actual filter data
     * @return Mage_Catalog_Model_Layer_Filter_Attribute
     */
    public function getFilter() {
    	return $this->_filter;
    }
}