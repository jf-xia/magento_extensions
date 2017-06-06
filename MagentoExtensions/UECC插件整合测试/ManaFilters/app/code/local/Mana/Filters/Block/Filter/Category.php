<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Block type for showing subcategory filter
 * @author Mana Team
 * Injected into layout instead of standard catalog/layer_filter_category in Mana_Filters_Block_View_Category::_initBlocks.
 */
class Mana_Filters_Block_Filter_Category extends Mage_Catalog_Block_Layer_Filter_Category {
    /**
     * Overridden constructor injects our template instead of standard one
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'mana_filters/filter_category';
    }
    /** 
     * This function is typically called to initialize underlying model of filter and apply it to current 
     * product set if needed. Here we leave it as is except that we assign template file here not in constructor,
     * not how standard Magento does.
     * @see Mage_Catalog_Block_Layer_Filter_Abstract::init()
     */
    public function init() {
    	/* @var $ext Mana_Filters_Helper_Extended */ $ext = Mage::helper(strtolower('Mana_Filters/Extended'));
    	$this->setTemplate($ext->getFilterTemplate($this)); 
        return parent::init();
    }
    
    /**
     * Returns underlying model object which contains actual filter data
     * @return Mage_Catalog_Model_Layer_Filter_Attribute
     */
    public function getFilter() {
    	return $this->_filter;
    }
}