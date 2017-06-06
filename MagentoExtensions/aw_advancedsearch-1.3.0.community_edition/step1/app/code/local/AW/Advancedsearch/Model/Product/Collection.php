<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Advancedsearch_Model_Product_Collection extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
{
    /**
     * This flag shows is url rewrites has been
     * already added to collection or not.
     * @var bool
     */
    protected $_isUrlRewritesAdded = false;

    public function addUrlRewrites()
    {
        if (!$this->_isUrlRewritesAdded) {
            $this->getSelect()->joinLeft(array('urwr' => $this->getTable('core/url_rewrite')),
                '(urwr.product_id=e.entity_id) AND (urwr.store_id=' . $this->getStoreId() . ')',
                array('request_path'));
            $this->groupByAttribute('entity_id');
            $this->_isUrlRewritesAdded = true;
        }
        return $this;
    }

    /**
     * Selecting products from multiple categories
     * @param string $categories categories list separated by commas
     * @return AW_Featured_Model_Product_Collection
     */
    public function addCategoriesFilter($categories, $includeSubCategories = false)
    {
        if (!is_array($categories))
            $categories = @explode(',', $categories);
        $sqlCategories = array();
        if ($includeSubCategories) {
            foreach ($categories as $categoryId) {
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $sqlCategories = array_merge($sqlCategories, $category->getAllChildren(true));
            }
        } else {
            $sqlCategories = $categories;
        }
        $sqlCategories = array_unique($sqlCategories);
        if (is_array($sqlCategories)) {
            $categories = @implode(',', $sqlCategories);
        }
        $alias = 'cat_index';
        $categoryCondition = $this->getConnection()->quoteInto(
            $alias . '.product_id=e.entity_id' . ($includeSubCategories ? '' : ' AND ' . $alias . '.is_parent=1') . ' AND ' . $alias . '.store_id=? AND ',
            $this->getStoreId());
        $categoryCondition .= $alias . '.category_id IN (' . $categories . ')';
        $this->getSelect()->joinInner(
            array($alias => $this->getTable('catalog/category_product_index')),
            $categoryCondition,
            array('position' => 'position')
        );
        $this->_categoryIndexJoined = true;
        $this->_joinFields['position'] = array('table' => $alias, 'field' => 'position');

        return $this;
    }

    public function addFilterByIds($ids)
    {
        if ($ids) {
            $whereString = '(e.entity_id IN (';
            $whereString .= implode(',', $ids);
            $whereString .= '))';
            $this->getSelect()->where($whereString);
        }
        return $this;
    }

    /**
     * Covers bug in Magento function
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $catalogProductFlatHelper = Mage::helper('catalog/product_flat');
        if ($catalogProductFlatHelper && $catalogProductFlatHelper->isEnabled()) {
            return parent::getSelectCountSql();
        }
        $this->_renderFilters();
        $countSelect = clone $this->getSelect();
        return $countSelect->reset()->from($this->getSelect(), array())->columns('COUNT(*)');
    }

    public function addAttributeToSort($attribute, $dir = Mage_Catalog_Model_Resource_Product_Collection::SORT_ORDER_ASC)
    {
        if ($attribute === 'relevance') {
            $this->getSelect()->order("relevance {$dir}");
            return $this;
        }
        return parent::addAttributeToSort($attribute, $dir);
    }

    public function setSizeTo($size)
    {
        $this->_totalRecords = $size;
        return $this;
    }

    /**
     * Apply limitation filters to collection
     *
     * Method allows using one time category product index table (or product website table)
     * for different combinations of store_id/category_id/visibility filter states
     *
     * Method supports multiple changes in one collection object for this parameters
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    protected function _applyProductLimitations()
    {
        if (Mage::helper('awadvancedsearch')->checkExtensionVersion('Mage_Catalog', '1.4.0.0.38', '<=')) { // Magento <= 1.4.2.0

            $this->_prepareProductLimitationFilters();
            $this->_productLimitationJoinWebsite();
            $this->_productLimitationJoinPrice();
            $filters = $this->_productLimitationFilters;

            if (!isset($filters['category_id']) && !isset($filters['visibility'])) {
                return $this;
            }

            $category = Mage::getModel('catalog/category')->load($filters['category_id']);
            $filters['category_id'] = $category->getAllChildren(true);
            $storeId = isset($filters['store_id']) ? $filters['store_id'] : Mage::app()->getStore()->getId();
            $groupedCatIndex = new Zend_Db_Select($this->getConnection());
            $groupedCatIndex->from($this->getTable('catalog/category_product_index'));
            $groupedCatIndex->reset(Zend_Db_Select::COLUMNS)
                ->columns('product_id')
                ->where('category_id IN (?)', $filters['category_id'])
                ->where('store_id = ?', $storeId);
            if (isset($filters['category_is_anchor'])) {
                $groupedCatIndex->where('is_parent = ?', $filters['category_is_anchor']);
            }
            if (isset($filters['visibility']) && !isset($filters['store_table'])) {
                $groupedCatIndex->where('visibility IN (?)', $filters['visibility']);
            }
            $productIds = $this->getConnection()->fetchCol($groupedCatIndex);
            $this->addFieldToFilter('entity_id', array('in' => $productIds));

            Mage::dispatchEvent('catalog_product_collection_apply_limitations_after', array(
                'collection' => $this
            ));

            return $this;
        }
        return parent::_applyProductLimitations();
    }
}
