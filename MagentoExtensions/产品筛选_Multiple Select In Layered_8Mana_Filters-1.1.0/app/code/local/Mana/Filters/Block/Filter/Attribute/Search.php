<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Block type for showing options for filter based on custom attribute, specific for search
 * @author Mana Team
 * Injected into layout instead of standard catalog/layer_filter_attribute in Mana_Filters_Block_View_Search::_initBlocks.
 */
class Mana_Filters_Block_Filter_Attribute_Search extends Mana_Filters_Block_Filter_Attribute {
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'mana_filters/filter_attribute_search';
    }
}