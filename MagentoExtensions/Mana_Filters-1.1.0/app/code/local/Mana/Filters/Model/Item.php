<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * In-memory representation of a single option of a filter
 * @method bool getMSelected()
 * @method Mana_Filters_Model_Item setMSelected(bool $value)
 * @author Mana Team
 * Injected instead of standard catalog/layer_filter_item in Mana_Filters_Model_Filter_Attribute::_createItemEx()
 * method.
 */
class Mana_Filters_Model_Item extends Mage_Catalog_Model_Layer_Filter_Item {
    /**
     * Returns URL which should be loaded if person chooses to add this filter item into active filters
     * @return string
     * @see Mage_Catalog_Model_Layer_Filter_Item::getUrl()
     * This method is overridden by copying (method body was pasted from parent class and modified as needed). All
     * changes are marked with comments.
     */
    public function getUrl()
    {
    	// MANA BEGIN: add multivalue filter handling
    	/* @var $ext Mana_Filters_Helper_Extended */ $ext = Mage::helper(strtolower('Mana_Filters/Extended'));
    	$values = $this->getFilter()->getMSelectedValues(); // this could fail if called from some kind of standard filter
    	if (!in_array($this->getValue(), $values)) $values[] = $this->getValue();
    	// MANA END
        
    	$query = array(
        	// MANA BEGIN: save multiple values in URL as concatenated with '_'
            $this->getFilter()->getRequestVar()=>implode('_', $values),
            // MANA_END
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return $ext->getFilterUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
    }
    
    /** 
     * Returns URL which should be loaded if person chooses to remove this filter item from active filters
     * @return string
     * @see Mage_Catalog_Model_Layer_Filter_Item::getRemoveUrl()
     * This method is overridden by copying (method body was pasted from parent class and modified as needed). All
     * changes are marked with comments.
     */
    public function getRemoveUrl()
    {
    	// MANA BEGIN: add multivalue filter handling
    	/* @var $ext Mana_Filters_Helper_Extended */ $ext = Mage::helper(strtolower('Mana_Filters/Extended'));
    	$values = $this->getFilter()->getMSelectedValues(); // this could fail if called from some kind of standard filter
    	unset($values[array_search($this->getValue(), $values)]);
    	if (count($values) > 0) {
	    	$query = array(
	            $this->getFilter()->getRequestVar()=>implode('_', $values),
	            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
	        );
    	}
    	else {
    		$query = array($this->getFilter()->getRequestVar()=>$this->getFilter()->getResetValue());
    	}
    	// MANA END
    	
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        $params['_escape']      = true;
        return $ext->getFilterUrl('*/*/*', $params);
    }
	public function getUniqueId() {
		/* @var $helper Mana_Filters_Helper_Data */ $helper = Mage::helper(strtolower('Mana_Filters'));
		return 'filter_'.$helper->getFilterName($this->getFilter()).'_'.$this->getValue();
	}
}