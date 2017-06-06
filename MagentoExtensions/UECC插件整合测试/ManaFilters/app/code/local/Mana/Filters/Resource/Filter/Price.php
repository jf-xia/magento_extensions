<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Resource type which contains sql code for applying filters and related operations
 * @author Mana Team
 * Injected instead of standard resource catalog/layer_filter_attribute in 
 * Mana_Filters_Model_Filter_Price::_getResource().
 */
class Mana_Filters_Resource_Filter_Price extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price {
    /**
     * Applies one or more price filters to currently viewed product collection
     * @param Mana_Filters_Model_Filter_Price $filter
     * @param array $selections
     * @return Mana_Filters_Resource_Filter_Price
     * This method is cloned from method applyFilterToCollection() in parent class (method body was pasted from parent class 
     * and changed as needed. All changes marked with comments
     * Standard method did not give us possibility to filter multiple ranges. 
     */
    public function applyFilterToCollectionEx($filter, $selections)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

        $select     = $collection->getSelect();
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);

        $table      = $this->_getIndexTableAlias();
        $additional = join('', $response->getAdditionalCalculations());
        $rate       = $filter->getCurrencyRate();
        $priceExpr  = new Zend_Db_Expr("(({$table}.min_price {$additional}) * {$rate})");

		// MANA BEGIN: modify select formation to include multiple price ranges
    	/* @var $ext Mana_Filters_Helper_Extended */ $ext = Mage::helper(strtolower('Mana_Filters/Extended'));
        $condition = '';
        foreach ($selections as $selection) {
        	list($index, $range) = explode(',', $selection);
        	$range = $ext->getPriceRange($index, $range);
        	if ($condition != '') $condition .= ' OR ';
        	$condition .= '(('.$priceExpr . ' >= '. $range['from'].') '.
        		'AND ('.$priceExpr . ' < '. $range['to'].'))';
        }
        $select
        	->where($condition);
        // MANA END
        return $this;
    }
    /**
     * For each option visible to person as a filter choice counts how many products are there given that all the 
     * other filters are applied
     * @param Mana_Filters_Model_Filter_Price $filter
     * @param int $range The whole price range is split into several using this range step
     * @return array Each entry in result is int index => int count
     * This method is overridden by copying (method body was pasted from parent class and modified as needed). All
     * changes are marked with comments.
     * @see Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute::getCount()
     */
    public function getCount($filter, $range)
    {
        $select     = $this->_getSelect($filter);
        $connection = $this->_getReadAdapter();
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);
        $table      = $this->_getIndexTableAlias();

        $additional = join('', $response->getAdditionalCalculations());
        $rate       = $filter->getCurrencyRate();
        $countExpr  = new Zend_Db_Expr('COUNT(*)');
        $rangeExpr  = new Zend_Db_Expr("FLOOR((({$table}.min_price {$additional}) * {$rate}) / {$range}) + 1");

        $select->columns(array(
            'range' => $rangeExpr,
            'count' => $countExpr
        ));
        
        // MANA BEGIN: make sure price filter is not applied
        $select->reset(Zend_Db_Select::WHERE);
        // MANA END
        
        $select->where("{$table}.min_price > 0");
        $select->group('range');

		return $connection->fetchPairs($select);
    }
    /**
     * Retrieve maximal price for attribute
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return float
     */
    public function getMaxPrice($filter)
    {
        $select     = $this->_getSelect($filter);
        $connection = $this->_getReadAdapter();
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);

        $table = $this->_getIndexTableAlias();

        $additional   = join('', $response->getAdditionalCalculations());
        $maxPriceExpr = new Zend_Db_Expr("MAX({$table}.min_price {$additional})");

        // MANA BEGIN: make sure no filter is applied
        $select->reset(Zend_Db_Select::WHERE);
        $from = $select->getPart(Zend_Db_Select::FROM);
        foreach ($from as $key => $options) {
        	if ($key == 'cat_index') {
        		/* @var $layer Mage_Catalog_Model_Layer */ $layer = Mage::getSingleton('catalog/layer');
        		$needle = "cat_index.category_id='";
        		$startPos = strpos($options['joinCondition'], $needle);
        		if ($startPos === false)  throw new Exception('Not implemented');
        		$endPos = strpos($options['joinCondition'], "'", $startPos + strlen($needle));
        		$from[$key]['joinCondition'] = 
        			substr($options['joinCondition'], 0, $startPos + strlen($needle)).
        			$layer->getCurrentCategory()->getId().
        			substr($options['joinCondition'], $endPos);
        	}
        	elseif (strrpos($key, '_idx') === strlen($key) - strlen('_idx')) {
        		unset($from[$key]);
        	}
        }
        $select->setPart(Zend_Db_Select::FROM, $from);
        // MANA END
        $select->columns(array($maxPriceExpr));

        return $connection->fetchOne($select) * $filter->getCurrencyRate();
    }

}