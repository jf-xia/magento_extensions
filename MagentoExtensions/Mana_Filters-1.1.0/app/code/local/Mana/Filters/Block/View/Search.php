<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Block type for showing filters in search pages.
 * @author Mana Team
 * Injected into layout instead of standard catalogsearch/layer in layout XML file.
 */
class Mana_Filters_Block_View_Search extends Mage_CatalogSearch_Block_Layer {
    /**
     * This method is called during page rendering to determine block types to use inside layered navigation block. 
     * This method is overridden by copying (method body was pasted from parent class and modified as needed). All
     * changes are marked with comments.
     * @see Mage_CatalogSearch_Block_Layer::_initBlocks()
     */
    protected function _initBlocks()
    {
        $this->_stateBlockName = 'catalog/layer_state';
        // MANA BEGIN: replace standard block types with ours
        $this->_categoryBlockName = 'mana_filters/filter_category';
        $this->_attributeFilterBlockName = 'mana_filters/filter_attribute_search';
        $this->_priceFilterBlockName = 'mana_filters/filter_price';
        $this->_decimalFilterBlockName = 'mana_filters/filter_decimal';
        // MANA END
    }
}